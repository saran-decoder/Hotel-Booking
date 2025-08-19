<?php

require_once "Database.class.php";

class Patient
{
    private $conn;

    public $id;
    public $username;
    public $table;

    public static function login($phone)
    {
        $query = "SELECT * FROM `patients` WHERE `contact` = '$phone'";
        $conn = Database::getConnection();
        $result = $conn->query($query);
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if ($phone == $row['contact']) {
                return $row['name'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //User object can be constructed with both UserID and Username.
    public function __construct($username)
    {
        // die($username);
        $this->conn = Database::getConnection();
        $this->username = $username;
        $this->id = null;
        $this->table = 'patients';
        $sql = "SELECT `id` FROM `patients` WHERE `name`= '$username' OR `id` = '$username' OR `contact` = '$username' LIMIT 1";
        $result = $this->conn->query($sql);
        if ($result->num_rows) {
            $row = $result->fetch_assoc();
            $this->id = $row['id']; //Updating this from database
        } else {
            throw new Exception("Username does't exist");
        }
    }

}