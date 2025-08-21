<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['username', 'otp'])) {
        $username = $this->_request['username'];
        $code     = $this->_request['otp'];

        $verified = Verification::verifyEmailCode($username, $code);

        if ($verified) {
            $this->response($this->json([
                'message' => 'OTP Verified Successfully',
                'status'  => true
            ]), 200);
        } else {
            $this->response($this->json([
                'message' => 'OTP Verification Failed or Expired',
                'status'  => false
            ]), 401);
        }

    } else {
        $this->response($this->json([
            'message' => 'Bad Request. Required fields: contact, otp'
        ]), 400);
    }
};