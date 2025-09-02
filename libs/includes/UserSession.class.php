<?php

require_once "Database.class.php";
require_once("Session.class.php");

class UserSession
{
    /**
     * This function will return a session ID if username and password is correct.
     *
     * @return SessionID
     */

    public $data;
    public $id;
    public $conn;
    public $token;
    public $uid;

    // public static function authenticate($user, $pass)
    // {
    //     $username = User::login($user, $pass);
    //     if ($username) {
    //         $user = new User($username);
    //         $conn = Database::getConnection();
    //         $ip = $_SERVER['REMOTE_ADDR'];
    //         $agent = $_SERVER['HTTP_USER_AGENT'];

    //         $token = md5(random_int(0, 9999999) . $ip . $agent . time());
    //         $sql = "INSERT INTO `session` (`uid`, `token`, `login_time`, `ip`, `user_agent`, `active`, `type`)
    //         VALUES ('$user->id', '$token', now(), '$ip', '$agent', '1', 'admin')";
    //         if ($conn->query($sql)) {
    //             Session::set('session_token', $token);
    //             Session::set('session_type', 'admin');
    //             return $token;
    //         } else {
    //             return false;
    //         }
    //     } else {
    //         return false;
    //     }
    // }

    public static function authenticateAdmin($user, $pass)
    {
        $identifier = Admin::login($user, $pass);
        if ($identifier) {
            $admin = new Admin($identifier);
            $conn = Database::getConnection();
            $ip = $_SERVER['REMOTE_ADDR'];
            $agent = $_SERVER['HTTP_USER_AGENT'];

            $token = md5(random_int(0, 9999999) . $ip . $agent . time());

            $sql = "INSERT INTO `session` (`uid`, `token`, `login_time`, `ip`, `user_agent`, `active`, `type`)
                    VALUES ('$admin->id', '$token', NOW(), '$ip', '$agent', 1, 'admin')";

            if ($conn->query($sql)) {
                Session::set('session_token', $token);
                Session::set('session_type', 'admin');
                Session::set('username', $identifier);
                return $token;
            }
        }
        return false;
    }

    public static function authenticateUser($user, $pass)
    {
        $phone = $user;
        $username = User::login($user, $pass);
        if ($username) {
            $user = new User($username);
            $conn = Database::getConnection();
            $ip = $_SERVER['REMOTE_ADDR'];
            $agent = $_SERVER['HTTP_USER_AGENT'];

            $token = md5(random_int(0, 9999999) . $ip . $agent . time());
            $sql = "INSERT INTO `session` (`uid`, `token`, `login_time`, `ip`, `user_agent`, `active`, `type`)
                    VALUES ('$user->id', '$token', now(), '$ip', '$agent', '1', 'user')";
            if ($conn->query($sql)) {
                // Set session
                Session::set('session_token', $token);
                Session::set('session_type', 'user');
                Session::set('username', $username);
                Session::set('contact', $phone);
                Session::set('user_id', $user->id);
                Verification::sendSMSVerification($phone, $user->id);
                return $token;
            }
        }

        return false;
    }

    public static function authorize($token)
    {
        try {
            $session = new UserSession($token);
            if (isset($_SERVER['REMOTE_ADDR']) and isset($_SERVER["HTTP_USER_AGENT"])) {
                if ($session->isValid() and $session->isActive()) {
                    if ($_SERVER['REMOTE_ADDR'] == $session->getIP()) {
                        if ($_SERVER['HTTP_USER_AGENT'] == $session->getUserAgent()) {
                            Session::$user = $session->getUser();
                            return $session;
                        } else {
                            throw new Exception("User agent does't match");
                        }
                    } else {
                        throw new Exception("IP does't match");
                    }
                } else {
                    $session->removeSession();
                    error_log("Invalid session for token: $token");
                    throw new Exception("Invalid session");
                }
            } else {
                throw new Exception("IP and User_agent is null");
            }
        } catch (Exception $e) {
            throw new Exception("Authorization failed: " . $e->getMessage());
        }
    }


    /**
     * Cunstruct a user session with the given token
     *
     * @param SessionToken $token
     */
    public function __construct($token)
    {
        $this->conn = Database::getConnection();
        $this->token = $token;
        $this->data = null;
        $sql = "SELECT * FROM `session` WHERE `token`='$token' LIMIT 1";
        $result = $this->conn->query($sql);
        if ($result->num_rows) {
            $row = $result->fetch_assoc();
            $this->data = $row;
            $this->uid = $row['uid']; //Updating this from database
        } else {
            throw new Exception("Session is invalid.");
        }
    }

    public function getUser()
    {
        if (!isset($this->data['type'])) {
            throw new Exception("User type not set in session.");
        }

        if ($this->data['type'] === 'admin') {
            return new Admin($this->uid);
        } elseif ($this->data['type'] === 'user') {
            return new User($this->uid);
        } else {
            throw new Exception("Invalid user type in session.");
        }
    }

    /**
     * Check if the validity of the session is within one hour, else it inactive.
     *
     * @return boolean
     */
    public function isValid()
    {
        if (isset($this->data['login_time'])) {
            $login_time = DateTime::createFromFormat('Y-m-d H:i:s', $this->data['login_time']);
            if (2592000 > time() - $login_time->getTimestamp()) {
                return true;
            } else {
                return false;
            }
        } else {
            throw new Exception("login time is null");
        }
    }


    public function getIP()
    {
        return isset($this->data["ip"]) ? $this->data["ip"] : false;
    }

    public function getUserAgent()
    {
        return isset($this->data["user_agent"]) ? $this->data["user_agent"] : false;
    }

    public function isActive()
    {
        if (isset($this->data['active'])) {
            return $this->data['active'] ? true : false;
        }
    }

    //This function remove current session
    public function removeSession()
    {
        if (isset($this->data['id'])) {
            $id = $this->data['id'];
            if (!$this->conn) {
                $this->conn = Database::getConnection();
            }
            $sql = "DELETE FROM `session` WHERE `id` = $id;";
            if ($this->conn->query($sql)) {
                return true;
            } else {
                return false;
            }
        }
    }
}