<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>TNBooking.in - Find Your Perfect Stay in Tamil Nadu</title>
        
        <?php include "temp/head.php" ?>

    </head>

    <body>
        <!-- Navbar -->
        <?php include "temp/header.php" ?>

        <!-- Hero Section -->
        <section class="hero">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-12 p-4">
                        <div class="hero-content text-center">
                            <h1 class="mb-4 fw-bold">Find Your Perfect Stay</h1>
                            <p class="lead mb-5">Search deals on hotels, homes, and much more...</p>

                            <form class="search-form">
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
                                            <input type="text" class="form-control" placeholder="City or District" />
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
                                            <input type="date" class="form-control" placeholder="Check-in" />
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
                                            <input type="date" class="form-control" placeholder="Check-out" />
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
                                                <div class="dropdown-button form-select d-flex align-items-center" id="dropdownBtn">Select Guests</div>

                                                <div class="dropdown-panel shadow" id="dropdownPanel">
                                                    <div class="dropdown-row d-flex justify-content-between align-items-center mb-2">
                                                        <span>Adults</span>
                                                        <div class="counter-controls d-flex align-items-center gap-2">
                                                            <button type="button" class="btn btn-outline-dark rounded-circle btn-sm" onclick="changeCount('adults', -1)">−</button>
                                                            <span id="adultCount">1</span>
                                                            <button type="button" class="btn btn-outline-dark rounded-circle btn-sm" onclick="changeCount('adults', 1)">+</button>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown-row d-flex justify-content-between align-items-center mb-2">
                                                        <span>Children</span>
                                                        <div class="counter-controls d-flex align-items-center gap-2">
                                                            <button type="button" class="btn btn-outline-dark rounded-circle btn-sm" onclick="changeCount('children', -1)">−</button>
                                                            <span id="childCount">1</span>
                                                            <button type="button" class="btn btn-outline-dark rounded-circle btn-sm" onclick="changeCount('children', 1)">+</button>
                                                        </div>
                                                    </div>
                                                    <button class="btn btn-primary w-100 mt-2" type="button" onclick="applySelection()">Apply</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary btn-lg w-100 rounded-5 mt-5 d-flex align-items-center justify-content-center">
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

    </body>
</html>