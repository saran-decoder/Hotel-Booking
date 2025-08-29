<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['id', 'name', 'email', 'role'])) {
        $id = $this->_request['id'];
        $name = $this->_request['name'];
        $email = $this->_request['email'];
        $role = $this->_request['role'];
        
        // Call Admin method to edit employee
        $result = Admin::editEmployee($id, $name, $email, $role);
        
        if ($result['success']) {
            $this->response($this->json($result), 200);
        } else {
            $this->response($this->json($result), 400);
        }
    } else {
        $this->response($this->json([
            'success' => false,
            'message' => 'Missing required parameters: id, name, email, role'
        ]), 400);
    }
};