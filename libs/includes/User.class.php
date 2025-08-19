<?php

require_once "Database.class.php";

class User
{
    private $conn;

    public $id;
    public $username;
    public $table;

    public static function login($user, $pass)
    {
        $query = "SELECT * FROM `admin` WHERE `username` = '$user' OR `email` = '$user' OR `phone` = '$user'";
        $conn = Database::getConnection();
        $result = $conn->query($query);
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if ($pass == $row['password']) {
                return $row['username'];
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
        $this->conn = Database::getConnection();
        $this->username = $username;
        $this->id = null;
        $this->table = 'admin';
        $sql = "SELECT `id` FROM `admin` WHERE `username`= '$username' OR `id` = '$username' OR `email` = '$username' LIMIT 1";
        $result = $this->conn->query($sql);
        if ($result->num_rows) {
            $row = $result->fetch_assoc();
            $this->id = $row['id']; //Updating this from database
        } else {
            throw new Exception("Username does't exist");
        }
    }

    public static function setAdmin($respect, $name, $email, $password, $departments)
    {
        $conn = Database::getConnection();
        
        // Validate input
        if (empty($respect) || empty($name) || empty($email) || empty($password) || empty($departments)) {
            return "All fields are required";
        }

        // Convert array of department IDs to comma-separated string
        $departmentsStr = is_array($departments) ? implode(',', $departments) : $departments;

        try {
            // Insert doctor with departments
            $stmt = $conn->prepare("INSERT INTO `doctors` (`respect`, `name`, `email`, `password`, `department`, `created_at`) 
                                VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("sssss", $respect, $name, $email, $password, $departmentsStr);
            $stmt->execute();
            
            return "Doctor added successfully";
        } catch (Exception $e) {
            return "Error adding doctor: " . $e->getMessage();
        }
    }
    public static function updateAdmin($id, $respect, $name, $email, $password, $departments)
    {
        $conn = Database::getConnection();
        
        // Validate input
        if (empty($id) || empty($respect) || empty($name) || empty($email) || empty($departments)) {
            return "All fields except password are required";
        }

        // Convert array of department IDs to comma-separated string
        $departmentsStr = is_array($departments) ? implode(',', $departments) : $departments;

        try {
            // Update doctor - handle password only if provided
            if (!empty($password)) {
                $stmt = $conn->prepare("UPDATE `doctors` SET `respect`=?, `name`=?, `email`=?, `password`=?, `department`=? WHERE `id`=?");
                $stmt->bind_param("sssssi", $respect, $name, $email, $password, $departmentsStr, $id);
            } else {
                $stmt = $conn->prepare("UPDATE `doctors` SET `respect`=?, `name`=?, `email`=?, `department`=? WHERE `id`=?");
                $stmt->bind_param("ssssi", $respect, $name, $email, $departmentsStr, $id);
            }
            $stmt->execute();

            return "Doctor updated successfully";
        } catch (Exception $e) {
            return "Error updating doctor: " . $e->getMessage();
        }
    }
    public static function deleteAdmin($id)
    {
        $conn = Database::getConnection();
        $query = "DELETE FROM `doctors` WHERE `id` = $id";
        return mysqli_query($conn, $query) ? "Doctor deleted successfully" : false;
    }


    public static function addPatient($respect, $name, $contact, $dob, $gender, $address, $city, $pincode)
    {
        $conn = Database::getConnection();
        $query = "INSERT INTO `patients` 
            (`respect`, `name`, `contact`, `dob`, `gender`, `address`, `city`, `pincode`, `created_at`) 
            VALUES 
            ('$respect', '$name', '$contact', '$dob', '$gender', '$address', '$city', '$pincode', NOW())";

        return mysqli_query($conn, $query) ? "Patient added successfully" : false;
    }
    public static function updatePatient($id, $respect, $name, $contact, $dob, $gender, $address, $city, $pincode)
    {
        $conn = Database::getConnection();
        $query = "UPDATE `patients` SET 
            `respect` = '$respect',
            `name` = '$name',
            `contact` = '$contact',
            `dob` = '$dob',
            `gender` = '$gender',
            `address` = '$address',
            `city` = '$city',
            `pincode` = '$pincode',
            `created_at` = NOW()
            WHERE `id` = $id";

        return mysqli_query($conn, $query) ? "Patient updated successfully" : false;
    }
    public static function deletePatient($id)
    {
        $conn = Database::getConnection();
        $query = "DELETE FROM `patients` WHERE `id` = $id";
        return mysqli_query($conn, $query) ? "Patient deleted successfully" : false;
    }

}