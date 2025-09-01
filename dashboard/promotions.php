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
        <title>Promotions Management</title>
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
                                <table class="table" id="promotionsTable">
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
                                        <tr id="loadingRow">
                                            <td colspan="8" class="text-center py-4">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <p class="mt-2 mb-0">Loading promotions...</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
        
                            <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-3" id="paginationContainer">
                                <span class="text-muted" id="resultCount">Showing 0 of 0 promotions</span>
                                <nav id="paginationNav" style="display: none;">
                                    <ul class="pagination mb-0" id="paginationList">
                                        <li class="page-item"><a class="page-link" href="#" id="prevPage">Previous</a></li>
                                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item"><a class="page-link" href="#" id="nextPage">Next</a></li>
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
                // Global variables for pagination
                let currentPage = 1;
                let totalPages = 1;
                const limit = 10; // Set limit to 10 promotions per page
                
                // Function to format date
                function formatDate(dateString) {
                    if (!dateString) return '-';
                    const date = new Date(dateString);
                    return date.toLocaleDateString('en-GB'); // DD/MM/YYYY format
                }
                
                // Function to determine status based on current date
                function getStatusBadge(startDate, endDate) {
                    const today = new Date();
                    const start = new Date(startDate);
                    const end = new Date(endDate);
                    
                    // Set time to midnight for accurate date comparison
                    today.setHours(0, 0, 0, 0);
                    start.setHours(0, 0, 0, 0);
                    end.setHours(0, 0, 0, 0);
                    
                    if (today < start) {
                        return '<span class="badge badge-pending px-3 py-1 rounded-pill">Upcoming</span>';
                    } else if (today > end) {
                        return '<span class="badge badge-cancelled px-3 py-1 rounded-pill">Expired</span>';
                    } else {
                        return '<span class="badge badge-confirmed px-3 py-1 rounded-pill">Active</span>';
                    }
                }
                
                // Function to load promotions with pagination
                function loadPromotions(page = 1) {
                    currentPage = page;
                    
                    $.ajax({
                        url: '../api/promotion/list',
                        type: 'GET',
                        data: {
                            page: page,
                            limit: limit
                        },
                        dataType: 'json',
                        success: function(response) {
                            $('#loadingRow').remove();
                            
                            // Handle both old and new API response formats
                            let promotions = [];
                            let total = 0;
                            let totalPages = 1;
                            
                            if (response && response.promotions) {
                                // New format with pagination
                                promotions = response.promotions;
                                total = response.total || promotions.length;
                                totalPages = response.total_pages || Math.ceil(total / limit);
                            } else if (Array.isArray(response)) {
                                // Old format - simple array
                                promotions = response;
                                total = promotions.length;
                                totalPages = Math.ceil(total / limit);
                                
                                // Apply client-side pagination for old API
                                const startIndex = (page - 1) * limit;
                                promotions = promotions.slice(startIndex, startIndex + limit);
                            }
                            
                            if (promotions.length > 0) {
                                const tbody = $('#promotionsTable tbody');
                                tbody.empty();
                                
                                $.each(promotions, function(index, promotion) {
                                    const row = `
                                        <tr>
                                            <td>${promotion.promotion_name || 'N/A'}</td>
                                            <td>${promotion.coupon_code || 'N/A'}</td>
                                            <td>${promotion.discount || '0'}%</td>
                                            <td>${formatDate(promotion.start_date)}</td>
                                            <td>${formatDate(promotion.end_date)}</td>
                                            <td>${promotion.usage_count || '0'}</td>
                                            <td>${getStatusBadge(promotion.start_date, promotion.end_date)}</td>
                                            <td>
                                                <div class="d-flex align-item-center">
                                                    <a href="edit-promotion.php?id=${promotion.id}" class="text-decoration-none">Edit</a>
                                                    <a href="#" class="m-0 ms-3 text-danger text-decoration-none delete-promotion" data-id="${promotion.id}">Delete</a>
                                                </div>
                                            </td>
                                        </tr>
                                    `;
                                    tbody.append(row);
                                });
                                
                                // Update pagination
                                updatePagination(total, totalPages);
                                
                                // Update result count
                                const start = ((page - 1) * limit) + 1;
                                const end = Math.min(page * limit, total);
                                $('#resultCount').text(`Showing ${start}-${end} of ${total} promotions`);
                                
                            } else {
                                $('#promotionsTable tbody').html(`
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-tags fa-2x mb-3"></i>
                                                <p>No promotions found</p>
                                            </div>
                                        </td>
                                    </tr>
                                `);
                                
                                // Hide pagination if no results
                                $('#paginationNav').hide();
                                $('#resultCount').text('Showing 0 of 0 promotions');
                            }
                        },
                        error: function(xhr, status, error) {
                            $('#loadingRow').html(`
                                <td colspan="8" class="text-center py-4 text-danger">
                                    <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                                    <p>Error loading promotions</p>
                                    <button class="btn btn-outline-primary mt-2" onclick="loadPromotions()">Try Again</button>
                                </td>
                            `);
                            console.error('Error loading promotions:', error, xhr.responseText);
                        }
                    });
                }
                
                // Function to update pagination controls
                function updatePagination(totalItems, totalPages) {
                    const paginationList = $('#paginationList');
                    paginationList.empty();
                    
                    // Previous button
                    paginationList.append('<li class="page-item" id="prevPageLi"><a class="page-link" href="#" id="prevPage">Previous</a></li>');
                    
                    // Page numbers
                    const maxVisiblePages = 5;
                    let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
                    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
                    
                    if (endPage - startPage + 1 < maxVisiblePages) {
                        startPage = Math.max(1, endPage - maxVisiblePages + 1);
                    }
                    
                    for (let i = startPage; i <= endPage; i++) {
                        const activeClass = i === currentPage ? 'active' : '';
                        paginationList.append(`<li class="page-item ${activeClass}"><a class="page-link page-number" href="#" data-page="${i}">${i}</a></li>`);
                    }
                    
                    // Next button
                    paginationList.append('<li class="page-item" id="nextPageLi"><a class="page-link" href="#" id="nextPage">Next</a></li>');
                    
                    // Enable/disable previous and next buttons
                    $('#prevPageLi').toggleClass('disabled', currentPage === 1);
                    $('#nextPageLi').toggleClass('disabled', currentPage === totalPages);
                    
                    // Show pagination if there are multiple pages
                    $('#paginationNav').toggle(totalPages > 1);
                }
                
                // Load promotions on page load
                loadPromotions();
                
                // Pagination event handlers
                $(document).on('click', '#prevPage', function(e) {
                    e.preventDefault();
                    if (currentPage > 1) {
                        loadPromotions(currentPage - 1);
                    }
                });
                
                $(document).on('click', '#nextPage', function(e) {
                    e.preventDefault();
                    if (currentPage < totalPages) {
                        loadPromotions(currentPage + 1);
                    }
                });
                
                $(document).on('click', '.page-number', function(e) {
                    e.preventDefault();
                    const page = parseInt($(this).data('page'));
                    loadPromotions(page);
                });
                
                // Delete promotion handler
                $(document).on('click', '.delete-promotion', function(e) {
                    e.preventDefault();
                    const promotionId = $(this).data('id');
                    const promotionName = $(this).closest('tr').find('td:first').text();
                    
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this! " + promotionName,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.post("../api/promotion/delete", { id: promotionId }, function (response) {
                                if (response || response.message === "Promotion deleted successfully") { 
                                    showToast("Success", response.message || "Promotion deleted successfully", 'success'); 
                                    loadPromotions(); // Reload the page data
                                } else { 
                                    showToast("Error", response.message || "Delete failed", 'error'); 
                                }
                            }, "json");
                        }
                    });
                });
            });
        </script>
    </body>
</html>