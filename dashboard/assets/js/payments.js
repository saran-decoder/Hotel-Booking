$(document).ready(function () {
    let allPayments = [];
    let currentPage = 1;
    const itemsPerPage = 10;
    let filteredPayments = [];

    function getStatusBadgeClass(status) {
        if (!status) return 'badge-secondary';
        
        status = status.toLowerCase();
        switch(status) {
            case 'completed':
                return 'badge-confirmed';
            case 'pending':
                return 'badge-pending';
            case 'refunded':
                return 'badge-refunded';
            case 'failed':
                return 'badge-cancelled';
            default:
                return 'badge-secondary';
        }
    }

    function formatPaymentMethod(method) {
        if (!method) return 'Razorpay';
        return method.split('_').map(word => 
            word.charAt(0).toUpperCase() + word.slice(1)
        ).join(' ');
    }

    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        try {
            const date = new Date(dateString);
            return date.toLocaleDateString();
        } catch (e) {
            return dateString;
        }
    }

    function isSameDay(date1, date2) {
        return date1.getFullYear() === date2.getFullYear() &&
            date1.getMonth() === date2.getMonth() &&
            date1.getDate() === date2.getDate();
    }

    function isThisWeek(date) {
        const now = new Date();
        const startOfWeek = new Date(now);
        startOfWeek.setDate(now.getDate() - now.getDay()); // Sunday
        startOfWeek.setHours(0, 0, 0, 0);
        
        const endOfWeek = new Date(now);
        endOfWeek.setDate(now.getDate() + (6 - now.getDay())); // Saturday
        endOfWeek.setHours(23, 59, 59, 999);
        
        return date >= startOfWeek && date <= endOfWeek;
    }

    function filterPayments() {
        const statusFilter = $('.status-filter').val();
        const dateFilter = parseInt($('.date-filter').val());
        const now = new Date();

        filteredPayments = allPayments.filter(payment => {
            const matchesStatus = !statusFilter || payment.status === statusFilter;
            
            // Skip date filtering if no filter is selected
            if (!dateFilter) {
                return matchesStatus;
            }

            const paymentDate = new Date(payment.payment_date);
            
            // Date filtering logic
            switch(dateFilter) {
                case 1: // Today
                    return matchesStatus && isSameDay(paymentDate, now);
                
                case 7: // This week
                    return matchesStatus && isThisWeek(paymentDate);
                
                case 30: // Last 30 days
                    const thirtyDaysAgo = new Date();
                    thirtyDaysAgo.setDate(now.getDate() - 30);
                    thirtyDaysAgo.setHours(0, 0, 0, 0);
                    return matchesStatus && paymentDate >= thirtyDaysAgo;
                
                case 365: // This year
                    const startOfYear = new Date(now.getFullYear(), 0, 1);
                    return matchesStatus && paymentDate >= startOfYear;
                
                default:
                    return matchesStatus;
            }
        });

        currentPage = 1;
        renderPayments();
        updatePagination();
    }

    function renderPayments() {
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const currentPayments = filteredPayments.slice(startIndex, endIndex);

        if (currentPayments.length > 0) {
            let rows = "";
            currentPayments.forEach((p) => {
                const badgeClass = getStatusBadgeClass(p.status);
                
                rows += `
                    <tr>
                        <td>${p.order_id || 'N/A'}</td>
                        <td>${p.booking_ref || 'N/A'}</td>
                        <td>${p.customer_email || 'N/A'}</td>
                        <td>${p.hotel_name || 'N/A'}</td>
                        <td>₹${parseFloat(p.amount || 0).toFixed(2)}</td>
                        <td>${formatDate(p.payment_date)}</td>
                        <td>${formatPaymentMethod(p.payment_method)}</td>
                        <td><span class="badge ${badgeClass} px-3 py-1 rounded-pill">
                            ${p.status || 'Unknown'}
                        </span></td>
                    </tr>`;
            });
            $("#paymentTableBody").html(rows);
        } else {
            $("#paymentTableBody").html(
                `<tr><td colspan="8" class="text-center">No payments found</td></tr>`
            );
        }

        // Update showing text
        const total = filteredPayments.length;
        const showingStart = total > 0 ? startIndex + 1 : 0;
        const showingEnd = Math.min(endIndex, total);
        $("#showingText").text(`Showing ${showingStart}-${showingEnd} of ${total} payments`);
    }

    function updatePagination() {
        const totalPages = Math.ceil(filteredPayments.length / itemsPerPage);
        const pagination = $("#pagination");
        pagination.empty();

        if (totalPages <= 1) return;

        // Previous button
        pagination.append(`
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a>
            </li>
        `);

        // Page numbers
        const maxVisiblePages = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
        
        if (endPage - startPage + 1 < maxVisiblePages) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }

        // First page and ellipsis
        if (startPage > 1) {
            pagination.append(`
                <li class="page-item">
                    <a class="page-link" href="#" data-page="1">1</a>
                </li>
            `);
            if (startPage > 2) {
                pagination.append('<li class="page-item disabled"><span class="page-link">...</span></li>');
            }
        }

        // Page numbers
        for (let i = startPage; i <= endPage; i++) {
            pagination.append(`
                <li class="page-item ${currentPage === i ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `);
        }

        // Last page and ellipsis
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                pagination.append('<li class="page-item disabled"><span class="page-link">...</span></li>');
            }
            pagination.append(`
                <li class="page-item">
                    <a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a>
                </li>
            `);
        }

        // Next button
        pagination.append(`
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage + 1}">Next</a>
            </li>
        `);
    }

    function updateStats(stats) {
        $('#totalRevenue').text('₹' + parseFloat(stats.total_revenue || 0).toFixed(2));
        $('#pendingPayments').text('₹' + parseFloat(stats.pending_payments || 0).toFixed(2));
        $('#refundsCount').text(stats.refunds_count || 0);
    }

    function loadPayments() {
        $.get("../api/booking/payment-list", function (response) {
            console.log("API Response:", response); // Debug log
            
            if (response.success && response.data) {
                allPayments = response.data;
                filteredPayments = [...allPayments];
                
                updateStats(response.stats || {});
                renderPayments();
                updatePagination();
            } else {
                $("#paymentTableBody").html(
                    `<tr><td colspan="8" class="text-center">${response.message || 'No payments found'}</td></tr>`
                );
                updateStats(response.stats || {});
            }
        }, "json").fail(function(xhr, status, error) {
            console.error("Error loading payments:", error, xhr.responseText);
            $("#paymentTableBody").html(
                `<tr><td colspan="8" class="text-center">Error loading payments. Please try again.</td></tr>`
            );
        });
    }

    // Event listeners
    $('.status-filter, .date-filter').on('change', filterPayments);

    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        const page = parseInt($(this).data('page'));
        const totalPages = Math.ceil(filteredPayments.length / itemsPerPage);
        
        if (!isNaN(page) && page >= 1 && page <= totalPages) {
            currentPage = page;
            renderPayments();
            updatePagination();
            
            // Scroll to top of table
            $('html, body').animate({
                scrollTop: $(".table-responsive").offset().top - 100
            }, 300);
        }
    });

    // Manual refresh button (optional - add this if you want refresh functionality)
    function addRefreshButton() {
        const refreshBtn = $('<button>', {
            class: 'btn btn-outline-primary btn-sm ms-2',
            html: '<i class="fas fa-sync-alt"></i> Refresh',
            click: function() {
                $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Refreshing...');
                loadPayments();
                setTimeout(() => {
                    $(this).prop('disabled', false).html('<i class="fas fa-sync-alt"></i> Refresh');
                }, 1000);
            }
        });
        
        $('.bookings-title').after(refreshBtn);
    }

    // Initial load
    loadPayments();
    
    // Add refresh button (optional)
    // addRefreshButton();
});