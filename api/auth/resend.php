<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['username'])) {
         $conn = Database::getConnection();
        $email = $this->_request['username'];
        $qry = $conn->query("SELECT `id` FROM `admin` WHERE `email` = '$email'")->fetch_array();
        $userId = $qry["id"];

        // Generate and send OTP (or reuse existing logic)
        $otpSent = Verification::resendEmailCode($userId, $email);

        if ($otpSent) {
            $this->response($this->json([
                'message' => 'OTP resent successfully',
                'status' => 'success'
            ]), 200);
        } else {
            $this->response($this->json([
                'message' => 'Failed to send OTP. Try again later.',
                'status' => 'fail'
            ]), 500);
        }

    } else {
        $this->response($this->json([
            'message' => 'Bad request. login again.',
            'status' => 'error'
        ]), 400);
    }
};