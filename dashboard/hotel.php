<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>TNBooking Dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <?php include "temp/head.php" ?>

        <style>
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
                /* background-color: #fff; */
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
                            <div class="col-md-12">
                                <div class="card p-4 mb-4">
                                    <h2 class="mb-3" id="hotelNameDisplay">Grand Plaza Hotel</h2>
                                    <p class="text-muted"><i class="fas fa-star text-warning"></i> <strong>4.8</strong> (324 reviews) • <i class="fas fa-map-marker-alt"></i> <span id="hotelLocationDisplay">Downtown, New York</span></p>
                                    
                                    <p id="hotelDescriptionDisplay">Experience luxury and comfort in the heart of New York City. Our hotel offers spacious rooms with stunning views, exceptional service, and world-class amenities. Just steps away from major attractions, shopping, and dining.</p>
                                    
                                    <h4 class="mt-4 mb-3">Amenities</h4>
                                    <div class="row" id="hotelAmenitiesDisplay">
                                        <!-- Amenities will be populated by JavaScript -->
                                        <div class="col-md-6 mb-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-wifi me-3"></i>
                                                <span>Free Wifi</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-swimming-pool me-3"></i>
                                                <span>Swimming Pool</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-parking me-3"></i>
                                                <span>Free Parking</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-utensils me-3"></i>
                                                <span>Restaurant</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-dumbbell me-3"></i>
                                                <span>Fitness Center</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-concierge-bell me-3"></i>
                                                <span>24-Hour Front Desk</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-cocktail me-3"></i>
                                                <span>Bar/Lounge</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-bus me-3"></i>
                                                <span>Airport Shuttle</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h3 class="mb-4">Rooms</h3>
                                
                                <!-- Room Cards -->
                                <div id="roomSelectionContainer">
                                    <!-- Rooms will be populated by JavaScript -->
                                    <div class="room-card card" data-room-type="Deluxe">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" class="img-fluid rounded" alt="Deluxe King Room">
                                            </div>
                                            <div class="col-md-5 mt-3">
                                                <h4 class="room-type">Deluxe King Room</h4>
                                                <p>Spacious room with a king-size bed, work desk, and city views. Perfect for couples or business travelers.</p>
                                                <div class="amenities-container">
                                                </div>
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fas fa-check-circle amenity-icon"></i>
                                                    <span>Free WiFi</span>
                                                </div>
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fas fa-check-circle amenity-icon"></i>
                                                    <span>Air Conditioning</span>
                                                </div>
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fas fa-check-circle amenity-icon"></i>
                                                    <span>TV</span>
                                                </div>
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fas fa-check-circle amenity-icon"></i>
                                                    <span>Mini Bar</span>
                                                </div>
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fas fa-check-circle amenity-icon"></i>
                                                    <span>Safe</span>
                                                </div>
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fas fa-check-circle amenity-icon"></i>
                                                    <span>Lounge Access</span>
                                                </div>
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fas fa-check-circle amenity-icon"></i>
                                                    <span>Complimentary Breakfast</span>
                                                </div>
                                            </div>
                                            <div class="col-md-3 text-end d-flex flex-column justify-content-between align-items-end">
                                                <div class="mb-3">
                                                    <span class="room-price">₹199</span>
                                                    <span class="text-muted">/night</span>
                                                </div>
                                                <button class="btn btn-outline-primary select-room-btn" data-room="deluxe">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h3 class="mt-5 mb-4">Reviews</h3>
                                
                                <div id="hotelReviewsContainer">
                                    <!-- Reviews will be populated by JavaScript -->
                                    <div class="review-card card">
                                        <div class="d-flex justify-content-between">
                                            <div class="review-author">John D.</div>
                                            <div class="text-warning">★★★★☆</div>
                                        </div>
                                        <div class="review-date">2025-05-15</div>
                                        <p class="mt-2">Excellent hotel with great service. The room was spacious and clean, and the location was perfect for exploring the city.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <?php include "temp/footer.php" ?>
        
    </body>
</html>