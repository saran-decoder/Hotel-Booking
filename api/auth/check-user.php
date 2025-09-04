<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['phone'])) {
        $phone = $this->_request['phone'];
        
        // Check if user exists
        $userExists = User::checkUser($phone);
        
        $this->response($this->json([
            'exists' => $userExists,
            'message' => $userExists ? 'User exists' : 'New user'
        ]), 200);
    } else {
        $this->response($this->json([
            'message' => "Bad Request - Phone parameter required"
        ]), 400);
    }
};