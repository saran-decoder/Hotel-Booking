<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Hotels & Rooms Management</title>
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
                                <h4 class="bookings-title">Hotels & Rooms</h4>
                                <p class="bookings-subtitle">Manage your properties and room inventory</p>
                            </div>
                            <button class="btn btn-primary d-flex align-items-center justify-content-between">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="me-2" viewBox="0 0 18 18" fill="none">
                                <path d="M4.52368 9.23145H14.4316" stroke="white" stroke-width="2.12313" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9.47754 4.27734V14.1853" stroke="white" stroke-width="2.12313" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg> Add New Hotel
                            </button>
                        </div>

                        <div class="bg-white p-4 rounded-4">
                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
                                <div class="d-flex gap-2 flex-wrap">
                                    <h4 class="bookings-title">All Hotels</h4>
                                </div>
                            </div>
        
                            <div class="table-responsive">
                                <table class="table w-100">
                                    <thead>
                                        <tr>
                                            <th>Hotel Name</th>
                                            <th>Location</th>
                                            <th>Total Rooms</th>
                                            <th>Occupancy Rate</th>
                                            <th>Rating</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Seaside Resort</td>
                                            <td>Miami, FL</td>
                                            <td>45</td>
                                            <td>82%</td>
                                            <td>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill mb-1" style="color: #EAB308; font-family: Inter; font-size: 12.978px; font-style: normal; font-weight: 400; line-height: 21.811px;" viewBox="0 0 16 16">
                                                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                                </svg>
                                                4.7
                                            </td>
                                            <td><span class="badge badge-confirmed px-3 py-1 rounded-pill">Active</span></td>
                                            <td>
                                                <div class="d-flex align-item-center">
                                                    <a href="#" class="text-success text-decoration-none">Edit</a>
                                                    <a href="hotel.php" class="ms-3 text-decoration-none">View</a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Lakeside Inn</td>
                                            <td>Seattle, WA</td>
                                            <td>28</td>
                                            <td>54%</td>
                                            <td>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill mb-1" style="color: #EAB308; font-family: Inter; font-size: 12.978px; font-style: normal; font-weight: 400; line-height: 21.811px;" viewBox="0 0 16 16">
                                                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                                </svg>
                                                4
                                            </td>
                                            <td><span class="badge badge-cancelled px-3 py-1 rounded-pill">Maintenance</span></td>
                                            <td>
                                                <div class="d-flex align-item-center">
                                                    <a href="#" class="text-success text-decoration-none">Edit</a>
                                                    <a href="hotel.php" class="p-0 m-0 ms-3 text-decoration-none">View</a>
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