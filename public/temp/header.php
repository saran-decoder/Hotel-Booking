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
                    <a class="nav-link" href="logout?logout">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M12 1.09502C11.543 1.03241 11.0755 1 10.6 1C5.29807 1 1 5.02944 1 10C1 14.9706 5.29807 19 10.6 19C11.0755 19 11.543 18.9676 12 18.905" stroke="#DC2626" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M19 10L9 10M19 10C19 9.29977 17.0057 7.99153 16.5 7.5M19 10C19 10.7002 17.0057 12.0085 16.5 12.5" stroke="#DC2626" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                <?php } else { ?>
                    <button class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#authModal">Login</button>
                    <button class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#authModal" onclick="showTab('register')">Register</button>
                <?php } ?>
            </div>
        </div>
    </div>
</nav>