<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['contact'])) {
        $phone = $this->_request['contact'];
        $token = UserSession::authenticatePatient($phone);
        if($token) {
            $this->response($this->json([
                'message'=>'Authenticated',
                'token' => $token
            ]), 200);
        } else {
            $this->response($this->json([
                'message'=>'Unauthorized',
                'token' => $token
            ]), 401);
        }

    } else {
        $this->response($this->json([
            'message'=>"bad request"
        ]), 400);
    }
};