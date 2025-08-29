<?php

${basename(__FILE__, '.php')} = function () {
    $booking = Operations::getAllBookingLists();
    
    // Check if bookings were found
    if ($booking) {
        $response = [
            'success' => true,
            'data' => $booking
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No bookings found'
        ];
    }
    
    $this->response($this->json($response), 200);
};