$(document).ready(function () {
    // ---- Variables ----
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    let currentDate = new Date();
    currentDate.setDate(1);

    let checkInDate = null;
    let checkOutDate = null;

    let adultsCount = 1;
    let childrenCount = 0;

    let destination = "";
    let selectedHotel = null;
    let selectedRoom = null;
    let nights = 0;
    let hotels = [];
    let rooms = [];
    let bookingReference = null;

    // =============== UTIL HELPERS ===============
    function ymd(date) {
        if (!(date instanceof Date)) {
            date = new Date(date);
        }
        return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
    }

    function dmyText(date) {
        return date.toLocaleString("default", { month: "short", day: "numeric", year: "numeric" });
    }

    function safeNumber(v, fallback = 0) {
        const n = parseFloat(v);
        return Number.isFinite(n) ? n : fallback;
    }

    // =============== PAYMENT GATEWAY FUNCTIONS ===============
    function calculateTotalAmount() {
        if (!selectedRoom || !checkInDate || !checkOutDate) return 0;
        
        const timeDiff = checkOutDate.getTime() - checkInDate.getTime();
        const numberOfNights = Math.ceil(timeDiff / (1000 * 3600 * 24));
        
        if (numberOfNights <= 0) return 0;
        
        const roomPrice = selectedRoom.price || 0;
        const totalAmount = (roomPrice * numberOfNights) + 99; // Adding ₹99 as taxes/fees
        
        return totalAmount;
    }

    function submitBooking(callback) {
        if (!selectedHotel || !selectedRoom || !checkInDate || !checkOutDate) {
            showToast('Error', 'Missing booking information', 'error');
            callback(null);
            return;
        }

        const formData = {
            hotel_id: selectedHotel.id,
            room_id: selectedRoom.id,
            check_in: ymd(checkInDate),
            check_out: ymd(checkOutDate),
            adults: adultsCount,
            children: childrenCount,
            total_price: calculateTotalAmount()
        };

        $.ajax({
            url: 'public/../api/booking/add',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response && response.success) {
                    bookingReference = response.booking_id || response.booking_ref;
                    callback(bookingReference);
                } else {
                    showToast('Error', response.message || 'Booking failed', 'error');
                    callback(null);
                }
            },
            error: function (xhr, status, error) {
                console.error('Booking submission error:', error);
                showToast('Error', 'Network error. Please try again.', 'error');
                callback(null);
            }
        });
    }

    function initializeRazorpayPayment(amount, bookingRef) {
        if (amount < 1) {
            showToast('Error', 'Invalid payment amount', 'error');
            return;
        }
        
        $.ajax({
            url: "public/../api/booking/razorpay",
            method: "POST",
            data: { 
                amount: amount,
                currency: "INR",
                booking_id: bookingRef
            },
            success: function(response) {
                if(response.status === "success") {
                    var options = {
                        "key": response.key,
                        "amount": response.amount,
                        "currency": response.currency,
                        "name": "TNBooking.in",
                        "description": "Booking Payment for " + bookingRef,
                        "order_id": response.order_id,
                        "handler": function (paymentResponse){
                            verifyPayment(paymentResponse, amount, bookingRef);
                        },
                        "prefill": {
                            "name": "Guest",
                            "email": "guest@example.com"
                        },
                        "theme": { "color": "#3399cc" },
                        "modal": {
                            "ondismiss": function() {
                                showToast('Info', 'Payment cancelled', 'info');
                            }
                        }
                    };
                    
                    var rzp = new Razorpay(options);
                    rzp.open();
                } else {
                    showToast('Error', response.message || 'Payment initialization failed', 'error');
                }
            },
            error: function(xhr, status, error) {
                showToast('Error', 'Payment initialization failed. Please try again.', 'error');
                console.error('Razorpay init error:', error);
            }
        });
    }

    function verifyPayment(paymentResponse, amount, bookingRef) {
        $.post("public/../api/booking/verify", {
            razorpay_payment_id: paymentResponse.razorpay_payment_id,
            razorpay_order_id: paymentResponse.razorpay_order_id,
            razorpay_signature: paymentResponse.razorpay_signature,
            amount: amount,
            booking_id: bookingRef
        }, function(response) {
            if (response.status === 'success') {
                showToast('Success', 'Payment successful! Booking confirmed.', 'success');
                $('#confirmationBookingRef').text(bookingRef);
                $('#paymentStatus').html('<span class="badge bg-success">Paid</span>');
                updateConfirmationDetails();
                showStep(4);
            } else {
                showToast('Error', 'Payment verification failed: ' + (response.message || 'Unknown error'), 'error');
            }
        }).fail(function(xhr, status, error) {
            console.error('Verification error:', error);
            showToast('Error', 'Payment verification failed. Please contact support.', 'error');
        });
    }

    // =============== INITIAL SETUP ===============
    function initializeDateInputs() {
        const today = new Date().toISOString().split('T')[0];
        $('#checkInInput').attr('min', today);
        $('#checkOutInput').attr('min', today);

        $('#checkInInput').on('change', function() {
            $('#checkOutInput').attr('min', $(this).val());
        });
    }

    // =============== STEP NAVIGATION ===============
    function updateStepProgress(activeStep) {
        // Update wizard steps
        $('.step-wizard-item').each(function (index) {
            const stepNumber = index + 1;
            $(this).removeClass('active completed');
            
            if (stepNumber < activeStep) {
                $(this).addClass('completed');
            } else if (stepNumber === activeStep) {
                $(this).addClass('active');
            }
        });
        
        // Update progress bar fill
        const progressPercentage = ((activeStep - 1) / 3) * 100;
        $('.progress-bar-fill').css('width', progressPercentage + '%');
    }

    function showStep(n) {
        $('.step-content').removeClass('active').hide();
        $(`#step${n}`).addClass('active').show();
        updateStepProgress(n);

        // Show progress bar for steps 2-4
        if (n > 1) {
            $('#progressStepBar').show();
        } else {
            $('#progressStepBar').hide();
        }
    }

    // =============== TYPEAHEAD SEARCH ===============
    function setupTypeaheadSearch() {
        const $input = $('#destinationInput');
        const $results = $('#destinationResults');
        let allLocations = [];

        // Get all unique locations from hotels
        function updateLocationsList() {
            allLocations = [...new Set(hotels.map(h => h.location).filter(Boolean))];
        }

        // Filter locations based on input
        function filterLocations(query) {
            if (!query) return [];
            return allLocations.filter(location => 
                location.toLowerCase().includes(query.toLowerCase())
            );
        }

        // Show results
        function showResults(results) {
            $results.empty();
            
            if (results.length === 0) {
                $results.hide();
                return;
            }

            results.forEach(location => {
                $results.append(`
                    <div class="typeahead-result" data-location="${location}">
                        ${location}
                    </div>
                `);
            });

            $results.show();
        }

        // Handle input events
        $input.on('input', function() {
            const query = $(this).val().trim();
            const filtered = filterLocations(query);
            showResults(filtered);
        });

        // Handle result selection
        $results.on('click', '.typeahead-result', function() {
            const location = $(this).data('location');
            $input.val(location);
            $results.hide();
            destination = location;
            updateContinueButton();
        });

        // Hide results when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.typeahead-container').length) {
                $results.hide();
            }
        });

        // Handle keyboard navigation
        $input.on('keydown', function(e) {
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                $results.find('.typeahead-result').first().focus();
            }
        });

        $results.on('keydown', '.typeahead-result', function(e) {
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                $(this).next().focus();
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                const $prev = $(this).prev();
                if ($prev.length) {
                    $prev.focus();
                } else {
                    $input.focus();
                }
            } else if (e.key === 'Enter') {
                e.preventDefault();
                $(this).click();
            }
        });

        // Update locations when hotels are loaded
        $(document).on('hotelsLoaded', function() {
            updateLocationsList();
        });
    }

    // =============== DATE PICKER ===============
    function setupDatePickers() {
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);

        // Initialize check-in datepicker
        const checkInPicker = flatpickr('#checkInInput', {
            minDate: 'today',
            dateFormat: 'Y-m-d',
            onChange: function(selectedDates, dateStr) {
                if (selectedDates.length > 0) {
                    checkInDate = selectedDates[0];
                    
                    // Update check-out min date
                    if (checkOutPicker) {
                        const minCheckOut = new Date(selectedDates[0]);
                        minCheckOut.setDate(minCheckOut.getDate() + 1);
                        checkOutPicker.set('minDate', minCheckOut);
                        
                        // If check-out date is before new min date, clear it
                        if (checkOutDate && checkOutDate <= selectedDates[0]) {
                            checkOutPicker.clear();
                            checkOutDate = null;
                        }
                    }
                    
                    updateContinueButton();
                }
            }
        });

        // Initialize check-out datepicker
        const checkOutPicker = flatpickr('#checkOutInput', {
            minDate: tomorrow,
            dateFormat: 'Y-m-d',
            onChange: function(selectedDates, dateStr) {
                if (selectedDates.length > 0) {
                    checkOutDate = selectedDates[0];
                    updateContinueButton();
                }
            }
        });

        return { checkInPicker, checkOutPicker };
    }

    // =============== FORM VALIDATION ===============
    function validateSearchForm() {
        const destination = $('#destinationInput').val();
        const checkIn = $('#checkInInput').val();
        const checkOut = $('#checkOutInput').val();

        if (!destination || !checkIn || !checkOut) {
            showToast('Error', 'Please fill in all required fields', 'error');
            return false;
        }

        if (new Date(checkOut) <= new Date(checkIn)) {
            showToast('Error', 'Check-out date must be after check-in date', 'error');
            return false;
        }

        return true;
    }

    // =============== UPDATE CONTINUE BUTTON ===============
    function updateContinueButton() {
        const destination = $('#destinationInput').val();
        const checkIn = $('#checkInInput').val();
        const checkOut = $('#checkOutInput').val();

        if (destination && checkIn && checkOut && new Date(checkOut) > new Date(checkIn)) {
            $('#searchHotelsBtn').removeClass("btn-disabled").addClass("btn-primary").prop('disabled', false);
        } else {
            $('#searchHotelsBtn').addClass("btn-disabled").removeClass("btn-primary").prop('disabled', true);
        }
    }

    // =============== API: HOTELS ===============
    function fetchHotels() {
        $.ajax({
            url: 'public/../api/hotel/total-hotels',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (Array.isArray(response) && response.length > 0) {
                    hotels = processHotelData(response);
                    populateDestinationSelect(hotels);
                } else {
                    showNoHotelsMessage();
                }
            },
            error: function () {
                showErrorMessage();
            }
        });
    }

    function processHotelData(apiData) {
        return apiData.map(function (h) {
            let amenities = [];
            try {
                if (typeof h.hotel_amenities === 'string') {
                    amenities = JSON.parse(h.hotel_amenities);
                } else if (Array.isArray(h.hotel_amenities)) {
                    amenities = h.hotel_amenities;
                } else {
                    amenities = h.hotel_amenities ? String(h.hotel_amenities).split(',') : [];
                }
            } catch {
                amenities = h.hotel_amenities ? String(h.hotel_amenities).split(',') : [];
            }

            let imgs = [];
            try {
                if (h.hotel_images) {
                    imgs = typeof h.hotel_images === 'string' ? JSON.parse(h.hotel_images) : h.hotel_images;
                    imgs = Array.isArray(imgs) && imgs.length
                        ? imgs.map(img => (String(img).startsWith('http') ? img : 'public/../' + img))
                        : ['https://via.placeholder.com/500x300?text=No+Image'];
                } else {
                    imgs = ['https://via.placeholder.com/500x300?text=No+Image'];
                }
            } catch {
                imgs = ['https://via.placeholder.com/500x300?text=No+Image'];
            }

            const priceGuess = safeNumber(h.starting_price ?? h.price_per_night ?? h.min_price, 0);
            const id = h.hotel_id ?? h.id;

            return {
                id,
                hotel_id: id,
                name: h.hotel_name,
                rating: 4,
                reviews: Math.floor(Math.random() * 100) + 50,
                location: h.hotel_location_name,
                images: imgs,
                amenities: amenities,
                description: h.hotel_description,
                address: h.hotel_address,
                coordinates: h.hotel_coordinates,
                price: priceGuess || 1000
            };
        });
    }

    // =============== DESTINATION SELECT ===============
    function populateDestinationSelect(hotels) {
        $('#destinationSelect').empty().append('<option value="" selected disabled>Select destination</option>');
        const uniqueLocations = [...new Set(hotels.map(h => h.location).filter(Boolean))];
        uniqueLocations.forEach(loc => {
            $('#destinationSelect').append(`<option value="${loc}">${loc}</option>`);
        });
    }

    // =============== MESSAGES ===============
    function showNoHotelsMessage() {
        $('#hotelList').html('<div class="col-12 text-center py-5"><h5>No hotels available at the moment</h5><p>Please try again later</p></div>');
    }

    function showErrorMessage() {
        $('#hotelList').html('<div class="col-12 text-center py-5"><h5>Error loading hotels</h5><p>Please refresh the page</p></div>');
    }

    function showNoRoomsMessage() {
        $('#roomSelectionContainer').html('<div class="col-12 text-center py-5"><h5>No rooms available for this hotel</h5><p>Please select another hotel</p></div>');
    }

    function showRoomErrorMessage() {
        $('#roomSelectionContainer').html('<div class="col-12 text-center py-5"><h5>Error loading rooms</h5><p>Please try again</p></div>');
    }

    // =============== GUEST SELECTOR ===============
    function setupGuestSelector() {
        $('#guestSelector').on('click', function (e) {
            e.stopPropagation();
            $('#guestDropdown').toggle();
        });

        $('#decreaseAdults').on('click', function (e) {
            e.stopPropagation();
            if (adultsCount > 1) {
                adultsCount--;
                $('#adultCount').text(adultsCount);
                updateGuestDisplay();
            }
        });

        $('#increaseAdults').on('click', function (e) {
            e.stopPropagation();
            adultsCount++;
            $('#adultCount').text(adultsCount);
            updateGuestDisplay();
        });

        $('#decreaseChildren').on('click', function (e) {
            e.stopPropagation();
            if (childrenCount > 0) {
                childrenCount--;
                $('#childCount').text(childrenCount);
                updateGuestDisplay();
            }
        });

        $('#increaseChildren').on('click', function (e) {
            e.stopPropagation();
            childrenCount++;
            $('#childCount').text(childrenCount);
            updateGuestDisplay();
        });

        $('#applyGuests').on('click', function (e) {
            e.stopPropagation();
            updateGuestDisplay();
            $('#guestDropdown').hide();
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest('#guestSelector, #guestDropdown').length) {
                $('#guestDropdown').hide();
            }
        });
    }

    function updateGuestDisplay() {
        let txt = `${adultsCount} Adult${adultsCount !== 1 ? "s" : ""}`;
        if (childrenCount > 0) txt += `, ${childrenCount} Child${childrenCount !== 1 ? "ren" : ""}`;
        $('#guestDisplay').text(txt);
    }

    // =============== FILTERS & RENDER HOTELS ===============
    function filterHotels() {
        const maxPrice = parseInt($('#priceRange').val(), 10);
        const stars = $('.star-rating-checkbox:checked').map(function () {
            return parseInt(this.id.replace('rating', ''), 10);
        }).get();

        const amen = $('.amenities-checkbox:checked').map(function () {
            return this.id;
        }).get();

        return hotels.filter(function (h) {
            if (destination && h.location !== destination) return false;
            if (Number.isFinite(maxPrice) && Number.isFinite(h.price) && h.price > maxPrice) return false;
            if (stars.length && !stars.includes(h.rating)) return false;
            if (amen.length && !amen.every(a => (h.amenities || []).includes(a))) return false;
            return true;
        });
    }

    function renderHotels() {
        const filtered = filterHotels();
        $('#hotelList').empty();
        $('#hotelMap').empty();

        if (filtered.length === 0) {
            $('#hotelList').html('<div class="col-12 text-center py-5"><h5>No hotels match your filters</h5><p>Try adjusting your filters</p></div>');
            return;
        }

        filtered.forEach(function (hotel) {
            const isSelected = selectedHotel && selectedHotel.id === hotel.id;

            let stars = '';
            for (let i = 0; i < 5; i++) {
                stars += i < hotel.rating ? '<i class="fas fa-star text-warning"></i>' : '<i class="far fa-star text-warning"></i>';
            }

            const amenitiesHTML = (hotel.amenities || [])
                .map(a => `<span class="amenity-badge">${a}</span>`)
                .join('');

            const carouselIndicators = hotel.images.map((_, idx) =>
                `<button type="button" data-bs-target="#hotelCarousel-${hotel.id}" data-bs-slide-to="${idx}" class="${idx === 0 ? 'active' : ''}" aria-label="Slide ${idx + 1}"></button>`
            ).join('');

            const carouselItems = hotel.images.map((img, idx) =>
                `<div class="carousel-item ${idx === 0 ? 'active' : ''}">
                    <img src="${img}" class="d-block w-100 hotel-img" alt="${hotel.name} - Image ${idx + 1}">
                </div>`
            ).join('');

            const hotelCard = $(`
                <div class="card hotel-card mb-4" data-hotel-id="${hotel.id}" ${isSelected ? 'style="border: 2px solid #0d6efd;"' : ''}>
                    <div id="hotelCarousel-${hotel.id}" class="carousel slide m-0" data-bs-ride="carousel">
                        <div class="carousel-indicators">${carouselIndicators}</div>
                        <div class="carousel-inner">${carouselItems}</div>
                    </div>
                    <div class="card-body pb-0">
                        <h5 class="card-title">${hotel.name}</h5>
                        <div class="mb-2 d-none">
                            <span class="text-warning">${stars}</span>
                            <span class="text-muted ms-2">${hotel.reviews} reviews</span>
                        </div>
                        <p class="card-text text-muted">
                            <i class="fa fa-map-marker-alt me-2"></i>${hotel.location || ''}
                        </p>
                        <div class="hotel-amenities mb-3">${amenitiesHTML}</div>
                        <div class="d-flex justify-content-between align-items-center">
                            ${Number.isFinite(hotel.price) && hotel.price > 0 ?
                                `<div>
                                    <h4 class="text-primary mb-0">₹${hotel.price}<small class="text-muted"> /night</small></h4>
                                </div>` : ''
                            }
                            <button class="btn ${isSelected ? 'btn-primary' : 'btn-outline-primary'} select-hotel-btn" data-hotel-id="${hotel.id}">
                                ${isSelected ? '<i class="fas fa-check me-1"></i> Selected' : 'Select'}
                            </button>
                        </div>
                    </div>
                </div>
            `);
            
            $('#hotelList').append(hotelCard);
        });
    }

    // =============== HOTEL SELECTION ===============
    function setupHotelSelection() {
        $(document).on('click', '.select-hotel-btn', function () {
            const hotelId = $(this).data('hotel-id');
            selectedHotel = hotels.find(h => String(h.id) === String(hotelId));

            if (!selectedHotel) {
                console.error("Hotel not found for ID:", hotelId);
                return;
            }

            $('.hotel-card').css('border', '1px solid #dee2e6')
                .find('.select-hotel-btn')
                .removeClass('btn-primary')
                .addClass('btn-outline-primary')
                .html('Select');

            $(this).closest('.hotel-card').css('border', '2px solid #0d6efd');
            $(this).removeClass('btn-outline-primary').addClass('btn-primary').html('<i class="fas fa-check me-1"></i> Selected');

            $('#continueToRoomsBtn').prop('disabled', false);

            $('#hotelMap').empty();
            if (selectedHotel.coordinates) {
                $('#hotelMap').append(
                    `<a href="${selectedHotel.coordinates}" target="_blank" class="fs-5">View on Map</a>`
                );
            }
        });
    }

    // =============== API: ROOMS ===============
    function fetchRooms(hotelId) {
        $.ajax({
            url: `public/../api/hotel/info?id=${hotelId}`,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (Array.isArray(response) && response.length > 0) {
                    const hotelData = response[0];
                    if (hotelData.rooms && Array.isArray(hotelData.rooms)) {
                        rooms = processRoomData(hotelData.rooms);
                        console.log("Processed Rooms:", rooms);
                        renderRoomSelection();
                    } else {
                        showNoRoomsMessage();
                    }
                } else {
                    showNoRoomsMessage();
                }
            },
            error: function () {
                showRoomErrorMessage();
            }
        });
    }

    function processRoomData(apiData) {
        return apiData.map(function (r) {
            let amenities = [];
            try {
                if (typeof r.room_amenities === 'string') {
                    amenities = JSON.parse(r.room_amenities);
                } else if (Array.isArray(r.room_amenities)) {
                    amenities = r.room_amenities;
                } else {
                    amenities = r.room_amenities ? String(r.room_amenities).split(',') : [];
                }
            } catch {
                amenities = r.room_amenities ? String(r.room_amenities).split(',') : [];
            }

            let roomImages = [];
            try {
                if (r.room_images) {
                    roomImages = typeof r.room_images === 'string' ? JSON.parse(r.room_images) : r.room_images;
                    roomImages = Array.isArray(roomImages) && roomImages.length ? roomImages : [r.image || 'uploads/rooms/placeholder.jpg'];
                } else {
                    roomImages = [r.image || 'uploads/rooms/placeholder.jpg'];
                }
            } catch {
                roomImages = [r.image || 'uploads/rooms/placeholder.jpg'];
            }

            const id = r.room_id ?? r.id;

            return {
                id,
                hotelId: r.hotel_id,
                type: r.room_type,
                name: (r.room_type ? r.room_type.charAt(0).toUpperCase() + r.room_type.slice(1) : 'Room'),
                price: safeNumber(r.price_per_night, 1000),
                description: r.room_description || '',
                amenities,
                room_images: roomImages,
                guestsAllowed: r.guests_allowed || 2
            };
        });
    }

    // =============== ROOM SELECTION ===============
    function renderRoomSelection() {
        
        if (!selectedHotel) return;
        
        if (!checkInDate || !checkOutDate) {
            $('#roomSelectionContainer').html('<div class="col-12 text-center py-5"><h5>Select your dates first</h5></div>');
            return;
        }
        
        $('#roomSelectionContainer').empty();
        if (!rooms.length) {
            showNoRoomsMessage();
            return;
        }
        
        $('#roomSelectionContainer').html('<div class="col-12 text-center py-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Checking availability...</p></div>');
        // $('#roomSelectionContainer').html(
        //     '<div class="col-12 text-center py-3"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Checking room availability...</p></div>'
        // );

        const promises = rooms.map(room =>
            checkRoomAvailability(room.id, checkInDate, checkOutDate)
                .then(isAvailable => ({ room, isAvailable }))
                .catch(() => ({ room, isAvailable: true, checkFailed: true }))
        );

        Promise.all(promises).then(function (results) {
            $('#roomSelectionContainer').empty();
            results.forEach(obj => renderRoomCard(obj.room, obj.isAvailable, obj.checkFailed));
        });
    }

    // Replace the checkRoomAvailability function with this version
    function checkRoomAvailability(roomId, checkIn, checkOut) {
        const checkInFormatted = ymd(checkIn);
        const checkOutFormatted = ymd(checkOut);
        
        return $.ajax({
            url: 'public/../api/booking/check_availability',
            type: 'POST',
            data: {
                room_id: roomId,
                check_in: checkInFormatted,
                check_out: checkOutFormatted
            },
            dataType: 'json'
        }).then(function (res) {
            console.log("Availability API response:", res);
            
            // Extract the check_out date from the first booking detail if available
            let bookedUntil = null;
            if (!res.available && res.details && res.details.length > 0) {
                // Try to parse the check_out date from the details
                const checkOutStr = res.details[0].check_out;
                if (checkOutStr) {
                    // Parse date string like "Sep 24, 2025"
                    const dateParts = checkOutStr.split(' ');
                    if (dateParts.length === 3) {
                        const monthNames = {
                            'Jan': 0, 'Feb': 1, 'Mar': 2, 'Apr': 3, 'May': 4, 'Jun': 5,
                            'Jul': 6, 'Aug': 7, 'Sep': 8, 'Oct': 9, 'Nov': 10, 'Dec': 11
                        };
                        const month = monthNames[dateParts[0]];
                        const day = parseInt(dateParts[1].replace(',', ''));
                        const year = parseInt(dateParts[2]);
                        
                        if (!isNaN(month) && !isNaN(day) && !isNaN(year)) {
                            bookedUntil = new Date(year, month, day);
                        }
                    }
                }
            }
            
            return {
                available: res.available,
                booked_until: bookedUntil,
                response: res // Include the full response for additional details
            };
        }).catch(function (error) {
            console.error("Availability check error:", error);
            return { 
                available: true, 
                booked_until: null,
                response: null
            };
        });
    }

    // Update the room rendering function to use the API response details
    function renderRoomCard(room, availabilityResult, checkFailed) {
        const imgs = Array.isArray(room.room_images) ? room.room_images : [];
        const carouselIndicators = imgs.map((_, idx) =>
            `<button type="button" data-bs-target="#roomCarousel-${room.id}" data-bs-slide-to="${idx}" class="${idx === 0 ? 'active' : ''}" aria-label="Slide ${idx + 1}"></button>`
        ).join('');

        const carouselItems = imgs.map((image, idx) => {
            const url = image.startsWith('http') ? image : `public/../${image}`;
            return `<div class="carousel-item ${idx === 0 ? 'active' : ''}">
                        <img src="${url}" class="d-block w-100" alt="Room Image ${idx + 1}">
                    </div>`;
        }).join('');

        const amenitiesHTML = (room.amenities || [])
            .map(a => `<div class="d-flex align-items-center mb-2"><i class="fas fa-check-circle text-success me-2"></i><span>${a.replace(/_/g, ' ')}</span></div>`)
            .join('');

        const isSelected = selectedRoom && selectedRoom.id === room.id;
        
        // Extract availability info
        const isAvailable = availabilityResult.available;
        
        // Create the message based on API response
        let unavailableMessage = 'Not available for selected dates';
        if (!isAvailable && availabilityResult.response && availabilityResult.response.details && availabilityResult.response.details.length > 0) {
            const detail = availabilityResult.response.details[0];
            unavailableMessage = `Booked until ${detail.check_out}`;
        }

        const card = $(`
            <div class="card room-card mb-4 ${isSelected ? 'border-primary' : ''} ${!isAvailable ? 'opacity-50' : ''}" data-room-id="${room.id}" data-available="${isAvailable}">
                ${!isAvailable ? `<div class="position-absolute top-0 start-0 w-100 bg-warning text-dark p-2 text-center z-1 rounded"><i class="fas fa-exclamation-triangle me-2"></i>${unavailableMessage}</div>` : ''}
                ${checkFailed ? `<div class="position-absolute top-0 start-0 w-100 bg-info text-white p-2 text-center z-1 rounded"><i class="fas fa-info-circle me-2"></i>Availability could not be verified</div>` : ''}
                <div class="row g-0">
                    <div class="col-md-4">
                        <div id="roomCarousel-${room.id}" class="carousel slide room-carousel" data-bs-ride="carousel">
                            <div class="carousel-indicators">${carouselIndicators}</div>
                            <div class="carousel-inner rounded">${carouselItems}</div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="card-body">
                            <h4 class="card-title">${room.name}</h4>
                            <p class="card-text">${room.description || ''}</p>
                            <div class="amenities-container">${amenitiesHTML}</div>
                            <p class="card-text mt-2"><small class="text-muted">Up to ${room.guestsAllowed} guests</small></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card-body h-100 d-flex flex-column justify-content-between">
                            <div class="text-end mb-3">
                                <span class="h4 text-primary">₹${room.price}</span>
                                <span class="text-muted">/night</span>
                            </div>
                            <button class="btn ${isSelected ? 'btn-primary' : 'btn-outline-primary'} ${!isAvailable ? 'disabled' : ''} select-room-btn w-100" data-room-id="${room.id}" ${!isAvailable ? 'disabled' : ''}>
                                ${!isAvailable ? 'Not Available' : (isSelected ? '<i class="fas fa-check me-1"></i> Selected' : 'Select Room')}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `);

        $('#roomSelectionContainer').append(card);

        if (isAvailable) {
            card.find('.select-room-btn').on('click', function () {
                const roomId = $(this).data('room-id');
                selectedRoom = rooms.find(r => r.id === roomId);
                $('.room-card').removeClass('border-primary');
                $('.select-room-btn').removeClass('btn-primary').addClass('btn-outline-primary').html('Select Room');
                $(this).closest('.room-card').addClass('border-primary');
                $(this).removeClass('btn-outline-primary').addClass('btn-primary').html('<i class="fas fa-check me-1"></i> Selected');
                updateBookingSummary();
            });
        }
    }

    // =============== BOOKING SUMMARY ===============
    function calculateNights() {
        if (checkInDate && checkOutDate) {
            return Math.ceil(Math.abs(checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));
        }
        return 0;
    }

    function updateBookingSummary() {
        const n = calculateNights();
        
        // Update nights count
        $('#nightsCount').text(n);
        
        if (checkInDate && checkOutDate) {
            $('#bookingDatesSummary').text(`${dmyText(checkInDate)} - ${dmyText(checkOutDate)} (${n} nights)`);
        }

        $('#bookingGuestsSummary').text(
            `${adultsCount} Adult${adultsCount !== 1 ? 's' : ''}${childrenCount > 0 ? `, ${childrenCount} Child${childrenCount !== 1 ? 'ren' : ''}` : ''}`
        );

        if (selectedRoom) {
            $('#selectedRoomType').text(selectedRoom.name);
            $('#selectedRoomPrice').text(`₹${selectedRoom.price} / night`);
            $('#selectedRoomDisplay').show();
            const subtotal = selectedRoom.price * n;
            $('#roomSubtotal').text(`₹${subtotal}`);
            $('#bookingTotal').text(`₹${subtotal + 99}`);
            $('#continueToConfirmationBtn').prop('disabled', false);
        } else {
            $('#selectedRoomDisplay').hide();
            $('#roomSubtotal').text('₹0');
            $('#bookingTotal').text('₹0');
            $('#continueToConfirmationBtn').prop('disabled', true);
        }
        
        // Update date inputs in summary
        if (checkInDate) {
            $('#summaryCheckIn').val(ymd(checkInDate));
        }
        if (checkOutDate) {
            $('#summaryCheckOut').val(ymd(checkOutDate));
        }
    }

    // =============== HOTEL DETAILS ===============
    function renderHotelDetails() {
        if (!selectedHotel) return;

        $('#hotelNameDisplay').text(selectedHotel.name);
        $('#hotelLocationDisplay').text(selectedHotel.location || '');
        $('#hotelDescriptionDisplay').text(selectedHotel.description || `Experience luxury and comfort at ${selectedHotel.name}.`);

        renderHotelImageSlider();
        renderHotelAmenities();
    }

    function renderHotelImageSlider() {
        if (!selectedHotel || !selectedHotel.images) return;
        const images = selectedHotel.images;
        const indicators = images.map((_, idx) =>
            `<button type="button" data-bs-target="#hotelDetailsCarousel" data-bs-slide-to="${idx}" class="${idx === 0 ? 'active' : ''}" aria-label="Slide ${idx + 1}"></button>`
        ).join('');

        const carouselItems = images.map((img, idx) =>
            `<div class="carousel-item ${idx === 0 ? 'active' : ''}">
                <img src="${img}" class="d-block w-100 hotel-detail-img" alt="${selectedHotel.name} - Image ${idx + 1}">
            </div>`
        ).join('');

        $('#hotelImageSlider').html(`
            <div id="hotelDetailsCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                <div class="carousel-indicators">${indicators}</div>
                <div class="carousel-inner rounded">${carouselItems}</div>
            </div>
        `);
    }

    function renderHotelAmenities() {
        if (!selectedHotel) return;
        $('#hotelAmenitiesDisplay').empty();
        const amenities = selectedHotel.amenities || [];

        if (!amenities.length) {
            $('#hotelAmenitiesDisplay').html('<p>No amenities listed</p>');
            return;
        }

        amenities.forEach(function (a) {
            $('#hotelAmenitiesDisplay').append(`
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="${getAmenityIcon(a)} me-3 text-primary"></i>
                        <span>${a.replace(/_/g, ' ')}</span>
                    </div>
                </div>
            `);
        });
    }

    function getAmenityIcon(amenity) {
        const iconMap = {
            wifi: 'fas fa-wifi',
            parking: 'fas fa-parking',
            pool: 'fas fa-swimming-pool',
            ac: 'fas fa-snowflake',
            restaurant: 'fas fa-utensils',
            fitness: 'fas fa-dumbbell',
            air_conditioning: 'fas fa-snowflake',
            swimming_pool: 'fas fa-swimming-pool',
            fitness_center: 'fas fa-dumbbell',
            bar: 'fas fa-glass-martini-alt'
        };
        return iconMap[amenity] || 'fas fa-check-circle';
    }

    // =============== CONFIRMATION ===============
    function updateConfirmationDetails() {
        const n = calculateNights();
        $('#confirmationHotelName').text(selectedHotel.name);
        $('#confirmationDates').text(`${dmyText(checkInDate)} - ${dmyText(checkOutDate)} (${n} nights)`);
        $('#confirmationRoomType').text(selectedRoom.name);
        $('#confirmationGuests').text(`${adultsCount} Adult${adultsCount !== 1 ? 's' : ''}${childrenCount > 0 ? `, ${childrenCount} Child${childrenCount !== 1 ? 'ren' : ''}` : ''}`);

        const subtotal = selectedRoom.price * n;
        $('#confirmationTotal').text(`₹${subtotal + 99}`);
    }

    // =============== TOASTS ===============
    function showToast(title, message, type) {
        $('.toast-container').remove();
        $('body').append('<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1090;"></div>');

        const toastId = 'toast-' + Date.now();
        const toastHtml = `
            <div id="${toastId}" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header ${type === 'success' ? 'bg-success text-white' : type === 'error' ? 'bg-danger text-white' : type === 'warning' ? 'bg-warning text-dark' : 'bg-info text-white'}">
                    <strong class="me-auto">${title}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">${message}</div>
            </div>
        `;

        $('.toast-container').append(toastHtml);
        const toastElement = $('#' + toastId);
        const toast = new bootstrap.Toast(toastElement, { autohide: true, delay: 5000 });
        toast.show();

        toastElement.on('hidden.bs.toast', function () {
            $(this).remove();
        });
    }

    // =============== EVENT HANDLERS ===============
    function setupEventHandlers() {
        // Search form submission
        $('#homeSearchForm').on('submit', function(e) {
            e.preventDefault();

            if (!validateSearchForm()) return;

            destination = $('#destinationInput').val();
            checkInDate = new Date($('#checkInInput').val());
            checkOutDate = new Date($('#checkOutInput').val());

            // Update displays
            $('#destinationDisplay').text(destination);
            $('#bookingDetailsDisplay').text(
                `${$('#checkInInput').val()} - ${$('#checkOutInput').val()} • ${adultsCount} Adults, ${childrenCount} Children`
            );

            // Show step 2
            showStep(2);

            // Render hotels
            renderHotels();
        });

        // Real-time validation for inputs
        $('#destinationInput, #checkInInput, #checkOutInput').on('change input', function() {
            updateContinueButton();
        });

        // Back to search button
        $('#backToSearchBtn').on('click', function() {
            showStep(1);
        });

        // Continue to rooms button
        $('#continueToRoomsBtn').on('click', function() {
            if (!selectedHotel) {
                showToast('Error', 'Please select a hotel first', 'error');
                return;
            }

            fetchRooms(selectedHotel.id);
            renderHotelDetails();
            showStep(3);
            updateBookingSummary();
        });

        // Back to hotels button
        $('#backToHotelsBtn').on('click', function() {
            showStep(2);
        });

        // Continue to confirmation button - UPDATED FOR PAYMENT
        $('#continueToConfirmationBtn').on('click', function() {
            if (!selectedRoom) {
                showToast('Error', 'Please select a room first', 'error');
                return;
            }

            const totalAmount = calculateTotalAmount();
            
            if (totalAmount > 0) {
                // Submit booking first, then process payment
                submitBooking(function(bookingRef) {
                    if (bookingRef) {
                        initializeRazorpayPayment(totalAmount, bookingRef);
                    } else {
                        showToast('Error', 'Failed to create booking. Please try again.', 'error');
                    }
                });
            } else {
                showToast('Error', "Invalid booking amount", 'error');
            }
        });

        // Back to home button
        $('#backToHomeBtn').on('click', function() {
            checkInDate = null;
            checkOutDate = null;
            adultsCount = 1;
            childrenCount = 0;
            destination = "";
            selectedHotel = null;
            selectedRoom = null;

            $('#homeSearchForm')[0].reset();
            $('#guestDisplay').text('1 Adult, 0 Children');
            $('#adultCount').text('1');
            $('#childCount').text('0');
            $('#selectedRoomDisplay').hide();
            $('#continueToRoomsBtn').prop('disabled', true);
            $('#continueToConfirmationBtn').prop('disabled', true);

            showStep(1);
        });

        // Filters
        $('#priceRange').on('input', function() {
            $('#priceRangeValue').text($(this).val());
            renderHotels();
        });

        $('.star-rating-checkbox, .amenities-checkbox').on('change', function() {
            renderHotels();
        });

        $('#resetFilters').on('click', function() {
            $('.star-rating-checkbox, .amenities-checkbox').prop('checked', false);
            $('#priceRange').val(5000);
            $('#priceRangeValue').text('5000');
            renderHotels();
        });
    }

    // =============== DATE CHANGE FUNCTIONALITY ===============
    function setupDateChange() {
        // Initialize date inputs with current selection
        if (checkInDate) {
            $('#summaryCheckIn').val(ymd(checkInDate));
        }
        if (checkOutDate) {
            $('#summaryCheckOut').val(ymd(checkOutDate));
        }
        
        // Set minimum dates
        const today = new Date().toISOString().split('T')[0];
        $('#summaryCheckIn').attr('min', today);
        $('#summaryCheckOut').attr('min', today);
        
        // Update check-out min date when check-in changes
        $('#summaryCheckIn').on('change', function() {
            const newCheckIn = $(this).val();
            if (newCheckIn) {
                // Set min date for check-out to be the day after check-in
                const nextDay = new Date(newCheckIn);
                nextDay.setDate(nextDay.getDate() + 1);
                $('#summaryCheckOut').attr('min', ymd(nextDay));
                
                // If current check-out is before new check-in, clear it
                if ($('#summaryCheckOut').val() && new Date($('#summaryCheckOut').val()) <= new Date(newCheckIn)) {
                    $('#summaryCheckOut').val('');
                }
            }
        });
        
        // Handle update dates button click
        $('#updateDatesBtn').on('click', function() {
            const newCheckIn = $('#summaryCheckIn').val();
            const newCheckOut = $('#summaryCheckOut').val();
            
            if (!newCheckIn || !newCheckOut) {
                showToast('Error', 'Please select both check-in and check-out dates', 'error');
                return;
            }
            
            const newCheckInDate = new Date(newCheckIn);
            const newCheckOutDate = new Date(newCheckOut);
            
            if (newCheckOutDate <= newCheckInDate) {
                showToast('Error', 'Check-out date must be after check-in date', 'error');
                return;
            }
            
            // Update global dates
            checkInDate = newCheckInDate;
            checkOutDate = newCheckOutDate;
            
            // Show loading state
            const originalText = $('#updateDatesBtn').html();
            $('#updateDatesBtn').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...').prop('disabled', true);
            
            // Update the booking summary immediately
            updateBookingSummary();
            
            // Recheck room availability with new dates
            checkRoomAvailabilityForSelectedRoom().finally(() => {
                // Re-enable button regardless of outcome
                $('#updateDatesBtn').html(originalText).prop('disabled', false);
            });
        });
    }

    function checkRoomAvailabilityForSelectedRoom() {
        return new Promise((resolve) => {
            if (!selectedRoom) {
                // If no room is selected, just refresh all rooms
                renderRoomSelection();
                resolve();
                return;
            }
            
            // First check if the selected room is available for the new dates
            checkRoomAvailability(selectedRoom.id, checkInDate, checkOutDate)
                .then(function(availabilityResult) {
                    if (availabilityResult.available) {
                        // Room is available for new dates
                        showToast('Success', 'Dates updated successfully!', 'success');
                    } else {
                        // Room not available for new dates
                        let message = 'This room is not available for the selected dates.';
                        if (availabilityResult.booked_until) {
                            message = `This room is booked until ${availabilityResult.booked_until.toLocaleDateString()}.`;
                        }
                        
                        showToast('Not Available', message, 'error');
                        
                        // Deselect room if it's no longer available
                        selectedRoom = null;
                        $('.room-card').removeClass('border-primary');
                        $('.select-room-btn').removeClass('btn-primary').addClass('btn-outline-primary').html('Select Room');
                    }
                    
                    // Refresh all rooms with new dates
                    renderRoomSelection();
                    resolve();
                })
                .catch(function(error) {
                    console.error('Availability check error:', error);
                    showToast('Error', 'Could not verify availability. Please try again.', 'error');
                    // Still refresh rooms even if there was an error
                    renderRoomSelection();
                    resolve();
                });
        });
    }

    // =============== INITIALIZATION ===============
    function initialize() {
        initializeDateInputs();
        setupGuestSelector();
        setupTypeaheadSearch();
        setupDatePickers();
        setupEventHandlers();
        setupHotelSelection();
        setupDateChange(); // Add this line
        updateGuestDisplay();
        showStep(1);
        fetchHotels();
        
        // Initialize progress bar
        $('.progress-bar-fill').css('width', '0%');
        
        // Trigger hotels loaded event after fetch
        $(document).on('ajaxComplete', function() {
            $(document).trigger('hotelsLoaded');
        });
    }

    // Start everything
    initialize();
});