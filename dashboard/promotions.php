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
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <h4 class="bookings-title">Promotions</h4>
                                <p class="bookings-subtitle">Manage discounts, offers and special deals</p>
                            </div>
                            <a href="add-promotion.php" type="button" class="btn btn-primary d-flex align-items-center justify-content-between">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="me-2" viewBox="0 0 18 18" fill="none">
                                <path d="M4.52368 9.23145H14.4316" stroke="white" stroke-width="2.12313" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9.47754 4.27734V14.1853" stroke="white" stroke-width="2.12313" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg> New Promotion
                            </a>
                        </div>

                        <div class="bg-white p-4 rounded-4">
                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
                                <div class="d-flex gap-2 flex-wrap">
                                    <h4 class="bookings-title">All Promotions</h4>
                                </div>
                            </div>
        
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Promotion Name</th>
                                            <th>Coupon Code</th>
                                            <th>Discount</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Usage Count</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Summer Special</td>
                                            <td>SUMMER2023</td>
                                            <td>20%</td>
                                            <td>01/06/2023</td>
                                            <td>31/08/2023</td>
                                            <td>45</td>
                                            <td><span class="badge badge-confirmed px-3 py-1 rounded-pill">Active</span></td>
                                            <td>
                                                <div class="d-flex align-item-center">
                                                    <a href="edit-promotion.php" class="text-decoration-none">Edit</a>
                                                    <a href="#" class="m-0 ms-3 text-danger text-decoration-none">Delete</a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Spring Break</td>
                                            <td>SPRING2023</td>
                                            <td>10%</td>
                                            <td>01/03/2023</td>
                                            <td>30/04/2023</td>
                                            <td>120</td>
                                            <td><span class="badge badge-cancelled px-3 py-1 rounded-pill">Expired</span></td>
                                            <td>
                                                <div class="d-flex align-item-center">
                                                    <a href="edit-promotion.php" class="text-decoration-none">Edit</a>
                                                    <a href="#" class="m-0 ms-3 text-danger text-decoration-none">Delete</a>
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