// booking.js - Complete jQuery booking system with API integration
$(document).ready(function() {
    // Initialize variables
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
    let roomPrice = 0;
    let nights = 0;
    let hotels = [];
    let rooms = [];

    // Fetch hotels from API
    function fetchHotels() {
        $.ajax({
            url: '../api/hotel/list',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response && response.length > 0) {
                    hotels = processHotelData(response);
                    populateDestinationSelect(hotels);
                    renderHotels();
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

    // Process hotel data from API
    function processHotelData(apiData) {
        return apiData.map(hotel => {
            // Parse amenities from JSON string if needed
            let amenities = [];
            if (typeof hotel.hotel_amenities === 'string') {
                try {
                    amenities = JSON.parse(hotel.hotel_amenities);
                } catch (e) {
                    amenities = hotel.hotel_amenities.split(',');
                }
            } else {
                amenities = hotel.hotel_amenities || [];
            }

            // Get all hotel images
            let hotelImages = [];
            if (hotel.hotel_images) {
                try {
                    const images = typeof hotel.hotel_images === 'string' ? 
                        JSON.parse(hotel.hotel_images) : hotel.hotel_images;
                    if (images.length > 0) {
                        hotelImages = images.map(img => '../' + img);
                    } else {
                        hotelImages = ['https://via.placeholder.com/500x300?text=No+Image'];
                    }
                } catch (e) {
                    console.error('Error parsing hotel images:', e);
                    hotelImages = ['https://via.placeholder.com/500x300?text=No+Image'];
                }
            } else {
                hotelImages = ['https://via.placeholder.com/500x300?text=No+Image'];
            }

            return {
                id: hotel.id,
                name: hotel.hotel_name,
                rating: 4, // Default rating since API doesn't provide
                reviews: Math.floor(Math.random() * 100) + 50, // Random reviews
                location: hotel.hotel_location_name,
                price: parseFloat(hotel.price_per_night) || 1000,
                image: hotelImages[0], // Keep first image for backward compatibility
                images: hotelImages, // All images for carousel
                amenities: amenities,
                description: hotel.hotel_description,
                address: hotel.hotel_address,
                coordinates: hotel.hotel_coordinates
            };
        });
    }

    // Populate destination select dropdown
    function populateDestinationSelect(hotels) {
        $('#destinationSelect').empty();
        $('#destinationSelect').append('<option value="" selected disabled>Select destination</option>');
        
        // Get unique locations
        const uniqueLocations = [...new Set(hotels.map(hotel => hotel.location))];
        
        uniqueLocations.forEach(location => {
            $('#destinationSelect').append(`<option value="${location}">${location}</option>`);
        });
    }

    // Show no hotels message
    function showNoHotelsMessage() {
        $('#hotelList').html('<div class="col-12 text-center py-5"><h5>No hotels available at the moment</h5><p>Please try again later</p></div>');
    }

    // Show error message
    function showErrorMessage() {
        $('#hotelList').html('<div class="col-12 text-center py-5"><h5>Error loading hotels</h5><p>Please refresh the page</p></div>');
    }

    // Fetch rooms for selected hotel
    function fetchRooms(hotelId) {
        $.ajax({
            url: `../api/hotel/info?id=${hotelId}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response && response.length > 0) {
                    rooms = processRoomData(response);
                    renderRoomSelection();
                } else {
                    showNoRoomsMessage();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching rooms:', error);
                showRoomErrorMessage();
            }
        });
    }

    // Process room data from API
    function processRoomData(apiData) {
        return apiData.map(room => {
            // Parse amenities from JSON string if needed
            let amenities = [];
            if (typeof room.room_amenities === 'string') {
                try {
                    amenities = JSON.parse(room.room_amenities);
                } catch (e) {
                    amenities = room.room_amenities.split(',');
                }
            } else {
                amenities = room.room_amenities || [];
            }

            // Get all room images
            let roomImages = [];
            if (room.room_images) {
                try {
                    roomImages = typeof room.room_images === 'string' ? 
                        JSON.parse(room.room_images) : room.room_images;
                    // Prepend the correct path
                    roomImages = roomImages.map(img => img);
                } catch (e) {
                    console.error('Error parsing room images:', e);
                    // Fallback to single image if parsing fails
                    roomImages = ['../' + (room.image || 'uploads/rooms/placeholder.jpg')];
                }
            } else {
                // Use the single image as fallback
                roomImages = ['../' + (room.image || 'uploads/rooms/placeholder.jpg')];
            }

            return {
                id: room.room_id,
                hotelId: room.hotel_id,
                type: room.room_type,
                name: room.room_type.charAt(0).toUpperCase() + room.room_type.slice(1) + ' Room',
                price: parseFloat(room.price_per_night) || 1000,
                description: room.room_description,
                amenities: amenities,
                image: roomImages[0],
                room_images: roomImages,
                guestsAllowed: room.guests_allowed || 2
            };
        });
    }

    // Show no rooms message
    function showNoRoomsMessage() {
        $('#roomSelectionContainer').html('<div class="col-12 text-center py-5"><h5>No rooms available for this hotel</h5><p>Please select another hotel</p></div>');
    }

    // Show room error message
    function showRoomErrorMessage() {
        $('#roomSelectionContainer').html('<div class="col-12 text-center py-5"><h5>Error loading rooms</h5><p>Please try again</p></div>');
    }

    // Initialize calendar
    function renderCalendar() {
        $('#calendarDates').empty();

        // Set month and year display
        $('#currentMonth').text(currentDate.toLocaleString("default", { month: "long", year: "numeric" }));

        // Get first day of month and total days in month
        const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1).getDay();
        const daysInMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();

        // Add empty cells for days before the first day of the month
        for (let i = 0; i < firstDay; i++) {
            $('#calendarDates').append($('<div>').addClass(''));
        }

        // Add cells for each day of the month
        for (let day = 1; day <= daysInMonth; day++) {
            const dateCell = $('<div>').addClass('calendar-day').text(day);
            const cellDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);
            
            // Disable past dates
            if (cellDate < today) {
                dateCell.addClass('disabled');
            } else {
                // Check if this is today
                if (cellDate.getTime() === today.getTime()) {
                    dateCell.addClass('today');
                }

                // Check if this date is selected
                if (checkInDate && cellDate.getTime() === checkInDate.getTime()) {
                    dateCell.addClass('selected');
                } else if (checkOutDate && cellDate.getTime() === checkOutDate.getTime()) {
                    dateCell.addClass('selected');
                } else if (checkInDate && checkOutDate && cellDate > checkInDate && cellDate < checkOutDate) {
                    dateCell.css('backgroundColor', '#e6f0ff');
                }

                dateCell.on('click', () => selectDate(day));
            }

            $('#calendarDates').append(dateCell);
        }
    }

    // Handle date selection
    function selectDate(day) {
        const selectedDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);
        
        // Don't allow selection of past dates
        if (selectedDate < today) {
            return;
        }

        if (!checkInDate || (checkInDate && checkOutDate)) {
            // Start new selection
            checkInDate = selectedDate;
            checkOutDate = null;
            $('#checkInDate').text(formatDate(checkInDate));
            $('#checkOutDate').text("Select date");
        } else if (selectedDate > checkInDate) {
            // Complete the range
            checkOutDate = selectedDate;
            $('#checkOutDate').text(formatDate(checkOutDate));
        } else {
            // Select earlier date as check-in (but not before today)
            if (selectedDate >= today) {
                checkInDate = selectedDate;
                checkOutDate = null;
                $('#checkInDate').text(formatDate(checkInDate));
                $('#checkOutDate').text("Select date");
            }
        }

        // Enable continue button if all required fields are filled
        updateContinueButton();
        renderCalendar();
    }

    // Format date as "Month Day, Year"
    function formatDate(date) {
        return date.toLocaleString("default", { month: "short", day: "numeric", year: "numeric" });
    }

    // Format date as YYYY-MM-DD
    function formatDateYMD(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Update guest display
    function updateGuestDisplay() {
        let displayText = `${adultsCount} Adult${adultsCount !== 1 ? "s" : ""}`;
        if (childrenCount > 0) {
            displayText += `, ${childrenCount} Child${childrenCount !== 1 ? "ren" : ""}`;
        }
        $('#guestDisplay').text(displayText);
        updateContinueButton();
    }

    // Update booking details display
    function updateBookingDetailsDisplay() {
        if (checkInDate && checkOutDate) {
            const checkInStr = formatDateYMD(checkInDate);
            const checkOutStr = formatDateYMD(checkOutDate);
            $('#bookingDetailsDisplay').text(`${checkInStr} - ${checkOutStr} • ${adultsCount} Adults, ${childrenCount} Children`);
        }
    }

    // Update continue button state
    function updateContinueButton() {
        if (checkInDate && checkOutDate && $('#destinationSelect').val()) {
            $('#continueBtn').removeClass("btn-disabled").addClass("btn-primary").prop('disabled', false);
        } else {
            $('#continueBtn').addClass("btn-disabled").removeClass("btn-primary").prop('disabled', true);
        }
    }

    // Update step progress
    function updateStepProgress(activeStep) {
        $(".progress-step .step").each(function(index) {
            if (index + 1 === activeStep) {
                $(this).addClass("active");
            } else {
                $(this).removeClass("active");
            }
        });
    }

    // Show specific step
    function showStep(stepNumber) {
        // Hide all steps
        $('.step-content').removeClass('active');
        
        // Show the selected step
        $(`#step${stepNumber}`).addClass('active');
        
        // Update progress indicator
        updateStepProgress(stepNumber);
    }

    // Filter hotels based on selected filters
    function filterHotels() {
        const maxPrice = parseInt($('#priceRange').val());
        const selectedRatings = [];
        const selectedAmenities = [];
        
        // Get selected star ratings
        $('.star-rating-checkbox:checked').each(function() {
            selectedRatings.push(parseInt($(this).attr('id').replace('rating', '')));
        });
        
        // Get selected amenities
        $('.amenities-checkbox:checked').each(function() {
            selectedAmenities.push($(this).attr('id'));
        });
        
        return hotels.filter(hotel => {
            // Filter by destination
            if (destination && hotel.location !== destination) return false;
            
            // Filter by price
            if (hotel.price > maxPrice) return false;
            
            // Filter by star rating
            if (selectedRatings.length > 0 && !selectedRatings.includes(hotel.rating)) return false;
            
            // Filter by amenities
            if (selectedAmenities.length > 0) {
                const hasAllAmenities = selectedAmenities.every(amenity => 
                    hotel.amenities.includes(amenity)
                );
                if (!hasAllAmenities) return false;
            }
            
            return true;
        });
    }

    // Render hotel cards
    function renderHotels() {
        const filteredHotels = filterHotels();
        $('#hotelList').empty();
        
        if (filteredHotels.length === 0) {
            $('#hotelList').html('<div class="col-12 text-center py-5"><h5>No hotels match your filters</h5><p>Try adjusting your filters</p></div>');
            return;
        }
        
        filteredHotels.forEach(hotel => {
            
            const isSelected = selectedHotel && selectedHotel.id === hotel.id;
            
            // Generate star rating
            let stars = '';
            for (let i = 0; i < 5; i++) {
                stars += i < hotel.rating ? '<i class="fas fa-star text-warning"></i>' : '<i class="far fa-star text-warning"></i>';
            }
            
            // Generate amenities badges
            let amenitiesHTML = '';
            hotel.amenities.forEach(amenity => {
                amenitiesHTML += `<span class="amenity-badge">${amenity}</span>`;
            });
            
            const hotelCard = $(`
                <div class="card hotel-card mb-4" data-hotel-id="${hotel.id}" ${isSelected ? 'style="border: 2px solid #0d6efd;"' : ''}>
                    <div id="hotelCarousel-${hotel.id}" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            ${hotel.images.map((_, index) => `
                                <button type="button" data-bs-target="#hotelCarousel-${hotel.id}" 
                                    data-bs-slide-to="${index}" class="${index === 0 ? 'active' : ''}" 
                                    aria-label="Slide ${index + 1}"></button>
                            `).join('')}
                        </div>
                        <div class="carousel-inner">
                            ${hotel.images.map((image, index) => `
                                <div class="carousel-item ${index === 0 ? 'active' : ''}">
                                    <img src="${image}" class="d-block w-100 hotel-img" alt="${hotel.name} - Image ${index + 1}">
                                </div>
                            `).join('')}
                        </div>
                        ${hotel.images.length > 1 ? `
                            <button class="carousel-control-prev" type="button" data-bs-target="#hotelCarousel-${hotel.id}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#hotelCarousel-${hotel.id}" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        ` : ''}
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">${hotel.name}</h5>
                        <div class="mb-2">
                            <span class="text-warning">${stars}</span>
                            <span class="text-muted ms-2">${hotel.reviews} reviews</span>
                        </div>
                        <p class="card-text text-muted">
                            <i class="fa fa-map-marker-alt me-2"></i>${hotel.location}
                        </p>
                        <div class="hotel-amenities mb-3">
                            ${amenitiesHTML}
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="text-primary mb-0">₹${hotel.price}<small class="text-muted"> /night</small></h4>
                            </div>
                            <button class="btn ${isSelected ? 'btn-primary' : 'btn-outline-primary'} select-hotel-btn">
                                ${isSelected ? '<i class="fas fa-check me-1"></i> Selected' : 'Select'}
                            </button>
                        </div>
                    </div>
                </div>
            `);

            $('#hotelMap').html('<a href="' + hotel.coordinates + '" target="_blank">Click Here</a>');
            $('#hotelList').append(hotelCard);
        });

        // Add event listeners to select hotel buttons
        $('.select-hotel-btn').on('click', function() {
            const hotelId = parseInt($(this).closest('.hotel-card').data('hotel-id'));
            selectedHotel = hotels.find(hotel => hotel.id === hotelId);
            
            // Update all hotel cards and buttons
            $('.hotel-card').css('border', '1px solid #dee2e6')
                .find('.select-hotel-btn')
                .removeClass('btn-primary')
                .addClass('btn-outline-primary')
                .html('Select');
            
            // Update the selected card and button
            $(this).closest('.hotel-card').css('border', '2px solid #0d6efd');
            $(this).removeClass('btn-outline-primary')
                .addClass('btn-primary')
                .html('<i class="fas fa-check me-1"></i> Selected');
            
            // Enable continue to rooms button
            $('#continueToRoomsBtn').prop('disabled', false)
                .removeClass('btn-disabled')
                .addClass('btn-primary');
        });
    }

    // Calculate number of nights
    function calculateNights() {
        if (checkInDate && checkOutDate) {
            const diffTime = Math.abs(checkOutDate - checkInDate);
            nights = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            return nights;
        }
        return 0;
    }

    // Update booking summary
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

    // Check room availability
    function checkRoomAvailability(roomId, checkIn, checkOut) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: '../api/booking/check_availability',
                type: 'POST',
                data: {
                    room_id: roomId,
                    check_in: formatDateYMD(checkIn),
                    check_out: formatDateYMD(checkOut)
                },
                dataType: 'json',
                success: function(response) {
                    resolve(response.available);
                },
                error: function() {
                    // If availability check fails, assume room is available
                    console.error('Availability check failed');
                    resolve(true);
                }
            });
        });
    }

    // Render room selection
    function renderRoomSelection() {
        if (!selectedHotel) return;
        
        $('#roomSelectionContainer').empty();
        
        if (rooms.length === 0) {
            showNoRoomsMessage();
            return;
        }
        
        // Show loading state
        $('#roomSelectionContainer').html('<div class="col-12 text-center py-3"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Checking room availability...</p></div>');
        
        // Check availability for each room and render accordingly
        const availabilityChecks = rooms.map(room => {
            return checkRoomAvailability(room.id, checkInDate, checkOutDate)
                .then(isAvailable => {
                    return { room, isAvailable };
                })
                .catch(error => {
                    console.error('Availability check failed for room', room.id, error);
                    // If availability check fails, assume room is available but show warning
                    return { room, isAvailable: true, checkFailed: true };
                });
        });
        
        // Wait for all availability checks to complete
        Promise.all(availabilityChecks).then(results => {
            $('#roomSelectionContainer').empty();
            
            results.forEach(({ room, isAvailable, checkFailed }) => {
                renderRoomCard(room, isAvailable, checkFailed);
            });
        });
    }

    // Render individual room card
    function renderRoomCard(room, isAvailable, checkFailed = false) {
        // Reset carousel variables for each room
        let carouselItems = '';
        let carouselIndicators = '';
        
        // Get all room images (parse if needed)
        let roomImages = [];
        if (room.room_images) {
            try {
                roomImages = typeof room.room_images === 'string' ? 
                    JSON.parse(room.room_images) : room.room_images;
            } catch (e) {
                console.error('Error parsing room images:', e);
                // Fallback to single image if parsing fails
                roomImages = [room.image];
            }
        } else {
            // Use the single image as fallback
            roomImages = [room.image];
        }
        
        // Generate carousel items and indicators for all room images
        roomImages.forEach((image, imageIndex) => {
            const imageUrl = image.startsWith('http') ? image : `../${image}`;
            const isActive = imageIndex === 0 ? 'active' : '';
            
            carouselItems += `
                <div class="carousel-item ${isActive}">
                    <img src="${imageUrl}" class="d-block w-100" alt="Room Image ${imageIndex + 1}">
                </div>
            `;
            
            carouselIndicators += `
                <button type="button" data-bs-target="#roomCarousel-${room.id}" 
                    data-bs-slide-to="${imageIndex}" class="${isActive}" 
                    aria-current="${isActive ? 'true' : 'false'}" 
                    aria-label="Slide ${imageIndex + 1}"></button>
            `;
        });
        
        // Generate amenities list
        let amenitiesHTML = room.amenities.map(amenity => `
            <div class="d-flex align-items-center mb-2">
                <i class="fas fa-check-circle text-success me-2"></i>
                <span>${amenity.replace(/_/g, ' ')}</span>
            </div>
        `).join('');
        
        const isSelected = selectedRoom && selectedRoom.id === room.id;
        
        const roomCard = $(`
            <div class="card room-card mb-4 ${isSelected ? 'border-primary' : ''} ${!isAvailable ? 'opacity-50' : ''}" 
                data-room-id="${room.id}" data-available="${isAvailable}">
                ${!isAvailable ? `
                    <div class="position-absolute top-0 start-0 w-100 bg-warning text-dark p-2 text-center z-1 rounded">
                        <i class="fas fa-exclamation-triangle me-2"></i>Not available for selected dates
                    </div>
                ` : ''}
                
                ${checkFailed ? `
                    <div class="position-absolute top-0 start-0 w-100 bg-info text-white p-2 text-center z-1 rounded">
                        <i class="fas fa-info-circle me-2"></i>Availability could not be verified
                    </div>
                ` : ''}
                
                <div class="row g-0">
                    <div class="col-md-4">
                        <div id="roomCarousel-${room.id}" class="carousel slide room-carousel" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                ${carouselIndicators}
                            </div>
                            <div class="carousel-inner rounded">
                                ${carouselItems}
                            </div>
                            ${roomImages.length > 1 ? `
                                <button class="carousel-control-prev" style="width: max-content;" type="button" data-bs-target="#roomCarousel-${room.id}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" style="width: max-content;" type="button" data-bs-target="#roomCarousel-${room.id}" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            ` : ''}
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="card-body">
                            <h4 class="card-title">${room.name}</h4>
                            <p class="card-text">${room.description}</p>
                            <div class="amenities-container">
                                ${amenitiesHTML}
                            </div>
                            <p class="card-text mt-2"><small class="text-muted">Up to ${room.guestsAllowed} guests</small></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card-body h-100 d-flex flex-column justify-content-between">
                            <div class="text-end mb-3">
                                <span class="h4 text-primary">₹${room.price}</span>
                                <span class="text-muted">/night</span>
                            </div>
                            <button class="btn ${isSelected ? 'btn-primary' : 'btn-outline-primary'} 
                                ${!isAvailable ? 'disabled' : ''} select-room-btn w-100" 
                                data-room-id="${room.id}" ${!isAvailable ? 'disabled' : ''}>
                                ${!isAvailable ? 'Not Available' : (isSelected ? '<i class="fas fa-check me-1"></i> Selected' : 'Select Room')}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `);
        
        $('#roomSelectionContainer').append(roomCard);
        
        // Add event listener if room is available
        if (isAvailable) {
            roomCard.find('.select-room-btn').on('click', function() {
                const roomId = parseInt($(this).data('room-id'));
                selectedRoom = rooms.find(room => room.id === roomId);
                
                // Remove selected class from all rooms
                $('.room-card').removeClass('border-primary');
                $('.select-room-btn').removeClass('btn-primary').addClass('btn-outline-primary').html('Select Room');
                
                // Add selected class to clicked room
                $(this).closest('.room-card').addClass('border-primary');
                $(this).removeClass('btn-outline-primary').addClass('btn-primary').html('<i class="fas fa-check me-1"></i> Selected');
                
                // Update booking summary
                updateBookingSummary();
            });
        }
    }

    // Render hotel reviews
    function renderHotelReviews() {
        if (!selectedHotel) return;
        
        $('#hotelReviewsContainer').empty();
        
        // Sample reviews (in a real app, these would come from the API)
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
        
        reviews.forEach(review => {
            // Generate star rating
            let stars = '';
            for (let i = 0; i < 5; i++) {
                stars += i < review.rating ? '<i class="fas fa-star text-warning"></i>' : '<i class="far fa-star text-warning"></i>';
            }
            
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

    // Render hotel amenities
    function renderHotelAmenities() {
        if (!selectedHotel) return;
        
        $('#hotelAmenitiesDisplay').empty();
        
        const amenities = selectedHotel.amenities;
        
        if (amenities.length === 0) {
            $('#hotelAmenitiesDisplay').html('<p>No amenities listed</p>');
            return;
        }
        
        amenities.forEach(amenity => {
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

    // Function to render hotel image slider
    function renderHotelImageSlider() {
        if (!selectedHotel || !selectedHotel.images) return;
        
        $('#hotelImageSlider').empty();
        
        const images = selectedHotel.images;
        
        // Generate carousel indicators
        const indicators = images.map((_, index) => `
            <button type="button" data-bs-target="#hotelDetailsCarousel" 
                data-bs-slide-to="${index}" class="${index === 0 ? 'active' : ''}" 
                aria-label="Slide ${index + 1}"></button>
        `).join('');
        
        // Generate carousel items
        const carouselItems = images.map((image, index) => `
            <div class="carousel-item ${index === 0 ? 'active' : ''}">
                <img src="${image}" class="d-block w-100 hotel-detail-img" alt="${selectedHotel.name} - Image ${index + 1}">
            </div>
        `).join('');
        
        // Generate navigation controls if multiple images
        const navigationControls = images.length > 1 ? `
            <button class="carousel-control-prev ms-5" type="button" data-bs-target="#hotelCarousel" data-bs-slide="prev" style="background: none;">
                <svg xmlns="http://www.w3.org/2000/svg" class="control-prev-icon" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M15 18L9 12L15 6" stroke="#4A4A4A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next me-5" type="button" data-bs-target="#hotelCarousel" data-bs-slide="next" style="background: none;">
                <svg xmlns="http://www.w3.org/2000/svg" class="control-next-icon" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M9 18L15 12L9 6" stroke="#4A4A4A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="visually-hidden">Next</span>
            </button>
        ` : '';
        
        // Build the carousel HTML
        const carouselHTML = `
            <div id="hotelDetailsCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    ${indicators}
                </div>
                <div class="carousel-inner rounded">
                    ${carouselItems}
                </div>
                ${navigationControls}
            </div>
        `;
        
        $('#hotelImageSlider').html(carouselHTML);
    }

    // Get appropriate icon for amenity
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

    // Update confirmation details
    function updateConfirmationDetails() {
        $('#confirmationHotelName').text(selectedHotel.name);
        $('#confirmationDates').text(
            `${formatDate(checkInDate)} - ${formatDate(checkOutDate)} (${calculateNights()} nights)`
        );
        $('#confirmationRoomType').text(selectedRoom.name);
        $('#confirmationGuests').text(
            `${adultsCount} Adult${adultsCount !== 1 ? 's' : ''}${childrenCount > 0 ? `, ${childrenCount} Child${childrenCount !== 1 ? 'ren' : ''}` : ''}`
        );
        
        const subtotal = selectedRoom.price * nights;
        $('#confirmationTotal').text(`₹${subtotal + 99}`);
    }

    // Submit booking function
    function submitBooking(callback) {
        const formData = {
            hotel_id: selectedHotel.id, // Added missing parameter
            room_id: selectedRoom.id,
            check_in: formatDateYMD(checkInDate),
            check_out: formatDateYMD(checkOutDate),
            adults: adultsCount,
            children: childrenCount,
            total_price: calculateTotalAmount() // Changed parameter name
        };

        $.ajax({
            url: '../api/booking/add',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response && response.success) {
                    // Return booking reference via callback
                    callback(response.booking_id || response.booking_ref);
                } else {
                    showToast('Error', response.message || 'Booking failed', 'error');
                    callback(null);
                }
            },
            error: function(xhr, status, error) {
                console.error('Booking submission error:', error);
                showToast('Error', 'Network error. Please try again.', 'error');
                callback(null);
            }
        });
    }

    // Function to calculate total amount - UPDATED
    function calculateTotalAmount() {
        if (!selectedRoom) return 0;
        
        // Calculate number of nights
        const timeDiff = checkOutDate.getTime() - checkInDate.getTime();
        const numberOfNights = Math.ceil(timeDiff / (1000 * 3600 * 24));
        
        if (numberOfNights <= 0) return 0;
        
        // Calculate total amount (room price * number of nights + taxes/fees)
        const roomPrice = selectedRoom.price || 0;
        const totalAmount = (roomPrice * numberOfNights) + 99; // Adding ₹99 as taxes/fees
        
        return totalAmount;
    }

    // Event listeners
    $('#prevMonth').on('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });

    $('#nextMonth').on('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });

    $('#guestSelector').on('click', function(e) {
        e.stopPropagation();
        $('#guestDropdown').toggle();
    });

    $('#decreaseAdults').on('click', function(e) {
        e.stopPropagation();
        if (adultsCount > 1) {
            adultsCount--;
            $('#adultCount').text(adultsCount);
        }
    });

    $('#increaseAdults').on('click', function(e) {
        e.stopPropagation();
        adultsCount++;
        $('#adultCount').text(adultsCount);
    });

    $('#decreaseChildren').on('click', function(e) {
        e.stopPropagation();
        if (childrenCount > 0) {
            childrenCount--;
            $('#childCount').text(childrenCount);
        }
    });

    $('#increaseChildren').on('click', function(e) {
        e.stopPropagation();
        childrenCount++;
        $('#childCount').text(childrenCount);
    });

    $('#applyGuests').on('click', function(e) {
        e.stopPropagation();
        updateGuestDisplay();
        $('#guestDropdown').hide();
    });

    // Destination select change handler
    $('#destinationSelect').on('change', function() {
        destination = $(this).val();
        updateContinueButton();
        renderHotels();
    });

    // Continue button click handler (Step 1 -> Step 2)
    $('#continueBtn').on('click', function(e) {
        e.preventDefault();
        
        // Validate all required fields
        if (!$('#destinationSelect').val()) {
            showToast('Error', "Please select a destination", 'error');
            return;
        }
        
        if (!checkInDate || !checkOutDate) {
            showToast('Error', "Please select both check-in and check-out dates", 'error');
            return;
        }
        
        // Save destination
        destination = $('#destinationSelect').val();
        $('#destinationDisplay').text(destination);
        
        // Update booking details display
        updateBookingDetailsDisplay();
        
        // Show step 2
        showStep(2);
        
        // Render hotels
        renderHotels();
    });

    // Back to dates button (Step 2 -> Step 1)
    $('#backToDatesBtn').on('click', function(e) {
        e.preventDefault();
        showStep(1);
    });

    // Continue to rooms button (Step 2 -> Step 3)
    $('#continueToRoomsBtn').on('click', function(e) {
        e.preventDefault();
        
        if (!selectedHotel) {
            showToast('Error', "Please select a hotel first", 'error');
            return;
        }
        
        // Fetch rooms for selected hotel
        fetchRooms(selectedHotel.id);
        
        // Update hotel details display
        $('#hotelNameDisplay').text(selectedHotel.name);
        $('#hotelLocationDisplay').text(selectedHotel.location);
        $('#hotelDescriptionDisplay').text(selectedHotel.description || `Experience luxury and comfort at ${selectedHotel.name}.`);
        
        // Render hotel details
        renderHotelImageSlider()
        renderHotelAmenities();
        renderHotelReviews();
        
        // Show step 3
        showStep(3);
        
        // Update booking summary
        updateBookingSummary();
    });

    // Back to hotels button (Step 3 -> Step 2)
    $('#backToHotelsBtn').on('click', function(e) {
        e.preventDefault();
        showStep(2);
    });

    // Continue to confirmation button (Step 3 -> Step 4)
    $('#continueToConfirmationBtn').on('click', function(e) {
        e.preventDefault();
        
        if (!selectedRoom) {
            showToast('Error', "Please select a room first", 'error');
            return;
        }
        
        // Update confirmation details
        updateConfirmationDetails();
        
        // Calculate total amount
        const totalAmount = calculateTotalAmount();
        
        if (totalAmount > 0) {
            // Submit booking first, then process payment
            submitBooking(function(bookingRef) {
                if (bookingRef) {
                    // Proceed with payment after successful booking submission
                    initializeRazorpayPayment(totalAmount, bookingRef);
                } else {
                    showToast('Error', 'Failed to create booking. Please try again.', 'error');
                }
            });
        } else {
            showToast('Error', "Invalid booking amount", 'error');
        }
    });

    // Back to home button (Step 4 -> Step 1)
    $('#backToHomeBtn').on('click', function(e) {
        e.preventDefault();
        
        // Reset all selections
        checkInDate = null;
        checkOutDate = null;
        adultsCount = 1;
        childrenCount = 0;
        destination = "";
        selectedHotel = null;
        selectedRoom = null;
        roomPrice = 0;
        nights = 0;
        rooms = [];
        
        // Reset UI elements
        $('#checkInDate').text("Select date");
        $('#checkOutDate').text("Select date");
        $('#guestDisplay').text("1 Adult, 0 Children");
        $('#adultCount').text("1");
        $('#childCount').text("0");
        $('#destinationSelect').val("");
        $('#selectedRoomDisplay').hide();
        $('#continueToRoomsBtn').prop('disabled', true)
            .addClass('btn-disabled')
            .removeClass('btn-primary');
        $('#continueToConfirmationBtn').prop('disabled', true);
        
        window.location.href = "booking";
        
        // Re-render calendar
        renderCalendar();
    });

    // Price range slider live update
    $('#priceRange').on('input', function() {
        $('#priceRangeValue').text($(this).val());
        renderHotels();
    });

    // Star rating and amenities filter changes
    $('.star-rating-checkbox, .amenities-checkbox').on('change', function() {
        renderHotels();
    });

    // Reset filters button
    $('#resetFilters').on('click', function() {
        // Reset all checkboxes
        $('.star-rating-checkbox, .amenities-checkbox').prop('checked', false);
        
        // Reset price range
        $('#priceRange').val(5000);
        $('#priceRangeValue').text('5000');
        
        // Re-render hotels
        renderHotels();
    });

    // Close guest dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#guestSelector, #guestDropdown').length) {
            $('#guestDropdown').hide();
        }
    });

    function initializeRazorpayPayment(amount, bookingRef) {
        // Ensure amount is at least 1
        if (amount < 1) {
            showToast('Error', 'Invalid payment amount', 'error');
            return;
        }
        
        $.ajax({
            url: "../api/booking/razorpay",
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
                console.error('Razorpay init error:', error);
            }
        });
    }
    function verifyPayment(paymentResponse, amount, bookingRef) {
        $.post("../api/booking/verify", {
            razorpay_payment_id: paymentResponse.razorpay_payment_id,
            razorpay_order_id: paymentResponse.razorpay_order_id,
            razorpay_signature: paymentResponse.razorpay_signature,
            amount: amount,
            booking_id: bookingRef
        }, function(response) {
            if (response.status === 'success') {
                showToast('Success', 'Payment successful! Booking confirmed.', 'success');
                // Update confirmation details and show step 4
                $('#confirmationBookingRef').text(bookingRef);
                updateConfirmationDetails();
                showStep(4);
            } else {
                showToast('Error', 'Payment verification failed: ' + (response.message || 'Unknown error'), 'error');
                // Optionally, allow user to retry payment
            }
        }).fail(function(xhr, status, error) {
            console.error('Verification error:', xhr.responseText);
            try {
                const response = JSON.parse(xhr.responseText);
                showToast('Error', 'Payment verification failed: ' + (response.message || 'Network error'), 'error');
            } catch (e) {
                showToast('Error', 'Payment verification failed. Please contact support.', 'error');
            }
        });
    }

    // Function to show toast notification
    function showToast(title, message, type = 'info') {
        // Remove any existing toasts first
        $('.toast-container').remove();
        
        // Create toast container if it doesn't exist
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
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        `;
        
        $('.toast-container').append(toastHtml);
        
        // Add appropriate styling based on type
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
        
        // Initialize and show the toast
        const toast = new bootstrap.Toast(toastElement[0], {
            autohide: true,
            delay: 5000
        });
        toast.show();
        
        // Remove toast from DOM after it's hidden
        toastElement.on('hidden.bs.toast', function () {
            $(this).remove();
        });
    }

    // Initialize
    renderCalendar();
    updateGuestDisplay();
    updateContinueButton();
    showStep(1);
    fetchHotels();
});