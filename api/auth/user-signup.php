<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['name', 'email', 'phone', 'password'])) {
        $user = $this->_request['name'];
        $email = $this->_request['email'];
        $phone = $this->_request['phone'];
        $pass = $this->_request['password'];

        $result = User::signup($user, $pass, $email, $phone);
        if($result) {
            $this->response($this->json([
                'message'=>'Successfully Registered',
                'result' => $result
            ]), 200);
        } else {
            $this->response($this->json([
                'message'=>'Already Existing, Set Unique',
                'result' => $result
            ]), 400);
        }

    } else {
        $this->response($this->json([
            'message' => "Bad Request"
        ]), 400);
    }
};