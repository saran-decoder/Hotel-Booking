<?php

require __DIR__ . "/../../libs/vendor/autoload.php";

use Razorpay\Api\Api;

${basename(__FILE__, '.php')} = function () {
    if ($this->get_request_method() != "POST") {
        $this->response($this->json(['message' => 'Method Not Allowed']), 405);
        return;
    }

    // Check if all required parameters exist
    $required_params = ['razorpay_payment_id', 'razorpay_order_id', 'razorpay_signature', 'booking_id', 'amount'];
    $missing_params = [];
    
    foreach ($required_params as $param) {
        if (!isset($this->_request[$param])) {
            $missing_params[] = $param;
        }
    }
    
    if (!empty($missing_params)) {
        $this->response($this->json([
            'status' => 'error',
            'message' => 'Missing parameters',
            'missing_params' => $missing_params
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
        $attributes = [
            'razorpay_order_id'   => $this->_request['razorpay_order_id'],
            'razorpay_payment_id' => $this->_request['razorpay_payment_id'],
            'razorpay_signature'  => $this->_request['razorpay_signature']
        ];

        $api->utility->verifyPaymentSignature($attributes);
        
        // Store payment details in database
        $db = Database::getConnection();
        
        // Get user information
        $user_id = null;
        $username = null;
        
        // Start session if not already started
        Session::start();
        
        if(Session::get('user_id')) {
            $user_id = Session::get('user_id');
        } else if(Session::get('username')) {
            $username = Session::get('username');
        }
        
        $stmt = $db->prepare("INSERT INTO payments (payment_id, order_id, signature, amount, currency, status, booking_id, user_id, username, created_at) 
                             VALUES (?, ?, ?, ?, 'INR', 'completed', ?, ?, ?, NOW())");
        
        $payment_id = $this->_request['razorpay_payment_id'];
        $order_id = $this->_request['razorpay_order_id'];
        $signature = $this->_request['razorpay_signature'];
        $amount = floatval($this->_request['amount']);
        $booking_id = $this->_request['booking_id'];
        
        $stmt->bind_param('sssdiss', 
            $payment_id,
            $order_id,
            $signature,
            $amount,
            $booking_id,
            $user_id,
            $username
        );
        
        if($stmt->execute()) {
            // Update booking status to confirmed
            $updateBooking = $db->prepare("UPDATE bookings SET status = 'confirmed', payment_status = 'completed', payment_id = ? WHERE id = ?");
            $updateBooking->bind_param('ss', $payment_id, $booking_id);
            $updateBooking->execute();
            
            $this->response($this->json([
                'status' => 'success',
                'message' => 'Payment verified and recorded',
                'booking_id' => $booking_id,
                'payment_id' => $payment_id
            ]), 200);
        } else {
            $this->response($this->json([
                'status' => 'error',
                'message' => 'Failed to record payment: ' . $db->error
            ]), 500);
        }

    } catch (Exception $e) {
        error_log("Razorpay verification error: " . $e->getMessage());
        $this->response($this->json([
            'status' => 'error',
            'message' => 'Payment verification failed: ' . $e->getMessage()
        ]), 400);
    }
};