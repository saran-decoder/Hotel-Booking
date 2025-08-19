<?php

${basename(__FILE__, '.php')} = function () {
    
    $counts = [
        'departments' => Operations::getDptCount(),
        'doctors'     => Operations::getDrCount(),
        'patients'    => Operations::getPatientCount(),
        'tokens'      => Operations::getTokenCount()
    ];

    $this->response($this->json($counts), 200);
};