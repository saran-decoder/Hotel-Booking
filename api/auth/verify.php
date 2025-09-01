<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['username', 'otp'])) {
        $username = $this->_request['username'];
        $code     = $this->_request['otp'];

        $verified = Verification::verifyEmailCode($username, $code);

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