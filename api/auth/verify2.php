<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['contact', 'otp'])) {
        $phone = $this->_request['contact'];
        $otp = $this->_request['otp'];
        
        // Check if user exists
        $conn = Database::getConnection();
        $result = $conn->query("SELECT `id` FROM `users` WHERE `phone` = '$phone'");
        
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $userId = $user["id"];
            $verified = Verification::verifySMSCode($phone, $otp, $userId);
        } else {
            // New user verification
            $verified = Verification::verifySMSCode($phone, $otp, 0);
        }

        if ($verified) {
            $this->response($this->json([
                'status' => true,
                'message' => 'OTP verified successfully',
                'user_exists' => isset($user["id"])
            ]), 200);
        } else {
            $this->response($this->json([
                'status' => false,
                'message' => 'Invalid or expired OTP'
            ]), 401);
        }

    } else {
        $this->response($this->json([
            'message' => 'Bad request. Contact and OTP required.',
            'status' => false
        ]), 400);
    }
};