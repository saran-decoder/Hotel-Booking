$(document).ready(function() {
    // Initialize variables
    const today = new Date();
    today.setHours(0, 0, 0, 0); // Set to beginning of day

    let currentDate = new Date();
    currentDate.setDate(1); // Set to first day of current month
    let checkInDate = null;
    let checkOutDate = null;
    let adultsCount = 1;
    let childrenCount = 0;
    let destination = "";
    let selectedHotel = null;
    let selectedRoom = null;
    let roomPrice = 0;
    let nights = 0;

    // Sample hotel data with images
    const hotels = [
        {
            id: 1,
            name: "Grand Plaza Hotel",
            rating: 5,
            reviews: 324,
            location: "Downtown, New York",
            price: 199,
            image: "https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80",
            amenities: ["wifi", "parking", "ac", "restaurant", "fitness"]
        },
        {
            id: 2,
            name: "Sunset Bay Resort",
            rating: 5,
            reviews: 186,
            location: "Beachfront, Miami",
            price: 299,
            image: "https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80",
            amenities: ["wifi", "pool", "parking", "ac", "restaurant"]
        },
        {
            id: 3,
            name: "City Comfort Inn",
            rating: 4,
            reviews: 452,
            location: "Midtown, Chicago",
            price: 129,
            image: "https://images.unsplash.com/photo-1596178065887-1198b6148b2b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80",
            amenities: ["wifi", "parking", "ac"]
        },
        {
            id: 4,
            name: "Mountain View Lodge",
            rating: 5,
            reviews: 128,
            location: "Aspen, Colorado",
            price: 349,
            image: "https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80",
            amenities: ["wifi", "parking", "ac", "restaurant"]
        },
        {
            id: 5,
            name: "Harbor View Hotel",
            rating: 5,
            reviews: 215,
            location: "Fisherman's Wharf, San Francisco",
            price: 229,
            image: "https://images.unsplash.com/photo-1564501049412-61c2a3083791?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1632&q=80",
            amenities: ["wifi", "parking", "ac", "fitness"]
        },
        {
            id: 6,
            name: "Riverside Boutique",
            rating: 5,
            reviews: 163,
            location: "Riverwalk, San Antonio",
            price: 179,
            image: "https://images.unsplash.com/photo-1568084680786-a84f91d1153c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1374&q=80",
            amenities: ["wifi", "ac", "restaurant"]
        }
    ];

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
                stars += i < hotel.rating ? '★' : '☆';
            }
            
            // Generate amenities badges
            let amenitiesHTML = '';
            hotel.amenities.forEach(amenity => {
                amenitiesHTML += `<span class="amenity-badge">${amenity}</span>`;
            });
            
            const hotelCard = $(`
                <div class="card hotel-card" data-hotel-id="${hotel.id}" ${isSelected ? 'style="border: 2px solid #0d6efd;"' : ''}>
                    <img src="${hotel.image}" class="hotel-img" alt="${hotel.name}">
                    <div class="card-body">
                        <h5 class="card-title">${hotel.name}</h5>
                        <div class="mb-2">
                            <span class="text-warning">${stars}</span>
                            <span class="text-muted ms-2">${hotel.reviews} reviews</span>
                        </div>
                        <p class="card-text text-muted">
                            <i class="fas fa-map-marker-alt me-2"></i>${hotel.location}
                        </p>
                        <div class="hotel-amenities">
                            ${amenitiesHTML}
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <h4 class="text-primary mb-0">₹${hotel.price}<small class="text-muted"> /night</small></h4>
                            </div>
                            <button class="btn ${isSelected ? 'btn-primary' : 'btn-outline-primary'} select-hotel-btn">
                                ${isSelected ? 'Selected' : 'Select'}
                            </button>
                        </div>
                    </div>
                </div>
            `);
            
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
                .text('Select');
            
            // Update the selected card and button
            $(this).closest('.hotel-card').css('border', '2px solid #0d6efd');
            $(this).removeClass('btn-outline-primary')
                .addClass('btn-primary')
                .text('Selected');
            
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
            $('#selectedRoomType').text(
                selectedRoom === 'deluxe' ? 'Deluxe King Room' : 
                selectedRoom === 'executive' ? 'Executive Suite' : 'Twin Room'
            );
            
            $('#selectedRoomPrice').text(`₹${roomPrice} / night`);
            $('#selectedRoomDisplay').show();
            
            const subtotal = roomPrice * nights;
            $('#roomSubtotal').text(`₹${subtotal}`);
            $('#bookingTotal').text(`₹${subtotal + 99}`);
            
            $('#continueToConfirmationBtn').prop('disabled', false);
        }
    }

    // Render room selection
    function renderRoomSelection() {
        if (!selectedHotel) return;
        
        $('#roomSelectionContainer').empty();
        
        // Sample room types
        const rooms = [
            {
                type: 'deluxe',
                name: 'Deluxe King Room',
                price: 199,
                description: 'Spacious room with a king-size bed, work desk, and city views. Perfect for couples or business travelers.',
                amenities: ['Free WiFi', 'Air Conditioning', 'TV', 'Mini Bar', 'Safe'],
                image: 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80'
            },
            {
                type: 'executive',
                name: 'Executive Suite',
                price: 299,
                description: 'Luxurious suite with separate living area, premium amenities, and access to the executive lounge.',
                amenities: ['Free WiFi', 'Air Conditioning', 'TV', 'Mini Bar', 'Safe', 'Lounge Access', 'Complimentary Breakfast'],
                image: 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80'
            },
            {
                type: 'twin',
                name: 'Twin Room',
                price: 179,
                description: 'Comfortable room with two single beds, ideal for friends or family traveling together.',
                amenities: ['Free WiFi', 'Air Conditioning', 'TV', 'Safe'],
                image: 'https://images.unsplash.com/photo-1566669437687-7040a6926753?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1374&q=80'
            }
        ];
        
        rooms.forEach(room => {
            // Generate amenities list
            let amenitiesHTML = room.amenities.map(amenity => `
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-check-circle amenity-icon"></i>
                    <span>${amenity}</span>
                </div>
            `).join('');
            
            const roomCard = $(`
                <div class="room-card" data-room-type="${room.type}">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="${room.image}" class="img-fluid rounded" alt="${room.name}">
                        </div>
                        <div class="col-md-5">
                            <h4 class="room-type">${room.name}</h4>
                            <p>${room.description}</p>
                            <div class="amenities-container">
                                ${amenitiesHTML}
                            </div>
                        </div>
                        <div class="col-md-3 text-end d-flex flex-column justify-content-between align-items-end">
                            <div class="mb-3">
                                <span class="room-price">₹${room.price}</span>
                                <span class="text-muted">/night</span>
                            </div>
                            <button class="btn btn-outline-primary select-room-btn" data-room="${room.type}">Select Room</button>
                        </div>
                    </div>
                </div>
            `);
            
            $('#roomSelectionContainer').append(roomCard);
        });
        
        // Add event listeners to room selection buttons
        $('.select-room-btn').on('click', function() {
            // Remove selected class from all rooms
            $('.room-card').removeClass('selected');
            
            // Add selected class to clicked room
            $(this).closest('.room-card').addClass('selected');
            
            // Update button text
            $('.select-room-btn').text('Select Room')
                .removeClass('btn-primary')
                .addClass('btn-outline-primary');
            
            $(this).text('Selected')
                .removeClass('btn-outline-primary')
                .addClass('btn-primary');
            
            // Set selected room
            selectedRoom = $(this).data('room');
            roomPrice = $(this).data('room') === 'deluxe' ? 199 : 
                    $(this).data('room') === 'executive' ? 299 : 179;
            
            // Update booking summary
            updateBookingSummary();
        });
    }

    // Render hotel reviews
    function renderHotelReviews() {
        if (!selectedHotel) return;
        
        $('#hotelReviewsContainer').empty();
        
        // Sample reviews
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
            },
            {
                author: 'Michael T.',
                date: '2025-03-10',
                rating: 5,
                comment: 'One of the best hotels I\'ve stayed at. The executive lounge was fantastic and the views from our room were breathtaking.'
            }
        ];
        
        reviews.forEach(review => {
            // Generate star rating
            let stars = '';
            for (let i = 0; i < 5; i++) {
                stars += i < review.rating ? '★' : '☆';
            }
            
            const reviewCard = $(`
                <div class="review-card">
                    <div class="d-flex justify-content-between">
                        <div class="review-author">${review.author}</div>
                        <div class="text-warning">${stars}</div>
                    </div>
                    <div class="review-date">${review.date}</div>
                    <p class="mt-2">${review.comment}</p>
                </div>
            `);
            
            $('#hotelReviewsContainer').append(reviewCard);
        });
    }

    // Render hotel amenities
    function renderHotelAmenities() {
        if (!selectedHotel) return;
        
        $('#hotelAmenitiesDisplay').empty();
        
        const amenities = [
            { icon: 'wifi', name: 'Free WiFi' },
            { icon: 'swimming-pool', name: 'Swimming Pool' },
            { icon: 'parking', name: 'Free Parking' },
            { icon: 'utensils', name: 'Restaurant' },
            { icon: 'dumbbell', name: 'Fitness Center' },
            { icon: 'concierge-bell', name: '24-Hour Front Desk' },
            { icon: 'cocktail', name: 'Bar/Lounge' },
            { icon: 'bus', name: 'Airport Shuttle' }
        ];
        
        amenities.forEach(amenity => {
            const col = $(`
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-${amenity.icon} me-3"></i>
                        <span>${amenity.name}</span>
                    </div>
                </div>
            `);
            
            $('#hotelAmenitiesDisplay').append(col);
        });
    }

    // Update confirmation details
    function updateConfirmationDetails() {
        $('#confirmationHotelName').text(selectedHotel.name);
        $('#confirmationDates').text(
            `${formatDate(checkInDate)} - ${formatDate(checkOutDate)} (${calculateNights()} nights)`
        );
        $('#confirmationRoomType').text(
            selectedRoom === 'deluxe' ? 'Deluxe King Room' : 
            selectedRoom === 'executive' ? 'Executive Suite' : 'Twin Room'
        );
        $('#confirmationGuests').text(
            `${adultsCount} Adult${adultsCount !== 1 ? 's' : ''}${childrenCount > 0 ? `, ${childrenCount} Child${childrenCount !== 1 ? 'ren' : ''}` : ''}`
        );
        
        const subtotal = roomPrice * nights;
        $('#confirmationTotal').text(`₹${subtotal + 99}`);
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
        updateContinueButton();
    });

    // Continue button click handler (Step 1 -> Step 2)
    $('#continueBtn').on('click', function(e) {
        e.preventDefault();
        
        // Validate all required fields
        if (!$('#destinationSelect').val()) {
            alert("Please select a destination");
            return;
        }
        
        if (!checkInDate || !checkOutDate) {
            alert("Please select both check-in and check-out dates");
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
            alert("Please select a hotel first");
            return;
        }
        
        // Update hotel details display
        $('#hotelNameDisplay').text(selectedHotel.name);
        $('#hotelLocationDisplay').text(selectedHotel.location);
        $('#hotelDescriptionDisplay').text(
            `Experience luxury and comfort at ${selectedHotel.name}. Our ${selectedHotel.rating}-star hotel offers exceptional service and world-class amenities.`
        );
        
        // Render room selection and other hotel details
        renderRoomSelection();
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
            alert("Please select a room first");
            return;
        }
        
        // Update confirmation details
        updateConfirmationDetails();
        
        // Show step 4
        showStep(4);
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
        
        // Reset UI elements
        $('#checkInDate').text("Select date");
        $('#checkOutDate').text("Select date");
        $('#guestDisplay').text("1 Adults, 0 Children");
        $('#adultCount').text("1");
        $('#childCount').text("0");
        $('#destinationSelect').val("");
        $('#selectedRoomDisplay').hide();
        $('#continueToRoomsBtn').prop('disabled', true)
            .addClass('btn-disabled')
            .removeClass('btn-primary');
        $('#continueToConfirmationBtn').prop('disabled', true);
        
        // Show step 1
        showStep(1);
        
        // Re-render calendar
        renderCalendar();
    });

    // Price range slider live update
    $('#priceRange').on('input', function() {
        $('#priceRangeValue').text($(this).val());
        renderHotels(); // Live filtering
    });

    // Star rating and amenities filter changes
    $('.star-rating-checkbox, .amenities-checkbox').on('change', function() {
        renderHotels(); // Live filtering
    });

    // Reset filters button
    $('#resetFilters').on('click', function() {
        // Reset all checkboxes
        $('.star-rating-checkbox, .amenities-checkbox').prop('checked', false);
        
        // Reset price range
        $('#priceRange').val(750);
        $('#priceRangeValue').text('750');
        
        // Re-render hotels
        renderHotels();
    });

    // Close guest dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#guestSelector, #guestDropdown').length) {
            $('#guestDropdown').hide();
        }
    });

    // Initialize
    renderCalendar();
    updateGuestDisplay();
    updateContinueButton();
    showStep(1);
});