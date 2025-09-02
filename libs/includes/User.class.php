<?php

require_once "Database.class.php";

class User
{
    private $conn;

    public $id;
    public $username;
    public $table;

    public static function signup($user, $pass, $email, $phone)
    {
        $conn = Database::getConnection();
        $sql = "INSERT INTO `users` (`username`, `email`, `phone`, `password`, `created_at`)
        VALUES ('$user', '$email', '$phone', '$pass', NOW());";
        try {
            if ($conn->query($sql)) {;
                return true;
            } else {
                throw new Exception("Error creating user profile: " . $conn->error);
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public static function login($user, $pass)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM `users` WHERE `phone` = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();

            // For plain text password (development)
            if ($pass === $row['password']) {
                return $row['username']; // success
            }
            return false; // password mismatch
        }
        return false; // user not found
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