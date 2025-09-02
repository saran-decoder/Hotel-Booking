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

    public static function changePassword($old, $new, $conf)
    {
        $conn = Database::getConnection();

        // Check if new password is same as old password
        if ($new === $old) {
            return "New password cannot be the same as the old password";
        }

        // Check if new password and confirm password match
        if ($new !== $conf) {
            return "New password and confirm password do not match";
        }

        // Get current admin user from session
        $username = Session::get("username");
        if (!$username) {
            return "User not logged in";
        }

        // Get current password from database
        $stmt = $conn->prepare("SELECT `password` FROM `admin` WHERE `email` = ? OR `phone` = ?");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows !== 1) {
            return "User not found";
        }

        $row = $result->fetch_assoc();
        $currentPassword = $row['password'];

        // Verify old password
        if ($old !== $currentPassword) {
            return "Old password is incorrect";
        }

        // Update password
        $stmt = $conn->prepare("UPDATE `admin` SET `password` = ? WHERE `email` = ? OR `phone` = ?");
        $stmt->bind_param("sss", $new, $username, $username);
        
        if ($stmt->execute()) {
            return true; // Success
        } else {
            return "Failed to update password";
        }
    }

    // Employee management methods
    public static function addEmployee($name, $email, $role) {
        $conn = Database::getConnection();
        $user = Session::get("username");
        
        // Validate input
        if (empty($name) || empty($email) || empty($role)) {
            return ['success' => false, 'message' => 'All fields are required'];
        }
        
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM workers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return ['success' => false, 'message' => 'Email already exists'];
        }
        
        try {
            $stmt = $conn->prepare("INSERT INTO workers (name, email, role, status, owner, created_at) VALUES (?, ?, ?, 'Active', ?, NOW())");
            $stmt->bind_param("ssss", $name, $email, $role, $user);
            
            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Employee added successfully'];
            } else {
                return ['success' => false, 'message' => 'Failed to add employee'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error adding employee: ' . $e->getMessage()];
        }
    }
    
    public static function editEmployee($id, $name, $email, $role) {
        $conn = Database::getConnection();
        
        // Validate input
        if (empty($id) || empty($name) || empty($email) || empty($role)) {
            return ['success' => false, 'message' => 'All fields except password are required'];
        }
        
        // Check if email already exists for another employee
        $stmt = $conn->prepare("SELECT id FROM workers WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return ['success' => false, 'message' => 'Email already exists for another employee'];
        }
        
        try {
            $stmt = $conn->prepare("UPDATE workers SET name = ?, email = ?, role = ? WHERE id = ?");
            $stmt->bind_param("sssi", $name, $email, $role, $id);
            
            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Employee updated successfully'];
            } else {
                return ['success' => false, 'message' => 'Failed to update employee'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error updating employee: ' . $e->getMessage()];
        }
    }
    
    public static function enableEmployee($id) {
        $conn = Database::getConnection();
        
        // Validate input
        if (empty($id)) {
            return ['success' => false, 'message' => 'Employee ID is required'];
        }
        
        try {
            // Check if employee exists
            $stmt = $conn->prepare("SELECT id FROM workers WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                return ['success' => false, 'message' => 'Employee not found'];
            }
            
            $stmt = $conn->prepare("UPDATE workers SET status = 'Active' WHERE id = ?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Employee enable successfully'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error enable employee: ' . $e->getMessage()];
        }
    }
    public static function disableEmployee($id) {
        $conn = Database::getConnection();
        
        // Validate input
        if (empty($id)) {
            return ['success' => false, 'message' => 'Employee ID is required'];
        }
        
        try {
            // Check if employee exists
            $stmt = $conn->prepare("SELECT id FROM workers WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                return ['success' => false, 'message' => 'Employee not found'];
            }
            
            $stmt = $conn->prepare("UPDATE workers SET status = 'Disbale' WHERE id = ?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Employee disabled successfully'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error disabling employee: ' . $e->getMessage()];
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
    public static function createBooking($user_id, $hotel_id, $room_id, $check_in, $check_out, $adults, $children, $total_price) {
        $db = Database::getConnection();
        
        try {
            // Double-check availability right before booking
            $availability = self::checkRoomAvailability($room_id, $check_in, $check_out);
            if (!$availability['available']) {
                return $availability['message'];
            }
            
            // Generate booking reference
            $booking_ref = self::generateBookingReference();
            
            // Convert dates to proper format
            $check_in_date = date('Y-m-d', strtotime($check_in));
            $check_out_date = date('Y-m-d', strtotime($check_out));
            
            // Insert booking
            $stmt = $db->prepare("INSERT INTO bookings (booking_ref, user_id, hotel_id, room_id, 
                                check_in_date, check_out_date, adults, children, total_price, status, payment_status, payment_id, created_at) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'confirmed', '0', '0', NOW())");
            
            $stmt->bind_param("ssiissiid", $booking_ref, $user_id, $hotel_id, $room_id, 
                            $check_in_date, $check_out_date, $adults, $children, $total_price);
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to create booking: " . $stmt->error);
            }
            
            $booking_id = $stmt->insert_id;
            return $booking_id;
            
        } catch (Exception $e) {
            error_log("Booking creation error: " . $e->getMessage());
            return $e->getMessage();
        }
    }

    // Generate unique booking reference
    private static function generateBookingReference() {
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

    // Check room availability with detailed response
    public static function checkRoomAvailability($room_id, $check_in, $check_out) {
        $db = Database::getConnection();
        
        // Validate dates
        if (!self::validateDates($check_in, $check_out)) {
            return [
                'available' => false,
                'message' => 'Invalid date range. Check-out date must be after check-in date.'
            ];
        }
        
        // Convert dates to proper format
        $check_in_date = date('Y-m-d', strtotime($check_in));
        $check_out_date = date('Y-m-d', strtotime($check_out));
        
        // Check for overlapping bookings
        $stmt = $db->prepare("SELECT b.id, b.booking_ref, b.check_in_date, b.check_out_date, 
                            u.username, u.email, b.status,
                            DATEDIFF(b.check_out_date, b.check_in_date) as nights_booked
                            FROM bookings b
                            JOIN users u ON b.user_id = u.id
                            WHERE b.room_id = ? 
                            AND b.status IN ('confirmed', 'checked_in')
                            AND (
                                (b.check_in_date < ? AND b.check_out_date > ?) OR
                                (b.check_in_date < ? AND b.check_out_date > ?) OR
                                (b.check_in_date >= ? AND b.check_out_date <= ?)
                            )
                            ORDER BY b.check_in_date ASC");
        
        $stmt->bind_param("issssss", $room_id, $check_out_date, $check_in_date, 
                        $check_out_date, $check_in_date, $check_in_date, $check_out_date);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $conflicting_bookings = [];
            
            while ($booking = $result->fetch_assoc()) {
                // Format the dates for display
                $conflict_check_in = date('M j, Y', strtotime($booking['check_in_date']));
                $conflict_check_out = date('M j, Y', strtotime($booking['check_out_date']));
                
                $conflicting_bookings[] = [
                    'booking_ref' => $booking['booking_ref'],
                    'check_in' => $conflict_check_in,
                    'check_out' => $conflict_check_out,
                    'nights' => $booking['nights_booked'],
                    'status' => $booking['status'],
                    'booked_by' => $booking['username']
                ];
            }
            
            $first_conflict = $conflicting_bookings[0];
            $message = "This room is already booked from {$first_conflict['check_in']} to {$first_conflict['check_out']}.";
            
            if (count($conflicting_bookings) > 1) {
                $message .= " There are " . (count($conflicting_bookings) - 1) . " other conflicting bookings.";
            }
            
            $message .= " Please select different dates or another room.";
            
            return [
                'available' => false,
                'message' => $message,
                'details' => $conflicting_bookings
            ];
        }
        
        return [
            'available' => true, 
            'message' => 'Room is available for selected dates'
        ];
    }

    // Validate date range
    private static function validateDates($check_in, $check_out) {
        $check_in_time = strtotime($check_in);
        $check_out_time = strtotime($check_out);
        
        // Check if dates are valid
        if (!$check_in_time || !$check_out_time) {
            return false;
        }
        
        // Check if check-out is after check-in
        if ($check_out_time <= $check_in_time) {
            return false;
        }
        
        // Check if dates are in the future
        if ($check_in_time < time() || $check_out_time < time()) {
            return false;
        }
        
        return true;
    }


    public static function addPromotion($hotel, $name, $discount, $coupon, $start, $end, $status, $usageLimit, $description) {
        $conn = Database::getConnection();
        $user = Session::get('username');
        try {
            $stmt = $conn->prepare("INSERT INTO promotions 
                (hotel_id, owner, promotion_name, discount, coupon_code, start_date, end_date, status, usage_limit, description, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

            $stmt->execute([
                $hotel,
                $user,
                $name,
                $discount,
                $coupon,
                $start,
                $end,
                $status,
                $usageLimit,
                $description
            ]);

            return ["success" => true, "message" => "Promotion added successfully"];
        } catch (Exception $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    public static function updatePromotion($promotionId, $hotel, $name, $discount, $coupon, $start, $end, $status, $usageLimit, $description) {
        $conn = Database::getConnection();

        try {
            $stmt = $conn->prepare("UPDATE promotions 
                SET hotel_id = ?, promotion_name = ?, discount = ?, coupon_code = ?, 
                    start_date = ?, end_date = ?, status = ?, usage_limit = ?, description = ?, updated_at = NOW()
                WHERE id = ?");

            $stmt->execute([
                $hotel,
                $name,
                $discount,
                $coupon,
                $start,
                $end,
                $status,
                $usageLimit,
                $description,
                $promotionId
            ]);

            return ["success" => true, "message" => "Promotion updated successfully"];
        } catch (Exception $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    public static function deletePromotion($id)
    {
        $conn = Database::getConnection();
        $promotionId = (int)$id;

        // Step 1: Delete the promotion
        $deleteQuery = "DELETE FROM promotions WHERE id = $promotionId";
        $result = $conn->query($deleteQuery);

        if ($result && $conn->affected_rows > 0) {
            return true; // Successfully deleted
        }

        return false; // Not deleted or ID not found
    }
}