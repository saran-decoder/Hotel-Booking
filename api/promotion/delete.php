<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['id'])) {
        $id = (int)$this->_request['id'];

        $status = Admin::deletePromotion($id);

        if ($status) {
            $this->response($this->json([
                'success' => true,
                'message' => 'Promotion deleted successfully'
            ]), 200);
        } else {
            $this->response($this->json([
                'success' => false,
                'message' => 'Failed to delete Promotion'
            ]), 500);
        }
    } else {
        $this->response($this->json([
            'success' => false,
            'message' => 'Missing Promotion ID'
        ]), 400);
    }
};