<?php

class Operations
{
    public static function getUserAccount() {
        $conn = Database::getConnection();
        $sql = "SELECT * FROM `admin` ORDER BY `uploaded_time` DESC";
        $result = $conn->query($sql);
        return iterator_to_array($result);
    }

    public static function getAllHotels() {
        $conn = Database::getConnection();
        $username = Session::get("username");

        // Use prepared statements for security (avoid SQL injection)
        $sql = "SELECT 
                    h.id,
                    h.owner AS hotel_owner,
                    h.name AS hotel_name,
                    h.location_name AS hotel_location_name,
                    h.coordinates AS hotel_coordinates,
                    h.address AS hotel_address,
                    h.description AS hotel_description,
                    h.amenities AS hotel_amenities,
                    h.images AS hotel_images,
                    h.created_at AS hotel_created_at,
                    h.updated_at AS hotel_updated_at,

                    r.id AS room_id,
                    r.hotel_id AS room_hotel_id,
                    r.room_type,
                    r.guests_allowed,
                    r.description AS room_description,
                    r.price_per_night,
                    r.amenities AS room_amenities,
                    r.images AS room_images,
                    r.status AS room_status,
                    r.created_at AS room_created_at,
                    r.updated_at AS room_updated_at
                FROM hotels h
                LEFT JOIN rooms r ON h.id = r.hotel_id
                WHERE h.owner = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Convert to array
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function getHotelRooms($hotelId = null)
    {
        $conn = Database::getConnection();
        $username = Session::get("username");

        // Use prepared statements for security (avoid SQL injection)
        if ($hotelId) {
            // Get specific hotel with rooms
            $sql = "SELECT 
                        h.id AS hotel_id,
                        h.owner AS hotel_owner,
                        h.name AS hotel_name,
                        h.location_name AS hotel_location_name,
                        h.coordinates AS hotel_coordinates,
                        h.address AS hotel_address,
                        h.description AS hotel_description,
                        h.amenities AS hotel_amenities,
                        h.images AS hotel_images,
                        h.created_at AS hotel_created_at,
                        h.updated_at AS hotel_updated_at,

                        r.id AS room_id,
                        r.hotel_id AS room_hotel_id,
                        r.room_type,
                        r.guests_allowed,
                        r.description AS room_description,
                        r.price_per_night,
                        r.amenities AS room_amenities,
                        r.images AS room_images,
                        r.status AS room_status,
                        r.created_at AS room_created_at,
                        r.updated_at AS room_updated_at
                    FROM hotels h
                    LEFT JOIN rooms r ON h.id = r.hotel_id
                    WHERE (h.owner = ? OR h.id = ?) AND h.id = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sii", $username, $hotelId, $hotelId);
        } else {
            // Get all hotels with rooms
            $sql = "SELECT 
                        h.id AS hotel_id,
                        h.owner AS hotel_owner,
                        h.name AS hotel_name,
                        h.location_name AS hotel_location_name,
                        h.coordinates AS hotel_coordinates,
                        h.address AS hotel_address,
                        h.description AS hotel_description,
                        h.amenities AS hotel_amenities,
                        h.images AS hotel_images,
                        h.created_at AS hotel_created_at,
                        h.updated_at AS hotel_updated_at,

                        r.id AS room_id,
                        r.hotel_id AS room_hotel_id,
                        r.room_type,
                        r.guests_allowed,
                        r.description AS room_description,
                        r.price_per_night,
                        r.amenities AS room_amenities,
                        r.images AS room_images,
                        r.status AS room_status,
                        r.created_at AS room_created_at,
                        r.updated_at AS room_updated_at
                    FROM hotels h
                    LEFT JOIN rooms r ON h.id = r.hotel_id
                    WHERE h.owner = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();

        // Convert to array
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public static function getHotelDetails($hotelId)
    {
        $conn = Database::getConnection();
        $username = Session::get("username");

        $sql = "SELECT 
                    h.id AS hotel_id,
                    h.owner AS hotel_owner,
                    h.name AS hotel_name,
                    h.location_name AS hotel_location_name,
                    h.coordinates AS hotel_coordinates,
                    h.address AS hotel_address,
                    h.description AS hotel_description,
                    h.amenities AS hotel_amenities,
                    h.images AS hotel_images,
                    h.created_at AS hotel_created_at,
                    h.updated_at AS hotel_updated_at,

                    r.id AS room_id,
                    r.hotel_id AS room_hotel_id,
                    r.room_type,
                    r.guests_allowed,
                    r.description AS room_description,
                    r.price_per_night,
                    r.amenities AS room_amenities,
                    r.images AS room_images,
                    r.status AS room_status,
                    r.created_at AS room_created_at,
                    r.updated_at AS room_updated_at
                FROM hotels h
                LEFT JOIN rooms r ON h.id = r.hotel_id
                WHERE h.id = ? AND h.owner = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $hotelId, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function getHotel($id = '')
    {
        $conn = Database::getConnection();

        if ($id !== '') {
            $stmt = $conn->prepare("SELECT * FROM `hotels` WHERE `id` = ?");
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $conn->query("SELECT * FROM `hotels`");
        }

        $doctors = [];
        while ($row = $result->fetch_assoc()) {
            $doctors[] = $row;
        }

        return $doctors;
    }

    public static function getAdminHotelId($username)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM `hotels` WHERE `id` = ?, OR `owner` = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public static function getAllRooms() {
        $conn = Database::getConnection();
        $sql = "SELECT * FROM `rooms` ORDER BY `created_at` DESC";
        $result = $conn->query($sql);
        return iterator_to_array($result);
    }

    public static function getRoom($id = '')
    {
        $conn = Database::getConnection();

        if ($id !== '') {
            $stmt = $conn->prepare("SELECT * FROM `rooms` WHERE `id` = ?");
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $conn->query("SELECT * FROM `rooms`");
        }

        $doctors = [];
        while ($row = $result->fetch_assoc()) {
            $doctors[] = $row;
        }

        return $doctors;
    }

    // Get available rooms for a hotel and date range
    public static function getAvailableRooms($hotel_id, $check_in, $check_out) {
        $db = Database::getConnection();
        
        // Convert dates to proper format
        $check_in_date = date('Y-m-d', strtotime($check_in));
        $check_out_date = date('Y-m-d', strtotime($check_out));
        
        // Get all rooms for the hotel
        $stmt = $db->prepare("SELECT r.*, h.hotel_name 
                            FROM rooms r 
                            JOIN hotels h ON r.hotel_id = h.id 
                            WHERE r.hotel_id = ? AND r.status = 'active'");
        $stmt->bind_param("i", $hotel_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $available_rooms = [];
        
        while ($room = $result->fetch_assoc()) {
            // Check availability for each room
            $availability = Admin::checkRoomAvailability($room['id'], $check_in, $check_out);
            
            if ($availability['available']) {
                $room['availability'] = $availability;
                $available_rooms[] = $room;
            }
        }
        
        return $available_rooms;
    }
    
    // Get booking details by ID
    public static function getBookingById($booking_id) {
        $db = Database::getConnection();
        
        $stmt = $db->prepare("SELECT b.*, h.*, r.*, p.*
                             FROM bookings b
                             JOIN hotels h ON b.hotel_id = h.id
                             JOIN rooms r ON b.room_id = r.id
                             JOIN payments ON b.booking_id = b.id
                             WHERE b.id = ?");
        
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $booking = $result->fetch_assoc();
        
        return $booking;
    }

}

?>