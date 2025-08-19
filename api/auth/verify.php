<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['contact', 'otp'])) {
        $phone = $this->_request['contact'];
        $otp   = $this->_request['otp'];

        $verified = UserSession::verifyOtp($phone, $otp);

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