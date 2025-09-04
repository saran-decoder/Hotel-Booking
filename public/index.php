<?php
    include "../libs/load.php";

    if (
        Session::get('session_token') &&
        Session::get('session_type')  == 'user' &&
        Session::get('username') &&
        !Session::get('sms_verified') == 'verified'
    ) {
        header("Location: 2fa");
        exit;
    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>TNBooking.in - Find Your Perfect Stay in Tamil Nadu</title>

        <?php include "temp/head.php" ?>

        <!-- Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

        <link rel="stylesheet" href="public/assets/css/booking.css" />
    </head>

    <body>
        <!-- Navbar -->
        <?php include "temp/header.php" ?>

        <!-- Step 1: Hero Section (Search Form) -->
        <section class="hero step-content align-content-center active" id="step1">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-12 p-4">
                        <div class="hero-content text-center">
                            <h1 class="mb-4 fw-bold">Find Your Perfect Stay</h1>
                            <p class="lead mb-5">Search deals on hotels, homes, and much more...</p>

                            <form class="search-form" id="homeSearchForm">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label for="Destination">Destination</label>
                                        <div class="input-group">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="23" viewBox="0 0 19 23" fill="none" style="position: fixed; align-self: center; margin-left: 10px;">
                                                <path
                                                    d="M4.5 17.5C2.67107 17.9117 1.5 18.5443 1.5 19.2537C1.5 20.4943 5.08172 21.5 9.5 21.5C13.9183 21.5 17.5 20.4943 17.5 19.2537C17.5 18.5443 16.3289 17.9117 14.5 17.5"
                                                    stroke="black"
                                                    stroke-opacity="0.5"
                                                    stroke-width="1.5"
                                                    stroke-linecap="round"
                                                />
                                                <path d="M12 8.5C12 9.88071 10.8807 11 9.5 11C8.11929 11 7 9.88071 7 8.5C7 7.11929 8.11929 6 9.5 6C10.8807 6 12 7.11929 12 8.5Z" stroke="black" stroke-opacity="0.5" stroke-width="1.5" />
                                                <path
                                                    d="M10.7574 16.9936C10.4201 17.3184 9.96932 17.5 9.50015 17.5C9.03099 17.5 8.58018 17.3184 8.2429 16.9936C5.1543 14.0008 1.01519 10.6575 3.03371 5.80373C4.1251 3.17932 6.74494 1.5 9.50015 1.5C12.2554 1.5 14.8752 3.17933 15.9666 5.80373C17.9826 10.6514 13.8536 14.0111 10.7574 16.9936Z"
                                                    stroke="black"
                                                    stroke-opacity="0.5"
                                                    stroke-width="1.5"
                                                />
                                            </svg>
                                            <select class="form-control" id="destinationSelect">
                                                <option value="" selected disabled>City or District</option>
                                                <!-- Options will be populated by JavaScript -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="Check-in">Check-in</label>
                                        <div class="input-group">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="23" viewBox="0 0 21 23" fill="none" style="position: fixed; align-self: center; margin-left: 10px;">
                                                <path d="M16.5 1.5V3.5M4.5 1.5V3.5" stroke="black" stroke-opacity="0.5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                <path
                                                    d="M10.4955 12.5H10.5045M10.4955 16.5H10.5045M14.491 12.5H14.5M6.5 12.5H6.50897M6.5 16.5H6.50897"
                                                    stroke="black"
                                                    stroke-opacity="0.5"
                                                    stroke-width="2"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                                <path d="M2 7.5H19" stroke="black" stroke-opacity="0.5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                <path
                                                    d="M1 11.7432C1 7.38594 1 5.20728 2.25212 3.85364C3.50424 2.5 5.51949 2.5 9.55 2.5H11.45C15.4805 2.5 17.4958 2.5 18.7479 3.85364C20 5.20728 20 7.38594 20 11.7432V12.2568C20 16.6141 20 18.7927 18.7479 20.1464C17.4958 21.5 15.4805 21.5 11.45 21.5H9.55C5.51949 21.5 3.50424 21.5 2.25212 20.1464C1 18.7927 1 16.6141 1 12.2568V11.7432Z"
                                                    stroke="black"
                                                    stroke-opacity="0.5"
                                                    stroke-width="1.5"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                                <path d="M1.5 7.5H19.5" stroke="black" stroke-opacity="0.5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <!-- <input type="date" class="form-control" id="homeCheckIn" placeholder="Check-in" /> -->
                                            <input id="checkInInput" type="date" class="form-control mb-2" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="Check-out">Check-out</label>
                                        <div class="input-group">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="23" viewBox="0 0 21 23" fill="none" style="position: fixed; align-self: center; margin-left: 10px;">
                                                <path d="M16.5 1.5V3.5M4.5 1.5V3.5" stroke="black" stroke-opacity="0.5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                <path
                                                    d="M10.4955 12.5H10.5045M10.4955 16.5H10.5045M14.491 12.5H14.5M6.5 12.5H6.50897M6.5 16.5H6.50897"
                                                    stroke="black"
                                                    stroke-opacity="0.5"
                                                    stroke-width="2"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                                <path d="M2 7.5H19" stroke="black" stroke-opacity="0.5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                <path
                                                    d="M1 11.7432C1 7.38594 1 5.20728 2.25212 3.85364C3.50424 2.5 5.51949 2.5 9.55 2.5H11.45C15.4805 2.5 17.4958 2.5 18.7479 3.85364C20 5.20728 20 7.38594 20 11.7432V12.2568C20 16.6141 20 18.7927 18.7479 20.1464C17.4958 21.5 15.4805 21.5 11.45 21.5H9.55C5.51949 21.5 3.50424 21.5 2.25212 20.1464C1 18.7927 1 16.6141 1 12.2568V11.7432Z"
                                                    stroke="black"
                                                    stroke-opacity="0.5"
                                                    stroke-width="1.5"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                                <path d="M1.5 7.5H19.5" stroke="black" stroke-opacity="0.5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <!-- <input type="date" class="form-control" id="homeCheckOut" placeholder="Check-out" /> -->
                                            <input id="checkOutInput" type="date" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="Guests">Guests</label>
                                        <div class="input-group">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" viewBox="0 0 24 20" fill="none" style="position: fixed; align-self: center; margin-left: 10px;">
                                                <path
                                                    d="M18.6161 17.5H19.1063C20.2561 17.5 21.1707 16.9761 21.9919 16.2436C24.078 14.3826 19.1741 12.5 17.5 12.5M15.5 2.56877C15.7271 2.52373 15.9629 2.5 16.2048 2.5C18.0247 2.5 19.5 3.84315 19.5 5.5C19.5 7.15685 18.0247 8.5 16.2048 8.5C15.9629 8.5 15.7271 8.47627 15.5 8.43123"
                                                    stroke="black"
                                                    stroke-opacity="0.5"
                                                    stroke-width="1.5"
                                                    stroke-linecap="round"
                                                />
                                                <path
                                                    d="M4.48131 13.6112C3.30234 14.243 0.211137 15.5331 2.09388 17.1474C3.01359 17.936 4.03791 18.5 5.32572 18.5H12.6743C13.9621 18.5 14.9864 17.936 15.9061 17.1474C17.7889 15.5331 14.6977 14.243 13.5187 13.6112C10.754 12.1296 7.24599 12.1296 4.48131 13.6112Z"
                                                    stroke="black"
                                                    stroke-opacity="0.5"
                                                    stroke-width="1.5"
                                                />
                                                <path d="M13 5C13 7.20914 11.2091 9 9 9C6.79086 9 5 7.20914 5 5C5 2.79086 6.79086 1 9 1C11.2091 1 13 2.79086 13 5Z" stroke="black" stroke-opacity="0.5" stroke-width="1.5" />
                                            </svg>
                                            <div class="dropdown-container position-relative">
                                                <div class="dropdown-button form-select d-flex align-items-center" id="guestSelector">
                                                    <span id="guestDisplay">1 Adult, 0 Children</span>
                                                </div>

                                                <div class="dropdown-panel shadow" id="guestDropdown" style="display: none;">
                                                    <div class="dropdown-row d-flex justify-content-between align-items-center mb-2">
                                                        <span>Adults</span>
                                                        <div class="counter-controls d-flex align-items-center gap-2">
                                                            <button type="button" class="btn btn-outline-dark rounded-circle btn-sm" id="decreaseAdults">−</button>
                                                            <span id="adultCount">1</span>
                                                            <button type="button" class="btn btn-outline-dark rounded-circle btn-sm" id="increaseAdults">+</button>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown-row d-flex justify-content-between align-items-center mb-2">
                                                        <span>Children</span>
                                                        <div class="counter-controls d-flex align-items-center gap-2">
                                                            <button type="button" class="btn btn-outline-dark rounded-circle btn-sm" id="decreaseChildren">−</button>
                                                            <span id="childCount">0</span>
                                                            <button type="button" class="btn btn-outline-dark rounded-circle btn-sm" id="increaseChildren">+</button>
                                                        </div>
                                                    </div>
                                                    <button class="btn btn-primary w-100 mt-2" type="button" id="applyGuests">Apply</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary btn-lg w-100 rounded-5 mt-5 d-flex align-items-center justify-content-center" id="searchHotelsBtn">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="me-2" viewBox="0 0 30 31" fill="none">
                                                <path d="M26.2502 27.1857L20.8252 21.7607" stroke="white" stroke-width="3.41637" stroke-linecap="round" stroke-linejoin="round" />
                                                <path
                                                    d="M13.7493 24.6855C19.2721 24.6855 23.7493 20.2084 23.7493 14.6855C23.7493 9.1627 19.2721 4.68555 13.7493 4.68555C8.22642 4.68555 3.74927 9.1627 3.74927 14.6855C3.74927 20.2084 8.22642 24.6855 13.7493 24.6855Z"
                                                    stroke="white"
                                                    stroke-width="3.41637"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                            </svg>
                                            Search Hotels
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Step Progress Bar -->
        <div class="container my-5" id="progressStepBar" style="display: none;">
            <div class="progress-step">
                <div class="step completed" data-step="1">
                    <span class="circle-number">1</span><br />
                    Search Hotels
                </div>
                <div class="step active" data-step="2">
                    <span class="circle-number">2</span><br />
                    Choose Hotel
                </div>
                <div class="step" data-step="3">
                    <span class="circle-number">3</span><br />
                    Select Room
                </div>
                <div class="step" data-step="4">
                    <span class="circle-number">4</span><br />
                    Confirm Booking
                </div>
            </div>
        </div>

        <!-- Step 2: Choose Hotel -->
        <div class="container mb-5 step-content align-content-center" id="step2" style="display: none;">
            <div class="row">
                <!-- Filters Column -->
                <div class="col-md-3">
                    <div class="card p-3 mb-3 sticky-sidebar">
                        <h5 class="fw-bold mb-3">Price Range</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>₹0</span>
                            <span>₹<span id="priceRangeValue">5000</span></span>
                        </div>
                        <input type="range" class="form-range price-range-slider" min="0" max="5000" id="priceRange" />

                        <div class="d-none">
                            <h5 class="fw-bold mt-4 mb-3">Star Rating</h5>
                            <div class="form-check mb-2">
                                <input class="form-check-input star-rating-checkbox" type="checkbox" id="rating5" />
                                <label class="form-check-label" for="rating5">★★★★★</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input star-rating-checkbox" type="checkbox" id="rating4" />
                                <label class="form-check-label" for="rating4">★★★★</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input star-rating-checkbox" type="checkbox" id="rating3" />
                                <label class="form-check-label" for="rating3">★★★</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input star-rating-checkbox" type="checkbox" id="rating1" />
                                <label class="form-check-label" for="rating1">★</label>
                            </div>
                        </div>

                        <h5 class="fw-bold mt-4 mb-3">Amenities</h5>
                        <div class="form-check mb-2">
                            <input class="form-check-input amenities-checkbox" type="checkbox" id="wifi" />
                            <label class="form-check-label" for="wifi">WiFi</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input amenities-checkbox" type="checkbox" id="swimming_pool" />
                            <label class="form-check-label" for="swimming_pool">Swimming Pool</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input amenities-checkbox" type="checkbox" id="parking" />
                            <label class="form-check-label" for="parking">Parking</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input amenities-checkbox" type="checkbox" id="air_conditioning" />
                            <label class="form-check-label" for="ac">Air Conditioning</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input amenities-checkbox" type="checkbox" id="restaurant" />
                            <label class="form-check-label" for="restaurant">Restaurant</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input amenities-checkbox" type="checkbox" id="fitness_center" />
                            <label class="form-check-label" for="fitness_center">Fitness Center</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input amenities-checkbox" type="checkbox" id="temple" />
                            <label class="form-check-label" for="temple">Temple Nearby</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input amenities-checkbox" type="checkbox" id="beach" />
                            <label class="form-check-label" for="beach">Beach Access</label>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button class="btn btn-outline-secondary" id="resetFilters">Reset</button>
                        </div>
                    </div>
                </div>

                <!-- Hotels Column -->
                <div class="col-md-9">
                    <div class="card p-3 mb-3">
                        <h4 class="fw-bold" id="destinationDisplay">N/A</h4>
                        <p class="text-muted m-0" id="bookingDetailsDisplay">N/A</p>
                    </div>

                    <!-- Hotel Cards -->
                    <div class="hotel-grid" id="hotelList">
                        <!-- Hotel cards will be populated by JavaScript -->
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="navigation-buttons mt-4">
                        <button class="btn btn-outline-secondary" id="backToSearchBtn">Back to Search</button>
                        <button class="btn btn-primary" id="continueToRoomsBtn" disabled>Continue to Rooms</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3: Select Room -->
        <div class="container mb-5 step-content" id="step3" style="display: none;">
            <div class="row">
                <!-- Carousel Section -->
                <div id="hotelCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                    <div class="carousel-inner rounded" id="hotelImageSlider">
                        <!-- Carousel items will be populated by JavaScript -->
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card p-4 mb-4">
                        <h2 class="mb-3" id="hotelNameDisplay">Hotel Name</h2>
                        <p class="text-muted">
                            <i class="fas fa-star text-warning d-none"></i>
                            <strong class="d-none">4.8</strong>
                            <span class="d-none"> (324 reviews) • </span>
                            <i class="fas fa-map-marker-alt"></i>
                            <span id="hotelLocationDisplay">Location</span>
                        </p>

                        <p id="hotelDescriptionDisplay">Hotel description will appear here.</p>

                        <h4 class="mt-4 mb-3">Amenities</h4>
                        <div class="row" id="hotelAmenitiesDisplay">
                            <!-- Amenities will be populated by JavaScript -->
                        </div>
                    </div>

                    <h3 class="mb-4">Select Your Room</h3>

                    <!-- Room Cards -->
                    <div id="roomSelectionContainer">
                        <!-- Room cards will be populated by JavaScript -->
                    </div>

                    <h3 class="mt-5 mb-4 d-none">Guest Reviews</h3>

                    <div id="hotelReviewsContainer" class="d-none">
                        <!-- Reviews will be populated by JavaScript -->
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card p-4 sticky-sidebar">
                        <h4 class="mb-3">Your Booking Summary</h4>

                        <div id="selectedRoomDisplay" class="selected-room" style="display: none;">
                            <h5 id="selectedRoomType"></h5>
                            <p id="selectedRoomPrice" class="mb-0"></p>
                        </div>

                        <div class="mb-3">
                            <h6>Dates</h6>
                            <p id="bookingDatesSummary" class="text-muted"></p>
                        </div>

                        <div class="mb-3">
                            <h6>Guests</h6>
                            <p id="bookingGuestsSummary" class="text-muted"></p>
                        </div>

                        <div class="mb-4">
                            <h6>Price Breakdown</h6>
                            <div class="d-flex justify-content-between">
                                <span>Room (total nights)</span>
                                <span id="roomSubtotal">₹0</span>
                            </div>
                            <hr />
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total</span>
                                <span id="bookingTotal">₹0</span>
                            </div>
                        </div>

                        <button class="btn btn-primary w-100" id="continueToConfirmationBtn" disabled>Continue to Confirmation</button>
                        <button class="btn btn-outline-secondary w-100 mt-2" id="backToHotelsBtn">Back to Hotels</button>
                    </div>
                    <div class="card p-4 mt-4 sticky-sidebar">
                        <h4 class="mb-0">Location <span id="hotelMap"></span></h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 4: Confirm Booking -->
        <div class="container mb-5 step-content" id="step4" style="display: none;">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card p-5 text-center">
                        <div class="mb-4">
                            <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                        </div>
                        <h2 class="mb-3">Booking Confirmed!</h2>
                        <p class="text-muted mb-4">Thank you for choosing TNBooking.in.</p>

                        <div class="card mb-4">
                            <div class="card-body text-start">
                                <h4 class="mb-3" id="confirmationHotelName">N/A</h4>
                                <p class="text-muted" id="confirmationDates">N/A</p>
                                <p class="text-muted" id="confirmationRoomType">N/A</p>
                                <p class="text-muted" id="confirmationGuests">N/A</p>
                                <hr />
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Total</span>
                                    <span id="confirmationTotal">₹0</span>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-primary w-100" id="backToHomeBtn">Back to Home</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Popular Destinations in Tamil Nadu -->
        <section class="py-5 my-5">
            <div class="container">
                <h2 class="text-center section-title">Popular Destinations</h2>

                <div class="position-relative">
                    <!-- Carousel Container -->
                    <div class="destinations-scroller">
                        <div class="destinations-track">
                            <!-- Card 1 - Chennai -->
                            <div class="destination-card">
                                <img src="public/assets/images/chennai.jpg" class="card-img-top" alt="Chennai" />
                                <div class="card-body p-4 pt-3">
                                    <h5 class="card-title fw-bold mb-2">Chennai</h5>
                                    <p class="d-flex align-items-center m-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="me-2" viewBox="0 0 28 28" fill="none">
                                            <g clip-path="url(#clip0_208_4668)">
                                                <path
                                                    d="M23.1642 11.747C23.1642 17.433 16.8565 23.3547 14.7383 25.1836C14.541 25.332 14.3008 25.4122 14.0539 25.4122C13.807 25.4122 13.5668 25.332 13.3695 25.1836C11.2514 23.3547 4.9436 17.433 4.9436 11.747C4.9436 9.33083 5.90344 7.01359 7.61195 5.30507C9.32047 3.59655 11.6377 2.63672 14.0539 2.63672C16.4701 2.63672 18.7874 3.59655 20.4959 5.30507C22.2044 7.01359 23.1642 9.33083 23.1642 11.747Z"
                                                    stroke="#4B5563"
                                                    stroke-width="3.41637"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                                <path
                                                    d="M14.0538 15.1628C15.9406 15.1628 17.4702 13.6333 17.4702 11.7464C17.4702 9.85964 15.9406 8.33008 14.0538 8.33008C12.167 8.33008 10.6375 9.85964 10.6375 11.7464C10.6375 13.6333 12.167 15.1628 14.0538 15.1628Z"
                                                    stroke="#4B5563"
                                                    stroke-width="3.41637"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_208_4668">
                                                    <rect width="27.331" height="27.331" fill="white" transform="translate(0.387939 0.358398)" />
                                                </clipPath>
                                            </defs>
                                        </svg>
                                        1,250 properties
                                    </p>
                                </div>
                            </div>

                            <!-- Card 2 - Madurai -->
                            <div class="destination-card">
                                <img src="public/assets/images/madurai.jpg" class="card-img-top" alt="Madurai" />
                                <div class="card-body p-4 pt-3">
                                    <h5 class="card-title fw-bold mb-2">Madurai</h5>
                                    <p class="d-flex align-items-center m-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="me-2" viewBox="0 0 28 28" fill="none">
                                            <g clip-path="url(#clip0_208_4668)">
                                                <path
                                                    d="M23.1642 11.747C23.1642 17.433 16.8565 23.3547 14.7383 25.1836C14.541 25.332 14.3008 25.4122 14.0539 25.4122C13.807 25.4122 13.5668 25.332 13.3695 25.1836C11.2514 23.3547 4.9436 17.433 4.9436 11.747C4.9436 9.33083 5.90344 7.01359 7.61195 5.30507C9.32047 3.59655 11.6377 2.63672 14.0539 2.63672C16.4701 2.63672 18.7874 3.59655 20.4959 5.30507C22.2044 7.01359 23.1642 9.33083 23.1642 11.747Z"
                                                    stroke="#4B5563"
                                                    stroke-width="3.41637"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                                <path
                                                    d="M14.0538 15.1628C15.9406 15.1628 17.4702 13.6333 17.4702 11.7464C17.4702 9.85964 15.9406 8.33008 14.0538 8.33008C12.167 8.33008 10.6375 9.85964 10.6375 11.7464C10.6375 13.6333 12.167 15.1628 14.0538 15.1628Z"
                                                    stroke="#4B5563"
                                                    stroke-width="3.41637"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_208_4668">
                                                    <rect width="27.331" height="27.331" fill="white" transform="translate(0.387939 0.358398)" />
                                                </clipPath>
                                            </defs>
                                        </svg>
                                        780 properties
                                    </p>
                                </div>
                            </div>

                            <!-- Card 3 - Coimbatore -->
                            <div class="destination-card">
                                <img src="public/assets/images/coimbatore.jpg" class="card-img-top" alt="Coimbatore" />
                                <div class="card-body p-4 pt-3">
                                    <h5 class="card-title fw-bold mb-2">Coimbatore</h5>
                                    <p class="d-flex align-items-center m-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="me-2" viewBox="0 0 28 28" fill="none">
                                            <g clip-path="url(#clip0_208_4668)">
                                                <path
                                                    d="M23.1642 11.747C23.1642 17.433 16.8565 23.3547 14.7383 25.1836C14.541 25.332 14.3008 25.4122 14.0539 25.4122C13.807 25.4122 13.5668 25.332 13.3695 25.1836C11.2514 23.3547 4.9436 17.433 4.9436 11.747C4.9436 9.33083 5.90344 7.01359 7.61195 5.30507C9.32047 3.59655 11.6377 2.63672 14.0539 2.63672C16.4701 2.63672 18.7874 3.59655 20.4959 5.30507C22.2044 7.01359 23.1642 9.33083 23.1642 11.747Z"
                                                    stroke="#4B5563"
                                                    stroke-width="3.41637"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                                <path
                                                    d="M14.0538 15.1628C15.9406 15.1628 17.4702 13.6333 17.4702 11.7464C17.4702 9.85964 15.9406 8.33008 14.0538 8.33008C12.167 8.33008 10.6375 9.85964 10.6375 11.7464C10.6375 13.6333 12.167 15.1628 14.0538 15.1628Z"
                                                    stroke="#4B5563"
                                                    stroke-width="3.41637"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_208_4668">
                                                    <rect width="27.331" height="27.331" fill="white" transform="translate(0.387939 0.358398)" />
                                                </clipPath>
                                            </defs>
                                        </svg>
                                        920 properties
                                    </p>
                                </div>
                            </div>

                            <!-- Card 4 - Ooty -->
                            <div class="destination-card">
                                <img src="public/assets/images/ooty.jpg" class="card-img-top" alt="Ooty" />
                                <div class="card-body p-4 pt-3">
                                    <h5 class="card-title fw-bold mb-2">Ooty</h5>
                                    <p class="d-flex align-items-center m-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="me-2" viewBox="0 0 28 28" fill="none">
                                            <g clip-path="url(#clip0_208_4668)">
                                                <path
                                                    d="M23.1642 11.747C23.1642 17.433 16.8565 23.3547 14.7383 25.1836C14.541 25.332 14.3008 25.4122 14.0539 25.4122C13.807 25.4122 13.5668 25.332 13.3695 25.1836C11.2514 23.3547 4.9436 17.433 4.9436 11.747C4.9436 9.33083 5.90344 7.01359 7.61195 5.30507C9.32047 3.59655 11.6377 2.63672 14.0539 2.63672C16.4701 2.63672 18.7874 3.59655 20.4959 5.30507C22.2044 7.01359 23.1642 9.33083 23.1642 11.747Z"
                                                    stroke="#4B5563"
                                                    stroke-width="3.41637"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                                <path
                                                    d="M14.0538 15.1628C15.9406 15.1628 17.4702 13.6333 17.4702 11.7464C17.4702 9.85964 15.9406 8.33008 14.0538 8.33008C12.167 8.33008 10.6375 9.85964 10.6375 11.7464C10.6375 13.6333 12.167 15.1628 14.0538 15.1628Z"
                                                    stroke="#4B5563"
                                                    stroke-width="3.41637"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_208_4668">
                                                    <rect width="27.331" height="27.331" fill="white" transform="translate(0.387939 0.358398)" />
                                                </clipPath>
                                            </defs>
                                        </svg>
                                        650 properties
                                    </p>
                                </div>
                            </div>

                            <!-- Card 5 - Kodaikanal -->
                            <div class="destination-card">
                                <img src="public/assets/images/kodaikanal.jpg" class="card-img-top" alt="Kodaikanal" />
                                <div class="card-body p-4 pt-3">
                                    <h5 class="card-title fw-bold mb-2">Kodaikanal</h5>
                                    <p class="d-flex align-items-center m-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="me-2" viewBox="0 0 28 28" fill="none">
                                            <g clip-path="url(#clip0_208_4668)">
                                                <path
                                                    d="M23.1642 11.747C23.1642 17.433 16.8565 23.3547 14.7383 25.1836C14.541 25.332 14.3008 25.4122 14.0539 25.4122C13.807 25.4122 13.5668 25.332 13.3695 25.1836C11.2514 23.3547 4.9436 17.433 4.9436 11.747C4.9436 9.33083 5.90344 7.01359 7.61195 5.30507C9.32047 3.59655 11.6377 2.63672 14.0539 2.63672C16.4701 2.63672 18.7874 3.59655 20.4959 5.30507C22.2044 7.01359 23.1642 9.33083 23.1642 11.747Z"
                                                    stroke="#4B5563"
                                                    stroke-width="3.41637"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                                <path
                                                    d="M14.0538 15.1628C15.9406 15.1628 17.4702 13.6333 17.4702 11.7464C17.4702 9.85964 15.9406 8.33008 14.0538 8.33008C12.167 8.33008 10.6375 9.85964 10.6375 11.7464C10.6375 13.6333 12.167 15.1628 14.0538 15.1628Z"
                                                    stroke="#4B5563"
                                                    stroke-width="3.41637"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_208_4668">
                                                    <rect width="27.331" height="27.331" fill="white" transform="translate(0.387939 0.358398)" />
                                                </clipPath>
                                            </defs>
                                        </svg>
                                        520 properties
                                    </p>
                                </div>
                            </div>

                            <!-- Card 6 - Rameswaram -->
                            <div class="destination-card">
                                <img src="public/assets/images/rameswaram.jpg" class="card-img-top" alt="Rameswaram" />
                                <div class="card-body p-4 pt-3">
                                    <h5 class="card-title fw-bold mb-2">Rameswaram</h5>
                                    <p class="d-flex align-items-center m-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="me-2" viewBox="0 0 28 28" fill="none">
                                            <g clip-path="url(#clip0_208_4668)">
                                                <path
                                                    d="M23.1642 11.747C23.1642 17.433 16.8565 23.3547 14.7383 25.1836C14.541 25.332 14.3008 25.4122 14.0539 25.4122C13.807 25.4122 13.5668 25.332 13.3695 25.1836C11.2514 23.3547 4.9436 17.433 4.9436 11.747C4.9436 9.33083 5.90344 7.01359 7.61195 5.30507C9.32047 3.59655 11.6377 2.63672 14.0539 2.63672C16.4701 2.63672 18.7874 3.59655 20.4959 5.30507C22.2044 7.01359 23.1642 9.33083 23.1642 11.747Z"
                                                    stroke="#4B5563"
                                                    stroke-width="3.41637"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                                <path
                                                    d="M14.0538 15.1628C15.9406 15.1628 17.4702 13.6333 17.4702 11.7464C17.4702 9.85964 15.9406 8.33008 14.0538 8.33008C12.167 8.33008 10.6375 9.85964 10.6375 11.7464C10.6375 13.6333 12.167 15.1628 14.0538 15.1628Z"
                                                    stroke="#4B5563"
                                                    stroke-width="3.41637"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_208_4668">
                                                    <rect width="27.331" height="27.331" fill="white" transform="translate(0.387939 0.358398)" />
                                                </clipPath>
                                            </defs>
                                        </svg>
                                        380 properties
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Arrows -->
                    <div style="position: absolute; top: -60px; right: 70px;">
                        <button class="scroller-nav scroller-prev d-block" style="position: absolute; right: 0;" aria-label="Previous">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button class="scroller-nav scroller-next d-block" style="position: absolute; left: 40px;" aria-label="Next">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Special Offers for Tamil Nadu -->
        <section class="special-offers">
            <div class="container">
                <h2 class="text-center section-title text-white">Tamil Nadu Specials</h2>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="p-4 bg-white rounded-4 shadow-sm text-center">
                            <p class="text-danger fw-bold">TAMIL NADU TOURISM</p>
                            <h5 class="text-dark">Heritage Stay Packages</h5>
                            <p class="text-muted">Experience Tamil Nadu's rich heritage with 25% off on heritage hotels and palaces.</p>
                            <a href="#" class="btn btn-primary">Explore Heritage</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-4 bg-white rounded-4 shadow-sm text-center">
                            <p class="text-success fw-bold">LOCAL SPECIAL</p>
                            <h5 class="text-dark">Monsoon Getaways</h5>
                            <p class="text-muted">Enjoy the monsoon in Tamil Nadu's hill stations with special discounts.</p>
                            <a href="#" class="btn btn-primary">Hill Station Deals</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Choose Us -->
        <section class="py-5 my-5">
            <div class="container">
                <h2 class="text-center section-title">Why Choose TNBooking.in</h2>

                <div class="row g-4 mt-4">
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-lightning-charge"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Tamil Nadu Experts</h4>
                            <p class="text-muted">Our team has in-depth knowledge of Tamil Nadu's hospitality landscape to recommend the best stays for you.</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Local Support</h4>
                            <p class="text-muted">24/7 customer support in Tamil and English to assist you throughout your stay in Tamil Nadu.</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 35 35" fill="none">
                                    <path
                                        d="M12.9687 17.6324L15.8379 20.5016L21.5763 14.7632M29.6359 9.00181C25.0998 9.2427 20.6535 7.67425 17.2725 4.64062C13.8915 7.67425 9.44516 9.2427 4.90911 9.00181C4.54405 10.4151 4.3599 11.8689 4.36109 13.3286C4.36109 21.3494 9.847 28.0906 17.2725 30.0015C24.698 28.0906 30.1839 21.3508 30.1839 13.3286C30.1839 11.8337 29.9931 10.3848 29.6359 9.00181Z"
                                        stroke="#007BFF"
                                        stroke-width="1.07595"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                            </div>
                            <h4 class="fw-bold mb-3">Exclusive TN Deals</h4>
                            <p class="text-muted">Special discounts and packages available only for Tamil Nadu properties through our platform.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <?php include "temp/footer.php" ?>

        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

        <script>
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
                    $(".progress-step .step").each(function (index) {
                        $(this).removeClass("active completed");
                        if (index + 1 < activeStep) {
                            $(this).addClass("completed");
                        } else if (index + 1 === activeStep) {
                            $(this).addClass("active");
                        }
                    });
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

                // =============== FORM VALIDATION ===============
                function validateSearchForm() {
                    const destination = $('#destinationSelect').val();
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
                        // amenities
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

                // =============== HOTEL SELECTION ===============
                function setupHotelSelection() {
                    $(document).on('click', '.select-hotel-btn', function () {
                        const hotelId = $(this).data('hotel-id');
                        selectedHotel = hotels.find(h => String(h.id) === String(hotelId));

                        if (!selectedHotel) {
                            console.error("Hotel not found for ID:", hotelId);
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
                        $('#continueToRoomsBtn').prop('disabled', false);

                        // Show map link
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

                    $('#roomSelectionContainer').html(
                        '<div class="col-12 text-center py-3"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Checking room availability...</p></div>'
                    );

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

                function checkRoomAvailability(roomId, checkIn, checkOut) {
                    console.log(roomId);
                    console.log(checkIn);
                    console.log(checkOut);
                    
                    
                    
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
                    // Remove existing toast container
                    $('.toast-container').remove();

                    // Create new toast container
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

                        destination = $('#destinationSelect').val();
                        checkInDate = new Date($('#checkInInput').val());
                        checkOutDate = new Date($('#checkOutInput').val());

                        // Update displays
                        $('#destinationDisplay').text($('#destinationSelect option:selected').text());
                        $('#bookingDetailsDisplay').text(
                            `${$('#checkInInput').val()} - ${$('#checkOutInput').val()} • ${adultsCount} Adults, ${childrenCount} Children`
                        );

                        // Show step 2
                        showStep(2);

                        // Render hotels
                        renderHotels();
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

                    // Continue to confirmation button
                    $('#continueToConfirmationBtn').on('click', function() {
                        if (!selectedRoom) {
                            showToast('Error', 'Please select a room first', 'error');
                            return;
                        }

                        updateConfirmationDetails();
                        showStep(4);
                    });

                    // Back to home button
                    $('#backToHomeBtn').on('click', function() {
                        // Reset everything
                        checkInDate = null;
                        checkOutDate = null;
                        adultsCount = 1;
                        childrenCount = 0;
                        destination = "";
                        selectedHotel = null;
                        selectedRoom = null;

                        // Reset form
                        $('#homeSearchForm')[0].reset();
                        $('#guestDisplay').text('1 Adult, 0 Children');
                        $('#adultCount').text('1');
                        $('#childCount').text('0');
                        $('#selectedRoomDisplay').hide();
                        $('#continueToRoomsBtn').prop('disabled', true);
                        $('#continueToConfirmationBtn').prop('disabled', true);

                        // Show step 1
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

                // =============== INITIALIZATION ===============
                function initialize() {
                    initializeDateInputs();
                    setupGuestSelector();
                    setupEventHandlers();
                    setupHotelSelection();
                    updateGuestDisplay();
                    showStep(1);
                    fetchHotels();
                }

                // Start everything
                initialize();
            });
        </script>
    </body>
</html>