<?php

${basename(__FILE__, '.php')} = function () {
    // Check if parameters exist first
    if ($this->paramsExists(['room_id', 'check_in', 'check_out'])) {
        $room_id = $this->_request['room_id'];
        $check_in = $this->_request['check_in'];
        $check_out = $this->_request['check_out'];

        // Check room availability
        $availability = Admin::checkRoomAvailability($room_id, $check_in, $check_out);
        
        // Return consistent response structure
        $response = [
            'success' => true,
            'available' => $availability['available'],
            'message' => $availability['message']
        ];
        
        // Only include details if they exist
        if (isset($availability['details']) && $availability['details'] !== null) {
            $response['details'] = $availability['details'];
        }
        
        $this->response($this->json($response), 200);

    } else {
        // Debug what parameters were actually received
        $received_params = [];
        foreach (['room_id', 'check_in', 'check_out'] as $param) {
            $received_params[$param] = isset($this->_request[$param]) ? $this->_request[$param] : 'NOT_RECEIVED';
        }
        
        $this->response($this->json([
            'success' => false,
            'message' => "Bad Request - Missing required parameters",
            'received_params' => $received_params,
            'all_params' => $this->_request
        ]), 400);
    }
};