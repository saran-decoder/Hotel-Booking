<?php
    include "../libs/load.php";
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Booking Management</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <?php include "temp/head.php" ?>

        <!-- Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

        <!-- Flatpickr CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

        <!-- Flatpickr JS -->
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

        <link rel="stylesheet" href="public/assets/css/booking.css">

        <style>
            .hero {
                background: #dee2e6 !important;
            }
        </style>

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
                            <form class="search-form" id="homeSearchForm">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label for="Destination">Destination</label>
                                        <div class="input-group typeahead-container">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="23" viewBox="0 0 19 23" fill="none" style="position: fixed; align-self: center; margin-left: 10px; z-index: 2;">
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
                                            <input type="text" class="form-control" id="destinationInput" placeholder="City or District" autocomplete="off">
                                            <div class="typeahead-results" id="destinationResults"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="Check-in">Check-in</label>
                                        <div class="input-group">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="23" viewBox="0 0 21 23" fill="none" style="position: fixed; align-self: center; margin-left: 10px; z-index: 2;">
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
                                            <input type="text" class="form-control datepicker" id="checkInInput" placeholder="Check-in Date" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="Check-out">Check-out</label>
                                        <div class="input-group">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="23" viewBox="0 0 21 23" fill="none" style="position: fixed; align-self: center; margin-left: 10px; z-index: 2;">
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
                                            <input type="text" class="form-control datepicker" id="checkOutInput" placeholder="Check-out Date" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
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
                                        <button type="submit" class="btn btn-primary btn-lg w-100 rounded-5 mt-3 d-flex align-items-center justify-content-center" id="searchHotelsBtn">
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
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="progress-step-wizard">
                        <!-- Step 1: Search -->
                        <div class="step-wizard-item completed" data-step="1">
                            <div class="step-wizard-circle">
                                <span class="step-number">1</span>
                            </div>
                            <div class="step-wizard-label">Search Hotels</div>
                        </div>
                        
                        <!-- Step 2: Choose Hotel -->
                        <div class="step-wizard-item active" data-step="2">
                            <div class="step-wizard-circle">
                                <span class="step-number">2</span>
                            </div>
                            <div class="step-wizard-label">Choose Hotel</div>
                        </div>
                        
                        <!-- Step 3: Select Room -->
                        <div class="step-wizard-item" data-step="3">
                            <div class="step-wizard-circle">
                                <span class="step-number">3</span>
                            </div>
                            <div class="step-wizard-label">Select Room</div>
                        </div>
                        
                        <!-- Step 4: Confirm Booking -->
                        <div class="step-wizard-item" data-step="4">
                            <div class="step-wizard-circle">
                                <span class="step-number">4</span>
                            </div>
                            <div class="step-wizard-label">Confirm Booking</div>
                        </div>
                        
                        <!-- Progress line -->
                        <div class="progress-bar-background">
                            <div class="progress-bar-fill"></div>
                        </div>
                    </div>
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
                        
                        <!-- Date Change Section -->
                        <div class="mb-3">
                            <h6>Dates</h6>
                            <div class="date-change-container">
                                <div class="mb-2">
                                    <label class="form-label small">Check-in</label>
                                    <input type="date" class="form-control form-control-sm" id="summaryCheckIn" />
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small">Check-out</label>
                                    <input type="date" class="form-control form-control-sm" id="summaryCheckOut" />
                                </div>
                                <button class="btn btn-sm btn-outline-primary w-100" id="updateDatesBtn">Update Dates</button>
                            </div>
                            <p id="bookingDatesSummary" class="text-muted mt-2"></p>
                        </div>

                        <div id="selectedRoomDisplay" class="selected-room" style="display: none;">
                            <h5 id="selectedRoomType"></h5>
                            <p id="selectedRoomPrice" class="mb-0"></p>
                        </div>

                        <div class="mb-3">
                            <h6>Guests</h6>
                            <p id="bookingGuestsSummary" class="text-muted"></p>
                        </div>

                        <div class="mb-4">
                            <h6>Price Breakdown</h6>
                            <div class="d-flex justify-content-between">
                                <span>Room (<span id="nightsCount">0</span> nights)</span>
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

        <?php include "temp/footer.php" ?>

        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

        <script src="public/assets/js/booking.js"></script>
        
    </body>
</html>