<?php

class Operations
{
    public static function getUserData($userID)
    {
        $conn = Database::getConnection();
        $sql = "SELECT * FROM `users` WHERE `id` = '$userID'";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }

    public static function getAdminAccount() {
        $conn = Database::getConnection();
        $username = Session::get("username");
        $sql = "SELECT `email`,`phone` FROM `admin` WHERE `email` = '$username'";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
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
    public static function getTotalHotels() {
        $conn = Database::getConnection();

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
                LEFT JOIN rooms r ON h.id = r.hotel_id";

        $result = $conn->query($sql);

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

    public static function getAllBookingLists() {
        $conn = Database::getConnection();
        $username = Session::get("username");

        $sql = "SELECT 
                    b.id AS booking_id,
                    b.booking_ref,
                    b.user_id AS guest_name,
                    b.check_in_date,
                    b.check_out_date,
                    b.adults,
                    b.children,
                    b.total_price,
                    b.status AS booking_status,
                    b.payment_status,
                    b.created_at AS booking_created_at,

                    r.room_type,
                    r.guests_allowed,
                    r.price_per_night,

                    h.name AS hotel_name,
                    h.location_name,
                    h.address,
                    h.owner AS hotel_owner
                FROM bookings b
                INNER JOIN rooms r ON b.room_id = r.id
                INNER JOIN hotels h ON b.hotel_id = h.id
                WHERE h.owner = ? 
                ORDER BY b.created_at DESC";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $bookings = $result->fetch_all(MYSQLI_ASSOC);
        
        // Process the bookings to set status based on payment status
        foreach ($bookings as &$booking) {
            if ($booking['payment_status'] == 0) {
                $booking['booking_status'] = 'pending';
            } elseif ($booking['payment_status'] == 1) {
                $booking['booking_status'] = 'cancelled';
            }
        }
        
        return $bookings;
    }

    // In your Operations class
    public static function getAllPayments() {
        $conn = Database::getConnection();
        $username = Session::get("username");

        $sql = "SELECT 
                    p.id AS payment_id,
                    p.order_id,
                    p.amount,
                    p.status,
                    p.created_at AS payment_date,
                    
                    b.booking_ref,
                    b.user_id AS customer_email,
                    b.total_price,
                    
                    h.name AS hotel_name,
                    h.owner AS hotel_owner
                FROM payments p
                INNER JOIN bookings b ON p.booking_id = b.id
                INNER JOIN hotels h ON b.hotel_id = h.id
                WHERE h.owner = ? 
                ORDER BY p.created_at DESC";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function getPaymentStats() {
        $conn = Database::getConnection();
        $username = Session::get("username");

        $sql = "SELECT
                    SUM(CASE WHEN p.status = 'completed' THEN p.amount ELSE 0 END) AS total_revenue,
                    SUM(CASE WHEN p.status = 'pending' THEN p.amount ELSE 0 END) AS pending_payments,
                    SUM(CASE WHEN p.status = 'refunded' THEN 1 ELSE 0 END) AS refunds_count,
                    COUNT(p.id) AS total_payments
                FROM payments p
                INNER JOIN bookings b ON p.booking_id = b.id
                INNER JOIN hotels h ON b.hotel_id = h.id
                WHERE h.owner = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public static function getAllEmployees() {
        $conn = Database::getConnection();
        $user = Session::get("username");

        try {
            $stmt = $conn->prepare("SELECT * FROM workers WHERE owner = '$user'");
            $stmt->execute();
            $result = $stmt->get_result();
            
            $employees = [];
            while ($row = $result->fetch_assoc()) {
                $employees[] = $row;
            }
            
            return ['success' => true, 'data' => $employees];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error fetching employees: ' . $e->getMessage()];
        }
    }

    public static function getPromotion($id = '')
    {
        $conn = Database::getConnection();

        if ($id !== '') {
            $stmt = $conn->prepare("SELECT * FROM `promotions` WHERE `id` = ?");
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $conn->query("SELECT * FROM `promotions`");
        }

        $doctors = [];
        while ($row = $result->fetch_assoc()) {
            $doctors[] = $row;
        }

        return $doctors;
    }

    public static function getAllPromotionWithPagination($page = 1, $limit = 10) {
        $conn = Database::getConnection();
        $user = Session::get("username");
        
        $offset = ($page - 1) * $limit;
        $sql = "SELECT * FROM `promotions` WHERE `owner` = '$user' LIMIT $limit OFFSET $offset";
        $result = $conn->query($sql);
        
        return iterator_to_array($result);
    }

    public static function getPromotionCount() {
        $conn = Database::getConnection();
        $user = Session::get("username");
        
        $sql = "SELECT COUNT(*) as count FROM `promotions` WHERE `owner` = '$user'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        
        return $row['count'];
    }
}

?>