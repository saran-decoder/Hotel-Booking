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
        $stmt = $conn->prepare("SELECT * FROM `hotels` WHERE `id` = ?");
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

    public static function getAllDepartments() {
        $conn = Database::getConnection();
        $sql = "SELECT * FROM `departments` ORDER BY `created_at` DESC";
        $result = $conn->query($sql);
        return iterator_to_array($result);
    }

    public static function getAllSettings() {
        $conn = Database::getConnection();
        $sql = "SELECT * FROM `settings` ORDER BY `created_at` DESC";
        $result = $conn->query($sql);
        return iterator_to_array($result);
    }

    public static function getAllPatients() {
        $conn = Database::getConnection();
        $sql = "SELECT * FROM `patients` ORDER BY `created_at` DESC";
        $result = $conn->query($sql);
        return iterator_to_array($result);
    }

    public static function getAllAptTime($doctor = '')
    {
        $conn = Database::getConnection();
        
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        try {
            if ($doctor !== '') {
                // Use prepared statement to prevent SQL injection
                $sql = "SELECT * FROM `apttimer` WHERE `doctor` = '$doctor' OR `id` = '$doctor' ORDER BY `created_at` DESC";
            } else {
                $sql = "SELECT * FROM `apttimer` ORDER BY `created_at` DESC";
            }
            
            $result = $conn->query($sql);
            
            return iterator_to_array($result);
        } catch (Exception $e) {
            error_log("Error in getAllAptTime: " . $e->getMessage());
            return "Getting Faild: " . $e->getMessage();
        }
    }

    public static function getAllBookings()
    {
        $conn = Database::getConnection();
        $sql = "SELECT * FROM `booking` ORDER BY `created_at` DESC";
        $result = $conn->query($sql);

        return $result ? iterator_to_array($result) : [];
    }
    public static function getUserBookings($username, $contact)
    {
        $conn = Database::getConnection();
        $sql = "SELECT * FROM `booking` WHERE `username` = '$username' AND `contact` = '$contact'";
        $result = $conn->query($sql);

        return $result ? iterator_to_array($result) : [];
    }
    public static function getBookedSlots($dpt, $doctor, $apt_date)
    {
        $conn = Database::getConnection();

        try {
            // 1. Get slots booked by other users for this doctor
            $stmt1 = $conn->prepare("SELECT `apt_time`, `status` FROM `booking`
                                    WHERE `dpt` = ? AND `doctor` = ? AND `apt_date` = ?
                                    ORDER BY `apt_time`");
            $stmt1->bind_param("sss", $dpt, $doctor, $apt_date);
            $stmt1->execute();
            $result1 = $stmt1->get_result();

            $bookedByOthers = [];
            while ($row = $result1->fetch_assoc()) {
                $bookedByOthers[] = [
                    'time' => $row['apt_time'],
                    'status' => $row['status']
                ];
            }

            // 2. Get all slots booked by current user on this date (any doctor)
            $stmt2 = $conn->prepare("SELECT `apt_time`, `status`, `doctor` FROM `booking`
                                    WHERE `doctor` = ? AND `apt_date` = ?
                                    ORDER BY `apt_time`");
            $stmt2->bind_param("ss", $doctor, $apt_date);
            $stmt2->execute();
            $result2 = $stmt2->get_result();

            $bookedByUser = [];
            while ($row = $result2->fetch_assoc()) {
                $bookedByUser[] = [
                    'time' => $row['apt_time'],
                    'status' => $row['status'],
                    'doctor' => $row['doctor']
                ];
            }

            // 3. Get all available time slots for this doctor
            $availableSlots = Operations::getDoctorAvailableSlots($doctor, $dpt, $apt_date);

            return [
                'success' => true,
                'bookedByOthers' => $bookedByOthers,
                'bookedByUser' => $bookedByUser,
                'availableSlots' => $availableSlots
            ];

        } catch (Exception $e) {
            error_log("Error getting booked slots: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error retrieving slot information'];
        }
    }

    private static function getDoctorAvailableSlots($doctor, $dpt, $date) {
        $conn = Database::getConnection();

        try {
            // Validate and format the date
            $dateObj = DateTime::createFromFormat('Y-m-d', $date);
            if (!$dateObj) {
                throw new Exception("Invalid date format");
            }
            $formattedDate = $dateObj->format('M d, Y');

            // Use prepared statement to prevent SQL injection
            $stmt = $conn->prepare("SELECT * FROM `apttimer` 
                                WHERE `doctor` = ? AND `department` = ? AND `date_range` = ? 
                                ORDER BY `created_at` DESC");
            $stmt->bind_param("sss", $doctor, $dpt, $formattedDate);
            $stmt->execute();
            
            $result = $stmt->get_result();
            $slots = [];
            while ($row = $result->fetch_assoc()) {
                $slots[] = $row;
            }
            
            return $slots;
        } catch (Exception $e) {
            error_log("Error getting doctor available slots: " . $e->getMessage());
            return [];
        }
    }

    public static function startToken($id)
    {
        $conn = Database::getConnection();
        $id = $conn->real_escape_string($id);

        $sql = "UPDATE `booking` SET `status` = 'In Progress' WHERE `id` = '$id'";
        return $conn->query($sql);
    }
    public static function finishToken($id)
    {
        $conn = Database::getConnection();
        $id = $conn->real_escape_string($id);

        $sql = "UPDATE `booking` SET `status` = 'Completed', `reason` = '—' WHERE `id` = '$id'";
        return $conn->query($sql);
    }
    public static function updateAppointmentStatus($id, $status)
    {
        $conn = Database::getConnection();
        $id = $conn->real_escape_string($id);
        $status = $conn->real_escape_string($status);

        $allowed = ['skipped', 'rescheduled', 'cancelled'];
        $mappedStatuses = [
            'skipped' => 'Skipped',
            'rescheduled' => 'Rescheduled',
            'cancelled' => 'Cancelled'
        ];

        if (!in_array($status, $allowed)) {
            return false;
        }

        $finalStatus = $mappedStatuses[$status];

        $sql = "UPDATE `booking` SET `status` = '$finalStatus' WHERE `id` = '$id'";
        return $conn->query($sql);
    }

    public static function getTodayAppointments($department, $doctor, $department_filter = 'all')
    {
        $conn = Database::getConnection();
        
        // Validate inputs
        if (empty($department) || empty($doctor)) {
            throw new Exception("Department and doctor parameters are required");
        }
        
        // Escape inputs
        $department = $conn->real_escape_string($department);
        $doctor = $conn->real_escape_string($doctor);
        $today = date('Y-m-d');

        // Base query
        $sql = "SELECT 
                    b.id, 
                    p.name, 
                    p.contact, 
                    p.dob,
                    p.gender,
                    p.address,
                    p.city,
                    p.pincode,
                    b.dpt as department, 
                    b.doctor, 
                    b.apt_date as appointment_date, 
                    b.apt_time as appointment_time,
                    b.tokens, 
                    b.status, 
                    b.reason, 
                    b.changed_by, 
                    b.created_at,
                    TIMESTAMPDIFF(YEAR, p.dob, CURDATE()) as age
                FROM booking b
                LEFT JOIN patients p ON b.username = p.name
                WHERE b.doctor = '$doctor' 
                AND b.apt_date = '$today'";
        
        // Handle comma-separated departments
        $departments = explode(',', $department);
        $deptConditions = [];
        foreach ($departments as $dept) {
            $dept = trim($dept);
            if (!empty($dept)) {
                $deptConditions[] = "FIND_IN_SET('$dept', b.dpt) > 0";
            }
        }
        
        if (!empty($deptConditions)) {
            $sql .= " AND (" . implode(' OR ', $deptConditions) . ")";
        }
        
        // Add department filter
        if ($department_filter !== 'all') {
            $sql .= " AND FIND_IN_SET('$department_filter', b.dpt) > 0";
        }
        
        // Order by time
        $sql .= " ORDER BY b.apt_time ASC";
        
        // Execute query
        $result = $conn->query($sql);
        
        if (!$result) {
            throw new Exception("Failed to fetch today's appointments: " . $conn->error);
        }
        
        $appointments = [];
        while ($row = $result->fetch_assoc()) {
            // Format dates for display
            $row['display_date'] = $row['appointment_date'];
            $row['display_time'] = date('h:i A', strtotime($row['appointment_time']));
            
            $appointments[] = $row;
        }
        
        return $appointments;
    }
    
    public static function getTodayCount($department, $doctor)
    {
        $conn = Database::getConnection();
        $doctor = $conn->real_escape_string($doctor);
        $today = date('Y-m-d');
        
        // Handle multiple departments
        $departments = explode(',', $department);
        $deptConditions = [];
        foreach ($departments as $dept) {
            $dept = trim($conn->real_escape_string($dept));
            if (!empty($dept)) {
                $deptConditions[] = "dpt = '$dept'";
            }
        }
        
        if (empty($deptConditions)) {
            return 0;
        }
        
        $deptCondition = implode(' OR ', $deptConditions);
        
        $sql = "SELECT COUNT(*) AS count 
                FROM booking 
                WHERE ($deptCondition)
                AND doctor = '$doctor'";
                // AND apt_date = '$today'";
                
        $result = $conn->query($sql);
        return $result->fetch_assoc()['count'] ?? 0;
    }

    public static function getCompletedCount($department, $doctor)
    {
        return Operations::getStatusCount($department, $doctor, 'Completed');
    }

    public static function getQueueCount($department, $doctor)
    {
        return Operations::getStatusCount($department, $doctor, 'Upcoming');
    }

    public static function getSkippedCount($department, $doctor)
    {
        return Operations::getStatusCount($department, $doctor, 'Skipped');
    }

    public static function getCancelledCount($department, $doctor)
    {
        return Operations::getStatusCount($department, $doctor, 'Cancelled');
    }

    public static function getRescheduledCount($department, $doctor)
    {
        return Operations::getStatusCount($department, $doctor, 'Rescheduled');
    }

    private static function getStatusCount($department, $doctor, $status)
    {
        $conn = Database::getConnection();
        $doctor = $conn->real_escape_string($doctor);
        $status = $conn->real_escape_string($status);
        
        // Handle multiple departments
        $departments = explode(',', $department);
        $deptConditions = [];
        foreach ($departments as $dept) {
            $dept = trim($conn->real_escape_string($dept));
            if (!empty($dept)) {
                $deptConditions[] = "dpt = '$dept'";
            }
        }
        
        if (empty($deptConditions)) {
            return 0;
        }
        
        $deptCondition = implode(' OR ', $deptConditions);
        
        $sql = "SELECT COUNT(*) AS count 
                FROM booking 
                WHERE ($deptCondition)
                AND doctor = '$doctor' 
                AND status = '$status'";
                
        $result = $conn->query($sql);
        return $result->fetch_assoc()['count'] ?? 0;
    }

    public static function getBookingCounts($department, $doctor)
    {
        $conn = Database::getConnection();
        $department = $conn->real_escape_string($department);
        $doctor = $conn->real_escape_string($doctor);
        $today = date('Y-m-d');

        $sql = "SELECT 
                SUM(CASE WHEN `date` = '$today' THEN 1 ELSE 0 END) as today,
                SUM(CASE WHEN `status` = 'Completed' AND `date` = '$today' THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN `status` = 'In Progress' AND `date` = '$today' THEN 1 ELSE 0 END) as queue,
                SUM(CASE WHEN `status` = 'Skipped' AND `date` = '$today' THEN 1 ELSE 0 END) as skipped,
                SUM(CASE WHEN `status` = 'Cancelled' AND `date` = '$today' THEN 1 ELSE 0 END) as cancelled,
                SUM(CASE WHEN `status` = 'Rescheduled' AND `date` = '$today' THEN 1 ELSE 0 END) as rescheduled
                FROM `booking` 
                WHERE `dpt` = '$department' 
                AND `doctor` = '$doctor'";

        $result = $conn->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            return [
                'today' => $row['today'] ?? 0,
                'completed' => $row['completed'] ?? 0,
                'queue' => $row['queue'] ?? 0,
                'skipped' => $row['skipped'] ?? 0,
                'cancelled' => $row['cancelled'] ?? 0,
                'rescheduled' => $row['rescheduled'] ?? 0
            ];
        }

        return [
            'today' => 0,
            'completed' => 0,
            'queue' => 0,
            'skipped' => 0,
            'cancelled' => 0,
            'rescheduled' => 0
        ];
    }

    public static function getDptCount() {
        $conn = Database::getConnection();
        $sql = "SELECT COUNT(*) AS count FROM `departments`";
        $result = $conn->query($sql);
        return $result ? $result->fetch_assoc()['count'] : 0;
    }

    public static function getDrCount() {
        $conn = Database::getConnection();
        $sql = "SELECT COUNT(*) AS count FROM `doctors`";
        $result = $conn->query($sql);
        return $result ? $result->fetch_assoc()['count'] : 0;
    }

    public static function getPatientCount() {
        $conn = Database::getConnection();
        $sql = "SELECT COUNT(*) AS count FROM `patients`";
        $result = $conn->query($sql);
        return $result ? $result->fetch_assoc()['count'] : 0;
    }

    public static function getTokenCount() {
        $conn = Database::getConnection();
        $sql = "SELECT COUNT(*) AS count FROM `booking`";
        $result = $conn->query($sql);
        return $result ? $result->fetch_assoc()['count'] : 0;
    }

    public static function getAllAppointments($department, $doctor, $status = 'all', $department_filter = 'all', $date_filter = 'all', $search = '', $page = 1, $limit = 10)
    {
        $conn = Database::getConnection();
        
        // Validate inputs
        if (empty($department) || empty($doctor)) {
            throw new Exception("Department and doctor parameters are required");
        }
        
        // Escape inputs
        $department = $conn->real_escape_string($department);
        $doctor = $conn->real_escape_string($doctor);
        $status = $conn->real_escape_string($status);
        $department_filter = $conn->real_escape_string($department_filter);
        $search = $conn->real_escape_string($search);
        $offset = ($page - 1) * $limit;

        // Base query with patient table join
        $sql = "SELECT 
                    b.id, 
                    p.name, 
                    p.contact, 
                    p.dob,
                    p.gender,
                    p.address,
                    p.city,
                    p.pincode,
                    b.dpt as department, 
                    b.doctor, 
                    b.apt_date as appointment_date, 
                    b.apt_time as appointment_time,
                    b.tokens, 
                    b.status, 
                    b.reason, 
                    b.changed_by, 
                    b.created_at,
                    TIMESTAMPDIFF(YEAR, p.dob, CURDATE()) as age
                FROM booking b
                LEFT JOIN patients p ON b.username = p.name
                WHERE b.doctor = '$doctor'";
        
        // Handle comma-separated departments
        $departments = explode(',', $department);
        $deptConditions = [];
        foreach ($departments as $dept) {
            $dept = trim($dept);
            if (!empty($dept)) {
                $deptConditions[] = "FIND_IN_SET('$dept', b.dpt) > 0";
            }
        }
        
        if (!empty($deptConditions)) {
            $sql .= " AND (" . implode(' OR ', $deptConditions) . ")";
        }
        
        // Add department filter
        if ($department_filter !== 'all') {
            $sql .= " AND FIND_IN_SET('$department_filter', b.dpt) > 0";
        }
        
        // Add status filter
        if ($status !== 'all') {
            $sql .= " AND b.status = '$status'";
        }
        
        // Handle date filters
        $today = date('Y-m-d');
        switch ($date_filter) {
            case 'today':
                $sql .= " AND b.apt_date = '$today'";
                break;
            case 'tomorrow':
                $tomorrow = date('Y-m-d', strtotime('+1 day'));
                $sql .= " AND b.apt_date = '$tomorrow'";
                break;
            case 'this_week':
                $monday = date('Y-m-d', strtotime('monday this week'));
                $sunday = date('Y-m-d', strtotime('sunday this week'));
                $sql .= " AND b.apt_date BETWEEN '$monday' AND '$sunday'";
                break;
            case 'next_week':
                $next_monday = date('Y-m-d', strtotime('monday next week'));
                $next_sunday = date('Y-m-d', strtotime('sunday next week'));
                $sql .= " AND b.apt_date BETWEEN '$next_monday' AND '$next_sunday'";
                break;
            case 'this_month':
                $first_day = date('Y-m-01');
                $last_day = date('Y-m-t');
                $sql .= " AND b.apt_date BETWEEN '$first_day' AND '$last_day'";
                break;
            case 'next_month':
                $first_day_next = date('Y-m-01', strtotime('+1 month'));
                $last_day_next = date('Y-m-t', strtotime('+1 month'));
                $sql .= " AND b.apt_date BETWEEN '$first_day_next' AND '$last_day_next'";
                break;
        }
        
        // Add search filter
        if (!empty($search)) {
            $search_terms = explode(' ', $search);
            $search_conditions = [];
            foreach ($search_terms as $term) {
                if (!empty($term)) {
                    $term = $conn->real_escape_string($term);
                    $search_conditions[] = "(p.name LIKE '%$term%' OR b.tokens LIKE '%$term%' OR b.status LIKE '%$term%' OR p.contact LIKE '%$term%' OR b.dpt LIKE '%$term%')";
                }
            }
            if (!empty($search_conditions)) {
                $sql .= " AND (" . implode(' AND ', $search_conditions) . ")";
            }
        }
        
        // Get total count
        $count_sql = "SELECT COUNT(*) as total FROM ($sql) as counted";
        $count_result = $conn->query($count_sql);
        
        if (!$count_result) {
            throw new Exception("Failed to count appointments: " . $conn->error);
        }
        
        $total_data = $count_result->fetch_assoc();
        $total_records = (int)$total_data['total'];
        $total_pages = $limit > 0 ? max(1, ceil($total_records / $limit)) : 1;
        
        // Add sorting and pagination
        $sql .= " ORDER BY b.apt_date ASC, b.apt_time ASC";
        
        if ($limit > 0) {
            $sql .= " LIMIT $limit OFFSET $offset";
        }
        
        // Execute main query
        $result = $conn->query($sql);
        
        if (!$result) {
            throw new Exception("Failed to fetch appointments: " . $conn->error);
        }
        
        $appointments = [];
        while ($row = $result->fetch_assoc()) {
            // Format dates for display
            $row['display_date'] = date('d M Y', strtotime($row['appointment_date']));
            $row['display_time'] = date('h:i A', strtotime($row['appointment_time']));
            
            // Format address
            $address_parts = [];
            if (!empty($row['address'])) $address_parts[] = $row['address'];
            if (!empty($row['city'])) $address_parts[] = $row['city'];
            if (!empty($row['pincode'])) $address_parts[] = $row['pincode'];
            $row['formatted_address'] = implode(', ', $address_parts);
            
            $appointments[] = $row;
        }
        
        return [
            'appointments' => $appointments,
            'total' => $total_records,
            'current_page' => $page,
            'total_pages' => $total_pages,
            'from' => $total_records > 0 ? $offset + 1 : 0,
            'to' => $limit > 0 ? min($offset + $limit, $total_records) : $total_records
        ];
    }

}

?>