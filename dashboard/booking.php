<?php
    include "../libs/load.php";

    if (!Session::get('email_verified') == 'verified') {
        header("Location: 2fa");
        exit;
    }

    if (
        !Session::get('session_token') || 
        Session::get('session_type') != 'admin' && 
        !Session::get('username') || 
        Session::get('email_verified') != 'verified'
    ) {
        header("Location: logout?logout");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Booking Management</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <?php include "temp/head.php" ?>
        
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <?php include "temp/sideheader.php" ?>

                <!-- Main Content -->
                <div class="main-content p-0 overflow-hidden">
                    <!-- Top Navbar -->
                    <?php include "temp/header.php" ?>

                    <div class="p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <h4 class="bookings-title">Bookings</h4>
                                <p class="bookings-subtitle">Manage all hotel bookings</p>
                            </div>
                            <a href="add-booking.php" class="btn btn-primary d-flex align-items-center justify-content-between">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="me-2" viewBox="0 0 18 18" fill="none">
                                <path d="M4.52368 9.23145H14.4316" stroke="white" stroke-width="2.12313" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M9.47754 4.27734V14.1853" stroke="white" stroke-width="2.12313" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg> Add Booking
                            </a>
                        </div>

                        <div class="bg-white p-4 rounded-4">
                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
                                <div class="d-flex gap-2 flex-wrap">
                                    <select class="form-select hotel-filter" style="width: 150px;">
                                        <option value="">All Hotels</option>
                                    </select>
                                    <select class="form-select status-filter" style="width: 150px;">
                                        <option value="">All Statuses</option>
                                        <option value="confirmed">Confirmed</option>
                                        <option value="pending">Pending</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>

                                <div class="d-none d-sm-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" style="margin: 12px 15px; position: absolute;">
                                        <path d="M17.3906 17.4052L13.9414 13.9561" stroke="#9CA3AF" stroke-width="2.11929" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M9.44333 15.8159C12.9547 15.8159 15.8012 12.9693 15.8012 9.45798C15.8012 5.94662 12.9547 3.1001 9.44333 3.1001C5.93197 3.1001 3.08545 5.94662 3.08545 9.45798C3.08545 12.9693 5.93197 15.8159 9.44333 15.8159Z" stroke="#9CA3AF" stroke-width="2.11929" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <input type="text" class="form-control search-input" placeholder="Search Bookings..." />
                                </div>
                            </div>
        
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Booking ID</th>
                                            <th>Guest Name</th>
                                            <th>Hotel Name</th>
                                            <th>Check-in Date</th>
                                            <th>Check-out Date</th>
                                            <th>Room Type</th>
                                            <th>Status</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bookingTableBody"></tbody>
                                </table>
                            </div>
        
                            <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-3">
                                <span class="text-muted" id="showingText">Showing 0 of 0 bookings</span>
                                <nav>
                                    <ul class="pagination mb-0" id="pagination"></ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include "temp/footer.php" ?>
        
        <script src="assets/js/booking-list.js"></script>
    
    </body>
</html>