<?php

${basename(__FILE__, '.php')} = function () {
    // Check if required fields are present
    if (!isset($_POST['id']) || !isset($_POST['name'])) {
        $this->response($this->json([
            'success' => false,
            'message' => 'ID and name are required fields.'
        ]), 400);
        return;
    }

    $id = (int) $_POST['id'];
    $name = trim($_POST['name']);

    // Validate name
    if (empty($name)) {
        $this->response($this->json([
            'success' => false,
            'message' => 'Hospital name cannot be empty.'
        ]), 400);
        return;
    }

    $filePath = null;
    $uploadDir = "../uploads/logos/";

    // Handle file upload if provided
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        // Validate file
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($_FILES['logo']['tmp_name']);

        if (!in_array($fileType, $allowedTypes)) {
            $this->response($this->json([
                'success' => false,
                'message' => 'Only JPG, PNG, and GIF images are allowed.'
            ]), 400);
            return;
        }

        // Create directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                $this->response($this->json([
                    'success' => false,
                    'message' => 'Failed to create upload directory.'
                ]), 500);
                return;
            }
        }

        // Generate unique filename
        $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9\.\-_]/', '', $_FILES['logo']['name']);
        $filePath = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['logo']['tmp_name'], $filePath)) {
            $this->response($this->json([
                'success' => false,
                'message' => 'Failed to move uploaded file.'
            ]), 500);
            return;
        }
    } elseif (isset($_POST['existing_logo'])) {
        // Use existing logo if provided and no new file was uploaded
        $filePath = $_POST['existing_logo'];
    }

    try {
        // Save in database
        $result = User::updateSettings($id, $filePath, $name);

        if ($result) {
            $this->response($this->json([
                'success' => true,
                'message' => $result,
                'logoPath' => $filePath // Return the logo path for client-side updates
            ]), 200);
        } else {
            // If file was uploaded but DB update failed, try to delete the uploaded file
            if ($filePath && isset($_FILES['logo'])) {
                @unlink($filePath);
            }
            
            $this->response($this->json([
                'success' => false,
                'message' => 'Database update failed.'
            ]), 500);
        }
    } catch (Exception $e) {
        // If file was uploaded but an exception occurred, try to delete the uploaded file
        if ($filePath && isset($_FILES['logo'])) {
            @unlink($filePath);
        }
        
        $this->response($this->json([
            'success' => false,
            'message' => 'An error occurred: ' . $e->getMessage()
        ]), 500);
    }
};