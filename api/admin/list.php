<?php

${basename(__FILE__, '.php')} = function () {
    $result = Operations::getAdminAccount();
    
    // Check if result were found
    if ($result) {
        $response = [
            'success' => true,
            'data' => $result
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No data found'
        ];
    }
    
    $this->response($this->json($response), 200);
};