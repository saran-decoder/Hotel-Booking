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
        $hotelId          = trim($this->_request['hotelId'] ?? '');
        $hotelName        = trim($this->_request['hotelName'] ?? '');
        $locationName     = trim($this->_request['locationName'] ?? '');
        $mapCoordinates   = trim($this->_request['mapCoordinates'] ?? '');
        $fullAddress      = trim($this->_request['fullAddress'] ?? '');
        $hotelDescription = trim($this->_request['hotelDescription'] ?? '');
        $amenities        = json_decode($this->_request['amenities'] ?? '[]', true);
        $imagesToDelete   = json_decode($this->_request['imagesToDelete'] ?? '[]', true);

        if ($hotelId === '' || $hotelName === '') {
            return $this->response($this->json([
                'success' => false,
                'message' => 'Hotel ID and name are required'
            ]), 400);
        }

        // Initialize new image paths array
        $newImagePaths = [];

        // Handle new file uploads
        if (!empty($_FILES['images']) && is_array($_FILES['images']['tmp_name'])) {
            $uploadDir = 'uploads/hotels/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            foreach ($_FILES['images']['tmp_name'] as $i => $tmpName) {
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION));
                    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
                    
                    if (in_array($ext, $allowed)) {
                        $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '', $_FILES['images']['name'][$i]);
                        $targetPath = $uploadDir . $fileName;

                        if (move_uploaded_file($tmpName, $targetPath)) {
                            $newImagePaths[] = $targetPath;
                        }
                    }
                }
            }
        }

        // Call the update function
        $success = Admin::updateHotel(
            $hotelId, 
            $hotelName, 
            $locationName, 
            $mapCoordinates, 
            $fullAddress, 
            $hotelDescription, 
            $amenities, 
            $newImagePaths,
            $imagesToDelete
        );

        if ($success) {
            return $this->response($this->json([
                'success' => true,
                'message' => 'Hotel updated successfully'
            ]), 200);
        } else {
            return $this->response($this->json([
                'success' => false,
                'message' => 'Failed to update hotel'
            ]), 500);
        }

    } catch (Exception $e) {
        return $this->response($this->json([
            'success' => false,
            'message' => 'Server error: ' . $e->getMessage()
        ]), 500);
    }
};