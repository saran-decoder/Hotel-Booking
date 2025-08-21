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
        <meta charset="UTF-8" />
        <title>TNBooking Dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <?php include "temp/head.php" ?>

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
                                    <div class="d-flex justify-content-between align-item-center">
                                        <h2 class="mb-3" id="hotelNameDisplay">Grand Plaza Hotel</h2>
                                        <a href="edit-hotel" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="26" viewBox="0 0 25 26" fill="none">
                                            <path d="M17.8847 4.03305L19.6758 2.24192C20.6651 1.2527 22.2689 1.25269 23.2581 2.24191C24.2473 3.23113 24.2473 4.83497 23.2581 5.82419L21.467 7.61533M17.8847 4.03305L11.197 10.7208C9.86132 12.0564 9.19348 12.7243 8.73872 13.5381C8.28397 14.3519 7.82643 16.2736 7.38892 18.1111C9.22647 17.6736 11.1481 17.2161 11.9619 16.7613C12.7758 16.3065 13.4436 15.6387 14.7793 14.303L21.467 7.61533M17.8847 4.03305L21.467 7.61533" stroke="#007BFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M24 13C24 18.4212 24 21.1317 22.3159 22.8159C20.6317 24.5 17.9212 24.5 12.5 24.5C7.07885 24.5 4.36827 24.5 2.68414 22.8159C1 21.1317 1 18.4212 1 13C1 7.57885 1 4.86827 2.68414 3.18414C4.36827 1.5 7.07885 1.5 12.5 1.5" stroke="#007BFF" stroke-width="2" stroke-linecap="round"/>
                                        </svg></a>
                                    </div>
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

                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <h3 class="m-0">Rooms</h3>
                                    <a href="add-rooms" class="btn btn-primary d-flex align-items-center justify-content-between">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="me-2" viewBox="0 0 18 18" fill="none">
                                        <path d="M4.52368 9.23145H14.4316" stroke="white" stroke-width="2.12313" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M9.47754 4.27734V14.1853" stroke="white" stroke-width="2.12313" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>  Add Another Room
                                    </a>
                                </div>
                                
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
                                                <a href="edit-rooms" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="26" viewBox="0 0 25 26" fill="none">
                                                    <path d="M17.8847 4.03305L19.6758 2.24192C20.6651 1.2527 22.2689 1.25269 23.2581 2.24191C24.2473 3.23113 24.2473 4.83497 23.2581 5.82419L21.467 7.61533M17.8847 4.03305L11.197 10.7208C9.86132 12.0564 9.19348 12.7243 8.73872 13.5381C8.28397 14.3519 7.82643 16.2736 7.38892 18.1111C9.22647 17.6736 11.1481 17.2161 11.9619 16.7613C12.7758 16.3065 13.4436 15.6387 14.7793 14.303L21.467 7.61533M17.8847 4.03305L21.467 7.61533" stroke="#007BFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M24 13C24 18.4212 24 21.1317 22.3159 22.8159C20.6317 24.5 17.9212 24.5 12.5 24.5C7.07885 24.5 4.36827 24.5 2.68414 22.8159C1 21.1317 1 18.4212 1 13C1 7.57885 1 4.86827 2.68414 3.18414C4.36827 1.5 7.07885 1.5 12.5 1.5" stroke="#007BFF" stroke-width="2" stroke-linecap="round"/>
                                                </svg></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h3 class="mt-5 mb-4">Reviews</h3>
                                
                                <div id="hotelReviewsContainer">
                                    <!-- Reviews will be populated by JavaScript -->
                                    <div class="review-card card">
                                        <div class="d-flex justify-content-between">
                                            <div class="review-author d-flex align-item-center">John D. • <div class="ms-1 review-date">10 Days ago</div></div>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="23" viewBox="0 0 21 23" fill="none">
                                                <path d="M18.3125 4.72852L17.667 15.1713C17.502 17.8394 17.4195 19.1734 16.7508 20.1325C16.4201 20.6067 15.9944 21.0069 15.5007 21.3077C14.5022 21.916 13.1656 21.916 10.4924 21.916C7.81575 21.916 6.4774 21.916 5.47818 21.3066C4.98417 21.0053 4.55833 20.6044 4.2278 20.1294C3.55924 19.1687 3.4786 17.8328 3.3173 15.161L2.6875 4.72852" stroke="#F44343" stroke-width="2" stroke-linecap="round"/>
                                                <path d="M1.125 4.72982H19.875M14.7247 4.72982L14.0136 3.26287C13.5412 2.28842 13.305 1.80119 12.8976 1.49732C12.8072 1.42992 12.7116 1.36997 12.6115 1.31805C12.1603 1.08398 11.6189 1.08398 10.536 1.08398C9.42586 1.08398 8.87081 1.08398 8.41217 1.32786C8.31052 1.38191 8.21352 1.44429 8.12218 1.51437C7.71004 1.83055 7.47982 2.3356 7.01938 3.34571L6.38846 4.72982" stroke="#F44343" stroke-width="2" stroke-linecap="round"/>
                                                <path d="M7.89587 16.1875L7.89587 9.9375" stroke="#F44343" stroke-width="2" stroke-linecap="round"/>
                                                <path d="M13.1041 16.1875L13.1041 9.9375" stroke="#F44343" stroke-width="2" stroke-linecap="round"/>
                                            </svg>
                                        </div>
                                        <div class="text-warning">★★★★☆</div>
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