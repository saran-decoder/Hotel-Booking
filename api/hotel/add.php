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
        // Collect data
        $hotelName        = trim($this->_request['hotelName'] ?? '');
        $locationName     = trim($this->_request['locationName'] ?? '');
        $mapCoordinates   = trim($this->_request['mapCoordinates'] ?? '');
        $fullAddress      = trim($this->_request['fullAddress'] ?? '');
        $hotelDescription = trim($this->_request['hotelDescription'] ?? '');
        $amenities        = json_decode($this->_request['amenities'] ?? '[]', true);

        if ($hotelName === '') {
            return $this->response($this->json([
                'success' => false,
                'message' => 'Hotel name is required'
            ]), 400);
        }

        // Handle image uploads
        $uploadedImages = [];
        if (!empty($_FILES['images']) && is_array($_FILES['images']['tmp_name'])) {
            $uploadDir = 'uploads/hotels/';
            if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

            foreach ($_FILES['images']['tmp_name'] as $i => $tmpName) {
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION));
                    $allowed = ['jpg','jpeg','png','webp'];
                    if (!in_array($ext, $allowed)) continue;

                    $fileName   = time() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '', $_FILES['images']['name'][$i]);
                    $targetPath = $uploadDir . $fileName;

                    if (move_uploaded_file($tmpName, $targetPath)) {
                        $uploadedImages[] = 'uploads/hotels/' . $fileName;
                    }
                }
            }
        }

        // Insert hotel
        $hotelId = Admin::addHotel(
            $hotelName,
            $locationName,
            $mapCoordinates,
            $fullAddress,
            $hotelDescription,
            $amenities,
            $uploadedImages
        );

        if ($hotelId) {
            return $this->response($this->json([
                'success'  => true,
                'message'  => 'Hotel added successfully',
                'hotel_id' => $hotelId
            ]), 200);
        }

        return $this->response($this->json([
            'success' => false,
            'message' => 'Failed to add hotel'
        ]), 500);

    } catch (Exception $e) {
        return $this->response($this->json([
            'success' => false,
            'message' => 'Server error: ' . $e->getMessage()
        ]), 500);
    }
};