<?php

${basename(__FILE__, '.php')} = function () {
    $id = $_GET['id'] ?? ''; // Get department from URL
    $hotelID = Operations::getRoom($id); // Pass department
    $this->response($this->json($hotelID), 200);
};