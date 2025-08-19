<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['contact'])) {

        $phone = $this->_request['contact'];

        // Generate and send OTP (or reuse existing logic)
        $otpSent = UserSession::resendOtp($phone); // You need to implement resendOtp()

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
            'message' => 'Bad request. Phone number is required.',
            'status' => 'error'
        ]), 400);
    }
};