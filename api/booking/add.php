<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->get_request_method() != "POST") {
        $this->response($this->json([
            'message' => 'Method Not Allowed'
        ]), 405);
        return;
    }

    if ($this->paramsExists(['hotel_id', 'room_id', 'check_in', 'check_out', 'adults', 'children', 'total_price'])) {
        $hotel_id = $this->_request['hotel_id'];
        $room_id = $this->_request['room_id'];
        $check_in = $this->_request['check_in'];
        $check_out = $this->_request['check_out'];
        $adults = $this->_request['adults'];
        $children = $this->_request['children'];
        $total_price = $this->_request['total_price'];

        // Get user ID from token (if using authentication)
        if(Session::get('user_id')) {
            $user_id = Session::get('user_id');
        } else {
            $user_id = Session::get('username');
        }

        // Change the method call
        $booking_id = Admin::createBooking( // Changed from Admin::createBooking
            $user_id,
            $hotel_id,
            $room_id,
            $check_in,
            $check_out,
            $adults,
            $children,
            $total_price
        );

        if ($booking_id) {
            $this->response($this->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'booking_id' => $booking_id
            ]), 200);
        } else {
            $this->response($this->json([
                'success' => false,
                'message' => 'Failed to create booking',
                'booking_id' => $booking_id
            ]), 500);
        }

    } else {
        $this->response($this->json([
            'success' => false,
            'message' => "Bad Request - Missing required parameters"
        ]), 400);
    }
};