<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['contact', 'otp'])) {
        $phone = $this->_request['contact'];
        $otp = $this->_request['otp'];
        
        // Validate input
        if (!preg_match('/^[0-9]{10}$/', $phone)) {
            $this->response($this->json([
                'status' => false,
                'message' => 'Invalid phone number format'
            ]), 400);
            return;
        }

        if (!preg_match('/^[0-9]{6}$/', $otp)) {
            $this->response($this->json([
                'status' => false,
                'message' => 'Invalid OTP format'
            ]), 400);
            return;
        }
        
        // Check if user exists
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT `id` FROM `users` WHERE `phone` = ?");
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $userId = 0;
        $userExists = false;
        
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $userId = $user["id"];
            $userExists = true;
        }
        
        $verified = Verification::verifySMSCode($phone, $otp, $userId);
        
        // Check if verification was successful
        if ($verified === true) {
            $this->response($this->json([
                'status' => true,
                'message' => 'OTP verified successfully',
                'user_exists' => $userExists
            ]), 200);
        } else {
            // Log failed attempt for security monitoring
            error_log("Failed OTP attempt for phone: $phone, OTP: $otp");
            
            $this->response($this->json([
                'status' => false,
                'message' => $verified // Return the specific error message
            ]), 401);
        }

    } else {
        $this->response($this->json([
            'message' => 'Bad request. Contact and OTP required.',
            'status' => false
        ]), 400);
    }
};