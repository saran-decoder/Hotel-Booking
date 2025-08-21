<?php
    include "../libs/load.php";

    if (!Session::get('email_verified') == 'verified') {
        header("Location: 2fa");
        exit;
    }

    if (
        !Session::get('session_token') || 
		Session::get('session_type') != 'admin' && 
		!Session::get('username') || 
		Session::get('email_verified') != 'verified'
    ) {
		header("Location: logout?logout");
		exit;
	}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Booking Management</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <?php include "temp/head.php" ?>

        <link rel="stylesheet" href="assets/css/booking.css">
        
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

                    <div class="p-4">
                        <!-- Step Bar -->
                        <div class="container my-5">
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
                                            <option value="Chennai">Chennai</option>
                                            <option value="Coimbatore">Coimbatore</option>
                                            <option value="Madurai">Madurai</option>
                                            <option value="Ooty">Ooty</option>
                                            <option value="Kodaikanal">Kodaikanal</option>
                                            <option value="Rameswaram">Rameswaram</option>
                                            <option value="Mahabalipuram">Mahabalipuram</option>
                                            <option value="Kanyakumari">Kanyakumari</option>
                                            <option value="Trichy">Trichy</option>
                                            <option value="Pondicherry">Pondicherry</option>
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
                                            <span>₹<span id="priceRangeValue">5000</span></span>
                                        </div>
                                        <input type="range" class="form-range price-range-slider" min="0" max="5000" id="priceRange">
                                        
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
                                        <div class="form-check mb-2">
                                            <input class="form-check-input amenities-checkbox" type="checkbox" id="temple">
                                            <label class="form-check-label" for="temple">Temple Nearby</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input amenities-checkbox" type="checkbox" id="beach">
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
                                            <img src="public/assets/images/chennai.jpg" class="d-block w-100" alt="Hotel Room">
                                        </div>
                                        <div class="carousel-item">
                                            <img src="public/assets/images/coimbatore.jpg" class="d-block w-100" alt="Hotel Lobby">
                                        </div>
                                        <div class="carousel-item">
                                            <img src="public/assets/images/ooty.jpg" class="d-block w-100" alt="Hotel Pool">
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

                                <div class="col-md-8">
                                    <div class="card p-4 mb-4">
                                        <h2 class="mb-3" id="hotelNameDisplay">Taj Coromandel Chennai</h2>
                                        <p class="text-muted"><i class="fas fa-star text-warning"></i> <strong>4.8</strong> (324 reviews) • <i class="fas fa-map-marker-alt"></i> <span id="hotelLocationDisplay">Nungambakkam, Chennai</span></p>
                                        
                                        <p id="hotelDescriptionDisplay">Experience the epitome of luxury in the heart of Chennai. Our heritage property combines traditional Tamil hospitality with modern amenities. Located near major business districts and cultural landmarks, we offer authentic Chettinad cuisine and a rejuvenating spa experience.</p>
                                        
                                        <h4 class="mt-4 mb-3">Amenities</h4>
                                        <div class="row" id="hotelAmenitiesDisplay">
                                            <div class="col-md-6 mb-2">
                                                <i class="fas fa-wifi me-2"></i> Free WiFi
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <i class="fas fa-swimming-pool me-2"></i> Outdoor Pool
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <i class="fas fa-utensils me-2"></i> 3 Restaurants
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <i class="fas fa-spa me-2"></i> Ayurvedic Spa
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <i class="fas fa-dumbbell me-2"></i> Fitness Center
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <i class="fas fa-parking me-2"></i> Free Parking
                                            </div>
                                        </div>
                                    </div>

                                    <h3 class="mb-4">Select Your Room</h3>
                                    
                                    <!-- Room Cards -->
                                    <div id="roomSelectionContainer">
                                        <div class="card mb-3 room-card">
                                            <div class="row g-0">
                                                <div class="col-md-4">
                                                    <img src="public/assets/images/deluxe-room.jpg" class="img-fluid rounded-start" alt="Deluxe Room">
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Deluxe Room</h5>
                                                        <p class="card-text">Spacious room with traditional Tamil decor, king-size bed, and city views. Includes complimentary breakfast.</p>
                                                        <div class="amenities">
                                                            <span class="badge bg-light text-dark me-2"><i class="fas fa-wifi"></i> WiFi</span>
                                                            <span class="badge bg-light text-dark me-2"><i class="fas fa-tv"></i> TV</span>
                                                            <span class="badge bg-light text-dark me-2"><i class="fas fa-snowflake"></i> AC</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 d-flex flex-column justify-content-between p-3">
                                                    <div class="text-end">
                                                        <h5 class="mb-0">₹4,500</h5>
                                                        <small class="text-muted">per night</small>
                                                    </div>
                                                    <button class="btn btn-outline-primary select-room-btn">Select</button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="card mb-3 room-card">
                                            <div class="row g-0">
                                                <div class="col-md-4">
                                                    <img src="public/assets/images/executive-room.jpg" class="img-fluid rounded-start" alt="Executive Room">
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Executive Suite</h5>
                                                        <p class="card-text">Luxurious suite with separate living area, premium amenities, and panoramic city views. Butler service included.</p>
                                                        <div class="amenities">
                                                            <span class="badge bg-light text-dark me-2"><i class="fas fa-wifi"></i> WiFi</span>
                                                            <span class="badge bg-light text-dark me-2"><i class="fas fa-tv"></i> TV</span>
                                                            <span class="badge bg-light text-dark me-2"><i class="fas fa-snowflake"></i> AC</span>
                                                            <span class="badge bg-light text-dark me-2"><i class="fas fa-coffee"></i> Tea Maker</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 d-flex flex-column justify-content-between p-3">
                                                    <div class="text-end">
                                                        <h5 class="mb-0">₹7,200</h5>
                                                        <small class="text-muted">per night</small>
                                                    </div>
                                                    <button class="btn btn-outline-primary select-room-btn">Select</button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="card mb-3 room-card">
                                            <div class="row g-0">
                                                <div class="col-md-4">
                                                    <img src="public/assets/images/heritage-room.jpg" class="img-fluid rounded-start" alt="Heritage Room">
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Heritage Room</h5>
                                                        <p class="card-text">Experience traditional Tamil architecture with modern comforts. Features antique furniture and courtyard view.</p>
                                                        <div class="amenities">
                                                            <span class="badge bg-light text-dark me-2"><i class="fas fa-wifi"></i> WiFi</span>
                                                            <span class="badge bg-light text-dark me-2"><i class="fas fa-tv"></i> TV</span>
                                                            <span class="badge bg-light text-dark me-2"><i class="fas fa-snowflake"></i> AC</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 d-flex flex-column justify-content-between p-3">
                                                    <div class="text-end">
                                                        <h5 class="mb-0">₹5,800</h5>
                                                        <small class="text-muted">per night</small>
                                                    </div>
                                                    <button class="btn btn-outline-primary select-room-btn">Select</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <h3 class="mt-5 mb-4">Guest Reviews</h3>
                                    
                                    <div id="hotelReviewsContainer">
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <h5 class="mb-0">Excellent stay</h5>
                                                    <div>
                                                        <i class="fas fa-star text-warning"></i>
                                                        <i class="fas fa-star text-warning"></i>
                                                        <i class="fas fa-star text-warning"></i>
                                                        <i class="fas fa-star text-warning"></i>
                                                        <i class="fas fa-star text-warning"></i>
                                                    </div>
                                                </div>
                                                <p class="text-muted">By Rajesh from Bangalore • Stayed in June 2025</p>
                                                <p>The authentic Tamil hospitality was exceptional. The Chettinad restaurant served the best meals we had during our Tamil Nadu trip.</p>
                                            </div>
                                        </div>
                                        
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <h5 class="mb-0">Perfect location</h5>
                                                    <div>
                                                        <i class="fas fa-star text-warning"></i>
                                                        <i class="fas fa-star text-warning"></i>
                                                        <i class="fas fa-star text-warning"></i>
                                                        <i class="fas fa-star text-warning"></i>
                                                        <i class="fas fa-star-half-alt text-warning"></i>
                                                    </div>
                                                </div>
                                                <p class="text-muted">By Priya from Chennai • Stayed in May 2025</p>
                                                <p>Great hotel for both business and leisure. The spa's traditional Ayurvedic treatments were rejuvenating after long meetings.</p>
                                            </div>
                                        </div>
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
                                    <div class="card p-4 mt-4 sticky-sidebar">
                                        <h4 class="mb-3">Location</h4>
                                        
                                        <iframe
                                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3886.0080690943427!2d80.2407223153466!3d13.060900990787582!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a5265e8f1f1a1d9%3A0x3a5265e8f1f1a1d9!2sTaj%20Coromandel!5e0!3m2!1sen!2sin!4v1755236572589!5m2!1sen!2sin"
                                            style="border:0;"
                                            allowfullscreen=""
                                            loading="lazy"
                                            referrerpolicy="no-referrer-when-downgrade">
                                        </iframe>
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
                                            <svg xmlns="http://www.w3.org/2000/svg" width="86" height="86" viewBox="0 0 86 86" style="background: rgba(52, 199, 89, 0.10); border-radius: 4rem; padding: 1rem;" fill="none">
                                                <path d="M77.789 35.9274C79.4099 43.882 78.2547 52.1519 74.5162 59.3579C70.7777 66.5639 64.6818 72.2704 57.2451 75.5259C49.8084 78.7814 41.4803 79.389 33.6499 77.2474C25.8194 75.1058 18.9597 70.3445 14.2149 63.7575C9.47001 57.1704 7.12675 49.1558 7.57586 41.0501C8.02497 32.9445 11.2393 25.2378 16.6828 19.2153C22.1263 13.1927 29.47 9.21836 37.4892 7.95496C45.5083 6.69156 53.7182 8.21549 60.7498 12.2726" stroke="#34C759" stroke-width="4.25895" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M32.3545 39.4766L43.0018 50.1239L78.4929 14.6328" stroke="#34C759" stroke-width="4.25895" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                        <h2 class="mb-3">Booking Confirmed!</h2>
                                        <p class="text-muted mb-4">Thank you for choosing TNBooking.in. We've sent a confirmation email to your registered email address. Vanakkam!</p>
                                        
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
                    </div>
                </div>
            </div>
        </div>

        <?php include "temp/footer.php" ?>

        <script src="assets/js/booking.js"></script>
        
    </body>
</html>