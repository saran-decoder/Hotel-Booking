<?php

${basename(__FILE__, '.php')} = function () {
    // Call Operations method to get all employees
    $result = Operations::getAllEmployees();
    
    if ($result['success']) {
        $this->response($this->json($result), 200);
    } else {
        $this->response($this->json($result), 500);
    }
};