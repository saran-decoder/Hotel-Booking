<?php

${basename(__FILE__, '.php')} = function () {
    // Log the incoming request
    error_log("User signup request: " . print_r($this->_request, true));
    
    if ($this->paramsExists(['name', 'phone', 'email', 'dob', 'gender', 'city'])) {
        $name = $this->_request['name'];
        $phone = $this->_request['phone'];
        $email = $this->_request['email'];
        $dob = $this->_request['dob'];
        $gender = $this->_request['gender'];
        $city = $this->_request['city'];

        error_log("Attempting to register: $name, $phone, $email");

        $result = User::signup($name, $phone, $email, $dob, $gender, $city);
        
        error_log("Signup result: " . print_r($result, true));
        
        if($result === true) {
            $this->response($this->json([
                'message'=>'Successfully Registered',
                'result' => true
            ]), 200);
        } else if ($result === "exists") {
            $this->response($this->json([
                'message'=>'User already exists with this phone or email',
                'result' => false
            ]), 409);
        } else {
            $this->response($this->json([
                'message'=>'Registration failed. Please try again.',
                'result' => false
            ]), 400);
        }
    } else {
        error_log("Missing parameters: " . print_r($this->_request, true));
        $this->response($this->json([
            'message' => "Bad Request - Missing parameters"
        ]), 400);
    }
};