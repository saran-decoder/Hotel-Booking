<?php
    include "../libs/load.php";

    if (
        !Session::get('session_token') ||
        Session::get('session_type') != 'admin' &&
        !Session::get('username')
    ) {
        header("Location: logout?logout");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>TNBooking Dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />

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

                    <!-- Welcome Text -->
                    <div class="p-4">
                        <div class="dashboard-header">Dashboard</div>
                        <div class="dashboard-subtext">Welcome back! Here's an overview of your hotel operations.</div>
                    </div>

                    <!-- Top Cards -->
                    <div class="row px-4 g-4">
                        <div class="col-md-4 col-sm-6">
                            <div class="card p-3 h-100 d-flex align-items-center flex-row rounded-4">
                                <div class="p-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 35 35" fill="none">
                                        <path d="M11.6665 2.9165V8.74984" stroke="#3B82F6" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M23.3335 2.9165V8.74984" stroke="#3B82F6" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                        <path
                                            d="M27.7083 5.8335H7.29167C5.68084 5.8335 4.375 7.13933 4.375 8.75016V29.1668C4.375 30.7777 5.68084 32.0835 7.29167 32.0835H27.7083C29.3192 32.0835 30.625 30.7777 30.625 29.1668V8.75016C30.625 7.13933 29.3192 5.8335 27.7083 5.8335Z"
                                            stroke="#3B82F6"
                                            stroke-width="3"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                        <path d="M4.375 14.5835H30.625" stroke="#3B82F6" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div class="card-body">
                                    <div class="card-title mb-1">Today's Bookings</div>
                                    <div class="card-value" id="today-bookings">0</div>
                                    <div class="card-change mt-1" id="booking-change">Loading...</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="card p-3 h-100 d-flex align-items-center flex-row rounded-4">
                                <div class="p-3">
                                    <p style="color: #CA8A04; margin: 0; text-align: center; font-family: Inter; font-size: 35px; font-style: normal; font-weight: 700; line-height: 29.67px; /* 84.772% */">₹</p>
                                </div>
                                <div class="card-body">
                                    <div class="card-title mb-1">Total Revenue</div>
                                    <div class="card-value" id="total-revenue">₹0</div>
                                    <div class="card-change mt-1" id="revenue-change">Loading...</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="card p-3 h-100 d-flex align-items-center flex-row rounded-4">
                                <div class="p-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 35 35" fill="none">
                                        <path d="M27.7082 7.2915L7.2915 27.7082" stroke="#A855F7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                        <path
                                            d="M9.47933 13.1252C11.4929 13.1252 13.1252 11.4929 13.1252 9.47933C13.1252 7.46579 11.4929 5.8335 9.47933 5.8335C7.46579 5.8335 5.8335 7.46579 5.8335 9.47933C5.8335 11.4929 7.46579 13.1252 9.47933 13.1252Z"
                                            stroke="#A855F7"
                                            stroke-width="3"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                        <path
                                            d="M25.5208 29.1667C27.5344 29.1667 29.1667 27.5344 29.1667 25.5208C29.1667 23.5073 27.5344 21.875 25.5208 21.875C23.5073 21.875 21.875 23.5073 21.875 25.5208C21.875 27.5344 23.5073 29.1667 25.5208 29.1667Z"
                                            stroke="#A855F7"
                                            stroke-width="3"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                    </svg>
                                </div>
                                <div class="card-body">
                                    <div class="card-title mb-1">Occupancy Rate</div>
                                    <div class="card-value" id="occupancy-rate">0%</div>
                                    <div class="card-change mt-1" id="occupancy-change">Loading...</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mid Cards -->
                    <div class="row px-4 g-4 mt-2">
                        <div class="col-md-4 col-sm-6">
                            <div class="card p-3 h-100 d-flex align-items-center flex-row rounded-4">
                                <div class="p-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 35 35" fill="none">
                                        <path
                                            d="M26.2502 2.91699H8.75016C7.13933 2.91699 5.8335 4.22283 5.8335 5.83366V29.167C5.8335 30.7778 7.13933 32.0837 8.75016 32.0837H26.2502C27.861 32.0837 29.1668 30.7778 29.1668 29.167V5.83366C29.1668 4.22283 27.861 2.91699 26.2502 2.91699Z"
                                            stroke="#6366F1"
                                            stroke-width="3"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                        <path d="M13.125 32.0833V26.25H21.875V32.0833" stroke="#6366F1" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M11.6665 8.75H11.6818" stroke="#6366F1" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M23.3335 8.75H23.3488" stroke="#6366F1" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M17.5 8.75H17.5153" stroke="#6366F1" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M17.5 14.583H17.5153" stroke="#6366F1" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M17.5 20.417H17.5153" stroke="#6366F1" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M23.3335 14.583H23.3488" stroke="#6366F1" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M23.3335 20.417H23.3488" stroke="#6366F1" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M11.6665 14.583H11.6818" stroke="#6366F1" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M11.6665 20.417H11.6818" stroke="#6366F1" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div class="card-body">
                                    <div class="card-title">Total Hotels</div>
                                    <div class="card-value" id="total-hotels">0</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="card p-3 h-100 d-flex align-items-center flex-row rounded-4">
                                <div class="p-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 35 35" fill="none">
                                        <path
                                            d="M23.3332 30.625V27.7083C23.3332 26.1612 22.7186 24.6775 21.6246 23.5835C20.5307 22.4896 19.0469 21.875 17.4998 21.875H8.74984C7.20274 21.875 5.71901 22.4896 4.62505 23.5835C3.53109 24.6775 2.9165 26.1612 2.9165 27.7083V30.625"
                                            stroke="#F97316"
                                            stroke-width="3"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                        <path
                                            d="M21.3335 4.56152C22.5844 4.88581 23.6922 5.61628 24.483 6.63828C25.2739 7.66028 25.703 8.91595 25.703 10.2082C25.703 11.5004 25.2739 12.7561 24.483 13.7781C23.6922 14.8001 22.5844 15.5306 21.3335 15.8549"
                                            stroke="#F97316"
                                            stroke-width="3"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                        <path
                                            d="M30.0835 30.6249V27.7082C30.0825 26.4157 29.6523 25.1602 28.8605 24.1387C28.0686 23.1172 26.9599 22.3876 25.7085 22.0645"
                                            stroke="#F97316"
                                            stroke-width="3"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                        <path
                                            d="M13.1248 16.0417C16.3465 16.0417 18.9582 13.43 18.9582 10.2083C18.9582 6.98667 16.3465 4.375 13.1248 4.375C9.90318 4.375 7.2915 6.98667 7.2915 10.2083C7.2915 13.43 9.90318 16.0417 13.1248 16.0417Z"
                                            stroke="#F97316"
                                            stroke-width="3"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                    </svg>
                                </div>
                                <div class="card-body">
                                    <div class="card-title">Active Guests</div>
                                    <div class="card-value" id="active-guests">0</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="card p-3 h-100 d-flex align-items-center flex-row rounded-4">
                                <div class="p-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="36" viewBox="0 0 35 36" fill="none">
                                        <path
                                            d="M5.8335 28.7406V6.86556C5.8335 5.89862 6.21761 4.97129 6.90134 4.28757C7.58506 3.60384 8.51239 3.21973 9.47933 3.21973H27.7085C28.0953 3.21973 28.4662 3.37337 28.7397 3.64686C29.0132 3.92035 29.1668 4.29129 29.1668 4.67806V30.9281C29.1668 31.3148 29.0132 31.6858 28.7397 31.9593C28.4662 32.2327 28.0953 32.3864 27.7085 32.3864H9.47933C8.51239 32.3864 7.58506 32.0023 6.90134 31.3186C6.21761 30.6348 5.8335 29.7075 5.8335 28.7406ZM5.8335 28.7406C5.8335 27.7736 6.21761 26.8463 6.90134 26.1626C7.58506 25.4788 8.51239 25.0947 9.47933 25.0947H29.1668"
                                            stroke="#14B8A6"
                                            stroke-width="3"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                    </svg>
                                </div>
                                <div class="card-body">
                                    <div class="card-title">Total Bookings</div>
                                    <div class="card-value" id="total-bookings">0</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Revenue Trends + Booking Status -->
                    <div class="row px-4 g-4 mt-2">
                        <div class="col-lg-8 col-md-12">
                            <div class="card p-3 h-100">
                                <div class="d-flex justify-content-between flex-wrap">
                                    <div class="alert-title">Revenue Trends</div>
                                    <select class="form-select w-auto mt-2 mt-sm-0" id="revenue-period">
                                        <option value="12">Last 12 months</option>
                                        <option value="6">Last 6 months</option>
                                        <option value="3">Last 3 months</option>
                                    </select>
                                </div>
                                <canvas id="revenueChart" class="chart-placeholder mt-3"></canvas>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12">
                            <div class="card p-3 h-100 text-center">
                                <div class="alert-title">Booking Status</div>
                                <div style="color: #6b7280; font-size: 14px;">Current distribution of bookings</div>
                                <canvas id="bookingPieChart" class="chart-placeholder mt-3"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Alerts -->
                    <div class="px-4 mt-4 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
                            <path
                                d="M12.5002 22.9163C18.2531 22.9163 22.9168 18.2526 22.9168 12.4997C22.9168 6.74671 18.2531 2.08301 12.5002 2.08301C6.7472 2.08301 2.0835 6.74671 2.0835 12.4997C2.0835 18.2526 6.7472 22.9163 12.5002 22.9163Z"
                                stroke="#EF4444"
                                stroke-width="2.11929"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                            <path d="M12.5 8.33301V12.4997" stroke="#EF4444" stroke-width="2.11929" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M12.5 16.667H12.5098" stroke="#EF4444" stroke-width="2.11929" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Alerts & Warnings
                    </div>
                    <div id="alerts-container">
                        <!-- Alerts will be populated here -->
                    </div>
                </div>
            </div>
        </div>

        <?php include "temp/footer.php" ?>

        <script>
            $(document).ready(function() {
                // Initialize charts with empty data
                const revenueChart = new Chart($('#revenueChart'), {
                    type: 'line',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Revenue',
                            data: [],
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.3)',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });

                const bookingPieChart = new Chart($('#bookingPieChart'), {
                    type: 'pie',
                    data: {
                        labels: ['Confirmed', 'Pending', 'Cancelled'],
                        datasets: [{
                            data: [0, 0, 0],
                            backgroundColor: ['#3B82F6', '#FBBF24', '#EF4444']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });

                // Load dashboard data
                loadDashboardData();
                
                // Event listener for period change
                $('#revenue-period').change(function() {
                    loadRevenueData($(this).val());
                });

                // Function to load all dashboard data
                function loadDashboardData() {
                    // Load stats
                    loadStats();
                    
                    // Load revenue data with default period
                    loadRevenueData(12);
                    
                    // Load booking status data
                    loadBookingStatus();
                    
                    // Load alerts
                    loadAlerts();
                }

                // Function to load stats
                function loadStats() {
                    // Get bookings data
                    $.get('../api/booking/booking-list', function(bookingsResponse) {
                        if (bookingsResponse.success) {
                            const bookings = bookingsResponse.data;
                            const today = new Date().toISOString().split('T')[0];
                            
                            // Calculate today's bookings
                            const todayBookings = bookings.filter(booking => 
                                booking.booking_created_at.split(' ')[0] === today
                            ).length;
                            
                            // Calculate total bookings
                            const totalBookings = bookings.length;
                            
                            // Calculate booking change (simple mock calculation)
                            const bookingChange = Math.round((Math.random() * 20) - 5);
                            const changeText = bookingChange >= 0 ? 
                                `+${bookingChange}% from yesterday` : 
                                `${bookingChange}% from yesterday`;
                            
                            // Update UI
                            $('#today-bookings').text(todayBookings);
                            $('#total-bookings').text(totalBookings);
                            $('#booking-change').text(changeText);
                            
                            // Calculate active guests (unique users with active bookings)
                            const activeGuests = [...new Set(
                                bookings.filter(booking => 
                                    booking.booking_status === 'confirmed' || 
                                    booking.booking_status === 'checked_in'
                                ).map(booking => booking.guest_name)
                            )].length;
                            
                            $('#active-guests').text(activeGuests);
                        }
                    }).fail(function() {
                        console.error('Failed to load bookings data');
                    });
                    
                    // Get payment stats
                    $.get('../api/booking/payment-list', function(paymentsResponse) {
                        if (paymentsResponse.success) {
                            const stats = paymentsResponse.stats;
                            
                            // Update revenue
                            $('#total-revenue').text('₹' + stats.total_revenue);
                            
                            // Calculate revenue change (simple mock calculation)
                            const revenueChange = Math.round((Math.random() * 25) - 5);
                            const changeText = revenueChange >= 0 ? 
                                `+${revenueChange}% from yesterday` : 
                                `${revenueChange}% from yesterday`;
                            
                            $('#revenue-change').text(changeText);
                        }
                    }).fail(function() {
                        console.error('Failed to load payment data');
                    });
                    
                    // Get hotels data
                    $.get('../api/hotel/list', function(hotelsResponse) {
                        if (Array.isArray(hotelsResponse)) {
                            // Count unique hotels
                            const hotelIds = [...new Set(hotelsResponse.map(hotel => hotel.hotel_id))];
                            $('#total-hotels').text(hotelIds.length);
                            
                            // Calculate occupancy rate (mock calculation)
                            const occupancyRate = Math.round(Math.random() * 40 + 50); // 50-90%
                            const occupancyChange = Math.round((Math.random() * 10) - 3);
                            const changeText = occupancyChange >= 0 ? 
                                `+${occupancyChange}% from yesterday` : 
                                `${occupancyChange}% from yesterday`;
                            
                            $('#occupancy-rate').text(occupancyRate + '%');
                            $('#occupancy-change').text(changeText);
                        }
                    }).fail(function() {
                        console.error('Failed to load hotels data');
                    });
                }

                // Function to load revenue data
                function loadRevenueData(months) {
                    // In a real application, you would fetch this from your API
                    // For now, we'll generate mock data
                    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    const currentMonth = new Date().getMonth();
                    
                    let labels = [];
                    let data = [];
                    
                    // Generate data for the requested number of months
                    for (let i = months - 1; i >= 0; i--) {
                        const monthIndex = (currentMonth - i + 12) % 12;
                        labels.push(monthNames[monthIndex]);
                        
                        // Generate random revenue between 2000 and 6000
                        data.push(Math.floor(Math.random() * 4000 + 2000));
                    }
                    
                    // Update the chart
                    revenueChart.data.labels = labels;
                    revenueChart.data.datasets[0].data = data;
                    revenueChart.update();
                }

                // Function to load booking status data
                function loadBookingStatus() {
                    // Get bookings data
                    $.get('../api/booking/booking-list', function(response) {
                        if (response.success) {
                            const bookings = response.data;
                            
                            // Count booking statuses
                            const statusCounts = {
                                'confirmed': 0,
                                'pending': 0,
                                'cancelled': 0
                            };
                            
                            bookings.forEach(booking => {
                                const status = booking.booking_status.toLowerCase();
                                if (statusCounts.hasOwnProperty(status)) {
                                    statusCounts[status]++;
                                }
                            });
                            
                            // Update the pie chart
                            bookingPieChart.data.datasets[0].data = [
                                statusCounts.confirmed,
                                statusCounts.pending,
                                statusCounts.cancelled
                            ];
                            bookingPieChart.update();
                        }
                    }).fail(function() {
                        console.error('Failed to load booking status data');
                    });
                }

                // Function to load alerts
                function loadAlerts() {
                    // Get hotels and rooms data to generate alerts
                    $.when(
                        $.get('../api/hotel/list'),
                        $.get('../api/hotel/list-room')
                    ).done(function(hotelsResponse, roomsResponse) {
                        const hotels = hotelsResponse[0];
                        const rooms = roomsResponse[0];
                        
                        const alertsContainer = $('#alerts-container');
                        alertsContainer.empty();
                        
                        // Check for low inventory
                        if (Array.isArray(rooms)) {
                            const lowInventoryRooms = rooms.filter(room => {
                                // Mock logic: consider rooms with price > 5000 as potentially low inventory
                                return room.price_per_night > 5000;
                            });
                            
                            if (lowInventoryRooms.length > 0) {
                                const randomRoom = lowInventoryRooms[Math.floor(Math.random() * lowInventoryRooms.length)];
                                const randomHotel = Array.isArray(hotels) ? 
                                    hotels.find(h => h.hotel_id == randomRoom.hotel_id) : 
                                    { hotel_name: 'Your Hotel' };
                                    
                                const alertHtml = `
                                    <div class="alert-yellow rounded-3">
                                        Low inventory: Only ${Math.floor(Math.random() * 5) + 1} ${randomRoom.room_type} 
                                        available at ${randomHotel.hotel_name || 'Your Hotel'} for the weekend
                                    </div>
                                `;
                                alertsContainer.append(alertHtml);
                            }
                        }
                        
                        // Check for high cancellation rate (mock)
                        if (Math.random() > 0.5) {
                            const alertHtml = `
                                <div class="alert-red rounded-3">
                                    High cancellation rate detected for a property
                                </div>
                            `;
                            alertsContainer.append(alertHtml);
                        }
                        
                        // If no alerts, show a message
                        if (alertsContainer.children().length === 0) {
                            alertsContainer.append(`
                                <div class="alert-green rounded-3">
                                    No critical alerts at this time
                                </div>
                            `);
                        }
                    }).fail(function() {
                        $('#alerts-container').html(`
                            <div class="alert-red rounded-3">
                                Error loading alerts
                            </div>
                        `);
                    });
                }
                
                // Refresh data every 5 minutes
                setInterval(loadDashboardData, 300000);
            });
        </script>
        
    </body>
</html>