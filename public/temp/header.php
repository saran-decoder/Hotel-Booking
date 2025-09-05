<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
    <div class="container">
        <a class="navbar-brand text-primary" href="./">
            TNBooking.in
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="./">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                        <?= Session::get('user_id')
                                ? 'href="booking"'
                                : 'type="button" data-bs-toggle="modal" data-bs-target="#authModal"' ?>>
                        Booking
                    </a>

                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Support</a>
                </li>
            </ul>
            <!-- Modal Trigger Buttons -->
            <div class="d-flex align-items-center">
                <?php if (
                            Session::get('session_token') &&
                            Session::get('session_type')  == 'user' &&
                            Session::get('username') &&
                            Session::get('sms_verified') == 'verified'
                        ) {
                ?>
                    <div class="dropdown">
                        <div class="d-flex align-items-center gap-3 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                            <svg xmlns="http://www.w3.org/2000/svg" class="rounded-circle" width="35" height="35" viewBox="0 0 18 18" fill="none" style="background: #3b82f6; padding: 8px;">
                                <path d="M13.7899 15.3123V13.8995C13.7899 13.15 13.4922 12.4313 12.9623 11.9014C12.4324 11.3714 11.7136 11.0737 10.9642 11.0737H6.72563C5.9762 11.0737 5.25746 11.3714 4.72754 11.9014C4.19761 12.4313 3.8999 13.15 3.8999 13.8995V15.3123" stroke="white" stroke-width="2.11929" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M8.84477 8.24764C10.4054 8.24764 11.6705 6.98252 11.6705 5.42192C11.6705 3.86131 10.4054 2.59619 8.84477 2.59619C7.28416 2.59619 6.01904 3.86131 6.01904 5.42192C6.01904 6.98252 7.28416 8.24764 8.84477 8.24764Z" stroke="white" stroke-width="2.11929" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <div class="d-none d-sm-block">
                                <div class="profile-name"><?= Session::get('username'); ?></div>
                            </div>
                        </div>

                        <!-- Dropdown Menu -->
                        <div class="dropdown-menu dropdown-menu-end mt-2 sidebar">
                            <div class="sidebar-title">MY ACCOUNT</div>
                            <ul class="nav flex-column sidebar-nav">
                                <li class="dropdown-item nav-item">
                                    <a class="nav-link active" href="#">
                                        <i class="bi bi-person"></i> Personal Info
                                    </a>
                                </li>
                                <li class="dropdown-item nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-key"></i> Change Password
                                    </a>
                                </li>
                                <li class="dropdown-item nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-journal-text"></i> My Bookings
                                    </a>
                                </li>
                                <li class="dropdown-item nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-tag"></i> Offers / Coupons
                                    </a>
                                </li>
                                <li class="dropdown-item nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-star"></i> My Reviews / Ratings
                                    </a>
                                </li>
                                <li class="dropdown-item nav-item">
                                    <a class="nav-link text-danger" href="logout?logout">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                <?php } else { ?>
                    <button class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#authModal">Login</button>
                <?php } ?>
            </div>
        </div>
    </div>
</nav>