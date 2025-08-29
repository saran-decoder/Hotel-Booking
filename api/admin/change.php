<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['old', 'new', 'conf'])) {
        $old = $this->_request['old'];
        $new = $this->_request['new'];
        $conf = $this->_request['conf'];
        
        $result = Admin::changePassword($old, $new, $conf);
        
        if ($result === true) {
            $this->response($this->json([
                'success' => true,
                'message' => 'Password changed successfully'
            ]), 200);
        } else {
            $this->response($this->json([
                'success' => false,
                'message' => $result
            ]), 400);
        }
    } else {
        $this->response($this->json([
            'success' => false,
            'message' => 'Missing required parameters: old, new, conf'
        ]), 400);
    }
};