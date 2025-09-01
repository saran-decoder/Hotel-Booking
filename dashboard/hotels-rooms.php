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
                                    fetchHotels();
                                    showToast('Success', 'Hotel & Rooms deleted successfully', 'success'); 
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
                                <td>${hotel.hotel_name || 'N/A'}</td>
                                <td><a href="${hotel.hotel_coordinates || '#'}">Find</a></td>
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
                                        <a href="hotel?id=${hotel.id}" class="me-3 text-decoration-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="14" viewBox="0 0 20 14" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6 7.16797C6 6.17341 6.39509 5.21958 7.09835 4.51632C7.80161 3.81306 8.75544 3.41797 9.75 3.41797C10.7446 3.41797 11.6984 3.81306 12.4017 4.51632C13.1049 5.21958 13.5 6.17341 13.5 7.16797C13.5 8.16253 13.1049 9.11636 12.4017 9.81962C11.6984 10.5229 10.7446 10.918 9.75 10.918C8.75544 10.918 7.80161 10.5229 7.09835 9.81962C6.39509 9.11636 6 8.16253 6 7.16797ZM9.75 4.91797C9.15326 4.91797 8.58097 5.15502 8.15901 5.57698C7.73705 5.99894 7.5 6.57123 7.5 7.16797C7.5 7.76471 7.73705 8.337 8.15901 8.75896C8.58097 9.18092 9.15326 9.41797 9.75 9.41797C10.3467 9.41797 10.919 9.18092 11.341 8.75896C11.7629 8.337 12 7.76471 12 7.16797C12 6.57123 11.7629 5.99894 11.341 5.57698C10.919 5.15502 10.3467 4.91797 9.75 4.91797Z" fill="#007AFF"/>
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M2.073 5.81397C1.654 6.41797 1.5 6.89097 1.5 7.16797C1.5 7.44497 1.654 7.91797 2.073 8.52197C2.479 9.10497 3.081 9.73797 3.843 10.323C5.37 11.495 7.463 12.418 9.75 12.418C12.037 12.418 14.13 11.495 15.657 10.323C16.419 9.73797 17.021 9.10497 17.427 8.52197C17.846 7.91797 18 7.44497 18 7.16797C18 6.89097 17.846 6.41797 17.427 5.81397C17.021 5.23097 16.419 4.59797 15.657 4.01297C14.13 2.84097 12.037 1.91797 9.75 1.91797C7.463 1.91797 5.37 2.84097 3.843 4.01297C3.081 4.59797 2.479 5.23097 2.073 5.81397ZM2.929 2.82297C4.66 1.49497 7.066 0.417969 9.75 0.417969C12.434 0.417969 14.84 1.49497 16.57 2.82297C17.437 3.48797 18.153 4.22997 18.659 4.95897C19.151 5.66797 19.5 6.44497 19.5 7.16797C19.5 7.89097 19.15 8.66797 18.659 9.37697C18.153 10.106 17.437 10.847 16.571 11.513C14.841 12.841 12.434 13.918 9.75 13.918C7.066 13.918 4.66 12.841 2.93 11.513C2.063 10.848 1.347 10.106 0.841 9.37697C0.35 8.66797 0 7.89097 0 7.16797C0 6.44497 0.35 5.66797 0.841 4.95897C1.347 4.22997 2.063 3.48897 2.929 2.82297Z" fill="#007AFF"/>
                                            </svg>
                                        </a>
                                        <a type="button" data-id="${hotel.id}" class="delete-btn text-danger text-decoration-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="15" viewBox="0 0 13 15" fill="none">
                                                <path d="M11.1875 3.4375L10.8002 9.70318C10.7012 11.304 10.6517 12.1045 10.2505 12.6799C10.0521 12.9644 9.79666 13.2046 9.50044 13.385C8.90132 13.75 8.09937 13.75 6.49546 13.75C4.88945 13.75 4.08644 13.75 3.48691 13.3843C3.1905 13.2035 2.935 12.963 2.73668 12.678C2.33555 12.1016 2.28716 11.3001 2.19038 9.69697L1.8125 3.4375" stroke="#F83D56" stroke-width="1.5" stroke-linecap="round"/>
                                                <path d="M0.875 3.4375H12.125M9.03483 3.4375L8.60816 2.55733C8.32473 1.97266 8.18302 1.68033 7.93857 1.498C7.88435 1.45756 7.82693 1.42159 7.76689 1.39044C7.4962 1.25 7.17132 1.25 6.52158 1.25C5.85552 1.25 5.52249 1.25 5.2473 1.39632C5.18631 1.42875 5.12811 1.46618 5.07331 1.50823C4.82602 1.69794 4.68789 2.00097 4.41163 2.60704L4.03308 3.4375" stroke="#F83D56" stroke-width="1.5" stroke-linecap="round"/>
                                                <path d="M4.9375 10.3125L4.9375 6.5625" stroke="#F83D56" stroke-width="1.5" stroke-linecap="round"/>
                                                <path d="M8.0625 10.3125L8.0625 6.5625" stroke="#F83D56" stroke-width="1.5" stroke-linecap="round"/>
                                            </svg>
                                        </a>
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