<?php

class Session
{
    public static $isError = false;
    public static $user = null;
    public static $usersession = null;
    
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            // Ensure no output has been sent
            if (headers_sent()) {
                throw new RuntimeException('Cannot start session, headers already sent');
            }
            
            // Set session parameters before starting
            ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 30); // 1 month
            session_set_cookie_params([
                'lifetime' => 60 * 60 * 24 * 30,
                'path' => '/',
                'domain' => $_SERVER['HTTP_HOST'] ?? '',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
            
            session_start();
        }
    }

    public static function regenerate()
    {
        session_regenerate_id(true);
    }

    public static function unset()
    {
        session_unset();
    }
    public static function destroy()
    {
        session_destroy();
        /*
        If UserSession is active, set it to inactive.
        */
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function delete($key)
    {
        unset($_SESSION[$key]);
    }

    public static function isset($key)
    {
        return isset($_SESSION[$key]);
    }

    public static function get($key, $default=false)
    {
        if (Session::isset($key)) {
            return $_SESSION[$key];
        } else {
            return $default;
        }
    }

    public static function getUser()
    {
        return Session::$user;
    }

    public static function getUserSession()
    {
        return Session::$usersession;
    }

    /**
     * Takes an email as input and returns if the session user has same email
     *
     * @param string $owner
     * @return boolean
     */
    public static function isOwnerOf($owner){
        $sess_user = Session::getUser();
        if ($sess_user) {
            if (
                (method_exists($sess_user, 'getEmail') && $sess_user->getEmail() == $owner) ||
                (method_exists($sess_user, 'getPhone') && $sess_user->getPhone() == $owner)
            ) {
                return true;
            }
        }
        return false;
    }


    public static function currentScript()
    {
        return basename($_SERVER['SCRIPT_NAME'], '.php');
    }

    public static function isAuthenticated()
    {
        if (Session::getUserSession() instanceof UserSession) {
            return Session::getUserSession()->isValid();
        }
        return false;
    }
}
