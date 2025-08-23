<?php

include "../../libs/load.php";

${basename(__FILE__, '.php')} = function () {
    // Check if user is authenticated
    if (!Session::isAuthenticated() || Session::get('session_type') !== 'admin') {
        return $this->response($this->json([
            'success' => false,
            'message' => 'Unauthorized access'
        ]), 401);
    }

    // Get hotel ID from query parameter
    $hotelId = isset($_GET['id']) ? intval($_GET['id']) : null;
    
    if (!$hotelId) {
        return $this->response($this->json([
            'success' => false,
            'message' => 'Hotel ID is required'
        ]), 400);
    }

    $hotel = Operations::getHotelDetails($hotelId);
    
    if ($hotel) {
        return $this->response($this->json([
            'success' => true,
            'data' => $hotel
        ]), 200);
    } else {
        return $this->response($this->json([
            'success' => false,
            'message' => 'Hotel not found'
        ]), 404);
    }
};