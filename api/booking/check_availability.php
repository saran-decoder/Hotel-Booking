<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['room_id', 'check_in', 'check_out'])) {
        $room_id = $this->_request['room_id'];
        $check_in = $this->_request['check_in'];
        $check_out = $this->_request['check_out'];

        // Check room availability
        $availability = Admin::checkRoomAvailability($room_id, $check_in, $check_out);
        
        $this->response($this->json([
            'available' => $availability['available'],
            'message' => $availability['message'],
            'details' => isset($availability['details']) ? $availability['details'] : null
        ]), 200);

    } else {
        $this->response($this->json([
            'success' => false,
            'message' => "Bad Request - Missing required parameters",
            'error' => $availability . ' / ' . $room_id . ' / ' . $check_in . ' / ' . $check_out
        ]), 400);
    }
};