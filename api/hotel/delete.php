<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['id'])) {
        $id = (int)$this->_request['id'];

        $status = Admin::deleteHotel($id);

        if ($status) {
            $this->response($this->json([
                'success' => true,
                'message' => 'Hotel & Rooms deleted successfully'
            ]), 200);
        } else {
            $this->response($this->json([
                'success' => false,
                'message' => 'Failed to delete Hotel & Rooms'
            ]), 500);
        }
    } else {
        $this->response($this->json([
            'success' => false,
            'message' => 'Missing Hotel ID'
        ]), 400);
    }
};