<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['name', 'email', 'role'])) {
        $name = $this->_request['name'];
        $email = $this->_request['email'];
        $role = $this->_request['role'];
        
        // Call Admin method to add employee
        $result = Admin::addEmployee($name, $email, $role);
        
        if ($result['success']) {
            $this->response($this->json($result), 200);
        } else {
            $this->response($this->json($result), 400);
        }
    } else {
        $this->response($this->json([
            'success' => false,
            'message' => 'Missing required parameters: name, email, role'
        ]), 400);
    }
};