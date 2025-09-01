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

    // Get hotel ID from URL parameter
    $hotelId = isset($_GET['id']) ? intval($_GET['id']) : 0;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>TNBooking Dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />

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
            
            .room-card {
                margin-bottom: 20px;
                border: 1px solid #e0e0e0;
                border-radius: 8px;
                overflow: hidden;
            }
            
            .room-card img {
                height: 250px;
                object-fit: cover;
            }
            
            .room-price {
                font-size: 24px;
                font-weight: bold;
                color: #007bff;
            }
            
            .amenity-icon {
                color: #28a745;
                margin-right: 8px;
            }
            
            .review-card {
                margin-bottom: 15px;
                padding: 15px;
                border: 1px solid #e0e0e0;
                border-radius: 8px;
            }
            
            .review-author {
                font-weight: 500;
            }
            
            .review-date {
                color: #6c757d;
            }
            
            .carousel-control-prev, .carousel-control-next {
                width: 50px;
            }
            
            .carousel-control-prev-icon, .carousel-control-next-icon {
                background-color: rgba(0, 0, 0, 0);
                border-radius: 50%;
                padding: 10px;
                width: auto;
                height: auto;
            }
            
            /* Room image carousel styling */
            .room-carousel {
                height: 250px;
            }
            
            .room-carousel .carousel-inner,
            .room-carousel .carousel-item,
            .room-carousel img {
                height: 100%;
                object-fit: cover;
            }
            
            .room-carousel .carousel-control-prev,
            .room-carousel .carousel-control-next {
                width: 30px;
                height: 30px;
                top: 50%;
                transform: translateY(-50%);
                background: rgba(0, 0, 0, 0.3);
                border-radius: 50%;
            }
            
            .room-carousel .carousel-control-prev {
                left: 10px;
            }
            
            .room-carousel .carousel-control-next {
                right: 10px;
            }
            
            .room-carousel .carousel-indicators {
                margin-bottom: 5px;
            }
            
            .room-carousel .carousel-indicators button {
                width: 8px;
                height: 8px;
                border-radius: 50%;
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
                    
                    <div class="container mb-5 step-content">
                        <div class="row">
                            <!-- Carousel Section -->
                            <div id="hotelCarousel" class="carousel slide my-4" data-bs-ride="carousel">
                                <div class="carousel-inner rounded" id="carouselImages">
                                    <div class="carousel-item active">
                                        <div class="text-center py-5">
                                            <div class="loading-spinner mx-auto mb-2"></div>
                                            <p class="text-muted">Loading images...</p>
                                        </div>
                                    </div>
                                </div>
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
                            </div>

                            <div class="col-md-12">
                                <!-- Hotel Info Card -->
                                <div class="card p-4 mb-4">
                                    <div class="d-flex justify-content-between align-item-center">
                                        <h2 class="mb-3" id="hotelNameDisplay">
                                            <div class="loading-spinner"></div>
                                            <span class="hotel-name-text">Loading hotel...</span>
                                        </h2>
                                        <a href="edit-hotel?id=<?= $hotelId ?>" type="button" id="editHotelBtn" style="display: none;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="26" viewBox="0 0 25 26" fill="none">
                                                <path d="M17.8847 4.03305L19.6758 2.24192C20.6651 1.2527 22.2689 1.25269 23.2581 2.24191C24.2473 3.23113 24.2473 4.83497 23.2581 5.82419L21.467 7.61533M17.8847 4.03305L11.197 10.7208C9.86132 12.0564 9.19348 12.7243 8.73872 13.5381C8.28397 14.3519 7.82643 16.2736 7.38892 18.1111C9.22647 17.6736 11.1481 17.2161 11.9619 16.7613C12.7758 16.3065 13.4436 15.6387 14.7793 14.303L21.467 7.61533M17.8847 4.03305L21.467 7.61533" stroke="#007BFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M24 13C24 18.4212 24 21.1317 22.3159 22.8159C20.6317 24.5 17.9212 24.5 12.5 24.5C7.07885 24.5 4.36827 24.5 2.68414 22.8159C1 21.1317 1 18.4212 1 13C1 7.57885 1 4.86827 2.68414 3.18414C4.36827 1.5 7.07885 1.5 12.5 1.5" stroke="#007BFF" stroke-width="2" stroke-linecap="round"/>
                                            </svg>
                                        </a>
                                    </div>
                                    <p class="text-muted" id="hotelRatingLocation"></p>
                                    
                                    <p id="hotelDescriptionDisplay"></p>
                                    
                                    <h4 class="mt-4 mb-3">Amenities</h4>
                                    <div class="row" id="hotelAmenitiesDisplay">
                                        <div class="col-12">
                                        </div>
                                    </div>
                                </div>

                                <!-- Rooms Section -->
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <h3 class="m-0">Rooms</h3>
                                    <a href="add-rooms?id=<?= $hotelId ?>" class="btn btn-primary d-flex align-items-center justify-content-between" id="addRoomBtn" style="display: none;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="me-2" viewBox="0 0 18 18" fill="none">
                                        <path d="M4.52368 9.23145H14.4316" stroke="white" stroke-width="2.12313" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M9.47754 4.27734V14.1853" stroke="white" stroke-width="2.12313" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg> Add Another Room
                                    </a>
                                </div>
                                
                                <!-- Room Cards -->
                                <div id="roomSelectionContainer">
                                    <div class="text-center py-4">
                                        <div class="loading-spinner mx-auto mb-2"></div>
                                        <p class="text-muted">Loading rooms...</p>
                                    </div>
                                </div>

                                <!-- Reviews Section -->
                                <h3 class="mt-5 mb-4">Reviews</h3>
                                
                                <div id="hotelReviewsContainer">
                                    <div class="text-center py-4">
                                        <div class="loading-spinner mx-auto mb-2"></div>
                                        <p class="text-muted">Loading reviews...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include "temp/footer.php" ?>

        <script>
            $(document).ready(function() {
                const hotelId = <?= $hotelId ?>;

                // Fetch hotel details from API
                function fetchHotelDetails() {
                    if (hotelId === 0) {
                        showErrorMessage('Invalid hotel ID');
                        return;
                    }

                    $.ajax({
                        url: '../api/hotel/info?id=' + hotelId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            console.log('API Response:', response); // Debug log
                            
                            if (response && response.length > 0) {
                                // Process the hotel data
                                processHotelData(response);
                            } else {
                                showErrorMessage('No hotel data found');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching hotel details:', error);
                            showErrorMessage('Error loading hotel details. Please try again.');
                        }
                    });
                }

                // Process hotel data
                function processHotelData(hotelData) {
                    console.log('Hotel Data:', hotelData); // Debug log
                    
                    // Group data by hotel and extract rooms
                    const hotels = {};
                    hotelData.forEach(item => {
                        if (!hotels[item.hotel_id]) {
                            hotels[item.hotel_id] = {
                                id: item.hotel_id,
                                name: item.hotel_name,
                                location_name: item.hotel_location_name,
                                hotel_coordinates: item.hotel_coordinates,
                                description: item.hotel_description,
                                amenities: item.hotel_amenities ? JSON.parse(item.hotel_amenities) : [],
                                images: item.hotel_images ? JSON.parse(item.hotel_images) : [],
                                rooms: []
                            };
                        }
                        
                        // Add room if it exists
                        if (item.room_id) {
                            hotels[item.hotel_id].rooms.push({
                                id: item.room_id,
                                room_type: item.room_type,
                                guests_allowed: item.guests_allowed,
                                description: item.room_description,
                                price_per_night: item.price_per_night,
                                amenities: item.room_amenities ? JSON.parse(item.room_amenities) : [],
                                images: item.room_images ? JSON.parse(item.room_images) : [],
                                status: item.room_status
                            });
                        }
                    });

                    // Get the current hotel
                    const currentHotel = hotels[hotelId];
                    
                    if (currentHotel) {
                        populateHotelInfo(currentHotel);
                        populateRooms(currentHotel.rooms);
                        populateReviews([]); // Empty reviews for now
                    } else {
                        showErrorMessage('Hotel not found in the data');
                    }
                }

                // Safe JSON parse function
                function safeJsonParse(str) {
                    try {
                        return JSON.parse(str);
                    } catch (e) {
                        console.error('JSON parse error:', e);
                        return [];
                    }
                }

                // Populate hotel information
                function populateHotelInfo(hotel) {
                    console.log('Populating hotel info:', hotel); // Debug log
                    
                    // Hotel name - Fix the selector to target the actual element
                    $('#hotelNameDisplay').html(hotel.name || 'Unknown Hotel');
                    
                    // Remove loading spinner from name
                    $('#hotelNameDisplay .loading-spinner').remove();
                    
                    // Rating and location
                    const locationHtml = hotel.location_name ? 
                        `<i class="fas fa-map-marker-alt"></i> ${hotel.location_name} <i class="fa-solid fa-link ms-2"></i> <a href='${hotel.hotel_coordinates}'>Location</a>` : 
                        '<span class="text-muted">No location specified</span>';
                    
                    $('#hotelRatingLocation').html(locationHtml);
                    $('#hotelRatingLocation .loading-spinner').remove();

                    // Description
                    const description = hotel.description || 'No description available.';
                    $('#hotelDescriptionDisplay').html(description);
                    $('#hotelDescriptionDisplay .loading-spinner').remove();

                    // Amenities
                    populateAmenities(hotel.amenities || []);

                    // Carousel images
                    populateCarousel(hotel.images || []);

                    // Show edit button
                    $('#editHotelBtn').show();
                    $('#addRoomBtn').show();
                }
 
                // Populate amenities
                function populateAmenities(amenities) {
                    const amenitiesContainer = $('#hotelAmenitiesDisplay');
                    amenitiesContainer.empty();

                    if (amenities.length === 0) {
                        amenitiesContainer.html('<div class="col-12 text-muted">No amenities listed</div>');
                        return;
                    }

                    // Map amenity keys to icons 
                    const amenityIcons = {
                        'wifi': 'fa-wifi',
                        'air_conditioning': 'fa-snowflake',
                        'swimming_pool': 'fa-swimming-pool',
                        'parking': 'fa-parking',
                        'restaurant': 'fa-utensils',
                        'fitness_center': 'fa-dumbbell',
                        'bar': 'fa-glass-martini-alt',
                        'spa': 'fa-spa',
                        'airport_shuttle': 'fa-bus',
                        'concierge': 'fa-concierge-bell',
                        'room_service': 'fa-bell',
                        'business_center': 'fa-briefcase',
                        'laundry': 'fa-tshirt'
                    };

                    // Map amenity keys to display names
                    const amenityNames = {
                        'wifi': 'Free WiFi',
                        'air_conditioning': 'Air Conditioning',
                        'swimming_pool': 'Swimming Pool',
                        'parking': 'Free Parking',
                        'restaurant': 'Restaurant',
                        'fitness_center': 'Fitness Center',
                        'bar': 'Bar/Lounge',
                        'spa': 'Spa',
                        'airport_shuttle': 'Airport Shuttle',
                        'concierge': '24-Hour Front Desk',
                        'room_service': 'Room Service',
                        'business_center': 'Business Center',
                        'laundry': 'Laundry Service'
                    };

                    amenities.forEach((amenity, index) => {
                        const iconClass = amenityIcons[amenity] || 'fa-ellipsis';
                        const displayName = amenityNames[amenity] || amenity.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                        
                        const amenityHtml = `
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas ${iconClass} me-3"></i>
                                    <span>${displayName}</span>
                                </div>
                            </div>
                        `;
                        
                        if (index % 2 === 0) {
                            amenitiesContainer.append('<div class="row">');
                        }
                        
                        amenitiesContainer.find('.row').last().append(amenityHtml);
                    });
                }

                // Populate carousel images
                function populateCarousel(images) {
                    const carouselContainer = $('#carouselImages');
                    carouselContainer.empty();

                    if (images.length === 0) {
                        // Use default image if no images available
                        carouselContainer.html(`
                            <div class="carousel-item active">
                                <img src="https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80" 
                                    class="d-block w-100" alt="Hotel Room" style="height: 400px; object-fit: cover;">
                            </div>
                        `);
                        return;
                    }

                    images.forEach((image, index) => {
                        const imageUrl = image.startsWith('http') ? image : `../${image}`;
                        const carouselItem = `
                            <div class="carousel-item ${index === 0 ? 'active' : ''}">
                                <img src="${imageUrl}" 
                                    class="d-block w-100" alt="Hotel Image ${index + 1}" style="height: 400px; object-fit: cover;">
                            </div>
                        `;
                        carouselContainer.append(carouselItem);
                    });
                }

                // Populate rooms
                function populateRooms(rooms) {
                    const roomsContainer = $('#roomSelectionContainer');
                    roomsContainer.empty();

                    if (rooms.length === 0) {
                        roomsContainer.html(`
                            <div class="text-center py-5">
                                <i class="fas fa-bed fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No rooms available</h5>
                                <p class="text-muted">Add rooms to get started</p>
                            </div>
                        `);
                        return;
                    }

                    rooms.forEach(room => {
                        const roomImages = room.images && room.images.length > 0 ? 
                            room.images : 
                            ['https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80'];
                        
                        // Generate carousel items
                        let carouselItems = '';
                        let carouselIndicators = '';
                        
                        roomImages.forEach((image, index) => {
                            const imageUrl = image.startsWith('http') ? image : `../${image}`;
                            const isActive = index === 0 ? 'active' : '';
                            
                            carouselItems += `
                                <div class="carousel-item ${isActive}">
                                    <img src="${imageUrl}" class="d-block w-100" alt="Room Image ${index + 1}">
                                </div>
                            `;
                            
                            carouselIndicators += `
                                <button type="button" data-bs-target="#roomCarousel-${room.id}" data-bs-slide-to="${index}" 
                                    class="${isActive}" aria-current="${isActive ? 'true' : 'false'}" aria-label="Slide ${index + 1}"></button>
                            `;
                        });
                        
                        const roomHtml = `
                            <div class="room-card card p-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div id="roomCarousel-${room.id}" class="carousel slide room-carousel" data-bs-ride="carousel">
                                            <div class="carousel-indicators">
                                                ${carouselIndicators}
                                            </div>
                                            <div class="carousel-inner rounded">
                                                ${carouselItems}
                                            </div>
                                            ${roomImages.length > 1 ? `
                                                <button class="carousel-control-prev" type="button" data-bs-target="#roomCarousel-${room.id}" data-bs-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Previous</span>
                                                </button>
                                                <button class="carousel-control-next" type="button" data-bs-target="#roomCarousel-${room.id}" data-bs-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Next</span>
                                                </button>
                                            ` : ''}
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <h4 class="room-type">${room.room_type || 'Room'} ${room.guests_allowed ? `(${room.guests_allowed} Guests)` : ''}</h4>
                                        <p>${room.description || 'Comfortable room with modern amenities.'}</p>
                                        <div class="amenities-container">
                                            ${populateRoomAmenities(room.amenities || [])}
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-end d-flex flex-column justify-content-between">
                                        <div class="mb-3">
                                            <span class="room-price">₹${room.price_per_night || '0'}</span>
                                            <span class="text-muted">/night</span>
                                        </div>
                                        <div>
                                            <a href="edit-rooms?id=${room.id}&hotel_id=${hotelId}" class="me-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="26" viewBox="0 0 25 26" fill="none">
                                                    <path d="M17.8847 4.03305L19.6758 2.24192C20.6651 1.2527 22.2689 1.25269 23.2581 2.24191C24.2473 3.23113 24.2473 4.83497 23.2581 5.82419L21.467 7.61533M17.8847 4.03305L11.197 10.7208C9.86132 12.0564 9.19348 12.7243 8.73872 13.5381C8.28397 14.3519 7.82643 16.2736 7.38892 18.1111C9.22647 17.6736 11.1481 17.2161 11.9619 16.7613C12.7758 16.3065 13.4436 15.6387 14.7793 14.303L21.467 7.61533M17.8847 4.03305L21.467 7.61533" stroke="#007BFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M24 13C24 18.4212 24 21.1317 22.3159 22.8159C20.6317 24.5 17.9212 24.5 12.5 24.5C7.07885 24.5 4.36827 24.5 2.68414 22.8159C1 21.1317 1 18.4212 1 13C1 7.57885 1 4.86827 2.68414 3.18414C4.36827 1.5 7.07885 1.5 12.5 1.5" stroke="#007BFF" stroke-width="2" stroke-linecap="round"/>
                                                </svg>
                                            </a>
                                            <button class="btn delete-room ms-2" data-room-id="${room.id}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="26" viewBox="0 0 24 26" fill="none">
                                                    <path d="M20.75 5.41699L20.027 17.1129C19.8423 20.1012 19.7499 21.5953 19.0009 22.6695C18.6306 23.2006 18.1538 23.6488 17.6008 23.9857C16.4825 24.667 14.9855 24.667 11.9915 24.667C8.99364 24.667 7.49469 24.667 6.37556 23.9844C5.82227 23.6469 5.34533 23.1979 4.97513 22.666C4.22635 21.59 4.13603 20.0938 3.95538 17.1013L3.25 5.41699" stroke="#F83D56" stroke-width="1.5" stroke-linecap="round"/>
                                                    <path d="M1.5 5.41634H22.5M16.7317 5.41634L15.9352 3.77336C15.4062 2.68197 15.1416 2.13628 14.6853 1.79595C14.5841 1.72046 14.4769 1.65331 14.3649 1.59516C13.8596 1.33301 13.2531 1.33301 12.0403 1.33301C10.797 1.33301 10.1753 1.33301 9.66163 1.60615C9.54778 1.66668 9.43915 1.73655 9.33684 1.81504C8.87525 2.16916 8.6174 2.73482 8.10171 3.86614L7.39508 5.41634" stroke="#F83D56" stroke-width="1.5" stroke-linecap="round"/>
                                                    <path d="M9.08337 18.25L9.08337 11.25" stroke="#F83D56" stroke-width="1.5" stroke-linecap="round"/>
                                                    <path d="M14.9166 18.25L14.9166 11.25" stroke="#F83D56" stroke-width="1.5" stroke-linecap="round"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        roomsContainer.append(roomHtml);
                    });

                    // Add delete room functionality
                    $('.delete-room').on('click', function() {
                        const roomId = $(this).data('room-id');
                        console.log("ID: " + roomId);
                        
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
                               $.post("../api/hotel/delete-room", { id: roomId }, function (response) {
                                    if (response || response.message === "Room deleted successfully") { 
                                        showToast("Success", response.message || "Room deleted successfully"); 
                                        fetchHotelDetails(); // Reload the page data
                                    } else { 
                                        showToast("Error", response.message || "Delete failed"); 
                                    }
                                }, "json");
                            }
                        });
                    });
                }

                // Populate room amenities
                function populateRoomAmenities(amenities) {
                    if (!amenities || amenities.length === 0) {
                        return '<div class="text-muted">No amenities listed</div>';
                    }

                    let amenitiesHtml = '';
                    amenities.forEach(amenity => {
                        const displayName = amenity.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                        amenitiesHtml += `
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-check-circle amenity-icon d-flex align-items-center justify-content-center"></i>
                                <span>${displayName}</span>
                            </div>
                        `;
                    });
                    return amenitiesHtml;
                }

                // Populate reviews (placeholder)
                function populateReviews(reviews) {
                    const reviewsContainer = $('#hotelReviewsContainer');
                    reviewsContainer.empty();

                    reviewsContainer.html(`
                        <div class="text-center py-4">
                            <i class="fas fa-comments fa-2x text-muted mb-3"></i>
                            <p class="text-muted">No reviews yet</p>
                        </div>
                    `);
                }

                // Show error message
                function showErrorMessage(message) {
                    // Clear all loading states
                    $('.loading-spinner').remove();
                    
                    $('#hotelNameDisplay').html(`<span class="text-danger">${message}</span>`);
                    $('#hotelRatingLocation').html('');
                    $('#hotelDescriptionDisplay').html('');
                    $('#hotelAmenitiesDisplay').html('');
                    $('#roomSelectionContainer').html(`<div class="text-center py-4 text-danger">${message}</div>`);
                    $('#hotelReviewsContainer').html('');
                }

                // Show toast notification
                function showToast(title, message, type = 'info') {
                    // Remove any existing toasts first
                    $('.toast-container').remove();
                    
                    // Create toast container
                    $('body').append('<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1090;"></div>');
                    
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
                    
                    // Add appropriate styling
                    const toastElement = $('#' + toastId);
                    if (type === 'success') {
                        toastElement.find('.toast-header').addClass('bg-success text-white');
                    } else if (type === 'error') {
                        toastElement.find('.toast-header').addClass('bg-danger text-white');
                    }
                    
                    // Initialize and show the toast
                    const toast = new bootstrap.Toast(toastElement[0], {
                        autohide: true,
                        delay: 3000
                    });
                    toast.show();
                    
                    // Remove toast from DOM after it's hidden
                    toastElement.on('hidden.bs.toast', function () {
                        $(this).remove();
                    });
                }

                // Initialize hotel details fetch
                fetchHotelDetails();
            });
        </script>
        
    </body>
</html>