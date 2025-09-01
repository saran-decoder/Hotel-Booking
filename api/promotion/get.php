<?php

${basename(__FILE__, '.php')} = function () {
    $id = $_GET['id'] ?? '';
    $promoID = Operations::getPromotion($id);
    $this->response($this->json($promoID), 200);
};