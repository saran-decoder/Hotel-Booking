<?php

class WebAPI
{
    public function __construct()
    {
        global $__site_config;
        $__site_config_path = __DIR__.'/../../env/config.json';
        $__site_config = file_get_contents($__site_config_path);
        Database::getConnection();
    }

    public function initiateSession()
    {
        Session::start();
        if (Session::isset("session_token")) {
            try {
                Session::$usersession = UserSession::authorize(Session::get('session_token')); 
            } 
            catch (Exception $e){
                error_log("Session authorization failed: " . $e->getMessage());
                Session::unset("session_token");
            }
        }
    }
}
