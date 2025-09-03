<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

class Verification
{
    public static function sendEmailVerification($email, $userId)
    {
        $conn = Database::getConnection();
        
        // Generate 6-digit code
        $emailCode = sprintf("%06d", random_int(0, 999999));
        
        // Set expiry time (10 minutes from now)
        $expiresAt = date('Y-m-d H:i:s', strtotime('+10 minutes'));
        
        // Store in database
        $query = "INSERT INTO `verification` (`user_id`, `email`, `email_code`, `email_code_expires`, `created_at`) 
                  VALUES ('$userId', '$email', '$emailCode', '$expiresAt', NOW())
                  ON DUPLICATE KEY UPDATE 
                  `email_code` = '$emailCode', 
                  `email_code_expires` = '$expiresAt',
                  `email_verified` = 0";
        
        if ($conn->query($query)) {
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
        } else {
            error_log("Database error: " . $conn->error);
            return false;
        }
    }
    public static function verifyEmailCode($username, $code)
    {
        $conn = Database::getConnection();

        $code = trim($conn->real_escape_string($code));
        $email = $conn->real_escape_string($username);

        $query = "SELECT `email_code`, `email_code_expires` FROM `verification` 
                WHERE `email` = '$email'";

        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();

            $dbCode = trim($row['email_code']);
            $expiryTimestamp = $row['email_code_expires'];

            if ($dbCode === $code && $expiryTimestamp >= time()) {
                $update = $conn->query("UPDATE `verification` SET `email_verified` = 1, `verified_at` = NOW() WHERE `email` = '$email'");
                if ($update) {
                    Session::set('email_verified', 'verified');
                    return true;
                } else {
                    return "Failed to update verification status.";
                }
            } else {
                return "Invalid or expired code.";
            }
        } else {
            return "Verification record not found.";
        }
    }

    public static function sendSMSVerification($phone, $userId)
    {
        $conn = Database::getConnection();
        
        // Generate 6-digit code
        $smsCode = sprintf("%06d", random_int(0, 999999));
        
        date_default_timezone_set('Asia/Kolkata'); // ensure correct timezone

        // Generate same digits pairs OTP (6-digit for example)
        $pairs = ['00', '11', '22', '33', '44', '55', '66', '77', '88', '99'];

        shuffle($pairs);
        $otp = $pairs[0] . $pairs[1] . $pairs[2];

        // Set expiry time (10 minutes from now)
        $expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));

        // Store in database
        $query = "INSERT INTO `verification` (`user_id`, `phone`, `sms_code`, `sms_code_expires`, `created_at`) 
                VALUES ('$userId', '$phone', '$otp', '$expires_at', NOW())
                ON DUPLICATE KEY UPDATE 
                `sms_code` = '$otp', 
                `sms_code_expires` = '$expires_at',
                `sms_verified` = 0";
                
        if ($conn->query($query)) {
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
            error_log("Database error: " . $conn->error);
            return false;
        }
    }
    public static function verifySMSCode($userId, $code)
    {
        $conn = Database::getConnection();
        
        $code   = $conn->real_escape_string(trim($code));
        $userId = $conn->real_escape_string($userId);

        // Always set timezone before working with dates
        date_default_timezone_set('Asia/Kolkata');

        $query = "SELECT `sms_code`, `sms_code_expires` 
                FROM `verification` 
                WHERE `user_id` = '$userId' 
                ORDER BY `id` DESC 
                LIMIT 1";

        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if (empty($row['sms_code']) || empty($row['sms_code_expires'])) {
                return "Invalid verification configuration.";
            }

            $expiryTimestamp = strtotime($row['sms_code_expires']);
            if ($expiryTimestamp === false) {
                return "Invalid expiry timestamp in database.";
            }

            if (time() > $expiryTimestamp) {
                return "OTP expired. Please request a new one.";
            }

            if ($row['sms_code'] !== $code) {
                return "Invalid OTP entered.";
            }

            // ✅ If we reached here, OTP is valid
            $update = $conn->query("
                UPDATE `verification` 
                SET `sms_verified` = 1, `verified_at` = NOW() 
                WHERE `user_id` = '$userId'
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

?>