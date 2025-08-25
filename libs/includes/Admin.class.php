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
    public static function updateHotel($id, $name, $location, $coordinates, $address, $description, $amenities, $newImages = [], $imagesToDelete = [])
    {
        $conn = Database::getConnection();
        
        try {
            // First, get the current images
            $stmt = $conn->prepare("SELECT images FROM hotels WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $currentData = $result->fetch_assoc();
            
            // Parse current images
            $currentImages = json_decode($currentData['images'], true) ?? [];
            
            // Remove images that were marked for deletion
            $updatedImages = [];
            foreach ($currentImages as $currentImage) {
                // Check if this image should be deleted
                $shouldDelete = false;
                foreach ($imagesToDelete as $imageToDelete) {
                    // Compare using basename to handle different paths
                    if (basename($currentImage) === basename($imageToDelete)) {
                        $shouldDelete = true;
                        
                        // Delete the physical file
                        $filePath = __DIR__ . '/../' . $currentImage;
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                        break;
                    }
                }
                
                // Keep the image if it shouldn't be deleted
                if (!$shouldDelete) {
                    $updatedImages[] = $currentImage;
                }
            }
            
            // Add new images to the array
            foreach ($newImages as $newImage) {
                $updatedImages[] = $newImage;
            }
            
            // Remove any duplicates that might have been created
            $updatedImages = array_unique($updatedImages);
            // Reindex the array to ensure proper JSON encoding
            $updatedImages = array_values($updatedImages);
            
            // Convert arrays to JSON strings for storage
            $imagesJson = json_encode($updatedImages, JSON_UNESCAPED_SLASHES);
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
    public static function deleteRoom($id)
    {
        $conn = Database::getConnection();
        $roomId = (int)$id;

        // Step 1 + 2: Delete the room and fetch images in one query
        $deleteQuery = "DELETE FROM rooms WHERE id = $roomId RETURNING images";
        $result = $conn->query($deleteQuery);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if (!empty($row['images'])) {
                $roomImages = json_decode($row['images'], true);
                if (is_array($roomImages)) {
                    foreach ($roomImages as $file) {
                        if (file_exists($file)) {
                            unlink($file);
                        }
                    }
                }
            }
            return true;
        }

        return false;
    }

    // Create a new booking
    public static function createBooking($user_id, $hotel_id, $room_id, $check_in, $check_out, $adults, $children, $total_price)
    {
        $db = Database::getConnection();
        
        try {
            
            // Check room availability
            if (!self::isRoomAvailable($room_id, $check_in, $check_out)) {
                return "Room not available for selected dates";
            }
            
            // Generate booking reference
            $booking_ref = self::generateBookingReference();
            
            // Insert booking - FIXED parameter types
            $stmt = $db->prepare("INSERT INTO bookings (booking_ref, user_id, hotel_id, room_id, check_in_date, check_out_date, adults, children, total_price, status, created_at) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'confirmed', NOW())");
            
            // Changed from "ssiissiid" to "siissiid" - user_id might be integer, not string
            $stmt->bind_param("ssissiid", $booking_ref, $user_id, $hotel_id, $room_id, $check_in, $check_out, $adults, $children, $total_price);
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to create booking: " . $stmt->error);
            }
            
            $booking_id = $stmt->insert_id;

            return $booking_id;
            
        } catch (Exception $e) {
            // Rollback transaction on error
            $db->rollback();
            error_log("Booking creation error: " . $e->getMessage());
            return $e->getMessage();
        }
    }
    
    // Check if room is available for given dates
    public static function isRoomAvailable($room_id, $check_in, $check_out) {
        $db = Database::getConnection();
        
        $stmt = $db->prepare("SELECT COUNT(*) as overlapping_bookings 
                             FROM bookings 
                             WHERE room_id = ? 
                             AND status IN ('confirmed', 'checked_in')
                             AND (
                                 (check_in_date <= ? AND check_out_date > ?) OR
                                 (check_in_date < ? AND check_out_date >= ?) OR
                                 (check_in_date >= ? AND check_out_date <= ?)
                             )");
        
        $stmt->bind_param("issssss", $room_id, $check_out, $check_in, $check_out, $check_in, $check_in, $check_out);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['overlapping_bookings'] == 0;
    }
    
    // Generate unique booking reference in format: [A-Z][1-9][A-Z][1-9]-[01-99]
    private static function generateBookingReference() {
        // Get total booking count to create sequence number
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM bookings");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        $sequence_number = str_pad($row['total'] + 1, 2, '0', STR_PAD_LEFT);
        
        // Generate random uppercase letters and numbers
        $first_char = chr(rand(65, 90)); // A-Z
        $first_num = rand(1, 9); // 1-9
        $second_char = chr(rand(65, 90)); // A-Z
        $second_num = rand(1, 9); // 1-9
        
        return $first_char . $first_num . $second_char . $second_num . '-' . $sequence_number;
    }
    
    // Get booking details by ID
    public static function getBookingById($booking_id) {
        $db = Database::getConnection();
        
        $stmt = $db->prepare("SELECT b.*, h.hotel_name, h.hotel_location, r.room_type, r.price_per_night
                             FROM bookings b
                             JOIN hotels h ON b.hotel_id = h.id
                             JOIN rooms r ON b.room_id = r.id
                             WHERE b.id = ?");
        
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $booking = $result->fetch_assoc();
        
        return $booking;
    }
}