<?php

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

    public static function authenticate($user, $pass)
    {
        $username = User::login($user, $pass);
        if ($username) {
            $user = new User($username);
            $conn = Database::getConnection();
            $ip = $_SERVER['REMOTE_ADDR'];
            $agent = $_SERVER['HTTP_USER_AGENT'];

            $token = md5(random_int(0, 9999999) . $ip . $agent . time());
            $sql = "INSERT INTO `session` (`uid`, `token`, `login_time`, `ip`, `user_agent`, `active`, `type`)
            VALUES ('$user->id', '$token', now(), '$ip', '$agent', '1', 'admin')";
            if ($conn->query($sql)) {
                Session::set('session_token', $token);
                Session::set('session_type', 'admin');
                return $token;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function authenticateAdmin($user, $pass)
    {
        $username = Admin::login($user, $pass);
        if ($username) {
            $admin = new Admin($username);
            $conn = Database::getConnection();
            $ip = $_SERVER['REMOTE_ADDR'];
            $agent = $_SERVER['HTTP_USER_AGENT'];

            $token = md5(random_int(0, 9999999) . $ip . $agent . time());

            $sql = "INSERT INTO `session` (`uid`, `token`, `login_time`, `ip`, `user_agent`, `active`, `type`)
                    VALUES (?, ?, NOW(), ?, ?, 1, 'admin')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isss", $admin->id, $token, $ip, $agent);

            if ($stmt->execute()) {
                Session::set('session_token', $token);
                Session::set('session_type', 'admin');
                Session::set('username', $username);
                return $token;
            }
        }
        return false;
    }

    public static function authenticateUser($phone)
    {
        $username = Patient::login($phone);
        if ($username) {
            $user = new Patient($username);
            $conn = Database::getConnection();
            $ip = $_SERVER['REMOTE_ADDR'];
            $agent = $_SERVER['HTTP_USER_AGENT'];

            $token = md5(random_int(0, 9999999) . $ip . $agent . time());
            $sql = "INSERT INTO `session` (`uid`, `token`, `login_time`, `ip`, `user_agent`, `active`, `type`)
                    VALUES ('$user->id', '$token', now(), '$ip', '$agent', '1', 'patient')";
            if ($conn->query($sql)) {
                // Set session
                Session::set('session_token', $token);
                Session::set('session_type', 'patient');
                Session::set('username', $username);
                Session::set('contact', $phone);

                // Generate same digits pairs OTP (4-digit for example)
                $pairs = ['00', '11', '22', '33', '44', '55', '66', '77', '88', '99'];

                shuffle($pairs);
                $otp = $pairs[0] . $pairs[1];

                // Set expiry time (5 minutes from now)
                $expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));

                // Insert OTP into DB
                $insertOtp = "INSERT INTO `patient_otp` (`phone`, `otp`, `created_at`, `expires_at`)
                            VALUES ('$phone', '$otp', NOW(), '$expires_at')";
                $conn->query($insertOtp);

                // Compose the SMS message
                $message = "The one time password for your account is $otp. Please use the password to verify the account. Thanks! - XLoan India ZEDUAPP";

                // Build the API URL
                $sms_url = "https://port1.bmindz.com/pushapi/sendbulkmsg?" . http_build_query([
                    'username'   => 'zeduvpy',
                    'dest'       => $phone,
                    'apikey'     => '4QxHXhEEDfbFKVtZjtsYCPp4ioB0gDcN',
                    'signature'  => 'ZEDUAP',
                    'msgtype'    => 'PM',
                    'msgtxt'     => $message,
                    'entityid'   => '1201160360592377078',
                    'templateid' => '1207167361765622697'
                ]);

                // Send the SMS using file_get_contents (or use cURL)
                file_get_contents($sms_url);

                return true;
            }
        }

        return false;
    }
    public static function verifyOTP($phone, $otp)
    {
        $conn = Database::getConnection();

        // Sanitize input
        $phone = $conn->real_escape_string($phone);
        $otp = $conn->real_escape_string($otp);

        // Select all necessary columns including expires_at
        $query = "SELECT `id`, `phone`, `otp`, `verified`, `expires_at` FROM `patient_otp` WHERE `phone` = '$phone' ORDER BY `created_at` DESC LIMIT 1";
        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Check if expires_at exists in the row
            if (!isset($row['expires_at'])) {
                return "Invalid OTP configuration.";
            }

            $expiryTimestamp = strtotime($row['expires_at']);
            if ($expiryTimestamp === false) {
                return "Invalid or expired OTP.";
            }

            if ($row['otp'] == trim($otp) && $expiryTimestamp >= time() && $row['verified'] == 0) {
                // Update the verification status
                $update = $conn->query("UPDATE `patient_otp` SET `verified` = 1 WHERE `id` = " . $row['id']);
                
                if ($update) {
                    Session::set('session_status', 'verified');
                    return true;
                } else {
                    return "Failed to update OTP status.";
                }
            } else {
                return "Invalid or expired OTP.";
            }
        } else {
            return "User not found.";
        }
    }

    public static function resendOtp($phone)
    {
        $conn = Database::getConnection();
        
        // Generate same digits pairs OTP (4-digit for example)
        $pairs = ['00', '11', '22', '33', '44', '55', '66', '77', '88', '99'];

        shuffle($pairs);
        $otp = $pairs[0] . $pairs[1];

        // Set expiry time (5 minutes from now)
        $expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        // Insert OTP into DB
        $insertOtp = "UPDATE `patient_otp` SET `otp` = '$otp', `created_at` = NOW(), `expires_at` = '$expires_at' WHERE `phone` = '$phone'";
        
        if ($conn->query($insertOtp)) {

            // Compose the SMS message
            $message = "The one time password for your account is $otp. Please use the password to verify the account. Thanks! - XLoan India ZEDUAPP";

            // Build the API URL
            $sms_url = "https://port1.bmindz.com/pushapi/sendbulkmsg?" . http_build_query([
                'username'   => 'zeduvpy',
                'dest'       => $phone,
                'apikey'     => '4QxHXhEEDfbFKVtZjtsYCPp4ioB0gDcN',
                'signature'  => 'ZEDUAP',
                'msgtype'    => 'PM',
                'msgtxt'     => $message,
                'entityid'   => '1201160360592377078',
                'templateid' => '1207167361765622697'
            ]);

            // Send the SMS using file_get_contents (or use cURL)
            file_get_contents($sms_url);

            return true;
        } else {
            return false;
        }
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
            return new User($this->uid);
        } elseif ($this->data['type'] === 'doctor') {
            return new Doctor($this->uid);
        } elseif ($this->data['type'] === 'patient') {
            return new Patient($this->uid);
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