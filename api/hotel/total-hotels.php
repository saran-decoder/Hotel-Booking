<?php

${basename(__FILE__, '.php')} = function () {
    $hotel = Operations::getTotalHotels(); // You'll define this in User model
    $this->response($this->json($hotel), 200);
};