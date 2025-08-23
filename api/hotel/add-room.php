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
        $hotelId = trim($this->_request['hotelID'] ?? '');
        $roomType = trim($this->_request['roomType'] ?? '');
        $guestsAllowed = trim($this->_request['guestsAllowed'] ?? '');
        $roomDescription = trim($this->_request['roomDescription'] ?? '');
        $pricePerNight = trim($this->_request['pricePerNight'] ?? '');
        $amenities = json_decode($this->_request['amenities'] ?? '[]', true);

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

        // Handle image uploads
        $uploadedImages = [];
        if (!empty($_FILES['images']) && is_array($_FILES['images']['tmp_name'])) {
            $uploadDir = 'uploads/rooms/';
            if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

            foreach ($_FILES['images']['tmp_name'] as $i => $tmpName) {
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION));
                    $allowed = ['jpg','jpeg','png','webp'];
                    if (!in_array($ext, $allowed)) continue;

                    $fileName   = time() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '', $_FILES['images']['name'][$i]);
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
            $uploadedImages
        );

        if ($roomId) {
            return $this->response($this->json([
                'success' => true,
                'message' => 'Room added successfully',
                'room_id' => $roomId,
                'hotel_id'=> $hotelId
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