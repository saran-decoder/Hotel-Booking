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

    // =============== UTIL HELPERS ===============
    function ymd(date) {
        return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
    }
    function dmyText(date) {
        return date.toLocaleString("default", { month: "short", day: "numeric", year: "numeric" });
    }
    function safeNumber(v, fallback = 0) {
        const n = parseFloat(v);
        return Number.isFinite(n) ? n : fallback;
    }

    // =============== API: HOTELS ===============
    function fetchHotels() {
        $.ajax({
            url: 'public/../api/hotel/total-hotels',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (Array.isArray(response) && response.length > 0) {
                    hotels = processHotelData(response); // normalize shape
                    populateDestinationSelect(hotels);
                    renderHotels();
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
            // amenities
            let amenities = [];
            try {
                if (typeof h.hotel_amenities === 'string') {
                    amenities = JSON.parse(h.hotel_amenities);
                } else if (Array.isArray(h.hotel_amenities)) {
                    amenities = h.hotel_amenities;
                } else if (typeof h.hotel_amenities === 'undefined' || h.hotel_amenities === null) {
                    amenities = [];
                } else {
                    amenities = String(h.hotel_amenities).split(',');
                }
            } catch {
                amenities = h.hotel_amenities ? String(h.hotel_amenities).split(',') : [];
            }

            // images
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

            // price (optional in your API) – don’t break filters if missing
            const priceGuess = safeNumber(h.starting_price ?? h.price_per_night ?? h.min_price, 0);

            // IMPORTANT: Normalize to a single id key
            const id = h.hotel_id ?? h.id;

            return {
                // normalized
                id,
                hotel_id: id, // keep mirror to be extra safe with legacy references
                name: h.hotel_name,
                rating: 4, // placeholder (API doesn’t provide rating)
                reviews: Math.floor(Math.random() * 100) + 50,
                location: h.hotel_location_name,
                images: imgs,
                amenities: amenities,
                description: h.hotel_description,
                address: h.hotel_address,
                coordinates: h.hotel_coordinates,
                price: priceGuess
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

    // =============== API: ROOMS ===============
    function fetchRooms(hotelId) {
        $.ajax({
            url: `public/../api/hotel/info?id=${hotelId}`,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (Array.isArray(response) && response.length > 0) {
                    rooms = processRoomData(response);
                    renderRoomSelection();
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
            // amenities
            let amenities = [];
            try {
                if (typeof r.room_amenities === 'string') {
                    amenities = JSON.parse(r.room_amenities);
                } else if (Array.isArray(r.room_amenities)) {
                    amenities = r.room_amenities;
                } else if (typeof r.room_amenities === 'undefined' || r.room_amenities === null) {
                    amenities = [];
                } else {
                    amenities = String(r.room_amenities).split(',');
                }
            } catch {
                amenities = r.room_amenities ? String(r.room_amenities).split(',') : [];
            }

            // images
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

    // =============== CALENDAR (custom) + NATIVE <input type="date"> SUPPORT ===============
    function renderCalendar() {
        $('#calendarDates').empty();
        $('#currentMonth').text(currentDate.toLocaleString("default", { month: "long", year: "numeric" }));

        const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1).getDay();
        const daysInMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();

        for (let i = 0; i < firstDay; i++) $('#calendarDates').append($('<div>'));

        for (let day = 1; day <= daysInMonth; day++) {
            const dateCell = $('<div>').addClass('calendar-day').text(day);
            const cellDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);
            if (cellDate < today) {
                dateCell.addClass('disabled');
            } else {
                if (cellDate.getTime() === today.getTime()) dateCell.addClass('today');
                if (checkInDate && cellDate.getTime() === checkInDate.getTime()) dateCell.addClass('selected');
                else if (checkOutDate && cellDate.getTime() === checkOutDate.getTime()) dateCell.addClass('selected');
                else if (checkInDate && checkOutDate && cellDate > checkInDate && cellDate < checkOutDate) dateCell.css('backgroundColor', '#e6f0ff');
                dateCell.on('click', function () { selectDate(day); });
            }
            $('#calendarDates').append(dateCell);
        }
    }

    function selectDate(day) {
        const selectedDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);
        if (selectedDate < today) return;

        if (!checkInDate || (checkInDate && checkOutDate)) {
            checkInDate = selectedDate;
            checkOutDate = null;
            $('#checkInDate').text(dmyText(checkInDate));
            $('#checkOutDate').text("Select date");
            // keep native inputs in sync if present
            $('#checkInInput').val(ymd(checkInDate));
            $('#checkOutInput').val('');
        } else if (selectedDate > checkInDate) {
            checkOutDate = selectedDate;
            $('#checkOutDate').text(dmyText(checkOutDate));
            $('#checkOutInput').val(ymd(checkOutDate));
        } else if (selectedDate >= today) {
            checkInDate = selectedDate;
            checkOutDate = null;
            $('#checkInDate').text(dmyText(checkInDate));
            $('#checkOutDate').text("Select date");
            $('#checkInInput').val(ymd(checkInDate));
            $('#checkOutInput').val('');
        }
        updateContinueButton();
        renderCalendar();
    }

    // Native <input type="date"> support (optional – if inputs exist in your HTML)
    $('#checkInInput').on('change', function () {
        const d = new Date($(this).val());
        if (isNaN(d.getTime())) return;
        if (d < today) { $(this).val(''); return; }
        checkInDate = d;
        // reset checkOut if now invalid
        if (checkOutDate && checkOutDate <= checkInDate) {
            checkOutDate = null;
            $('#checkOutInput').val('');
            $('#checkOutDate').text('Select date');
        }
        $('#checkInDate').text(dmyText(checkInDate));
        updateContinueButton();
        renderCalendar();
    });
    $('#checkOutInput').on('change', function () {
        const d = new Date($(this).val());
        if (isNaN(d.getTime()) || !checkInDate) return;
        if (d <= checkInDate) { $(this).val(''); return; }
        checkOutDate = d;
        $('#checkOutDate').text(dmyText(checkOutDate));
        updateContinueButton();
        renderCalendar();
    });

    // =============== GUESTS & DETAILS DISPLAY ===============
    function updateGuestDisplay() {
        let txt = `${adultsCount} Adult${adultsCount !== 1 ? "s" : ""}`;
        if (childrenCount > 0) txt += `, ${childrenCount} Child${childrenCount !== 1 ? "ren" : ""}`;
        $('#guestDisplay').text(txt);
        updateContinueButton();
    }

    function updateBookingDetailsDisplay() {
        if (checkInDate && checkOutDate) {
            $('#bookingDetailsDisplay').text(`${ymd(checkInDate)} - ${ymd(checkOutDate)} • ${adultsCount} Adults, ${childrenCount} Children`);
        }
    }

    // =============== CONTINUE BUTTON STATE ===============
    function updateContinueButton() {
        if (checkInDate && checkOutDate && $('#destinationSelect').val()) {
            $('#continueBtn').removeClass("btn-disabled").addClass("btn-primary").prop('disabled', false);
        } else {
            $('#continueBtn').addClass("btn-disabled").removeClass("btn-primary").prop('disabled', true);
        }
    }

    // =============== STEPS ===============
    function updateStepProgress(activeStep) {
        $(".progress-step .step").each(function (index) {
            $(this).toggleClass("active", index + 1 === activeStep);
        });
    }
    function showStep(n) {
        $('.step-content').removeClass('active');
        $(`#step${n}`).addClass('active');
        updateStepProgress(n);
    }

    // =============== FILTERS & RENDER HOTELS ===============
    function filterHotels() {
        const maxPrice = parseInt($('#priceRange').val(), 10);
        const stars = $('.star-rating-checkbox:checked').map(function () { return parseInt(this.id.replace('rating', ''), 10); }).get();
        const amen = $('.amenities-checkbox:checked').map(function () { return this.id; }).get();

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
        // clear the map placeholder – we only show a link when a hotel is selected
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
                    <div id="hotelCarousel-${hotel.id}" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">${carouselIndicators}</div>
                        <div class="carousel-inner">${carouselItems}</div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">${hotel.name}</h5>
                        <div class="mb-2 d-none">
                            <span class="text-warning">${stars}</span>
                            <span class="text-muted ms-2">${hotel.reviews} reviews</span>
                        </div>
                        <p class="card-text text-muted"><i class="fa fa-map-marker-alt me-2"></i>${hotel.location || ''}</p>
                        <div class="hotel-amenities mb-3">${amenitiesHTML}</div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="small text-muted">${Number.isFinite(hotel.price) && hotel.price > 0 ? `From ₹${hotel.price}/night` : ''}</div>
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

    // Delegated click handler (fixed: find by .id, not .hotel_id)
    $(document).on('click', '.select-hotel-btn', function () {
        const hotelId = String($(this).closest('.hotel-card').data('hotel-id'));
        selectedHotel = hotels.find(h => String(h.id) === hotelId); // <<< FIXED (was h.hotel_id)  :contentReference[oaicite:3]{index=3}

        if (!selectedHotel) {
            console.error("Hotel not found for ID:", hotelId, hotels);
            return;
        }

        // Update all cards
        $('.hotel-card').css('border', '1px solid #dee2e6')
            .find('.select-hotel-btn')
            .removeClass('btn-primary')
            .addClass('btn-outline-primary')
            .html('Select');

        // Highlight current
        $(this).closest('.hotel-card').css('border', '2px solid #0d6efd');
        $(this).removeClass('btn-outline-primary').addClass('btn-primary').html('<i class="fas fa-check me-1"></i> Selected');

        // Enable continue to rooms
        $('#continueToRoomsBtn').prop('disabled', false).removeClass('btn-disabled').addClass('btn-primary');

        // Show ONE map link for the selected hotel only (no more 3x duplicates)  :contentReference[oaicite:4]{index=4}
        $('#hotelMap').empty();
        if (selectedHotel.coordinates) {
            $('#hotelMap').append(
                `<a href="${selectedHotel.coordinates}" target="_blank" class="fs-5">View on Map</a>`
            );
        }
    });

    // =============== NIGHTS & SUMMARY ===============
    function calculateNights() {
        if (checkInDate && checkOutDate) {
            return Math.ceil(Math.abs(checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));
        }
        return 0;
    }

    function updateBookingSummary() {
        const n = calculateNights();
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
        }
    }

    // =============== ROOM AVAILABILITY & RENDER ===============
    function checkRoomAvailability(roomId, checkIn, checkOut) {
        return $.ajax({
            url: 'public/../api/booking/check_availability',
            type: 'POST',
            data: {
                room_id: roomId,
                check_in: ymd(checkIn),
                check_out: ymd(checkOut)
            },
            dataType: 'json'
        }).then(function (res) {
            return !!res.available;
        }).catch(function () {
            // if check fails, we don't want to block user
            return true;
        });
    }

    function renderRoomSelection() {
        if (!selectedHotel) return;

        // Don’t try availability without dates
        if (!checkInDate || !checkOutDate) {
            $('#roomSelectionContainer').html('<div class="col-12 text-center py-5"><h5>Select your dates first</h5></div>');
            return;
        }

        $('#roomSelectionContainer').empty();
        if (!rooms.length) {
            showNoRoomsMessage();
            return;
        }

        $('#roomSelectionContainer').html(
            '<div class="col-12 text-center py-3"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Checking room availability...</p></div>'
        );

        const promises = rooms.map(room =>
            checkRoomAvailability(room.id, checkInDate, checkOutDate).then(isAvailable => ({ room, isAvailable }))
                .catch(() => ({ room, isAvailable: true, checkFailed: true }))
        );

        Promise.all(promises).then(function (results) {
            $('#roomSelectionContainer').empty();
            results.forEach(obj => renderRoomCard(obj.room, obj.isAvailable, obj.checkFailed));
        });
    }

    function renderRoomCard(room, isAvailable, checkFailed) {
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

        const card = $(`
            <div class="card room-card mb-4 ${isSelected ? 'border-primary' : ''} ${!isAvailable ? 'opacity-50' : ''}" data-room-id="${room.id}" data-available="${isAvailable}">
                ${!isAvailable ? `<div class="position-absolute top-0 start-0 w-100 bg-warning text-dark p-2 text-center z-1 rounded"><i class="fas fa-exclamation-triangle me-2"></i>Not available for selected dates</div>` : ''}
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
                const roomId = parseInt($(this).data('room-id'), 10);
                selectedRoom = rooms.find(r => r.id === roomId);
                $('.room-card').removeClass('border-primary');
                $('.select-room-btn').removeClass('btn-primary').addClass('btn-outline-primary').html('Select Room');
                $(this).closest('.room-card').addClass('border-primary');
                $(this).removeClass('btn-outline-primary').addClass('btn-primary').html('<i class="fas fa-check me-1"></i> Selected');
                updateBookingSummary();
            });
        }
    }

    // =============== REVIEWS / AMENITIES / IMAGES (hotel details) ===============
    function renderHotelReviews() {
        if (!selectedHotel) return;
        $('#hotelReviewsContainer').empty();
        const reviews = [
            { author: 'John D.', date: '2025-05-15', rating: 5, comment: 'Excellent hotel with great service. The room was spacious and clean, and the location was perfect for exploring the city.' },
            { author: 'Sarah M.', date: '2025-04-22', rating: 4, comment: 'Very comfortable stay. The staff was friendly and helpful. The only minor issue was the slow WiFi in our room.' }
        ];
        reviews.forEach(function (r) {
            let stars = '';
            for (let i = 0; i < 5; i++) stars += i < r.rating ? '<i class="fas fa-star text-warning"></i>' : '<i class="far fa-star text-warning"></i>';
            $('#hotelReviewsContainer').append(`
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="card-title mb-0">${r.author}</h5>
                            <div>${stars}</div>
                        </div>
                        <h6 class="card-subtitle mb-2 text-muted">${r.date}</h6>
                        <p class="card-text">${r.comment}</p>
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

    // =============== CONFIRMATION + PAYMENT ===============
    function updateConfirmationDetails() {
        const n = calculateNights();
        $('#confirmationHotelName').text(selectedHotel.name);
        $('#confirmationDates').text(`${dmyText(checkInDate)} - ${dmyText(checkOutDate)} (${n} nights)`);
        $('#confirmationRoomType').text(selectedRoom.name);
        $('#confirmationGuests').text(`${adultsCount} Adult${adultsCount !== 1 ? 's' : ''}${childrenCount > 0 ? `, ${childrenCount} Child${childrenCount !== 1 ? 'ren' : ''}` : ''}`);
        const subtotal = selectedRoom.price * n;
        $('#confirmationTotal').text(`₹${subtotal + 99}`);
    }

    function calculateTotalAmount() {
        if (!selectedRoom || !checkInDate || !checkOutDate) return 0;
        const diff = checkOutDate.getTime() - checkInDate.getTime();
        const nights = Math.ceil(diff / (1000 * 3600 * 24));
        if (nights <= 0) return 0;
        return (selectedRoom.price || 0) * nights + 99;
    }

    function submitBooking(callback) {
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
                    callback(response.booking_id || response.booking_ref);
                } else {
                    showToast('Error', response.message || 'Booking failed', 'error');
                    callback(null);
                }
            },
            error: function () {
                showToast('Error', 'Network error. Please try again.', 'error');
                callback(null);
            }
        });
    }

    // Razorpay init — unchanged (ensure you include Razorpay checkout.js in your HTML <head>)
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
            success: function (response) {
                if (response.status === "success") {
                    var options = {
                        "key": response.key,
                        "amount": response.amount,
                        "currency": response.currency,
                        "name": "TNBooking.in",
                        "description": "Booking Payment for " + bookingRef,
                        "order_id": response.order_id,
                        "handler": function (paymentResponse) {
                            verifyPayment(paymentResponse, amount, bookingRef);
                        },
                        "prefill": {
                            "name": "Self",
                            "email": "Self"
                        },
                        "theme": { "color": "#3399cc" },
                        "modal": {
                            "ondismiss": function () {
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
            error: function () {
                showToast('Error', 'Payment initialization failed. Please try again.', 'error');
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
        }, function (response) {
            if (response.status === 'success') {
                showToast('Success', 'Payment successful! Booking confirmed.', 'success');
                $('#confirmationBookingRef').text(bookingRef);
                updateConfirmationDetails();
                showStep(4);
            } else {
                showToast('Error', 'Payment verification failed: ' + (response.message || 'Unknown error'), 'error');
            }
        }).fail(function (xhr) {
            try {
                const response = JSON.parse(xhr.responseText);
                showToast('Error', 'Payment verification failed: ' + (response.message || 'Network error'), 'error');
            } catch {
                showToast('Error', 'Payment verification failed. Please contact support.', 'error');
            }
        });
    }

    // =============== TOASTS ===============
    function showToast(title, message, type) {
        $('.toast-container').remove();
        if ($('.toast-container').length === 0) {
            $('body').append('<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1090;"></div>');
        }
        const toastId = 'toast-' + Date.now();
        const toastHtml = `
            <div id="${toastId}" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="me-auto">${title}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">${message}</div>
            </div>
        `;
        $('.toast-container').append(toastHtml);
        const toastElement = $('#' + toastId);
        if (type === 'success') {
            toastElement.find('.toast-header').addClass('bg-success text-white');
        } else if (type === 'error') {
            toastElement.find('.toast-header').addClass('bg-danger text-white');
        } else if (type === 'warning') {
            toastElement.find('.toast-header').addClass('bg-warning text-dark');
        } else {
            toastElement.find('.toast-header').addClass('bg-info text-white');
        }
        const toast = new bootstrap.Toast(toastElement, { autohide: true, delay: 5000 });
        toast.show();
        toastElement.on('hidden.bs.toast', function () {
            $(this).remove();
        });
    }

    // =============== EVENTS ===============
    $('#prevMonth').on('click', function () {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });
    $('#nextMonth').on('click', function () {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });

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

    $('#destinationSelect').on('change', function () {
        destination = $(this).val();
        updateContinueButton();
        renderHotels();
    });

    $('#continueBtn').on('click', function (e) {
        e.preventDefault();
        if (!$('#destinationSelect').val()) {
            showToast('Error', "Please select a destination", 'error');
            return;
        }
        if (!checkInDate || !checkOutDate) {
            showToast('Error', "Please select both check-in and check-out dates", 'error');
            return;
        }
        destination = $('#destinationSelect').val();
        $('#destinationDisplay').text(destination);
        updateBookingDetailsDisplay();
        showStep(2);
        renderHotels();
    });

    $('#backToDatesBtn').on('click', function (e) {
        e.preventDefault();
        showStep(1);
    });

    $('#continueToRoomsBtn').on('click', function (e) {
        e.preventDefault();
        if (!selectedHotel) {
            showToast('Error', "Please select a hotel first", 'error');
            return;
        }
        fetchRooms(selectedHotel.id);
        $('#hotelNameDisplay').text(selectedHotel.name);
        $('#hotelLocationDisplay').text(selectedHotel.location || '');
        $('#hotelDescriptionDisplay').text(selectedHotel.description || `Experience luxury and comfort at ${selectedHotel.name}.`);
        renderHotelImageSlider();
        renderHotelAmenities();
        renderHotelReviews();
        showStep(3);
        updateBookingSummary();
    });

    $('#backToHotelsBtn').on('click', function (e) {
        e.preventDefault();
        showStep(2);
    });

    $('#continueToConfirmationBtn').on('click', function (e) {
        e.preventDefault();
        if (!selectedRoom) {
            showToast('Error', "Please select a room first", 'error');
            return;
        }
        updateConfirmationDetails();
        const totalAmount = calculateTotalAmount();
        if (totalAmount > 0) {
            submitBooking(function (bookingRef) {
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

    $('#backToHomeBtn').on('click', function (e) {
        e.preventDefault();
        checkInDate = null;
        checkOutDate = null;
        adultsCount = 1;
        childrenCount = 0;
        destination = "";
        selectedHotel = null;
        selectedRoom = null;
        nights = 0;
        rooms = [];
        $('#checkInDate').text("Select date");
        $('#checkOutDate').text("Select date");
        $('#checkInInput').val('');
        $('#checkOutInput').val('');
        $('#guestDisplay').text("1 Adult, 0 Children");
        $('#adultCount').text("1");
        $('#childCount').text("0");
        $('#destinationSelect').val("");
        $('#selectedRoomDisplay').hide();
        $('#continueToRoomsBtn').prop('disabled', true).addClass('btn-disabled').removeClass('btn-primary');
        $('#continueToConfirmationBtn').prop('disabled', true);
        window.location.href = "booking";
        renderCalendar();
    });

    $('#priceRange').on('input', function () {
        $('#priceRangeValue').text($(this).val());
        renderHotels();
    });
    $('.star-rating-checkbox, .amenities-checkbox').on('change', function () {
        renderHotels();
    });
    $('#resetFilters').on('click', function () {
        $('.star-rating-checkbox, .amenities-checkbox').prop('checked', false);
        $('#priceRange').val(5000);
        $('#priceRangeValue').text('5000');
        renderHotels();
    });
    $(document).on('click', function (e) {
        if (!$(e.target).closest('#guestSelector, #guestDropdown').length) {
            $('#guestDropdown').hide();
        }
    });

    // =============== INIT ===============
    renderCalendar();
    updateGuestDisplay();
    updateContinueButton();
    showStep(1);
    fetchHotels();
});
