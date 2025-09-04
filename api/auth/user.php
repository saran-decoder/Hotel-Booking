<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['phone'])) {
        $phone = $this->_request['phone'];

        $token = UserSession::authenticateUser($phone);
        if ($token) {
            $this->response($this->json([
                'message' => 'Authenticated',
                'token'   => $token
            ]), 200);
        } else {
            $this->response($this->json([
                'message' => 'Unauthorized - invalid phone or password',
                'status' => $token
            ]), 401);
        }

    } else {
        $this->response($this->json([
            'message' => "Bad Request"
        ]), 400);
    }
};