<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Payments Management</title>
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
                            <h4 class="bookings-title">Payments</h4>
                            <p class="bookings-subtitle">Manage all financial transactions</p>
                        </div>

                        <!-- Top Cards -->
                        <div class="row pb-4 g-4">
                            <div class="col-md-4 col-sm-6">
                                <div class="card p-3 h-100 d-flex align-items-center flex-row rounded-4">
                                    <div class="card-body">
                                        <div class="card-title mb-2">Total Revenue</div>
                                        <div class="card-value">₹12,426.00</div>
                                        <div class="card-change mt-2">+8.2% <span class="text-muted">from last month</span></div>
                                    </div>
                                    <div class="p-3" style="background: #DCFCE7; border-radius: 2rem; height: 60px; width: 60px;">
                                        <p style="color: #16A34A; margin: 0; text-align: center; font-family: Inter; font-size: 35px; font-style: normal; font-weight: 700; line-height: 29.67px; /* 84.772% */">₹</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="card p-3 h-100 d-flex align-items-center flex-row rounded-4">
                                    <div class="card-body">
                                        <div class="card-title mb-2">Panding Payments</div>
                                        <div class="card-value">₹1,320.00</div>
                                        <div class="card-change text-warning mt-2">+2.5% <span class="text-muted">from last month</span></div>
                                    </div>
                                    <div class="p-3" style="background: #FEF9C3; border-radius: 2rem; height: 60px; width: 60px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none">
                                            <path d="M15.0327 26.6401C21.7203 26.6401 27.1418 21.2186 27.1418 14.531C27.1418 7.8433 21.7203 2.42188 15.0327 2.42188C8.34501 2.42188 2.92358 7.8433 2.92358 14.531C2.92358 21.2186 8.34501 26.6401 15.0327 26.6401Z" stroke="#CA8A04" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M15.0327 7.26562V14.5311L19.8764 16.9529" stroke="#CA8A04" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="card p-3 h-100 d-flex align-items-center flex-row rounded-4">
                                    <div class="card-body">
                                        <div class="card-title mb-1">Refunds Processed</div>
                                        <div class="card-value">78%</div>
                                        <div class="card-change text-danger mt-1">-1.2% <span class="text-muted">from last month</span></div>
                                    </div>
                                    <div class="p-3" style="background: #FEE2E2; border-radius: 2rem; height: 60px; width: 60px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none">
                                            <path d="M15.0327 6.05444V23.0072" stroke="#DC2626" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M23.5091 14.531L15.0328 23.0074L6.5564 14.531" stroke="#DC2626" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-4 rounded-4">
                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
                                <div class="d-flex gap-2 flex-wrap">
                                    <h4 class="bookings-title">Recent Transactions</h4>
                                </div>
                                <div class="d-flex gap-2 flex-wrap">
                                    <select class="form-select" style="width: 150px;">
                                        <option>All Status</option>
                                        <option>Completed</option>
                                        <option>Pending</option>
                                        <option>Refunded</option>
                                    </select>
                                    <select class="form-select" style="width: 150px;">
                                        <option>Last 30 Days</option>
                                        <option>Today</option>
                                        <option>Week</option>
                                        <option>Last 30 Days</option>
                                        <option>Year</option>
                                    </select>
                                </div>
                            </div>
        
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Transaction ID</th>
                                            <th>Booking ID</th>
                                            <th>Customer</th>
                                            <th>Hotel</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>Payment Method</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>TXN-1001</td>
                                            <td>B-1001</td>
                                            <td>John Smith</td>
                                            <td>Seaside Resort</td>
                                            <td>₹450.00</td>
                                            <td>12/06/2003</td>
                                            <td>Credit Card</td>
                                            <td><span class="badge badge-confirmed px-3 py-1 rounded-pill">Confirmed</span></td>
                                        </tr>
                                        <tr>
                                            <td>TXN-1002</td>
                                            <td>B-1002</td>
                                            <td>Emma Johnson</td>
                                            <td>Mountain View</td>
                                            <td>₹320.00</td>
                                            <td>15/06/2003</td>
                                            <td>PayPal</td>
                                            <td><span class="badge badge-pending px-3 py-1 rounded-pill">Pending</span></td>
                                        </tr>
                                        <tr>
                                            <td>TXN-1003</td>
                                            <td>B-1003</td>
                                            <td>Sarah Wilson</td>
                                            <td>Seaside Resort</td>
                                            <td>₹520.00</td>
                                            <td>18/06/2003</td>
                                            <td>Bank Transfer</td>
                                            <td><span class="badge badge-cancelled px-3 py-1 rounded-pill">Refunded</span></td>
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