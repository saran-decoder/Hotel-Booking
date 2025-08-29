// Custom debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

$(document).ready(function () {
    let allBookings = [];
    let currentPage = 1;
    const itemsPerPage = 10;
    let filteredBookings = [];

    function getStatusBadgeClass(status) {
        status = status.toLowerCase();
        switch(status) {
            case 'confirmed':
                return 'badge-confirmed';
            case 'pending':
                return 'badge-pending';
            case 'cancelled':
                return 'badge-cancelled';
            default:
                return 'badge-secondary';
        }
    }

    function populateHotelFilter(bookings) {
        const hotels = [...new Set(bookings.map(b => b.hotel_name))];
        const hotelFilter = $('.hotel-filter');
        
        // Clear existing options except the first one
        hotelFilter.find('option:not(:first)').remove();
        
        // Add hotel options
        hotels.forEach(hotel => {
            hotelFilter.append(`<option value="${hotel}">${hotel}</option>`);
        });
    }

    function filterBookings() {
        const hotelFilter = $('.hotel-filter').val();
        const statusFilter = $('.status-filter').val();
        const searchTerm = $('.search-input').val().toLowerCase();

        filteredBookings = allBookings.filter(booking => {
            const matchesHotel = !hotelFilter || booking.hotel_name === hotelFilter;
            const matchesStatus = !statusFilter || booking.booking_status.toLowerCase() === statusFilter;
            const matchesSearch = !searchTerm || 
                booking.booking_ref.toLowerCase().includes(searchTerm) ||
                booking.guest_name.toLowerCase().includes(searchTerm) ||
                booking.hotel_name.toLowerCase().includes(searchTerm) ||
                booking.room_type.toLowerCase().includes(searchTerm);

            return matchesHotel && matchesStatus && matchesSearch;
        });

        currentPage = 1;
        renderBookings();
        updatePagination();
    }

    function renderBookings() {
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const currentBookings = filteredBookings.slice(startIndex, endIndex);

        if (currentBookings.length > 0) {
            let rows = "";
            currentBookings.forEach((b) => {
                const badgeClass = getStatusBadgeClass(b.booking_status);
                rows += `
                    <tr>
                        <td>${b.booking_ref}</td>
                        <td>${b.guest_name}</td>
                        <td>${b.hotel_name}</td>
                        <td>${b.check_in_date}</td>
                        <td>${b.check_out_date}</td>
                        <td class="text-capitalize">${b.room_type}</td>
                        <td><span class="badge ${badgeClass} px-3 py-1 rounded-pill">
                            ${b.booking_status}
                        </span></td>
                        <td>₹${parseFloat(b.total_price).toFixed(2)}</td>
                    </tr>`;
            });
            $("#bookingTableBody").html(rows);
        } else {
            $("#bookingTableBody").html(
                `<tr><td colspan="8" class="text-center">No bookings found</td></tr>`
            );
        }

        // Update showing text
        const total = filteredBookings.length;
        const showingStart = total > 0 ? startIndex + 1 : 0;
        const showingEnd = Math.min(endIndex, total);
        $("#showingText").text(`Showing ${showingStart}-${showingEnd} of ${total} bookings`);
    }

    function updatePagination() {
        const totalPages = Math.ceil(filteredBookings.length / itemsPerPage);
        const pagination = $("#pagination");
        pagination.empty();

        if (totalPages <= 1) return;

        // Previous button
        pagination.append(`
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a>
            </li>
        `);

        // Page numbers - show limited pages with ellipsis
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

    function loadBookings() {
        $.get("../api/booking/booking-list", function (response) {
            let bookings = [];
            
            // Handle different response formats
            if (Array.isArray(response)) {
                bookings = response;
            } else if (response && response.success && Array.isArray(response.data)) {
                bookings = response.data;
            }

            allBookings = bookings;
            filteredBookings = [...allBookings];
            
            populateHotelFilter(bookings);
            renderBookings();
            updatePagination();

        }, "json").fail(function(xhr, status, error) {
            console.error("Error loading bookings:", error);
            $("#bookingTableBody").html(
                `<tr><td colspan="8" class="text-center">Error loading bookings. Please try again.</td></tr>`
            );
        });
    }

    // Create debounced filter function
    const debouncedFilter = debounce(filterBookings, 300);

    // Event listeners
    $('.hotel-filter, .status-filter').on('change', filterBookings);
    $('.search-input').on('input', debouncedFilter);

    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        const page = parseInt($(this).data('page'));
        if (!isNaN(page) && page >= 1 && page <= Math.ceil(filteredBookings.length / itemsPerPage)) {
            currentPage = page;
            renderBookings();
            updatePagination();
            
            // Scroll to top of table
            $('html, body').animate({
                scrollTop: $(".table-responsive").offset().top - 100
            }, 300);
        }
    });

    // Initial load
    loadBookings();
});