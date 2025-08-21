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
        <title>Customers Management</title>
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
                        <div class="mb-4">
                            <h4 class="bookings-title">Customers / Guests</h4>
                            <p class="bookings-subtitle">Manage your guest directory and customer information</p>
                        </div>

                        <div class="bg-white p-4 rounded-4">
                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
                                <div class="d-flex gap-2 flex-wrap">
                                    <h4 class="bookings-title">Guest Directory</h4>
                                </div>
                                <div>
                                    <input type="text" placeholder="Search..." class="form-control">
                                </div>
                            </div>
        
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Guest Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Total Bookings</th>
                                            <th>Total Spent</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>John Smith</td>
                                            <td>john.smith@example.com</td>
                                            <td>+1 (555) 123-4567</td>
                                            <td>8</td>
                                            <td>₹2,450</td>
                                            <td><span class="badge badge-confirmed px-3 py-1 rounded-pill">Active</span></td>
                                            <td>
                                                <div class="d-flex align-item-center">
                                                    <a href="#" class="text-decoration-none">View Profile</a>
                                                    <a href="#" class="ms-3 text-success text-decoration-none">Message</a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Michael Brown</td>
                                            <td>michael.b@example.com</td>
                                            <td>+1 (555) 345-6789</td>
                                            <td>12</td>
                                            <td>₹4,120</td>
                                            <td><span class="badge badge-pending px-3 py-1 rounded-pill">VIP</span></td>
                                            <td>
                                                <div class="d-flex align-item-center">
                                                    <a href="#" class="text-decoration-none">View Profile</a>
                                                    <a href="#" class="ms-3 text-success text-decoration-none">Message</a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Robert Garcia</td>
                                            <td>robert.g@example.com</td>
                                            <td>+1 (555) 567-8901</td>
                                            <td>5</td>
                                            <td>₹1,750</td>
                                            <td><span class="badge badge-cancelled px-3 py-1 rounded-pill">Inactive</span></td>
                                            <td>
                                                <div class="d-flex align-item-center">
                                                    <a href="#" class="text-decoration-none">View Profile</a>
                                                    <a href="#" class="ms-3 text-success text-decoration-none">Message</a>
                                                </div>
                                            </td>
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