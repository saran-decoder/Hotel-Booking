<?php

${basename(__FILE__, '.php')} = function () {
    $payments = Operations::getAllPayments();
    $stats = Operations::getPaymentStats();
    
    if ($payments) {
        $response = [
            'success' => true,
            'data' => $payments,
            'stats' => $stats
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No payments found',
            'stats' => [
                'total_revenue' => 0,
                'pending_payments' => 0,
                'refunds_count' => 0,
                'total_payments' => 0
            ]
        ];
    }
    
    $this->response($this->json($response), 200);
};