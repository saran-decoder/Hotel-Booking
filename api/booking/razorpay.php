<?php

require __DIR__ . "/../../libs/vendor/autoload.php";

use Razorpay\Api\Api;

${basename(__FILE__, '.php')} = function () {
    if ($this->get_request_method() != "POST") {
        $this->response($this->json([
            'message' => 'Method Not Allowed'
        ]), 405);
        return;
    }

    // Check if amount parameter exists
    if (!isset($this->_request['amount'])) {
        $this->response($this->json([
            'status' => 'error',
            'message' => 'Missing required parameter: amount'
        ]), 400);
        return;
    }

    $api_key    = get_config('razorpay_key');
    $api_secret = get_config('razorpay_secret');

    // Validate API keys
    if (empty($api_key) || empty($api_secret)) {
        $this->response($this->json([
            'status'  => 'error',
            'message' => 'Razorpay configuration missing'
        ]), 500);
        return;
    }

    $api = new Api($api_key, $api_secret);

    try {
        // Validate and sanitize amount
        $amount = floatval($this->_request['amount']);
        
        // Razorpay requires minimum amount of 1 INR (100 paise)
        if ($amount < 1) {
            $this->response($this->json([
                'status'  => 'error',
                'message' => 'Amount must be at least 1 INR'
            ]), 400);
            return;
        }
        
        // Convert to paise and round to nearest integer
        $amount_paise = round($amount * 100);

        // Generate a unique receipt ID
        $receipt_id = "rcpt_" . time() . "_" . rand(1000, 9999);
        
        $orderData = [
            'receipt'         => $receipt_id,
            'amount'          => $amount_paise,
            'currency'        => isset($this->_request['currency']) ? $this->_request['currency'] : 'INR',
            'payment_capture' => 1 // Auto capture payment
        ];

        $order = $api->order->create($orderData);

        $this->response($this->json([
            'status'    => 'success',
            'order_id'  => $order['id'],
            'amount'    => $order['amount'],
            'currency'  => $order['currency'],
            'receipt'   => $order['receipt'],
            'key'       => $api_key
        ]), 200);
    } catch (Exception $e) {
        error_log("Razorpay Error: " . $e->getMessage());
        $this->response($this->json([
            'status'  => 'error',
            'message' => 'Payment initialization failed: ' . $e->getMessage()
        ]), 500);
    }
};