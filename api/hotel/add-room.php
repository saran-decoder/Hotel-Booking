<?php

${basename(__FILE__, '.php')} = function () {

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return $this->response($this->json([
            'success' => false,
            'message' => 'Invalid request method'
        ]), 405);
    }

    if (!Session::isAuthenticated() || Session::get('session_type') !== 'admin') {
        return $this->response($this->json([
            'success' => false,
            'message' => 'Unauthorized access'
        ]), 401);
    }

    try {
        // Collect data from form (matching the field names in the HTML form)
        $roomType = trim($this->_request['roomType'] ?? '');
        $guestsAllowed = trim($this->_request['guestsAllowed'] ?? '');
        $roomDescription = trim($this->_request['roomDescription'] ?? '');
        $pricePerNight = trim($this->_request['pricePerNight'] ?? '');
        $amenities = json_decode($this->_request['amenities'] ?? '[]', true);
        $adults = trim($this->_request['adults'] ?? '');
        $children = trim($this->_request['children'] ?? '');

        // Validation - check for required fields
        if (empty($roomType)) {
            return $this->response($this->json([
                'success' => false,
                'message' => 'Room type is required'
            ]), 400);
        }

        if (empty($guestsAllowed) || !is_numeric($guestsAllowed) || $guestsAllowed < 1) {
            return $this->response($this->json([
                'success' => false,
                'message' => 'Guests allowed is required and must be a positive number'
            ]), 400);
        }

        if (empty($roomDescription) || strlen($roomDescription) < 10) {
            return $this->response($this->json([
                'success' => false,
                'message' => 'Room description is required and must be at least 10 characters long'
            ]), 400);
        }

        if (empty($pricePerNight) || !is_numeric($pricePerNight) || $pricePerNight < 0.01) {
            return $this->response($this->json([
                'success' => false,
                'message' => 'Price per night is required and must be a valid amount (minimum ₹0.01)'
            ]), 400);
        }

        if (empty($adults) || !is_numeric($adults) || $adults < 1) {
            return $this->response($this->json([
                'success' => false,
                'message' => 'Number of adults is required and must be at least 1'
            ]), 400);
        }

        // Handle image uploads
        $uploadedImages = [];
        if (!empty($_FILES['images']) && is_array($_FILES['images']['tmp_name'])) {
            $uploadDir = '../uploads/rooms/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            foreach ($_FILES['images']['tmp_name'] as $i => $tmpName) {
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    // Check file type
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $mime = $finfo->file($tmpName);
                    $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
                    
                    if (!in_array($mime, $allowedMimes)) {
                        continue;
                    }

                    // Check file size (max 10MB)
                    if ($_FILES['images']['size'][$i] > 10 * 1024 * 1024) {
                        continue;
                    }

                    // Generate safe filename
                    $ext = '';
                    switch ($mime) {
                        case 'image/jpeg': $ext = 'jpg'; break;
                        case 'image/png': $ext = 'png'; break;
                        case 'image/webp': $ext = 'webp'; break;
                    }
                    
                    $fileName = uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
                    $targetPath = $uploadDir . $fileName;

                    if (move_uploaded_file($tmpName, $targetPath)) {
                        $uploadedImages[] = 'uploads/rooms/' . $fileName;
                    }
                }
            }
        }

        // Check if at least one image was uploaded
        if (empty($uploadedImages)) {
            return $this->response($this->json([
                'success' => false,
                'message' => 'At least one room image is required'
            ]), 400);
        }

        // Get hotel ID - you might need to adjust this based on your application logic
        // For example, if the admin is associated with a specific hotel
        $hotelId = Operations::getAdminHotelId(Session::get('username'));
        
        if (!$hotelId) {
            return $this->response($this->json([
                'success' => false,
                'message' => 'Could not determine hotel association'
            ]), 400);
        }

        // Insert room
        $roomId = Admin::addRoom(
            $hotelId,
            $roomType,
            $guestsAllowed,
            $roomDescription,
            $pricePerNight,
            $amenities,
            $adults,
            $children,
            $uploadedImages
        );

        if ($roomId) {
            return $this->response($this->json([
                'success' => true,
                'message' => 'Room added successfully',
                'room_id' => $roomId
            ]), 200);
        }

        return $this->response($this->json([
            'success' => false,
            'message' => 'Failed to add room to the database'
        ]), 500);

    } catch (Exception $e) {
        error_log("Room addition error: " . $e->getMessage());
        return $this->response($this->json([
            'success' => false,
            'message' => 'Server error: Unable to process your request'
        ]), 500);
    }
};