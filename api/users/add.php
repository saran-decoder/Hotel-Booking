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

        // Get user identifier from session
        if (Session::get('user_id')) {
            $userIdentifier = Session::get('user_id');
        } else if (Session::get('username')) {
            $userIdentifier = Session::get('username');
        } else {
            // Fallback - use email or generate guest user
            $userIdentifier = 'guest_' . time();
        }

        // Check room availability first
        $availability_check = Admin::checkRoomAvailability($room_id, $check_in, $check_out);
        
        if (!$availability_check['available']) {
            $this->response($this->json([
                'success' => false,
                'message' => 'Room not available for selected dates',
                'details' => $availability_check['message']
            ]), 409); // 409 Conflict status code
            return;
        }

        // Create the booking
        $booking_id = Admin::createBooking(
            $userIdentifier,
            $hotel_id,
            $room_id,
            $check_in,
            $check_out,
            $adults,
            $children,
            $total_price
        );

        if ($booking_id && is_numeric($booking_id)) {
            $this->response($this->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'booking_id' => $booking_id
            ]), 200);
        } else {
            $this->response($this->json([
                'success' => false,
                'message' => 'Failed to create booking: ' . $booking_id
            ]), 500);
        }

    } else {
        $this->response($this->json([
            'success' => false,
            'message' => "Bad Request - Missing required parameters"
        ]), 400);
    }
};