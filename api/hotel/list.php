<?php

${basename(__FILE__, '.php')} = function () {
    $hotel = Operations::getAllHotels(); // You'll define this in User model
    $this->response($this->json($hotel), 200);
};