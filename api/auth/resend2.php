<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['contact'])) {
        $conn = Database::getConnection();
        $phone = $this->_request['contact'];
        
        // Check if user exists first
        $result = $conn->query("SELECT `id` FROM `users` WHERE `phone` = '$phone'");
        
        if ($result && $result->num_rows > 0) {
            $qry = $result->fetch_assoc();
            $userId = $qry["id"];
            
            // Generate and send OTP for existing user
            $otpSent = Verification::resendSMSCode($userId, $phone);
        } else {
            // For new users, create a temporary user ID or use phone as identifier
            $userId = null; // Or generate a temporary ID
            $otpSent = Verification::sendSMSVerification($phone, 0); // Use 0 or temporary ID
        }

        if ($otpSent) {
            $this->response($this->json([
                'message' => 'OTP sent successfully',
                'status' => 'success',
                'user_exists' => isset($qry["id"]) // Indicate if user exists
            ]), 200);
        } else {
            $this->response($this->json([
                'message' => 'Failed to send OTP. Try again later.',
                'status' => 'error'
            ]), 500);
        }

    } else {
        $this->response($this->json([
            'message' => 'Bad request. Phone number required.',
            'status' => 'error'
        ]), 400);
    }
};