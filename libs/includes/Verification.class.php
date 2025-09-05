<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

class Verification
{
    public static function sendEmailVerification($email, $userId = 0)
    {
        $conn = Database::getConnection();
        
        // Generate 6-digit code
        $emailCode = sprintf("%06d", random_int(0, 999999));
        
        // Set expiry time (10 minutes from now)
        $expiresAt = date('Y-m-d H:i:s', strtotime('+10 minutes'));
        
        if ($userId > 0) {
            // Existing user - store in database
            $query = "INSERT INTO `verification` (`user_id`, `email`, `email_code`, `email_code_expires`, `created_at`) 
                      VALUES ('$userId', '$email', '$emailCode', '$expiresAt', NOW())
                      ON DUPLICATE KEY UPDATE 
                      `email_code` = '$emailCode', 
                      `email_code_expires` = '$expiresAt',
                      `email_verified` = 0";
            
            if (!$conn->query($query)) {
                error_log("Database error: " . $conn->error);
                return false;
            }
        } else {
            // New user - store in session temporarily
            $_SESSION['temp_email_otp_' . $email] = $emailCode;
            $_SESSION['temp_email_otp_time_' . $email] = time();
            $_SESSION['temp_email_otp_expires_' . $email] = strtotime('+10 minutes');
        }
        
        // Send email using PHPMailer
        $mail = new PHPMailer(true);
        
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Set your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'saranmass685@gmail.com'; // SMTP username
            $mail->Password = 'hsvt dntq qfak bgvq'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;
            
            // Recipients
            $mail->setFrom('noreply@tnbooking.in', 'TNBooking');
            $mail->addAddress($email);
            
            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Your TNBooking Verification Code';
            $mail->Body = "
                <h2>Email Verification Code</h2>
                <p>Your verification code for TNBooking is: <strong>$emailCode</strong></p>
                <p>This code will expire in 10 minutes.</p>
                <br>
                <p>If you didn't request this code, please ignore this email.</p>
                <hr>
                <p>Best regards,<br>TNBooking Team</p>
            ";
            
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Email could not be sent. Error: {$mail->ErrorInfo}");
            return false;
        }
    }

    public static function verifyEmailCode($email, $code, $userId = 0)
    {
        $conn = Database::getConnection();
        $code = trim($conn->real_escape_string($code));
        $email = $conn->real_escape_string($email);

        if ($userId > 0) {
            // Existing user - check database
            $query = "SELECT `email_code`, `email_code_expires` FROM `verification` 
                    WHERE `email` = '$email' AND `user_id` = '$userId'";

            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $dbCode = trim($row['email_code']);
                $expiryTimestamp = strtotime($row['email_code_expires']);

                // Validate expiry time
                if ($expiryTimestamp === false) {
                    return "Invalid expiry timestamp in database.";
                }

                if (time() > $expiryTimestamp) {
                    return "Verification code expired. Please request a new one.";
                }

                if ($dbCode !== $code) {
                    return "Invalid verification code.";
                }

                $update = $conn->query("UPDATE `verification` SET `email_verified` = 1, `verified_at` = NOW() WHERE `email` = '$email' AND `user_id` = '$userId'");
                if ($update) {
                    Session::set('email_verified', 'verified');
                    return true;
                } else {
                    return "Failed to update verification status.";
                }
            } else {
                return "Verification record not found.";
            }
        } else {
            // New user - check session
            $sessionOtpKey = 'temp_email_otp_' . $email;
            $sessionExpiresKey = 'temp_email_otp_expires_' . $email;

            if (isset($_SESSION[$sessionOtpKey]) && 
                isset($_SESSION[$sessionExpiresKey]) &&
                $_SESSION[$sessionOtpKey] == $code) {
                
                // Check if code is expired
                if (time() > $_SESSION[$sessionExpiresKey]) {
                    // Clear expired code
                    unset($_SESSION[$sessionOtpKey]);
                    unset($_SESSION['temp_email_otp_time_' . $email]);
                    unset($_SESSION[$sessionExpiresKey]);
                    return "Verification code expired. Please request a new one.";
                }

                // Clear the temp code after verification
                unset($_SESSION[$sessionOtpKey]);
                unset($_SESSION['temp_email_otp_time_' . $email]);
                unset($_SESSION[$sessionExpiresKey]);
                
                Session::set('email_verified', 'verified');
                return true;
            } else {
                return "Invalid or expired verification code.";
            }
        }
    }

    public static function sendSMSVerification($phone, $userId = 0)
    {
        $conn = Database::getConnection();
        
        // Generate same digits pairs OTP (6-digit)
        $pairs = ['00', '11', '22', '33', '44', '55', '66', '77', '88', '99'];
        shuffle($pairs);
        $otp = $pairs[0] . $pairs[1] . $pairs[2];

        // Set expiry time (10 minutes from now)
        $expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));

        if ($userId > 0) {
            // Existing user - store in database
            $query = "INSERT INTO `verification` (`user_id`, `phone`, `sms_code`, `sms_code_expires`, `created_at`) 
                    VALUES ('$userId', '$phone', '$otp', '$expires_at', NOW())
                    ON DUPLICATE KEY UPDATE 
                    `sms_code` = '$otp', 
                    `sms_code_expires` = '$expires_at',
                    `sms_verified` = 0";
                    
            if (!$conn->query($query)) {
                error_log("Database error: " . $conn->error);
                return false;
            }
        } else {
            // New user - store in session temporarily
            $_SESSION['temp_sms_otp_' . $phone] = $otp;
            $_SESSION['temp_sms_otp_time_' . $phone] = time();
            $_SESSION['temp_sms_otp_expires_' . $phone] = strtotime('+10 minutes');
        }

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
        @file_get_contents($sms_url);

        return true;
    }

    public static function verifySMSCode($phone, $code, $userId = 0)
    {
        $conn = Database::getConnection();
        $code = trim($conn->real_escape_string($code));
        $phone = $conn->real_escape_string($phone);

        if ($userId > 0) {
            // Existing user - check database with proper validation
            $query = "SELECT `sms_code`, `sms_code_expires` 
                    FROM `verification` 
                    WHERE `phone` = '$phone' AND `user_id` = '$userId'
                    ORDER BY `id` DESC 
                    LIMIT 1";

            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // Validate that we have proper data
                if (empty($row['sms_code']) || empty($row['sms_code_expires'])) {
                    return "Invalid verification configuration.";
                }

                // Validate expiry time
                $expiryTimestamp = strtotime($row['sms_code_expires']);
                if ($expiryTimestamp === false) {
                    return "Invalid expiry timestamp in database.";
                }

                // Check if OTP is expired
                if (time() > $expiryTimestamp) {
                    return "OTP expired. Please request a new one.";
                }

                // Strict comparison of OTP codes
                if ($row['sms_code'] !== $code) {
                    return "Invalid OTP entered.";
                }

                // If we reached here, OTP is valid
                $update = $conn->query("
                    UPDATE `verification` 
                    SET `sms_verified` = 1, `verified_at` = NOW() 
                    WHERE `phone` = '$phone' AND `user_id` = '$userId'
                ");

                if ($update) {
                    Session::set('sms_verified', 'verified');
                    return true;
                } else {
                    return "Failed to update verification status.";
                }
            } else {
                return "Verification record not found.";
            }
        } else {
            // New user - check session with proper validation
            $sessionOtpKey = 'temp_sms_otp_' . $phone;
            $sessionTimeKey = 'temp_sms_otp_time_' . $phone;
            $sessionExpiresKey = 'temp_sms_otp_expires_' . $phone;

            if (isset($_SESSION[$sessionOtpKey]) && 
                isset($_SESSION[$sessionExpiresKey]) &&
                $_SESSION[$sessionOtpKey] == $code) {
                
                // Check if OTP is expired
                if (time() > $_SESSION[$sessionExpiresKey]) {
                    // Clear expired OTP
                    unset($_SESSION[$sessionOtpKey]);
                    unset($_SESSION[$sessionTimeKey]);
                    unset($_SESSION[$sessionExpiresKey]);
                    return "OTP expired. Please request a new one.";
                }

                // Clear the temp OTP after successful verification
                unset($_SESSION[$sessionOtpKey]);
                unset($_SESSION[$sessionTimeKey]);
                unset($_SESSION[$sessionExpiresKey]);
                
                Session::set('sms_verified', 'verified');
                return true;
            } else {
                return "Invalid or expired OTP.";
            }
        }
    }
    
    public static function resendEmailCode($userId, $email)
    {
        return self::sendEmailVerification($email, $userId);
    }
    
    public static function resendSMSCode($userId, $phone)
    {
        return self::sendSMSVerification($phone, $userId);
    }
}