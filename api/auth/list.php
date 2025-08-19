<?php

${basename(__FILE__, '.php')} = function () {
    $settings = Operations::getAllSettings(); // You'll define this in User model
    $this->response($this->json($settings), 200);
};