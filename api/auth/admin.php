<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['user', 'password'])) {
        $user = $this->_request['user'];
        $pass = $this->_request['password'];

        $token = UserSession::authenticateAdmin($user, $pass);
        if ($token) {
            $this->response($this->json([
                'message' => 'Authenticated',
                'token'   => $token
            ]), 200);
        } else {
            $this->response($this->json([
                'message' => 'Unauthorized - invalid email or password'
            ]), 401);
        }

    } else {
        $this->response($this->json([
            'message' => "Bad Request"
        ]), 400);
    }
};