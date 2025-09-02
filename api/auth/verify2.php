<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['contact', 'otp'])) {
        $conn = Database::getConnection();
        $phone = $this->_request['contact'];
        $qry = $conn->query("SELECT `id` FROM `users` WHERE `phone` = '$phone'")->fetch_array();
        $userId = $qry["id"];
        $code     = $this->_request['otp'];

        $verified = Verification::verifySMSCode($userId, $code);

        if ($verified === true) {
            $this->response($this->json([
                'message' => 'OTP Verified Successfully',
                'status'  => true
            ]), 200);
        } else {
            $this->response($this->json([
                'message' => 'OTP Verification Failed: ' . $verified,
                'status'  => false,
                'debug'   => $verified
            ]), 401);
        }

    } else {
        $this->response($this->json([
            'message' => 'Bad Request. Required fields: username, otp'
        ]), 400);
    }
};