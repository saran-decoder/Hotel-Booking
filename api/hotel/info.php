<?php

${basename(__FILE__, '.php')} = function () {
    // Get hotel ID from query parameter
    $hotelId = isset($_GET['id']) ? intval($_GET['id']) : null;
    
    $hotel = Operations::getHotelRooms($hotelId);
    $this->response($this->json($hotel), 200);
};