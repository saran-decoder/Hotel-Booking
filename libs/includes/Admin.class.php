<?php

require_once "Database.class.php";

class Admin
{
    private $conn;

    public $id;
    public $email;
    public $table;

    // Login function
    public static function login($user, $pass)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM `admin` WHERE `email` = ? OR `phone` = ?");
        $stmt->bind_param("ss", $user, $user);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();

            // For plain text password (development)
            if ($pass === $row['password']) {
                return $row['email']; // success
            }
            return false; // password mismatch
        }
        return false; // user not found
    }


    // Constructor: load Admin object by email or ID
    public function __construct($identifier)
    {
        $this->conn = Database::getConnection();
        $this->table = 'admin';

        $stmt = $this->conn->prepare("SELECT `id`, `email` FROM `admin` WHERE `email` = ? OR `id` = ? LIMIT 1");
        $stmt->bind_param("si", $identifier, $identifier);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows) {
            $row = $result->fetch_assoc();
            $this->id = $row['id'];
            $this->email = $row['email'];
        } else {
            throw new Exception("Admin user does not exist");
        }
    }

    public static function addHotel($name, $location, $coordinates, $address, $description, $amenities, $images)
    {
        $conn = Database::getConnection();
        
        try {
            // Convert amenities & images to JSON strings
            $imagesJson = json_encode($images, JSON_UNESCAPED_SLASHES);
            $amenitiesJson = json_encode($amenities, JSON_UNESCAPED_SLASHES);

            // Insert hotel with all data in one table
            $stmt = $conn->prepare("INSERT INTO hotels 
                (name, location_name, coordinates, address, description, amenities, images) 
                VALUES (?, ?, ?, ?, ?, ?, ?)");

            $stmt->bind_param("sssssss", $name, $location, $coordinates, $address, $description, $amenitiesJson, $imagesJson);

            if (!$stmt->execute()) {
                throw new Exception("Failed to insert hotel: " . $stmt->error);
            }

            $hotelId = $stmt->insert_id;
            $stmt->close();

            return $hotelId;

        } catch (Exception $e) {
            error_log("Hotel addition failed: " . $e->getMessage());
            return false;
        }
    }
    public static function updateHotel($id, $name, $location, $coordinates, $address, $description, $amenities, $images)
    {
        $conn = Database::getConnection();
        
        try {
            // Convert arrays to JSON strings for storage
            $imagesJson = json_encode($images, JSON_UNESCAPED_SLASHES);
            $amenitiesJson = json_encode($amenities, JSON_UNESCAPED_SLASHES);
            
            // Update hotel
            $stmt = $conn->prepare("UPDATE hotels SET name = ?, location_name = ?, coordinates = ?, address = ?, description = ?, amenities = ?, images = ? WHERE id = ?");
            $stmt->bind_param("sssssssi", $name, $location, $coordinates, $address, $description, $amenitiesJson, $imagesJson, $id);
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to update hotel: " . $stmt->error);
            }
            
            return true;
            
        } catch (Exception $e) {
            error_log("Hotel update failed: " . $e->getMessage());
            return false;
        }
    }
}