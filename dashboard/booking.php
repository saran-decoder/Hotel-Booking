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
                                    <select class="form-select" style="width: 150px;">
                                        <option>All Hotels</option>
                                        <option>Seaside Resort</option>
                                        <option>Mountain View</option>
                                        <option>City Center Hotel</option>
                                    </select>
                                    <select class="form-select" style="width: 150px;">
                                        <option>All Statuses</option>
                                        <option>Confirmed</option>
                                        <option>Pending</option>
                                        <option>Cancelled</option>
                                    </select>
                                </div>
                                
                                <div class="d-none d-sm-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" style="margin: 12px 15px; position: absolute;">
                                        <path d="M17.3906 17.4052L13.9414 13.9561" stroke="#9CA3AF" stroke-width="2.11929" stroke-linecap="round" stroke-linejoin="round" />
                                        <path
                                            d="M9.44333 15.8159C12.9547 15.8159 15.8012 12.9693 15.8012 9.45798C15.8012 5.94662 12.9547 3.1001 9.44333 3.1001C5.93197 3.1001 3.08545 5.94662 3.08545 9.45798C3.08545 12.9693 5.93197 15.8159 9.44333 15.8159Z"
                                            stroke="#9CA3AF"
                                            stroke-width="2.11929"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
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
                                            <th>Hotel</th>
                                            <th>Check-in Date</th>
                                            <th>Check-out Date</th>
                                            <th>Room Type</th>
                                            <th>Status</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>B-1001</td>
                                            <td>John Smith</td>
                                            <td>Seaside Resort</td>
                                            <td>15/06/2023</td>
                                            <td>18/06/2023</td>
                                            <td>Deluxe Suite</td>
                                            <td><span class="badge badge-confirmed px-3 py-1 rounded-pill">Confirmed</span></td>
                                            <td>₹450.00</td>
                                        </tr>
                                        <tr>
                                            <td>B-1002</td>
                                            <td>Emma Johnson</td>
                                            <td>Mountain View</td>
                                            <td>20/06/2023</td>
                                            <td>25/06/2023</td>
                                            <td>Standard Room</td>
                                            <td><span class="badge badge-pending px-3 py-1 rounded-pill">Pending</span></td>
                                            <td>₹320.00</td>
                                        </tr>
                                        <tr>
                                            <td>B-1003</td>
                                            <td>Michael Brown</td>
                                            <td>City Center Hotel</td>
                                            <td>18/06/2023</td>
                                            <td>19/06/2023</td>
                                            <td>Executive Suite</td>
                                            <td><span class="badge badge-confirmed px-3 py-1 rounded-pill">Confirmed</span></td>
                                            <td>₹280.00</td>
                                        </tr>
                                        <tr>
                                            <td>B-1004</td>
                                            <td>Sarah Wilson</td>
                                            <td>Seaside Resort</td>
                                            <td>22/06/2023</td>
                                            <td>24/06/2023</td>
                                            <td>Family Room</td>
                                            <td><span class="badge badge-cancelled px-3 py-1 rounded-pill">Cancelled</span></td>
                                            <td>₹520.00</td>
                                        </tr>
                                        <tr>
                                            <td>B-1005</td>
                                            <td>Robert Garcia</td>
                                            <td>Mountain View</td>
                                            <td>25/06/2023</td>
                                            <td>30/06/2023</td>
                                            <td>Deluxe Suite</td>
                                            <td><span class="badge badge-confirmed px-3 py-1 rounded-pill">Confirmed</span></td>
                                            <td>₹750.00</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
        
                            <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-3">
                                <span class="text-muted">Showing 5 of 125 bookings</span>
                                <nav>
                                    <ul class="pagination mb-0">
                                        <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item"><a class="page-link" href="#">Next</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include "temp/footer.php" ?>
        
    </body>
</html>