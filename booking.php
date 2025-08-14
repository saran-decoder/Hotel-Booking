<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>TN.Booking.in - Find Your Perfect Stay</title>

        <?php include "temp/head.php" ?>

        <!-- Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
        <style>
            .progress-step {
                display: flex;
                justify-content: space-between;
                margin: 40px 0 20px;
                text-align: center;
                font-size: 14px;
            }

            .progress-step div {
                flex: 1;
                color: #bbb;
            }

            .progress-step .active {
                color: #0d6efd;
                font-weight: 600;
            }

            .progress-step .circle-number {
                display: inline-block;
                width: 24px;
                height: 24px;
                line-height: 24px;
                border-radius: 50%;
                background-color: #0d6efd;
                color: white;
                margin-bottom: 5px;
            }

            .form-box {
                background-color: #f0f0f0;
                border-radius: 6px;
                padding: 14px 18px;
                cursor: pointer;
            }

            .form-select {
                border: 1px solid #ddd;
            }

            .guest-box {
                background-color: #f1f1f1;
                padding: 12px 15px;
                border-radius: 6px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                color: #555;
                cursor: pointer;
            }

            .btn-disabled {
                background-color: #cfd2d6;
                color: white;
                pointer-events: none;
            }

            .btn-primary {
                background-color: #0d6efd;
                border-color: #0d6efd;
            }

            .small-label {
                font-size: 12px;
                color: #888;
            }

            .calendar-day {
                padding: 10px 0;
                border-radius: 6px;
                cursor: pointer;
            }

            .calendar-day:hover:not(.disabled) {
                background-color: #eee;
            }

            .calendar-day.today {
                font-weight: bold;
                background-color: #f0f7ff;
            }

            .calendar-day.selected {
                background-color: #0d6efd;
                color: white;
            }

            .calendar-day.disabled {
                color: #ccc;
                cursor: not-allowed;
                background-color: #f9f9f9;
            }

            .guest-dropdown {
                position: absolute;
                top: 90%;
                left: 0;
                width: 100%;
                background: white;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                padding: 15px;
                z-index: 1000;
                display: none;
            }

            .guest-counter {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 15px;
            }

            .counter-btn {
                width: 30px;
                height: 30px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: #f0f0f0;
                border: none;
                cursor: pointer;
            }

            .counter-value {
                font-weight: bold;
                min-width: 30px;
                text-align: center;
            }

            .calendar-container {
                background-color: white;
                border-radius: 8px;
                padding: 24px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            }

            .calendar-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 16px;
            }

            .calendar-days {
                display: grid;
                grid-template-columns: repeat(7, 1fr);
                text-align: center;
                color: #999;
                font-size: 14px;
                margin-bottom: 8px;
            }

            .calendar-dates {
                display: grid;
                grid-template-columns: repeat(7, 1fr);
                text-align: center;
                font-size: 16px;
                color: #333;
                gap: 4px;
            }

            .calendar-dates div {
                padding: 10px 0;
                border-radius: 6px;
            }

            .calendar-dates div:hover:not(.disabled) {
                background-color: #eee;
                cursor: pointer;
            }

            .calendar-dates .today {
                font-weight: bold;
            }

            /* Step 2 styles */
            .hotel-card {
                transition: transform 0.2s;
                height: 100%;
            }

            .hotel-card:hover {
                transform: translateY(-5px);
            }

            .price-range-slider {
                width: 100%;
                margin: 15px 0;
            }

            .star-rating-checkbox {
                margin-right: 8px;
            }

            .amenities-checkbox {
                margin-right: 8px;
            }

            .step-content {
                display: none;
            }

            .step-content.active {
                display: block;
            }

            .hotel-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: 20px;
            }

            .hotel-img {
                height: 200px;
                object-fit: cover;
                width: 100%;
                border-radius: 8px 8px 0 0;
            }

            .navigation-buttons {
                display: flex;
                justify-content: space-between;
                margin-top: 20px;
            }

            .hotel-amenities {
                display: flex;
                flex-wrap: wrap;
                gap: 5px;
                margin-top: 10px;
            }

            .amenity-badge {
                background-color: #f0f7ff;
                color: #0d6efd;
                padding: 3px 8px;
                border-radius: 12px;
                font-size: 12px;
            }

            /* New styles for Step 3 */
            .room-card {
                border: 1px solid #dee2e6;
                border-radius: 8px;
                padding: 20px;
                margin-bottom: 20px;
                transition: all 0.3s ease;
            }

            .room-card:hover {
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            }

            .room-card.selected {
                border: 2px solid #0d6efd;
                background-color: #f8f9fa;
            }

            .amenity-icon {
                width: 24px;
                height: 24px;
                margin-right: 8px;
                color: #0d6efd;
            }

            .review-card {
                background-color: #f8f9fa;
                border-radius: 8px;
                padding: 15px;
                margin-bottom: 15px;
            }

            .review-author {
                font-weight: bold;
                margin-bottom: 5px;
            }

            .review-date {
                color: #6c757d;
                font-size: 0.9rem;
            }

            .room-type {
                font-size: 1.2rem;
                font-weight: bold;
                margin-bottom: 10px;
            }

            .room-price {
                font-size: 1.5rem;
                font-weight: bold;
                color: #0d6efd;
            }

            .select-room-btn {
                width: 100%;
            }

            .selected-room {
                background-color: #e7f1ff;
                padding: 15px;
                border-radius: 8px;
                margin-bottom: 20px;
            }

            /* Add this to your existing styles */
            .carousel {
                margin-bottom: 30px;
                border-radius: 8px;
                overflow: hidden;
            }

            .carousel-item img {
                object-fit: cover;
                height: 100%;
                width: 100%;
            }

            .carousel-control-prev,
            .carousel-control-next {
                width: 5%;
            }

            .control-prev-icon,
            .control-next-icon {
                background-color: rgba(255, 255, 255, 0.80);
                border-radius: 50%;
                width: 40px;
                height: 40px;
                padding: 8px;
            }
        </style>
    </head>

    <body>
        <!-- Navbar -->
        <?php include "temp/header.php" ?>

        <!-- Step Bar -->
        <div class="container mt-4">
            <div class="progress-step">
                <div class="step active" data-step="1">
                    <span class="circle-number">1</span><br />
                    Select Dates
                </div>
                <div class="step" data-step="2">
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

        <!-- Step 1: Select Dates -->
        <div class="container mb-5 step-content active" id="step1">
            <div class="card p-4 shadow-sm mx-auto" style="max-width: 700px;">
                <h5 class="fw-bold mb-3">Select Your Stay Dates</h5>

                <!-- Destination -->
                <div class="bg-light p-3 rounded mb-4">
                    <label class="form-label">Destination</label>
                    <div class="input-group">
                        <i class="fas fa-map-marker-alt position-absolute align-self-center ms-3"></i>
                        <select class="form-select" id="destinationSelect">
                            <option value="" selected disabled>Select destination</option>
                            <option value="New York">New York</option>
                            <option value="London">London</option>
                            <option value="Paris">Paris</option>
                            <option value="Tokyo">Tokyo</option>
                            <option value="Dubai">Dubai</option>
                            <option value="Sydney">Sydney</option>
                        </select>
                    </div>
                </div>

                <!-- Calendar -->
                <div class="card mb-4 p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3 calendar-header">
                        <strong>Select Dates</strong>
                        <div>
                            <button class="btn" id="prevMonth"><i class="fas fa-chevron-left"></i></button>
                            <span class="mx-2" id="currentMonth">July 2025</span>
                            <button class="btn" id="nextMonth"><i class="fas fa-chevron-right"></i></button>
                        </div>
                    </div>
                    <div class="row text-center text-muted mb-2 calendar-days">
                        <div class="col p-1">Sun</div>
                        <div class="col p-1">Mon</div>
                        <div class="col p-1">Tue</div>
                        <div class="col p-1">Wed</div>
                        <div class="col p-1">Thu</div>
                        <div class="col p-1">Fri</div>
                        <div class="col p-1">Sat</div>
                    </div>
                    <div class="row text-center calendar-dates" id="calendarDates">
                        <!-- Calendar dates will be generated by JavaScript -->
                    </div>
                </div>

                <!-- Check-in and Check-out -->
                <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="form-box" id="checkInBox">
                            <div class="small-label">Check-in</div>
                            <div id="checkInDate">Select date</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-box" id="checkOutBox">
                            <div class="small-label">Check-out</div>
                            <div id="checkOutDate">Select date</div>
                        </div>
                    </div>
                </div>

                <!-- Guests -->
                <div class="mb-4">
                    <label class="form-label">Guests</label>
                    <div class="guest-box" id="guestSelector">
                        <span> <i class="fas fa-user me-2"></i><span id="guestDisplay">1 Adults, 0 Children</span> </span>
                        <span><i class="fas fa-chevron-right"></i></span>
                    </div>
                    <div class="guest-dropdown" id="guestDropdown">
                        <div class="guest-counter">
                            <div>Adults</div>
                            <div class="d-flex align-items-center">
                                <button class="counter-btn" id="decreaseAdults">-</button>
                                <span class="counter-value mx-2" id="adultCount">1</span>
                                <button class="counter-btn" id="increaseAdults">+</button>
                            </div>
                        </div>
                        <div class="guest-counter">
                            <div>Children</div>
                            <div class="d-flex align-items-center">
                                <button class="counter-btn" id="decreaseChildren">-</button>
                                <span class="counter-value mx-2" id="childCount">0</span>
                                <button class="counter-btn" id="increaseChildren">+</button>
                            </div>
                        </div>
                        <button class="btn btn-primary w-100 mt-2" id="applyGuests">Apply</button>
                    </div>
                </div>

                <!-- Continue Button -->
                <div class="navigation-buttons">
                    <button class="btn btn-outline-secondary" disabled>Back</button>
                    <button class="btn btn-primary" id="continueBtn">Continue to Hotels</button>
                </div>
            </div>
        </div>

        <!-- Step 2: Choose Hotel -->
        <div class="container mb-5 step-content" id="step2">
            <div class="row">
                <!-- Filters Column -->
                <div class="col-md-3">
                    <div class="card p-3 mb-3 sticky-sidebar">
                        <h5 class="fw-bold mb-3">Price Range</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>₹0</span>
                            <span>₹<span id="priceRangeValue">750</span></span>
                        </div>
                        <input type="range" class="form-range price-range-slider" min="0" max="750" id="priceRange">
                        
                        <h5 class="fw-bold mt-4 mb-3">Star Rating</h5>
                        <div class="form-check mb-2">
                            <input class="form-check-input star-rating-checkbox" type="checkbox" id="rating5">
                            <label class="form-check-label" for="rating5">★★★★★</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input star-rating-checkbox" type="checkbox" id="rating4">
                            <label class="form-check-label" for="rating4">★★★★</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input star-rating-checkbox" type="checkbox" id="rating3">
                            <label class="form-check-label" for="rating3">★★★</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input star-rating-checkbox" type="checkbox" id="rating1">
                            <label class="form-check-label" for="rating1">★</label>
                        </div>
                        
                        <h5 class="fw-bold mt-4 mb-3">Amenities</h5>
                        <div class="form-check mb-2">
                            <input class="form-check-input amenities-checkbox" type="checkbox" id="wifi">
                            <label class="form-check-label" for="wifi">WiFi</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input amenities-checkbox" type="checkbox" id="pool">
                            <label class="form-check-label" for="pool">Pool</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input amenities-checkbox" type="checkbox" id="parking">
                            <label class="form-check-label" for="parking">Parking</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input amenities-checkbox" type="checkbox" id="ac">
                            <label class="form-check-label" for="ac">Air Conditioning</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input amenities-checkbox" type="checkbox" id="restaurant">
                            <label class="form-check-label" for="restaurant">Restaurant</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input amenities-checkbox" type="checkbox" id="fitness">
                            <label class="form-check-label" for="fitness">Fitness Center</label>
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
                        <button class="btn btn-outline-secondary" id="backToDatesBtn">Back to Dates</button>
                        <button class="btn btn-primary" id="continueToRoomsBtn" disabled>Continue to Rooms</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3: Select Room -->
        <div class="container mb-5 step-content" id="step3">
            <div class="row">
                <!-- Carousel Section -->
                <div id="hotelCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                    <div class="carousel-inner rounded">
                        <div class="carousel-item active">
                            <img src="https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" 
                                class="d-block w-100" alt="Hotel Room">
                        </div>
                        <div class="carousel-item">
                            <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" 
                                class="d-block w-100" alt="Hotel Lobby">
                        </div>
                        <div class="carousel-item">
                            <img src="https://images.unsplash.com/photo-1596178065887-1198b6148b2b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" 
                                class="d-block w-100" alt="Hotel Pool">
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
                <script>
                    // Initialization for ES Users
                    import { Carousel, initMDB } from "mdb-ui-kit";
                    initMDB({ Carousel });
                </script>
                <div class="col-md-8">
                    <div class="card p-4 mb-4">
                        <h2 class="mb-3" id="hotelNameDisplay">Grand Plaza Hotel</h2>
                        <p class="text-muted"><i class="fas fa-star text-warning"></i> <strong>4.8</strong> (324 reviews) • <i class="fas fa-map-marker-alt"></i> <span id="hotelLocationDisplay">Downtown, New York</span></p>
                        
                        <p id="hotelDescriptionDisplay">Experience luxury and comfort in the heart of New York City. Our hotel offers spacious rooms with stunning views, exceptional service, and world-class amenities. Just steps away from major attractions, shopping, and dining.</p>
                        
                        <h4 class="mt-4 mb-3">Amenities</h4>
                        <div class="row" id="hotelAmenitiesDisplay">
                            <!-- Amenities will be populated by JavaScript -->
                        </div>
                    </div>

                    <h3 class="mb-4">Select Your Room</h3>
                    
                    <!-- Room Cards -->
                    <div id="roomSelectionContainer">
                        <!-- Rooms will be populated by JavaScript -->
                    </div>

                    <h3 class="mt-5 mb-4">Guest Reviews</h3>
                    
                    <div id="hotelReviewsContainer">
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
                                <span>Room (x nights)</span>
                                <span id="roomSubtotal">₹0</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Taxes & Fees</span>
                                <span>₹99</span>
                            </div>
                            <hr>
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
        <div class="container mb-5 step-content" id="step4">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card p-5 text-center">
                        <div class="mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="86" height="86" viewBox="0 0 86 86" fill="none">
                                <path d="M77.789 35.9274C79.4099 43.882 78.2547 52.1519 74.5162 59.3579C70.7777 66.5639 64.6818 72.2704 57.2451 75.5259C49.8084 78.7814 41.4803 79.389 33.6499 77.2474C25.8194 75.1058 18.9597 70.3445 14.2149 63.7575C9.47001 57.1704 7.12675 49.1558 7.57586 41.0501C8.02497 32.9445 11.2393 25.2378 16.6828 19.2153C22.1263 13.1927 29.47 9.21836 37.4892 7.95496C45.5083 6.69156 53.7182 8.21549 60.7498 12.2726" stroke="#34C759" stroke-width="4.25895" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M32.3545 39.4766L43.0018 50.1239L78.4929 14.6328" stroke="#34C759" stroke-width="4.25895" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <h2 class="mb-3">Booking Confirmed!</h2>
                        <p class="text-muted mb-4">Thank you for your reservation. We've sent a confirmation email to your registered email address.</p>
                        
                        <div class="card mb-4">
                            <div class="card-body text-start">
                                <h4 class="mb-3" id="confirmationHotelName">N/A</h4>
                                <p class="text-muted" id="confirmationDates">N/A</p>
                                <p class="text-muted" id="confirmationRoomType">N/A</p>
                                <p class="text-muted" id="confirmationGuests">N/A</p>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Total</span>
                                    <span id="confirmationTotal">₹1492</span>
                                </div>
                            </div>
                        </div>
                        
                        <button class="btn btn-primary w-100" id="backToHomeBtn">Back to Home</button>
                    </div>
                </div>
            </div>
        </div>

        <?php include "temp/footer.php" ?>

        <script>
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

            // DOM elements
            const calendarDates = document.getElementById("calendarDates");
            const currentMonthEl = document.getElementById("currentMonth");
            const prevMonthBtn = document.getElementById("prevMonth");
            const nextMonthBtn = document.getElementById("nextMonth");
            const checkInBox = document.getElementById("checkInBox");
            const checkOutBox = document.getElementById("checkOutBox");
            const checkInDateEl = document.getElementById("checkInDate");
            const checkOutDateEl = document.getElementById("checkOutDate");
            const guestSelector = document.getElementById("guestSelector");
            const guestDropdown = document.getElementById("guestDropdown");
            const guestDisplay = document.getElementById("guestDisplay");
            const adultCountEl = document.getElementById("adultCount");
            const childCountEl = document.getElementById("childCount");
            const decreaseAdults = document.getElementById("decreaseAdults");
            const increaseAdults = document.getElementById("increaseAdults");
            const decreaseChildren = document.getElementById("decreaseChildren");
            const increaseChildren = document.getElementById("increaseChildren");
            const applyGuests = document.getElementById("applyGuests");
            const continueBtn = document.getElementById("continueBtn");
            const destinationSelect = document.getElementById("destinationSelect");
            const bookingDetailsDisplay = document.getElementById("bookingDetailsDisplay");
            const destinationDisplay = document.getElementById("destinationDisplay");
            const hotelList = document.getElementById("hotelList");
            const resetFiltersBtn = document.getElementById("resetFilters");
            const backToDatesBtn = document.getElementById("backToDatesBtn");
            const continueToRoomsBtn = document.getElementById("continueToRoomsBtn");
            const priceRange = document.getElementById("priceRange");
            const priceRangeValue = document.getElementById("priceRangeValue");
            const backToHotelsBtn = document.getElementById("backToHotelsBtn");
            const continueToConfirmationBtn = document.getElementById("continueToConfirmationBtn");
            const backToHomeBtn = document.getElementById("backToHomeBtn");

            // Initialize calendar
            function renderCalendar() {
                calendarDates.innerHTML = "";

                // Set month and year display
                currentMonthEl.textContent = currentDate.toLocaleString("default", { month: "long", year: "numeric" });

                // Get first day of month and total days in month
                const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1).getDay();
                const daysInMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();

                // Add empty cells for days before the first day of the month
                for (let i = 0; i < firstDay; i++) {
                    const emptyCell = document.createElement("div");
                    emptyCell.className = "";
                    calendarDates.appendChild(emptyCell);
                }

                // Add cells for each day of the month
                for (let day = 1; day <= daysInMonth; day++) {
                    const dateCell = document.createElement("div");
                    dateCell.className = "calendar-day";
                    dateCell.textContent = day;

                    const cellDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);
                    
                    // Disable past dates
                    if (cellDate < today) {
                        dateCell.classList.add("disabled");
                    } else {
                        // Check if this is today
                        if (cellDate.getTime() === today.getTime()) {
                            dateCell.classList.add("today");
                        }

                        // Check if this date is selected
                        if (checkInDate && cellDate.getTime() === checkInDate.getTime()) {
                            dateCell.classList.add("selected");
                        } else if (checkOutDate && cellDate.getTime() === checkOutDate.getTime()) {
                            dateCell.classList.add("selected");
                        } else if (checkInDate && checkOutDate && cellDate > checkInDate && cellDate < checkOutDate) {
                            dateCell.style.backgroundColor = "#e6f0ff";
                        }

                        dateCell.addEventListener("click", () => selectDate(day));
                    }

                    calendarDates.appendChild(dateCell);
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
                    checkInDateEl.textContent = formatDate(checkInDate);
                    checkOutDateEl.textContent = "Select date";
                } else if (selectedDate > checkInDate) {
                    // Complete the range
                    checkOutDate = selectedDate;
                    checkOutDateEl.textContent = formatDate(checkOutDate);
                } else {
                    // Select earlier date as check-in (but not before today)
                    if (selectedDate >= today) {
                        checkInDate = selectedDate;
                        checkOutDate = null;
                        checkInDateEl.textContent = formatDate(checkInDate);
                        checkOutDateEl.textContent = "Select date";
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
                guestDisplay.textContent = displayText;
                updateContinueButton();
            }

            // Update booking details display
            function updateBookingDetailsDisplay() {
                if (checkInDate && checkOutDate) {
                    const checkInStr = formatDateYMD(checkInDate);
                    const checkOutStr = formatDateYMD(checkOutDate);
                    bookingDetailsDisplay.textContent = `${checkInStr} - ${checkOutStr} • ${adultsCount} Adults, ${childrenCount} Children`;
                }
            }

            // Update continue button state
            function updateContinueButton() {
                if (checkInDate && checkOutDate && destinationSelect.value) {
                    continueBtn.classList.remove("btn-disabled");
                    continueBtn.classList.add("btn-primary");
                    continueBtn.disabled = false;
                } else {
                    continueBtn.classList.add("btn-disabled");
                    continueBtn.classList.remove("btn-primary");
                    continueBtn.disabled = true;
                }
            }

            // Update step progress
            function updateStepProgress(activeStep) {
                const steps = document.querySelectorAll(".progress-step .step");
                
                steps.forEach((step, index) => {
                    if (index + 1 === activeStep) {
                        step.classList.add("active");
                    } else {
                        step.classList.remove("active");
                    }
                });
            }

            // Show specific step
            function showStep(stepNumber) {
                // Hide all steps
                document.querySelectorAll('.step-content').forEach(step => {
                    step.classList.remove('active');
                });
                
                // Show the selected step
                document.getElementById(`step${stepNumber}`).classList.add('active');
                
                // Update progress indicator
                updateStepProgress(stepNumber);
            }

            // Filter hotels based on selected filters
            function filterHotels() {
                const maxPrice = parseInt(priceRange.value);
                const selectedRatings = [];
                const selectedAmenities = [];
                
                // Get selected star ratings
                document.querySelectorAll('.star-rating-checkbox:checked').forEach(checkbox => {
                    selectedRatings.push(parseInt(checkbox.id.replace('rating', '')));
                });
                
                // Get selected amenities
                document.querySelectorAll('.amenities-checkbox:checked').forEach(checkbox => {
                    selectedAmenities.push(checkbox.id);
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
                hotelList.innerHTML = '';
                
                if (filteredHotels.length === 0) {
                    hotelList.innerHTML = '<div class="col-12 text-center py-5"><h5>No hotels match your filters</h5><p>Try adjusting your filters</p></div>';
                    return;
                }
                
                filteredHotels.forEach(hotel => {
                    const hotelCard = document.createElement('div');
                    hotelCard.className = 'card hotel-card';
                    hotelCard.dataset.hotelId = hotel.id;
                    
                    // Check if this is the currently selected hotel
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
                    
                    hotelCard.innerHTML = `
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
                    `;
                    
                    // Set border if selected
                    if (isSelected) {
                        hotelCard.style.border = '2px solid #0d6efd';
                    }
                    
                    hotelList.appendChild(hotelCard);
                });

                // Add event listeners to select hotel buttons
                document.querySelectorAll('.select-hotel-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const hotelId = parseInt(this.closest('.hotel-card').dataset.hotelId);
                        selectedHotel = hotels.find(hotel => hotel.id === hotelId);
                        
                        // Update all hotel cards and buttons
                        document.querySelectorAll('.hotel-card').forEach(card => {
                            card.style.border = '1px solid #dee2e6';
                            const btn = card.querySelector('.select-hotel-btn');
                            btn.classList.remove('btn-primary');
                            btn.classList.add('btn-outline-primary');
                            btn.textContent = 'Select';
                        });
                        
                        // Update the selected card and button
                        this.closest('.hotel-card').style.border = '2px solid #0d6efd';
                        this.classList.remove('btn-outline-primary');
                        this.classList.add('btn-primary');
                        this.textContent = 'Selected';
                        
                        // Enable continue to rooms button
                        continueToRoomsBtn.disabled = false;
                        continueToRoomsBtn.classList.remove('btn-disabled');
                        continueToRoomsBtn.classList.add('btn-primary');
                    });
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
                    document.getElementById('bookingDatesSummary').textContent = 
                        `${formatDate(checkInDate)} - ${formatDate(checkOutDate)} (${calculateNights()} nights)`;
                }
                
                document.getElementById('bookingGuestsSummary').textContent = 
                    `${adultsCount} Adult${adultsCount !== 1 ? 's' : ''}${childrenCount > 0 ? `, ${childrenCount} Child${childrenCount !== 1 ? 'ren' : ''}` : ''}`;
                
                if (selectedRoom) {
                    document.getElementById('selectedRoomType').textContent = 
                        selectedRoom === 'deluxe' ? 'Deluxe King Room' : 
                        selectedRoom === 'executive' ? 'Executive Suite' : 'Twin Room';
                    
                    document.getElementById('selectedRoomPrice').textContent = 
                        `₹${roomPrice} / night`;
                    
                    document.getElementById('selectedRoomDisplay').style.display = 'block';
                    
                    const subtotal = roomPrice * nights;
                    document.getElementById('roomSubtotal').textContent = `₹${subtotal}`;
                    document.getElementById('bookingTotal').textContent = `₹${subtotal + 99}`;
                    
                    document.getElementById('continueToConfirmationBtn').disabled = false;
                }
            }

            // Render room selection
            function renderRoomSelection() {
                if (!selectedHotel) return;
                
                const roomSelectionContainer = document.getElementById('roomSelectionContainer');
                roomSelectionContainer.innerHTML = '';
                
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
                    const roomCard = document.createElement('div');
                    roomCard.className = 'room-card';
                    roomCard.dataset.roomType = room.type;
                    
                    // Generate amenities list
                    let amenitiesHTML = room.amenities.map(amenity => `
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-check-circle amenity-icon"></i>
                            <span>${amenity}</span>
                        </div>
                    `).join('');
                    
                    roomCard.innerHTML = `
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
                            <div class="col-md-3 text-end">
                                <div class="mb-3">
                                    <span class="room-price">₹${room.price}</span>
                                    <span class="text-muted">/night</span>
                                </div>
                                <button class="btn btn-outline-primary select-room-btn" data-room="${room.type}">Select Room</button>
                            </div>
                        </div>
                    `;
                    
                    roomSelectionContainer.appendChild(roomCard);
                });
                
                // Add event listeners to room selection buttons
                document.querySelectorAll('.select-room-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        // Remove selected class from all rooms
                        document.querySelectorAll('.room-card').forEach(card => {
                            card.classList.remove('selected');
                        });
                        
                        // Add selected class to clicked room
                        const roomCard = this.closest('.room-card');
                        roomCard.classList.add('selected');
                        
                        // Update button text
                        document.querySelectorAll('.select-room-btn').forEach(btn => {
                            btn.textContent = 'Select Room';
                            btn.classList.remove('btn-primary');
                            btn.classList.add('btn-outline-primary');
                        });
                        
                        this.textContent = 'Selected';
                        this.classList.remove('btn-outline-primary');
                        this.classList.add('btn-primary');
                        
                        // Set selected room
                        selectedRoom = this.dataset.room;
                        roomPrice = this.dataset.room === 'deluxe' ? 199 : 
                                this.dataset.room === 'executive' ? 299 : 179;
                        
                        // Update booking summary
                        updateBookingSummary();
                    });
                });
            }

            // Render hotel reviews
            function renderHotelReviews() {
                if (!selectedHotel) return;
                
                const reviewsContainer = document.getElementById('hotelReviewsContainer');
                reviewsContainer.innerHTML = '';
                
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
                    const reviewCard = document.createElement('div');
                    reviewCard.className = 'review-card';
                    
                    // Generate star rating
                    let stars = '';
                    for (let i = 0; i < 5; i++) {
                        stars += i < review.rating ? '★' : '☆';
                    }
                    
                    reviewCard.innerHTML = `
                        <div class="d-flex justify-content-between">
                            <div class="review-author">${review.author}</div>
                            <div class="text-warning">${stars}</div>
                        </div>
                        <div class="review-date">${review.date}</div>
                        <p class="mt-2">${review.comment}</p>
                    `;
                    
                    reviewsContainer.appendChild(reviewCard);
                });
            }

            // Render hotel amenities
            function renderHotelAmenities() {
                if (!selectedHotel) return;
                
                const amenitiesContainer = document.getElementById('hotelAmenitiesDisplay');
                amenitiesContainer.innerHTML = '';
                
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
                    const col = document.createElement('div');
                    col.className = 'col-md-6 mb-3';
                    
                    col.innerHTML = `
                        <div class="d-flex align-items-center">
                            <i class="fas fa-${amenity.icon} me-3"></i>
                            <span>${amenity.name}</span>
                        </div>
                    `;
                    
                    amenitiesContainer.appendChild(col);
                });
            }

            // Update confirmation details
            function updateConfirmationDetails() {
                document.getElementById('confirmationHotelName').textContent = selectedHotel.name;
                document.getElementById('confirmationDates').textContent = 
                    `${formatDate(checkInDate)} - ${formatDate(checkOutDate)} (${calculateNights()} nights)`;
                document.getElementById('confirmationRoomType').textContent = 
                    selectedRoom === 'deluxe' ? 'Deluxe King Room' : 
                    selectedRoom === 'executive' ? 'Executive Suite' : 'Twin Room';
                document.getElementById('confirmationGuests').textContent = 
                    `${adultsCount} Adult${adultsCount !== 1 ? 's' : ''}${childrenCount > 0 ? `, ${childrenCount} Child${childrenCount !== 1 ? 'ren' : ''}` : ''}`;
                
                const subtotal = roomPrice * nights;
                document.getElementById('confirmationTotal').textContent = `₹${subtotal + 99}`;
            }

            // Event listeners
            prevMonthBtn.addEventListener("click", () => {
                currentDate.setMonth(currentDate.getMonth() - 1);
                renderCalendar();
            });

            nextMonthBtn.addEventListener("click", () => {
                currentDate.setMonth(currentDate.getMonth() + 1);
                renderCalendar();
            });

            guestSelector.addEventListener("click", (e) => {
                e.stopPropagation();
                guestDropdown.style.display = guestDropdown.style.display === "block" ? "none" : "block";
            });

            decreaseAdults.addEventListener("click", (e) => {
                e.stopPropagation();
                if (adultsCount > 1) {
                    adultsCount--;
                    adultCountEl.textContent = adultsCount;
                }
            });

            increaseAdults.addEventListener("click", (e) => {
                e.stopPropagation();
                adultsCount++;
                adultCountEl.textContent = adultsCount;
            });

            decreaseChildren.addEventListener("click", (e) => {
                e.stopPropagation();
                if (childrenCount > 0) {
                    childrenCount--;
                    childCountEl.textContent = childrenCount;
                }
            });

            increaseChildren.addEventListener("click", (e) => {
                e.stopPropagation();
                childrenCount++;
                childCountEl.textContent = childrenCount;
            });

            applyGuests.addEventListener("click", (e) => {
                e.stopPropagation();
                updateGuestDisplay();
                guestDropdown.style.display = "none";
            });

            // Destination select change handler
            destinationSelect.addEventListener("change", () => {
                updateContinueButton();
            });

            // Continue button click handler (Step 1 -> Step 2)
            continueBtn.addEventListener("click", (e) => {
                e.preventDefault();
                
                // Validate all required fields
                if (!destinationSelect.value) {
                    alert("Please select a destination");
                    return;
                }
                
                if (!checkInDate || !checkOutDate) {
                    alert("Please select both check-in and check-out dates");
                    return;
                }
                
                // Save destination
                destination = destinationSelect.value;
                destinationDisplay.textContent = destination;
                
                // Update booking details display
                updateBookingDetailsDisplay();
                
                // Show step 2
                showStep(2);
                
                // Render hotels
                renderHotels();
            });

            // Back to dates button (Step 2 -> Step 1)
            backToDatesBtn.addEventListener("click", (e) => {
                e.preventDefault();
                showStep(1);
            });

            // Continue to rooms button (Step 2 -> Step 3)
            continueToRoomsBtn.addEventListener("click", (e) => {
                e.preventDefault();
                
                if (!selectedHotel) {
                    alert("Please select a hotel first");
                    return;
                }
                
                // Update hotel details display
                document.getElementById('hotelNameDisplay').textContent = selectedHotel.name;
                document.getElementById('hotelLocationDisplay').textContent = selectedHotel.location;
                document.getElementById('hotelDescriptionDisplay').textContent = 
                    `Experience luxury and comfort at ${selectedHotel.name}. Our ${selectedHotel.rating}-star hotel offers exceptional service and world-class amenities.`;
                
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
            backToHotelsBtn.addEventListener("click", (e) => {
                e.preventDefault();
                showStep(2);
            });

            // Continue to confirmation button (Step 3 -> Step 4)
            continueToConfirmationBtn.addEventListener("click", (e) => {
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
            backToHomeBtn.addEventListener("click", (e) => {
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
                checkInDateEl.textContent = "Select date";
                checkOutDateEl.textContent = "Select date";
                guestDisplay.textContent = "1 Adults, 0 Children";
                adultCountEl.textContent = "1";
                childCountEl.textContent = "0";
                destinationSelect.value = "";
                document.getElementById('selectedRoomDisplay').style.display = 'none';
                continueToRoomsBtn.disabled = true;
                continueToRoomsBtn.classList.add('btn-disabled');
                continueToRoomsBtn.classList.remove('btn-primary');
                continueToConfirmationBtn.disabled = true;
                
                // Show step 1
                showStep(1);
                
                // Re-render calendar
                renderCalendar();
            });

            // Price range slider live update
            priceRange.addEventListener("input", () => {
                priceRangeValue.textContent = priceRange.value;
                renderHotels(); // Live filtering
            });

            // Star rating and amenities filter changes
            document.querySelectorAll('.star-rating-checkbox, .amenities-checkbox').forEach(checkbox => {
                checkbox.addEventListener("change", () => {
                    renderHotels(); // Live filtering
                });
            });

            // Reset filters button
            resetFiltersBtn.addEventListener("click", () => {
                // Reset all checkboxes
                document.querySelectorAll('.star-rating-checkbox').forEach(checkbox => {
                    checkbox.checked = false;
                });
                document.querySelectorAll('.amenities-checkbox').forEach(checkbox => {
                    checkbox.checked = false;
                });
                
                
                // Reset price range
                priceRange.value = 750;
                priceRangeValue.textContent = true;
                
                // Re-render hotels
                renderHotels();
            });

            // Close guest dropdown when clicking outside
            document.addEventListener("click", (e) => {
                if (!guestSelector.contains(e.target) && !guestDropdown.contains(e.target)) {
                    guestDropdown.style.display = "none";
                }
            });

            // Initialize
            renderCalendar();
            updateGuestDisplay();
            updateContinueButton();
            showStep(1);
        </script>
    </body>
</html>