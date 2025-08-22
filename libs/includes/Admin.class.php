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

    public static function addHotel($owner, $name, $location, $coordinates, $address, $description, $amenities, $images)
    {
        $conn = Database::getConnection();
        
        try {
            // Convert amenities & images to JSON strings
            $imagesJson = json_encode($images, JSON_UNESCAPED_SLASHES);
            $amenitiesJson = json_encode($amenities, JSON_UNESCAPED_SLASHES);

            // Insert hotel with all data in one table
            $stmt = $conn->prepare("INSERT INTO hotels 
                (owner, name, location_name, coordinates, address, description, amenities, images) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->bind_param("ssssssss", $owner, $name, $location, $coordinates, $address, $description, $amenitiesJson, $imagesJson);

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
    public static function deleteHotel($id)
    {
        $conn = Database::getConnection();
        $id = (int)$id;

        // Step 1: Fetch hotel and room images
        $query = "SELECT h.images AS hotel_images, r.images AS room_images
                FROM hotels h
                LEFT JOIN rooms r ON h.id = r.hotel_id
                WHERE h.id = $id";
        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Delete hotel images (only once)
                if (!empty($row['hotel_images'])) {
                    $hotelImages = json_decode($row['hotel_images'], true);
                    if (is_array($hotelImages)) {
                        foreach ($hotelImages as $file) {
                            if (file_exists($file)) {
                                unlink($file);
                            }
                        }
                    }
                }

                // Delete room images
                if (!empty($row['room_images'])) {
                    $roomImages = json_decode($row['room_images'], true);
                    if (is_array($roomImages)) {
                        foreach ($roomImages as $file) {
                            if (file_exists($file)) {
                                unlink($file);
                            }
                        }
                    }
                }
            }
        }

        // Step 2: Delete DB records (rooms + hotel)
        $deleteQuery = "DELETE h, r 
                        FROM hotels h
                        LEFT JOIN rooms r ON h.id = r.hotel_id
                        WHERE h.id = $id";
        return $conn->query($deleteQuery) ? true : false;
    }
    
    public static function addRoom($hotelId, $roomType, $guestsAllowed, $description, $pricePerNight, $amenities, $images)
    {
        $conn = Database::getConnection();
        
        try {
            // Convert arrays to JSON strings for storage
            $imagesJson = json_encode($images, JSON_UNESCAPED_SLASHES);
            $amenitiesJson = json_encode($amenities, JSON_UNESCAPED_SLASHES);
            
            $status = 'active';

            $stmt = $conn->prepare("INSERT INTO rooms
                (hotel_id, room_type, guests_allowed, description, price_per_night, amenities, images, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->bind_param("isssdsss", 
                $hotelId, 
                $roomType, 
                $guestsAllowed, 
                $description, 
                $pricePerNight, 
                $amenitiesJson, 
                $imagesJson, 
                $status
            );

            if (!$stmt->execute()) {
                throw new Exception("Failed to insert room: " . $stmt->error);
            }

            $roomId = $stmt->insert_id;
            $stmt->close();

            return $roomId;

        } catch (Exception $e) {
            error_log("Room addition failed: " . $e->getMessage());
            return false;
        }
    }

    public static function updateRoom($roomId, $hotelId, $roomType, $guestsAllowed, $description, $pricePerNight, $amenities, $images, $status)
    {
        $conn = Database::getConnection();
        
        try {
            // Convert arrays to JSON strings for storage
            $imagesJson = json_encode($images, JSON_UNESCAPED_SLASHES);
            $amenitiesJson = json_encode($amenities, JSON_UNESCAPED_SLASHES);
            
            // Update room
            $stmt = $conn->prepare("UPDATE rooms SET 
                room_type = ?, 
                guests_allowed = ?, 
                description = ?, 
                price_per_night = ?, 
                amenities = ?, 
                images = ?,
                status = ?
                WHERE id = ? AND hotel_id = ?");

            $stmt->bind_param("sssdsssii", $roomType, $guestsAllowed, $description, $pricePerNight, $amenitiesJson, $imagesJson, $status, $roomId, $hotelId);

            if (!$stmt->execute()) {
                throw new Exception("Failed to update room: " . $stmt->error);
            }

            $affectedRows = $stmt->affected_rows;
            $stmt->close();

            return $affectedRows > 0;

        } catch (Exception $e) {
            error_log("Room update failed: " . $e->getMessage());
            return false;
        }
    }
}