<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['id'])) {
        $id = (int)$this->_request['id'];

        $status = Admin::deleteRoom($id);

        if ($status) {
            $this->response($this->json([
                'success' => true,
                'message' => 'Room deleted successfully'
            ]), 200);
        } else {
            $this->response($this->json([
                'success' => false,
                'message' => 'Failed to delete Room'
            ]), 500);
        }
    } else {
        $this->response($this->json([
            'success' => false,
            'message' => 'Missing Hotel ID'
        ]), 400);
    }
};