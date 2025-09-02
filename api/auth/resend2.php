<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['contact'])) {
        $conn = Database::getConnection();
        $phone = $this->_request['contact'];
        $qry = $conn->query("SELECT `id` FROM `users` WHERE `phone` = '$phone'")->fetch_array();
        $userId = $qry["id"];

        // Generate and send OTP (or reuse existing logic)
        $otpSent = Verification::resendSMSCode($userId, $phone);

        if ($otpSent) {
            $this->response($this->json([
                'message' => 'OTP resent successfully',
                'status' => 'success'
            ]), 200);
        } else {
            $this->response($this->json([
                'message' => 'Failed to send OTP. Try again later.',
                'status' => $otpSent
            ]), 500);
        }

    } else {
        $this->response($this->json([
            'message' => 'Bad request. login again.',
            'status' => 'error'
        ]), 400);
    }
};