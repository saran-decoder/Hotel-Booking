<?php

${basename(__FILE__, '.php')} = function () {
    // default months to 12
    $months = isset($this->_request['months']) ? (int)$this->_request['months'] : 12;
    if ($months <= 0 || $months > 36) {
        $this->response($this->json([
            'success' => false,
            'message' => 'Invalid months parameter. Must be between 1 and 36.'
        ]), 400);
        return;
    }

    try {
        $db = Database::getConnection();

        // Build month keys and labels: last $months months ending with current month
        $now = new DateTime(); // current date
        $labels = [];
        $values = [];
        $monthIndexMap = []; // 'YYYY-MM' => index in $values

        // create months from oldest -> newest (so chart shows left to right)
        for ($i = $months - 1; $i >= 0; $i--) {
            $dt = (clone $now)->modify("-{$i} months");
            $key = $dt->format('Y-m'); // e.g. 2025-09
            $labels[] = $dt->format('M Y'); // e.g. Sep 2025
            $monthIndexMap[$key] = count($values);
            $values[] = 0.0;
        }

        // Determine start and end dates for SQL (cover all included months)
        $firstMonth = (clone $now)->modify("-" . ($months - 1) . " months")
                                  ->modify('first day of this month')
                                  ->setTime(0, 0, 0);
        $lastMonth  = (clone $now)->modify('last day of this month')
                                  ->setTime(23, 59, 59);

        $startDate = $firstMonth->format('Y-m-d H:i:s');
        $endDate   = $lastMonth->format('Y-m-d H:i:s');

        // Query DB for monthly revenue (use total_price instead of amount)
        $sql = "
            SELECT 
                YEAR(created_at) AS year,
                MONTH(created_at) AS month,
                SUM(total_price) AS revenue
            FROM bookings
            WHERE created_at BETWEEN ? AND ?
              AND payment_status = 'completed'
            GROUP BY YEAR(created_at), MONTH(created_at)
            ORDER BY YEAR(created_at), MONTH(created_at)
        ";

        $stmt = $db->prepare($sql);
        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        $results = $stmt->get_result();

        // Fill monthly values
        while ($row = $results->fetch_assoc()) {
            $key = $row['year'] . '-' . str_pad($row['month'], 2, '0', STR_PAD_LEFT); // e.g. 2025-09
            if (isset($monthIndexMap[$key])) {
                $values[$monthIndexMap[$key]] = (float)$row['revenue'];
            }
        }

        $this->response($this->json([
            'success' => true,
            'data' => [
                'labels' => $labels,
                'values' => $values
            ]
        ]), 200);

    } catch (Exception $e) {
        $this->response($this->json([
            'success' => false,
            'message' => 'Error retrieving revenue data: ' . $e->getMessage()
        ]), 500);
    }
};
