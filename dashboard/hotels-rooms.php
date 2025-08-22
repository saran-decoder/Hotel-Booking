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
        <title>Hotels & Rooms Management</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <?php include "temp/head.php" ?>

        <style>
            .loading-spinner {
                display: inline-block;
                width: 20px;
                height: 20px;
                border: 3px solid #f3f3f3;
                border-top: 3px solid #3498db;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }
            
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            
            .status-badge {
                padding: 0.25rem 0.75rem;
                border-radius: 50rem;
                font-size: 0.875rem;
                font-weight: 500;
            }
            
            .status-active {
                background-color: #d1fae5;
                color: #065f46;
            }
            
            .status-inactive {
                background-color: #fee2e2;
                color: #b91c1c;
            }
            
            .status-maintenance {
                background-color: #fef3c7;
                color: #92400e;
            }
        </style>
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
                            <a href="add-hotel" class="btn btn-primary d-flex align-items-center justify-content-between">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="me-2" viewBox="0 0 18 18" fill="none">
                                <path d="M4.52368 9.23145H14.4316" stroke="white" stroke-width="2.12313" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9.47754 4.27734V14.1853" stroke="white" stroke-width="2.12313" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg> Add New Hotel
                            </a>
                        </div>

                        <div class="bg-white p-4 rounded-4">
                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
                                <div class="d-flex gap-2 flex-wrap">
                                    <h4 class="bookings-title">All Hotels</h4>
                                </div>
                            </div>
        
                            <div class="table-responsive">
                                <table class="table w-100" id="hotelsTable">
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
                                    <tbody id="hotelsTableBody">
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="loading-spinner mx-auto mb-2"></div>
                                                <p class="text-muted mb-0">Loading hotels...</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
        
                            <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-3">
                                <span class="text-muted" id="showingText">Showing 0 of 0 Hotels</span>
                                <nav id="paginationContainer" style="display: none;">
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
        
        <script>
            $(document).ready(function() {
                // Fetch hotels from API
                function fetchHotels() {
                    $.ajax({
                        url: '../api/hotel/list',
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response && response.length > 0) {
                                populateHotelsTable(response);
                                updatePagination(response);
                            } else {
                                showNoHotelsMessage();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching hotels:', error);
                            showErrorMessage();
                        }
                    });
                }

                // Delete doctor
                $(document).on("click", ".delete-btn", function () {
                    const id = $(this).data("id");

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.post("../api/hotel/delete", { id: id }, function (response) {
                                if (response || response.message === "Hotel & Rooms deleted successfully") { 
                                    showToast("Success", response.message || "Hotel & Rooms deleted successfully"); 
                                    setTimeout(function () {
                                        window.location.href = "hotels-rooms";
                                    }, 1500);
                                } else { 
                                    showToast("Error", response.message || "Delete failed"); 
                                }
                            }, "json");
                        }
                    });
                });

                // Populate hotels table with data
                function populateHotelsTable(hotels) {
                    const tableBody = $('#hotelsTableBody');
                    tableBody.empty();

                    // Group rooms by hotel ID
                    const hotelMap = {};
                    hotels.forEach(hotel => {
                        if (!hotelMap[hotel.id]) {
                            hotelMap[hotel.id] = {
                                ...hotel,
                                rooms: []
                            };
                        }
                        hotelMap[hotel.id].rooms.push({
                            room_id: hotel.room_id,
                            room_type: hotel.room_type,
                            room_status: hotel.room_status
                        });
                    });

                    // Loop hotels
                    Object.values(hotelMap).forEach(hotel => {
                        // Decide hotel status based on room statuses
                        let hotelStatus = "inactive";
                        if (hotel.rooms.length > 0) {
                            if (hotel.rooms.some(r => r.room_status === "maintenance")) {
                                hotelStatus = "maintenance";
                            } else if (hotel.rooms.every(r => r.room_status === "active")) {
                                hotelStatus = "active";
                            }
                        }

                        const row = `
                            <tr>
                                <td>${hotel.name || 'N/A'}</td>
                                <td><a href="${hotel.coordinates || '#'}">Find</a></td>
                                <td>${hotel.rooms.length}</td>
                                <td>${hotel.occupancy_rate || '0'}%</td>
                                <td>${hotel.rating || 'N/A'}</td>
                                <td>
                                    <span class="status-badge ${getStatusClass(hotelStatus)}">
                                        ${getStatusText(hotelStatus)}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-item-center">
                                        <a href="hotel?id=${hotel.id}" class="me-3 text-decoration-none">View</a>
                                        <a type="button" data-id="${hotel.id}" class="delete-btn text-danger text-decoration-none">Delete</a>
                                    </div>
                                </td>
                            </tr>
                        `;
                        tableBody.append(row);
                    });
                }

                // Get status class based on status value
                function getStatusClass(status) {
                    switch(status) {
                        case 'active': return 'status-active';
                        case 'inactive': return 'status-inactive';
                        case 'maintenance': return 'status-maintenance';
                        default: return 'status-inactive';
                    }
                }

                // Get status text based on status value
                function getStatusText(status) {
                    switch(status) {
                        case 'active': return 'Active';
                        case 'inactive': return 'Inactive';
                        case 'maintenance': return 'Maintenance';
                        default: return status || 'Inactive';
                    }
                }

                // Update pagination information
                function updatePagination(hotels) {
                    // Group by hotel.id to count unique hotels
                    const uniqueHotels = {};
                    hotels.forEach(h => {
                        uniqueHotels[h.id] = true;
                    });
                    const hotelCount = Object.keys(uniqueHotels).length;

                    $('#showingText').text(`Showing ${hotelCount} of ${hotelCount} Hotels`);

                    if (hotelCount > 10) {
                        $('#paginationContainer').show();
                    } else {
                        $('#paginationContainer').hide();
                    }
                }

                // Show no hotels message
                function showNoHotelsMessage() {
                    $('#hotelsTableBody').html(`
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-hotel fa-2x mb-3"></i>
                                    <p class="mb-1">No hotels found</p>
                                    <small>Add your first hotel to get started</small>
                                </div>
                            </td>
                        </tr>
                    `);
                    $('#showingText').text('Showing 0 of 0 Hotels');
                }

                // Show error message
                function showErrorMessage() {
                    $('#hotelsTableBody').html(`
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-danger">
                                    <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                                    <p class="mb-1">Error loading hotels</p>
                                    <small>Please try again later</small>
                                </div>
                            </td>
                        </tr>
                    `);
                }

                // Initialize hotels fetch
                fetchHotels();
            });
        </script>
        
    </body>
</html>