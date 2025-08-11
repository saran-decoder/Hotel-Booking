<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TNBooking.in - Admin Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom CSS -->
    <style>
        :root {
            --sidebar-width: 250px;
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
        }
        
        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f8f9fc;
            overflow-x: hidden;
        }
        
        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: linear-gradient(180deg, var(--primary-color) 10%, #224abe 100%);
            color: white;
            transition: all 0.3s;
            z-index: 1000;
        }
        
        .sidebar-brand {
            height: 4.375rem;
            text-decoration: none;
            font-size: 1.2rem;
            font-weight: 800;
            padding: 1.5rem 1rem;
            text-align: center;
            letter-spacing: 0.05rem;
            z-index: 1;
            color: white;
        }
        
        .sidebar-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            margin: 0 1rem 1rem;
        }
        
        .nav-item {
            position: relative;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1rem;
            font-weight: 700;
            font-size: 0.85rem;
        }
        
        .nav-link:hover {
            color: white;
        }
        
        .nav-link i {
            margin-right: 0.25rem;
        }
        
        .nav-link.active {
            color: white;
        }
        
        /* Main Content */
        #content {
            width: calc(100% - var(--sidebar-width));
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s;
        }
        
        /* Topbar */
        .topbar {
            height: 4.375rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            background-color: white;
        }
        
        .topbar .navbar-search {
            width: 25rem;
        }
        
        .topbar .navbar-search input {
            font-size: 0.85rem;
            height: auto;
        }
        
        .topbar .topbar-divider {
            width: 0;
            border-right: 1px solid #e3e6f0;
            height: calc(4.375rem - 2rem);
            margin: auto 1rem;
        }
        
        .topbar .nav-item .nav-link {
            height: 4.375rem;
            display: flex;
            align-items: center;
            padding: 0 0.75rem;
            color: #d1d3e2;
        }
        
        .topbar .nav-item .nav-link:hover {
            color: #b7b9cc;
        }
        
        .topbar .nav-item .nav-link .badge-counter {
            position: absolute;
            transform: scale(0.7);
            transform-origin: top right;
            right: 0.25rem;
            margin-top: -0.25rem;
        }
        
        /* Cards */
        .card {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 1.5rem;
            border: none;
            border-radius: 0.35rem;
        }
        
        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.35rem;
            font-weight: 700;
            color: #4e73df;
        }
        
        .card-body {
            padding: 1.25rem;
        }
        
        /* Stats Cards */
        .stat-card {
            border-left: 0.25rem solid;
            border-radius: 0.35rem;
        }
        
        .stat-card.primary {
            border-left-color: var(--primary-color);
        }
        
        .stat-card.success {
            border-left-color: var(--success-color);
        }
        
        .stat-card.info {
            border-left-color: var(--info-color);
        }
        
        .stat-card.warning {
            border-left-color: var(--warning-color);
        }
        
        .stat-card.danger {
            border-left-color: var(--danger-color);
        }
        
        .stat-card .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .stat-card .stat-label {
            font-size: 0.875rem;
            color: #858796;
        }
        
        .stat-card .stat-change {
            font-size: 0.75rem;
        }
        
        .stat-card .stat-change.positive {
            color: var(--success-color);
        }
        
        .stat-card .stat-change.negative {
            color: var(--danger-color);
        }
        
        /* Alerts */
        .alert-warning-custom {
            color: #856404;
            background-color: #fff3cd;
            border-color: #ffeeba;
        }
        
        .alert-danger-custom {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        
        /* Charts */
        .chart-area {
            position: relative;
            height: 20rem;
            width: 100%;
        }
        
        .chart-pie {
            position: relative;
            height: 15rem;
            width: 100%;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            
            #content {
                width: 100%;
                margin-left: 0;
            }
            
            .sidebar.toggled {
                margin-left: 0;
            }
            
            #content.toggled {
                margin-left: 250px;
                width: calc(100% - 250px);
            }
            
            .topbar .navbar-search {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
            <div class="sidebar-brand-text mx-3">TNBooking.in</div>
        </a>
        <hr class="sidebar-divider">
        
        <!-- Nav Items -->
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="#">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-calendar-check"></i>
                    <span>Bookings</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-building"></i>
                    <span>Hotels & Rooms</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-people"></i>
                    <span>Customers</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-credit-card"></i>
                    <span>Payments</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-tag"></i>
                    <span>Promotions</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-chat-left-text"></i>
                    <span>Feedback & Support</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-gear"></i>
                    <span>Admin Settings</span>
                </a>
            </li>
        </ul>
        
        <!-- Sidebar Toggle Button (Mobile) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </div>
    
    <!-- Main Content -->
    <div id="content">
        <!-- Topbar -->
        <nav class="navbar navbar-expand topbar mb-4 static-top">
            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="bi bi-list"></i>
            </button>
            
            <!-- Topbar Search -->
            <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </form>
            
            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                <li class="nav-item dropdown no-arrow d-sm-none">
                    <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-search"></i>
                    </a>
                </li>
                
                <!-- Nav Item - Alerts -->
                <li class="nav-item dropdown no-arrow mx-1">
                    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell"></i>
                        <!-- Counter - Alerts -->
                        <span class="badge badge-danger badge-counter">3+</span>
                    </a>
                </li>
                
                <div class="topbar-divider d-none d-sm-block"></div>
                
                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin Name</span>
                        <span class="badge bg-primary">Administrator</span>
                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-person mr-2"></i>
                            Profile
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-gear mr-2"></i>
                            Settings
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                            <i class="bi bi-box-arrow-right mr-2"></i>
                            Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- End of Topbar -->
        
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                <div class="dropdown">
                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-calendar"></i> Last 12 months
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="#">Today</a></li>
                        <li><a class="dropdown-item" href="#">This Week</a></li>
                        <li><a class="dropdown-item" href="#">This Month</a></li>
                        <li><a class="dropdown-item" href="#">This Year</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Custom Range</a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Content Row -->
            <div class="row">
                <!-- Today's Bookings Card -->
                <div class="col-xl-2 col-md-4 mb-4">
                    <div class="card stat-card primary h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="stat-label">Today's Bookings</div>
                                    <div class="stat-value">24</div>
                                    <div class="stat-change positive">
                                        <i class="bi bi-arrow-up"></i> 8% from yesterday
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-calendar-check fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Total Revenue Card -->
                <div class="col-xl-2 col-md-4 mb-4">
                    <div class="card stat-card success h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="stat-label">Total Revenue</div>
                                    <div class="stat-value">$12,426</div>
                                    <div class="stat-change positive">
                                        <i class="bi bi-arrow-up"></i> 12% from yesterday
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-currency-dollar fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Occupancy Rate Card -->
                <div class="col-xl-2 col-md-4 mb-4">
                    <div class="card stat-card info h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="stat-label">Occupancy Rate</div>
                                    <div class="stat-value">78%</div>
                                    <div class="stat-change positive">
                                        <i class="bi bi-arrow-up"></i> 3% from yesterday
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-house-door fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Total Hotels Card -->
                <div class="col-xl-2 col-md-4 mb-4">
                    <div class="card stat-card warning h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="stat-label">Total Hotels</div>
                                    <div class="stat-value">8</div>
                                    <div class="stat-change positive">
                                        <i class="bi bi-arrow-up"></i> 2% from last month
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-building fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Active Guests Card -->
                <div class="col-xl-2 col-md-4 mb-4">
                    <div class="card stat-card danger h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="stat-label">Active Guests</div>
                                    <div class="stat-value">142</div>
                                    <div class="stat-change negative">
                                        <i class="bi bi-arrow-down"></i> 5% from yesterday
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-people fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Total Bookings Card -->
                <div class="col-xl-2 col-md-4 mb-4">
                    <div class="card stat-card primary h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="stat-label">Total Bookings</div>
                                    <div class="stat-value">1,285</div>
                                    <div class="stat-change positive">
                                        <i class="bi bi-arrow-up"></i> 15% from last month
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-journal-check fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Content Row -->
            <div class="row">
                <!-- Revenue Trends Chart -->
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Revenue Trends</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-area">
                                <canvas id="revenueChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Booking Status Pie Chart -->
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Booking Status</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-pie">
                                <canvas id="bookingStatusChart"></canvas>
                            </div>
                            <div class="mt-4 text-center small">
                                <span class="mr-2">
                                    <i class="bi bi-circle-fill text-primary"></i> Confirmed
                                </span>
                                <span class="mr-2">
                                    <i class="bi bi-circle-fill text-warning"></i> Pending
                                </span>
                                <span class="mr-2">
                                    <i class="bi bi-circle-fill text-danger"></i> Cancelled
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Content Row -->
            <div class="row">
                <!-- Alerts & Warnings -->
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Alerts & Warnings</h6>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning-custom alert-dismissible fade show" role="alert">
                                <strong><i class="bi bi-exclamation-triangle-fill"></i> Low inventory:</strong> Only 2 Deluxe Rooms available at Seaside Resort for the weekend.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <div class="alert alert-danger-custom alert-dismissible fade show" role="alert">
                                <strong><i class="bi bi-exclamation-octagon-fill"></i> High cancellation rate</strong> detected for Mountain View Hotel.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- End of Main Content -->
    
    <!-- Bootstrap core JavaScript-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Sidebar Toggle
        $(document).ready(function() {
            $('#sidebarToggle, #sidebarToggleTop').on('click', function() {
                $('body').toggleClass('sidebar-toggled');
                $('.sidebar').toggleClass('toggled');
                if ($('.sidebar').hasClass('toggled')) {
                    $('.sidebar .collapse').collapse('hide');
                }
            });
            
            // Close any open menu accordions when window is resized below 768px
            $(window).resize(function() {
                if ($(window).width() < 768) {
                    $('.sidebar .collapse').collapse('hide');
                }
            });
            
            // Revenue Chart
            var ctx = document.getElementById('revenueChart').getContext('2d');
            var revenueChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Revenue',
                        data: [4000, 3500, 4200, 4800, 5200, 6000, 5800, 5500, 6200, 7000, 7500, 8000],
                        backgroundColor: 'rgba(78, 115, 223, 0.05)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                        borderWidth: 2,
                        fill: true
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value;
                                }
                            }
                        }
                    }
                }
            });
            
            // Booking Status Chart
            var ctx2 = document.getElementById('bookingStatusChart').getContext('2d');
            var bookingStatusChart = new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: ['Confirmed', 'Pending', 'Cancelled'],
                    datasets: [{
                        data: [65, 15, 20],
                        backgroundColor: ['#4e73df', '#f6c23e', '#e74a3b'],
                        hoverBackgroundColor: ['#2e59d9', '#dda20a', '#be2617'],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    cutout: '70%',
                },
            });
        });
    </script>
</body>
</html>