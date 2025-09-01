<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists([
        'promotionId', 
        'hotel', 
        'promotionName', 
        'discount', 
        'couponCode', 
        'startDate', 
        'endDate', 
        'status'
    ])) {

        $promotionId = $this->_request['promotionId'];
        $hotel       = $this->_request['hotel'];
        $name        = $this->_request['promotionName'];
        $discount    = $this->_request['discount'];
        $coupon      = $this->_request['couponCode'];
        $start       = $this->_request['startDate'];
        $end         = $this->_request['endDate'];
        $status      = $this->_request['status'];
        $usageLimit  = $this->_request['usageLimit'] ?? null;
        $description = $this->_request['description'] ?? null;

        // Call Admin method
        $result = Admin::updatePromotion(
            $promotionId,
            $hotel, 
            $name, 
            $discount, 
            $coupon, 
            $start, 
            $end, 
            $status, 
            $usageLimit, 
            $description
        );

        if ($result['success']) {
            $this->response($this->json($result), 200);
        } else {
            $this->response($this->json($result), 400);
        }

    } else {
        $this->response($this->json([
            'success' => false,
            'message' => 'Missing required parameters'
        ]), 400);
    }
};