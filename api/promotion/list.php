<?php

${basename(__FILE__, '.php')} = function () {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    
    $promotions = Operations::getAllPromotionWithPagination($page, $limit);
    $total = Operations::getPromotionCount();
    
    $response = [
        'promotions' => $promotions,
        'total' => $total,
        'total_pages' => ceil($total / $limit),
        'current_page' => $page
    ];
    
    $this->response($this->json($response), 200);
};