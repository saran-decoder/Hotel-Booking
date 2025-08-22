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
        $roomId           = trim($this->_request['roomId'] ?? '');
        $roomType         = trim($this->_request['roomType'] ?? '');
        $guestsAllowed    = trim($this->_request['guestsAllowed'] ?? '');
        $roomDescription  = trim($this->_request['roomDescription'] ?? '');
        $pricePerNight    = trim($this->_request['pricePerNight'] ?? '');
        $amenities        = json_decode($this->_request['amenities'] ?? '[]', true);
        $imagesToDelete   = json_decode($this->_request['imagesToDelete'] ?? '[]', true);
        $status           = trim($this->_request['status'] ?? '');

        // Validation
        if ($roomId === '') {
            return $this->response($this->json([
                'success' => false,
                'message' => 'Room ID are required'
            ]), 400);
        }

        if (empty($roomType) || empty($guestsAllowed) || empty($roomDescription) || empty($pricePerNight)) {
            return $this->response($this->json([
                'success' => false,
                'message' => 'All required fields must be filled'
            ]), 400);
        }

        if (!is_numeric($guestsAllowed) || $guestsAllowed < 1) {
            return $this->response($this->json([
                'success' => false,
                'message' => 'Guests allowed must be a positive number'
            ]), 400);
        }

        if (!is_numeric($pricePerNight) || $pricePerNight < 0.01) {
            return $this->response($this->json([
                'success' => false,
                'message' => 'Price per night must be a valid amount (minimum 0.01)'
            ]), 400);
        }

        // Get existing images
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT images, hotel_id FROM rooms WHERE id = ?");
        $stmt->bind_param("i", $roomId);
        $stmt->execute();
        $result = $stmt->get_result();
        $room = $result->fetch_assoc();

        $hotelId = $room["hotel_id"];
        
        if (!$room) {
            return $this->response($this->json([
                'success' => false,
                'message' => 'Room not found'
            ]), 404);
        }
        
        $existingImages = json_decode($room['images'] ?? '[]', true);

        // Remove deleted images from array + unlink files
        $updatedImages = [];
        foreach ($existingImages as $img) {
            if (in_array($img, $imagesToDelete)) {
                $filePath = __DIR__ . '/../' . $img;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            } else {
                $updatedImages[] = $img;
            }
        }

        // Handle new uploads
        if (!empty($_FILES['images']) && is_array($_FILES['images']['tmp_name'])) {
            $uploadDir = '../uploads/rooms/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
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
                        $updatedImages[] = 'uploads/rooms/' . $fileName;
                    }
                }
            }
        }

        // Check if we have at least one image
        if (empty($updatedImages)) {
            return $this->response($this->json([
                'success' => false,
                'message' => 'At least one room image is required'
            ]), 400);
        }

        // Call update function
        $updated = Admin::updateRoom(
            $roomId,
            $hotelId,
            $roomType,
            $guestsAllowed,
            $roomDescription,
            $pricePerNight,
            $amenities,
            $updatedImages,
            $status
        );

        if ($updated) {
            return $this->response($this->json([
                'success' => true,
                'message' => 'Room updated successfully'
            ]), 200);
        } else {
            return $this->response($this->json([
                'success' => false,
                'message' => 'Failed to update room'
            ]), 500);
        }

    } catch (Exception $e) {
        error_log("Room update error: " . $e->getMessage());
        return $this->response($this->json([
            'success' => false,
            'message' => 'Server error: Unable to process your request'
        ]), 500);
    }
};