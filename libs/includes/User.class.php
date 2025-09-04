<?php

require_once "Database.class.php";

class User
{
    private $conn;

    public $id;
    public $username;
    public $table;

    public static function signup($name, $phone, $email, $dob, $gender, $city)
    {
        $conn = Database::getConnection();
        
        error_log("Database connection established");
        
        $sql = "INSERT INTO `users` (`username`, `email`, `phone`, `dob`, `gender`, `city`, `created_at`)
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
        
        error_log("SQL: $sql");
        error_log("Values: $name, $email, $phone, $dob, $gender, $city");
        
        try {
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                error_log("Prepare failed: " . $conn->error);
                return false;
            }
            
            $stmt->bind_param("ssssss", $name, $email, $phone, $dob, $gender, $city);
            
            if ($stmt->execute()) {
                error_log("User inserted successfully");
                return true;
            } else {
                error_log("Execute failed: " . $stmt->error);
                error_log("Error code: " . $stmt->errno);
                
                // Check if it's a duplicate entry error
                if ($stmt->errno == 1062) {
                    return "exists";
                }
                return false;
            }
        } catch (Exception $e) {
            error_log("Exception in signup: " . $e->getMessage());
            return false;
        }
    }
    
    public static function login($phone)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM `users` WHERE `phone` = ?");
        $stmt->bind_param("s", $phone);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                return $row['username']; // success
            }
        }
        return false; // user not found
    }

    public static function checkUser($phone) {
        $conn = Database::getConnection();
        
        $stmt = $conn->prepare("SELECT id FROM users WHERE phone = ?");
        $stmt->bind_param("s", $phone);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->num_rows === 1;
        }
        
        return false;
    }

    //User object can be constructed with both UserID and Username.
    public function __construct($username)
    {
        $this->conn = Database::getConnection();
        $this->username = $username;
        $this->id = null;
        $this->table = 'admin';
        $sql = "SELECT `id` FROM `users` WHERE `username`= '$username' OR `id` = '$username' OR `email` = '$username' LIMIT 1";
        $result = $this->conn->query($sql);
        if ($result->num_rows) {
            $row = $result->fetch_assoc();
            $this->id = $row['id']; //Updating this from database
        } else {
            throw new Exception("Username does't exist");
        }
    }
}