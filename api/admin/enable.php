<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['id'])) {
        $id = $this->_request['id'];
        
        // Call Admin method to disable employee
        $result = Admin::enableEmployee($id);
        
        if ($result['success']) {
            $this->response($this->json($result), 200);
        } else {
            $this->response($this->json($result), 400);
        }
    } else {
        $this->response($this->json([
            'success' => false,
            'message' => 'Missing required parameter: id'
        ]), 400);
    }
};