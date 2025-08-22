<?php

${basename(__FILE__, '.php')} = function () {
    $room = Operations::getAllRooms(); // You'll define this in User model
    $this->response($this->json($room), 200);
};