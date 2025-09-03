$(document).ready(function() {
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

    // ---- API Fetch: Hotels ----
    function fetchHotels() {
        $.ajax({
            url: 'public/../api/hotel/total-hotels',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (Array.isArray(response) && response.length > 0) {
                    hotels = processHotelData(response);
                    populateDestinationSelect(hotels);
                    renderHotels();
                } else {
                    showNoHotelsMessage();
                }
            },
            error: function(xhr, status, error) {
                showErrorMessage();
            }
        });
    }

    // ---- Hotel Data Processing ----
    function processHotelData(apiData) {
        return apiData.map(function(hotel) {
            let amenities = [];
            try {
                if (typeof hotel.hotel_amenities === 'string') {
                    amenities = JSON.parse(hotel.hotel_amenities);
                } else if (Array.isArray(hotel.hotel_amenities)) {
                    amenities = hotel.hotel_amenities;
                } else {
                    amenities = [];
                }
            } catch (e) {
                amenities = hotel.hotel_amenities ? hotel.hotel_amenities.split(',') : [];
            }

            let hotelImages = [];
            try {
                if (hotel.hotel_images) {
                    hotelImages = typeof hotel.hotel_images === 'string'
                        ? JSON.parse(hotel.hotel_images)
                        : hotel.hotel_images;
                    hotelImages = hotelImages.length ? hotelImages.map(function(img) {return 'public/../' + img;}) : ['https://via.placeholder.com/500x300?text=No+Image'];
                } else {
                    hotelImages = ['https://via.placeholder.com/500x300?text=No+Image'];
                }
            } catch (e) {
                hotelImages = ['https://via.placeholder.com/500x300?text=No+Image'];
            }

            return {
                id: hotel.id,
                name: hotel.hotel_name,
                rating: 4,
                reviews: Math.floor(Math.random() * 100) + 50,
                location: hotel.hotel_location_name,
                price: parseFloat(hotel.price_per_night) || 1000,
                image: hotelImages,
                images: hotelImages,
                amenities: amenities,
                description: hotel.hotel_description,
                address: hotel.hotel_address,
                coordinates: hotel.hotel_coordinates
            };
        });
    }

    // ---- Populate Destination Select ----
    function populateDestinationSelect(hotels) {
        $('#destinationSelect').empty().append('<option value="" selected disabled>Select destination</option>');
        const uniqueLocations = [...new Set(hotels.map(function(hotel){return hotel.location;}))];
        uniqueLocations.forEach(function(location){
            $('#destinationSelect').append(`<option value="${location}">${location}</option>`);
        });
    }

    // ---- Message handling ----
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

    // ---- API Fetch: Rooms ----
    function fetchRooms(hotelId) {
        $.ajax({
            url: `public/../api/hotel/info?id=${hotelId}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (Array.isArray(response) && response.length > 0) {
                    rooms = processRoomData(response);
                    renderRoomSelection();
                } else {
                    showNoRoomsMessage();
                }
            },
            error: function(xhr, status, error) {
                showRoomErrorMessage();
            }
        });
    }

    // ---- Room Data Processing ----
    function processRoomData(apiData) {
        return apiData.map(function(room) {
            let amenities = [];
            try {
                if (typeof room.room_amenities === 'string') {
                    amenities = JSON.parse(room.room_amenities);
                } else if (Array.isArray(room.room_amenities)) {
                    amenities = room.room_amenities;
                } else {
                    amenities = [];
                }
            } catch (e) {
                amenities = room.room_amenities ? room.room_amenities.split(',') : [];
            }

            let roomImages = [];
            try {
                if (room.room_images) {
                    roomImages = typeof room.room_images === 'string'
                        ? JSON.parse(room.room_images)
                        : room.room_images;
                    roomImages = roomImages.map(function(img){return img;});
                } else {
                    roomImages = ['public/../' + (room.image || 'uploads/rooms/placeholder.jpg')];
                }
            } catch (e) {
                roomImages = ['public/../' + (room.image || 'uploads/rooms/placeholder.jpg')];
            }

            return {
                id: room.room_id,
                hotelId: room.hotel_id,
                type: room.room_type,
                name: room.room_type.charAt(0).toUpperCase() + room.room_type.slice(1) + ' Room',
                price: parseFloat(room.price_per_night) || 1000,
                description: room.room_description,
                amenities: amenities,
                image: roomImages,
                room_images: roomImages,
                guestsAllowed: room.guests_allowed || 2
            };
        });
    }

    // ---- Calendar ----
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
                dateCell.on('click', function(){ selectDate(day); });
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
            $('#checkInDate').text(formatDate(checkInDate));
            $('#checkOutDate').text("Select date");
        } else if (selectedDate > checkInDate) {
            checkOutDate = selectedDate;
            $('#checkOutDate').text(formatDate(checkOutDate));
        } else if (selectedDate >= today) {
            checkInDate = selectedDate;
            checkOutDate = null;
            $('#checkInDate').text(formatDate(checkInDate));
            $('#checkOutDate').text("Select date");
        }
        updateContinueButton();
        renderCalendar();
    }
    function formatDate(date) {
        return date.toLocaleString("default", { month: "short", day: "numeric", year: "numeric" });
    }
    function formatDateYMD(date) {
        return `${date.getFullYear()}-${String(date.getMonth()+1).padStart(2,'0')}-${String(date.getDate()).padStart(2,'0')}`;
    }

    // ---- Guest Display ----
    function updateGuestDisplay() {
        let displayText = `${adultsCount} Adult${adultsCount !== 1 ? "s" : ""}`;
        if (childrenCount > 0) displayText += `, ${childrenCount} Child${childrenCount !== 1 ? "ren" : ""}`;
        $('#guestDisplay').text(displayText);
        updateContinueButton();
    }

    // ---- Booking Details Display ----
    function updateBookingDetailsDisplay() {
        if (checkInDate && checkOutDate) {
            $('#bookingDetailsDisplay').text(`${formatDateYMD(checkInDate)} - ${formatDateYMD(checkOutDate)} • ${adultsCount} Adults, ${childrenCount} Children`);
        }
    }

    // ---- Continue Button State ----
    function updateContinueButton() {
        if (checkInDate && checkOutDate && $('#destinationSelect').val()) {
            $('#continueBtn').removeClass("btn-disabled").addClass("btn-primary").prop('disabled', false);
        } else {
            $('#continueBtn').addClass("btn-disabled").removeClass("btn-primary").prop('disabled', true);
        }
    }

    // ---- Step Progress ----
    function updateStepProgress(activeStep) {
        $(".progress-step .step").each(function(index){
            $(this).toggleClass("active", index + 1 === activeStep);
        });
    }
    function showStep(stepNumber) {
        $('.step-content').removeClass('active');
        $(`#step${stepNumber}`).addClass('active');
        updateStepProgress(stepNumber);
    }

    // ---- Hotel Filtering ----
    function filterHotels() {
        const maxPrice = parseInt($('#priceRange').val());
        const selectedRatings = $('.star-rating-checkbox:checked').map(function(){return parseInt(this.id.replace('rating', ''));}).get();
        const selectedAmenities = $('.amenities-checkbox:checked').map(function(){return this.id;}).get();
        return hotels.filter(function(hotel) {
            if (destination && hotel.location !== destination) return false;
            if (hotel.price > maxPrice) return false;
            if (selectedRatings.length && !selectedRatings.includes(hotel.rating)) return false;
            if (selectedAmenities.length && !selectedAmenities.every(function(amenity){return hotel.amenities.includes(amenity);})) return false;
            return true;
        });
    }

    // ---- Render Hotels ----
    function renderHotels() {
        const filteredHotels = filterHotels();
        $('#hotelList').empty();
        $('#hotelMap').empty();
        if (filteredHotels.length === 0) {
            $('#hotelList').html('<div class="col-12 text-center py-5"><h5>No hotels match your filters</h5><p>Try adjusting your filters</p></div>');
            return;
        }
        filteredHotels.forEach(function(hotel){
            const isSelected = selectedHotel && selectedHotel.id === hotel.id;
            let stars = '';
            for (let i = 0; i < 5; i++)
                stars += i < hotel.rating ? '<i class="fas fa-star text-warning"></i>' : '<i class="far fa-star text-warning"></i>';
            let amenitiesHTML = '';
            (hotel.amenities || []).forEach(function(amenity){
                amenitiesHTML += `<span class="amenity-badge">${amenity}</span>`;
            });
            const hotelCard = $(`
                <div class="card hotel-card mb-4" data-hotel-id="${hotel.id}" ${isSelected ? 'style="border: 2px solid #0d6efd;"' : ''}>
                    <div id="hotelCarousel-${hotel.id}" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            ${hotel.images.map(function(_, index){
                                return `<button type="button" data-bs-target="#hotelCarousel-${hotel.id}" data-bs-slide-to="${index}" class="${index === 0 ? 'active' : ''}" aria-label="Slide ${index+1}"></button>`;
                            }).join('')}
                        </div>
                        <div class="carousel-inner">
                            ${hotel.images.map(function(image, index){
                                return `<div class="carousel-item ${index === 0 ? 'active' : ''}">
                                    <img src="${image}" class="d-block w-100 hotel-img" alt="${hotel.name} - Image ${index+1}">
                                </div>`;
                            }).join('')}
                        </div>
                        ${hotel.images.length > 1 ?
                            `<button class="carousel-control-prev d-none" type="button" data-bs-target="#hotelCarousel-${hotel.id}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next d-none" type="button" data-bs-target="#hotelCarousel-${hotel.id}" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>` : ''
                        }
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">${hotel.name}</h5>
                        <div class="mb-2 d-none">
                            <span class="text-warning">${stars}</span>
                            <span class="text-muted ms-2">${hotel.reviews} reviews</span>
                        </div>
                        <p class="card-text text-muted"><i class="fa fa-map-marker-alt me-2"></i>${hotel.location}</p>
                        <div class="hotel-amenities mb-3">${amenitiesHTML}</div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="text-primary mb-0">₹${hotel.price}<small class="text-muted"> /night</small></h4>
                            </div>
                            <button class="btn ${isSelected ? 'btn-primary' : 'btn-outline-primary'} select-hotel-btn" data-hotel-id="${hotel.id}">
                                ${isSelected ? '<i class="fas fa-check me-1"></i> Selected' : 'Select'}
                            </button>
                        </div>
                    </div>
                </div>
            `);
            $('#hotelMap').append(`<a href="${hotel.coordinates}" target="_blank" class="fs-5">Click here</a>`);

            $('#hotelList').append(hotelCard);
        });
        
        // Use event delegation for dynamically created hotel selection buttons
        $('.select-hotel-btn').on('click', function() {
            const hotelId = $(this).closest('.hotel-card').data('hotel-id'); // keep as string
            selectedHotel = hotels.find(hotel => hotel.id == hotelId); // use == for safe match

            if (!selectedHotel) {
                console.error("Hotel not found for ID:", hotelId, hotels);
                return;
            }

            // Update all hotel cards and buttons
            $('.hotel-card').css('border', '1px solid #dee2e6')
                .find('.select-hotel-btn')
                .removeClass('btn-primary')
                .addClass('btn-outline-primary')
                .html('Select');

            // Highlight selected hotel
            $(this).closest('.hotel-card').css('border', '2px solid #0d6efd');
            $(this).removeClass('btn-outline-primary')
                .addClass('btn-primary')
                .html('<i class="fas fa-check me-1"></i> Selected');

            // Enable continue button
            $('#continueToRoomsBtn')
                .prop('disabled', false)
                .removeClass('btn-disabled')
                .addClass('btn-primary');
        });
    }

    // ---- Nights Calculation ----
    function calculateNights() {
        if (checkInDate && checkOutDate) {
            nights = Math.ceil(Math.abs(checkOutDate - checkInDate)/ (1000 * 60 * 60 * 24));
            return nights;
        }
        return 0;
    }

    // ---- Booking Summary ----
    function updateBookingSummary() {
        if (checkInDate && checkOutDate) {
            $('#bookingDatesSummary').text(
                `${formatDate(checkInDate)} - ${formatDate(checkOutDate)} (${calculateNights()} nights)`
            );
        }
        $('#bookingGuestsSummary').text(
            `${adultsCount} Adult${adultsCount !== 1 ? 's' : ''}${childrenCount > 0 ? `, ${childrenCount} Child${childrenCount !== 1 ? 'ren' : ''}` : ''}`
        );
        if (selectedRoom) {
            $('#selectedRoomType').text(selectedRoom.name);
            $('#selectedRoomPrice').text(`₹${selectedRoom.price} / night`);
            $('#selectedRoomDisplay').show();
            const subtotal = selectedRoom.price * nights;
            $('#roomSubtotal').text(`₹${subtotal}`);
            $('#bookingTotal').text(`₹${subtotal + 99}`);
            $('#continueToConfirmationBtn').prop('disabled', false);
        }
    }

    // ---- Room Availability ----
    function checkRoomAvailability(roomId, checkIn, checkOut) {
        return $.ajax({
            url: 'public/../api/booking/check_availability',
            type: 'POST',
            data: {
                room_id: roomId,
                check_in: formatDateYMD(checkIn),
                check_out: formatDateYMD(checkOut)
            },
            dataType: 'json'
        }).then(function(response) {
            return response.available;
        }).catch(function(){
            return true;
        });
    }

    // ---- Render Room Selection ----
    function renderRoomSelection() {
        if (!selectedHotel) return;
        $('#roomSelectionContainer').empty();
        if (!rooms.length) {
            showNoRoomsMessage();
            return;
        }
        $('#roomSelectionContainer').html('<div class="col-12 text-center py-3"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Checking room availability...</p></div>');
        const promises = rooms.map(function(room){
            return checkRoomAvailability(room.id, checkInDate, checkOutDate).then(function(isAvailable){
                return {room: room, isAvailable: isAvailable};
            }).catch(function(){
                return {room: room, isAvailable: true, checkFailed: true};
            });
        });
        Promise.all(promises).then(function(results){
            $('#roomSelectionContainer').empty();
            results.forEach(function(obj){ renderRoomCard(obj.room, obj.isAvailable, obj.checkFailed); });
        });
    }

    // ---- Render Individual Room Card ----
    function renderRoomCard(room, isAvailable, checkFailed) {
        let carouselItems = '', carouselIndicators = '';
        let roomImages = Array.isArray(room.room_images) ? room.room_images : [room.image];
        roomImages.forEach(function(image, idx){
            const imageUrl = image.startsWith('http') ? image : `public/../${image}`;
            const active = idx === 0 ? 'active' : '';
            carouselItems += `<div class="carousel-item ${active}"><img src="${imageUrl}" class="d-block w-100" alt="Room Image ${idx+1}"></div>`;
            carouselIndicators += `<button type="button" data-bs-target="#roomCarousel-${room.id}" data-bs-slide-to="${idx}" class="${active}" aria-label="Slide ${idx+1}"></button>`;
        });
        let amenitiesHTML = (room.amenities || []).map(function(amenity){
            return `<div class="d-flex align-items-center mb-2"><i class="fas fa-check-circle text-success me-2"></i><span>${amenity.replace(/_/g, ' ')}</span></div>`;
        }).join('');
        const isSelected = selectedRoom && selectedRoom.id === room.id;
        const roomCard = $(`
            <div class="card room-card mb-4 ${isSelected ? 'border-primary' : ''} ${!isAvailable ? 'opacity-50' : ''}" data-room-id="${room.id}" data-available="${isAvailable}">
                ${!isAvailable ? `<div class="position-absolute top-0 start-0 w-100 bg-warning text-dark p-2 text-center z-1 rounded"><i class="fas fa-exclamation-triangle me-2"></i>Not available for selected dates</div>` : ''}
                ${checkFailed ? `<div class="position-absolute top-0 start-0 w-100 bg-info text-white p-2 text-center z-1 rounded"><i class="fas fa-info-circle me-2"></i>Availability could not be verified</div>` : ''}
                <div class="row g-0">
                    <div class="col-md-4">
                        <div id="roomCarousel-${room.id}" class="carousel slide room-carousel" data-bs-ride="carousel">
                            <div class="carousel-indicators">${carouselIndicators}</div>
                            <div class="carousel-inner rounded">${carouselItems}</div>
                            ${roomImages.length > 1 ?
                                `<button class="carousel-control-prev" style="display: none; width: max-content; margin-left: 1.6rem;" type="button" data-bs-target="#roomCarousel-${room.id}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" style="display: none; width: max-content; margin-right: 1.6rem;" type="button" data-bs-target="#roomCarousel-${room.id}" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>` : ''
                            }
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="card-body">
                            <h4 class="card-title">${room.name}</h4>
                            <p class="card-text">${room.description}</p>
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
        $('#roomSelectionContainer').append(roomCard);
        if (isAvailable) {
            roomCard.find('.select-room-btn').on('click', function() {
                const roomId = parseInt($(this).data('room-id'));
                selectedRoom = rooms.find(function(room){return room.id === roomId;});
                $('.room-card').removeClass('border-primary');
                $('.select-room-btn').removeClass('btn-primary').addClass('btn-outline-primary').html('Select Room');
                $(this).closest('.room-card').addClass('border-primary');
                $(this).removeClass('btn-outline-primary').addClass('btn-primary').html('<i class="fas fa-check me-1"></i> Selected');
                updateBookingSummary();
            });
        }
    }

    // ---- Reviews ----
    function renderHotelReviews() {
        if (!selectedHotel) return;
        $('#hotelReviewsContainer').empty();
        const reviews = [
            {
                author: 'John D.',
                date: '2025-05-15',
                rating: 5,
                comment: 'Excellent hotel with great service. The room was spacious and clean, and the location was perfect for exploring the city.'
            },
            {
                author: 'Sarah M.',
                date: '2025-04-22',
                rating: 4,
                comment: 'Very comfortable stay. The staff was friendly and helpful. The only minor issue was the slow WiFi in our room.'
            }
        ];
        reviews.forEach(function(review){
            let stars = '';
            for (let i = 0; i < 5; i++)
                stars += i < review.rating ? '<i class="fas fa-star text-warning"></i>' : '<i class="far fa-star text-warning"></i>';
            const reviewCard = $(`
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="card-title mb-0">${review.author}</h5>
                            <div>${stars}</div>
                        </div>
                        <h6 class="card-subtitle mb-2 text-muted">${review.date}</h6>
                        <p class="card-text">${review.comment}</p>
                    </div>
                </div>
            `);
            $('#hotelReviewsContainer').append(reviewCard);
        });
    }

    // ---- Amenities ----
    function renderHotelAmenities() {
        if (!selectedHotel) return;
        $('#hotelAmenitiesDisplay').empty();
        const amenities = selectedHotel.amenities || [];
        if (!amenities.length) {
            $('#hotelAmenitiesDisplay').html('<p>No amenities listed</p>');
            return;
        }
        amenities.forEach(function(amenity){
            const amenityName = amenity.replace(/_/g, ' ');
            const iconClass = getAmenityIcon(amenity);
            const col = $(`
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="${iconClass} me-3 text-primary"></i>
                        <span>${amenityName}</span>
                    </div>
                </div>
            `);
            $('#hotelAmenitiesDisplay').append(col);
        });
    }
    function getAmenityIcon(amenity) {
        const iconMap = {
            'wifi': 'fas fa-wifi',
            'parking': 'fas fa-parking',
            'pool': 'fas fa-swimming-pool',
            'ac': 'fas fa-snowflake',
            'restaurant': 'fas fa-utensils',
            'fitness': 'fas fa-dumbbell',
            'air_conditioning': 'fas fa-snowflake',
            'swimming_pool': 'fas fa-swimming-pool',
            'fitness_center': 'fas fa-dumbbell',
            'bar': 'fas fa-glass-martini-alt'
        };
        return iconMap[amenity] || 'fas fa-check-circle';
    }

    // ---- Image Slider ----
    function renderHotelImageSlider() {
        if (!selectedHotel || !selectedHotel.images) return;
        $('#hotelImageSlider').empty();
        const images = selectedHotel.images;
        const indicators = images.map(function(_, idx){
            return `<button type="button" data-bs-target="#hotelDetailsCarousel" data-bs-slide-to="${idx}" class="${idx===0?'active':''}" aria-label="Slide ${idx+1}"></button>`;
        }).join('');
        const carouselItems = images.map(function(image, idx){
            return `<div class="carousel-item ${idx===0?'active':''}">
                <img src="${image}" class="d-block w-100 hotel-detail-img" alt="${selectedHotel.name} - Image ${idx+1}">
            </div>`;
        }).join('');
        const navigationControls = images.length > 1 ?
            `<button class="carousel-control-prev d-none ms-5" type="button" data-bs-target="#hotelCarousel" data-bs-slide="prev" style="background: none;">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next d-none me-5" type="button" data-bs-target="#hotelCarousel" data-bs-slide="next" style="background: none;">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>` : '';
        const carouselHTML = `
            <div id="hotelDetailsCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                <div class="carousel-indicators">${indicators}</div>
                <div class="carousel-inner rounded">${carouselItems}</div>
                ${navigationControls}
            </div>
        `;
        $('#hotelImageSlider').html(carouselHTML);
    }

    // ---- Confirmation Details ----
    function updateConfirmationDetails() {
        $('#confirmationHotelName').text(selectedHotel.name);
        $('#confirmationDates').text(`${formatDate(checkInDate)} - ${formatDate(checkOutDate)} (${calculateNights()} nights)`);
        $('#confirmationRoomType').text(selectedRoom.name);
        $('#confirmationGuests').text(
            `${adultsCount} Adult${adultsCount !== 1 ? 's' : ''}${childrenCount > 0 ? `, ${childrenCount} Child${childrenCount !== 1 ? 'ren' : ''}` : ''}`
        );
        const subtotal = selectedRoom.price * nights;
        $('#confirmationTotal').text(`₹${subtotal + 99}`);
    }

    // ---- Submit Booking ----
    function submitBooking(callback) {
        const formData = {
            hotel_id: selectedHotel.id,
            room_id: selectedRoom.id,
            check_in: formatDateYMD(checkInDate),
            check_out: formatDateYMD(checkOutDate),
            adults: adultsCount,
            children: childrenCount,
            total_price: calculateTotalAmount()
        };
        $.ajax({
            url: 'public/../api/booking/add',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response && response.success) {
                    callback(response.booking_id || response.booking_ref);
                } else {
                    showToast('Error', response.message || 'Booking failed', 'error');
                    callback(null);
                }
            },
            error: function(xhr, status, error) {
                showToast('Error', 'Network error. Please try again.', 'error');
                callback(null);
            }
        });
    }

    // ---- Payment Calculation ----
    function calculateTotalAmount() {
        if (!selectedRoom) return 0;
        const timeDiff = checkOutDate.getTime() - checkInDate.getTime();
        const numberOfNights = Math.ceil(timeDiff / (1000 * 3600 * 24));
        if (numberOfNights <= 0) return 0;
        const roomPrice = selectedRoom.price || 0;
        return (roomPrice * numberOfNights) + 99;
    }

    // ---- Event Listeners ----
    $('#prevMonth').on('click', function(){
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });
    $('#nextMonth').on('click', function(){
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });
    $('#guestSelector').on('click', function(e){
        e.stopPropagation();
        $('#guestDropdown').toggle();
    });
    $('#decreaseAdults').on('click', function(e){
        e.stopPropagation();
        if (adultsCount > 1) {
            adultsCount--;
            $('#adultCount').text(adultsCount);
            updateGuestDisplay();
        }
    });
    $('#increaseAdults').on('click', function(e){
        e.stopPropagation();
        adultsCount++;
        $('#adultCount').text(adultsCount);
        updateGuestDisplay();
    });
    $('#decreaseChildren').on('click', function(e){
        e.stopPropagation();
        if (childrenCount > 0) {
            childrenCount--;
            $('#childCount').text(childrenCount);
            updateGuestDisplay();
        }
    });
    $('#increaseChildren').on('click', function(e){
        e.stopPropagation();
        childrenCount++;
        $('#childCount').text(childrenCount);
        updateGuestDisplay();
    });
    $('#applyGuests').on('click', function(e){
        e.stopPropagation();
        updateGuestDisplay();
        $('#guestDropdown').hide();
    });
    $('#destinationSelect').on('change', function(){
        destination = $(this).val();
        updateContinueButton();
        renderHotels();
    });
    $('#continueBtn').on('click', function(e){
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
    $('#backToDatesBtn').on('click', function(e){
        e.preventDefault();
        showStep(1);
    });
    $('#continueToRoomsBtn').on('click', function(e){
        e.preventDefault();
        // if (!selectedHotel) {
        //     showToast('Error', "Please select a hotel first", 'error');
        //     return;
        // }
        fetchRooms(selectedHotel.id);
        $('#hotelNameDisplay').text(selectedHotel.name);
        $('#hotelLocationDisplay').text(selectedHotel.location);
        $('#hotelDescriptionDisplay').text(selectedHotel.description || `Experience luxury and comfort at ${selectedHotel.name}.`);
        renderHotelImageSlider();
        renderHotelAmenities();
        renderHotelReviews();
        showStep(3);
        updateBookingSummary();
    });
    $('#backToHotelsBtn').on('click', function(e){
        e.preventDefault();
        showStep(2);
    });
    $('#continueToConfirmationBtn').on('click', function(e){
        e.preventDefault();
        if (!selectedRoom) {
            showToast('Error', "Please select a room first", 'error');
            return;
        }
        updateConfirmationDetails();
        const totalAmount = calculateTotalAmount();
        if (totalAmount > 0) {
            submitBooking(function(bookingRef){
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
    $('#backToHomeBtn').on('click', function(e){
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
    $('#priceRange').on('input', function(){
        $('#priceRangeValue').text($(this).val());
        renderHotels();
    });
    $('.star-rating-checkbox, .amenities-checkbox').on('change', function(){
        renderHotels();
    });
    $('#resetFilters').on('click', function(){
        $('.star-rating-checkbox, .amenities-checkbox').prop('checked', false);
        $('#priceRange').val(5000);
        $('#priceRangeValue').text('5000');
        renderHotels();
    });
    $(document).on('click', function(e){
        if (!$(e.target).closest('#guestSelector, #guestDropdown').length) {
            $('#guestDropdown').hide();
        }
    });

    // ---- Razorpay Payment ----
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
                            "name": "Self",
                            "email": "Self"
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
                updateConfirmationDetails();
                showStep(4);
            } else {
                showToast('Error', 'Payment verification failed: ' + (response.message || 'Unknown error'), 'error');
            }
        }).fail(function(xhr){
            try {
                const response = JSON.parse(xhr.responseText);
                showToast('Error', 'Payment verification failed: ' + (response.message || 'Network error'), 'error');
            } catch (e) {
                showToast('Error', 'Payment verification failed. Please contact support.', 'error');
            }
        });
    }

    // ---- Toast Notification ----
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
        const toast = new bootstrap.Toast(toastElement, {autohide: true, delay: 5000});
        toast.show();
        toastElement.on('hidden.bs.toast', function () {
            $(this).remove();
        });
    }

    // ---- Initialization ----
    renderCalendar();
    updateGuestDisplay();
    updateContinueButton();
    showStep(1);
    fetchHotels();
});