<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
    <div class="container">
        <a class="navbar-brand text-primary" href="index.php">
            TNBooking.in
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="booking.php">Booking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Support</a>
                </li>
            </ul>
            <!-- Modal Trigger Buttons -->
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#authModal">Login</button>
                <button class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#authModal" onclick="showTab('register')">Register</button>
            </div>
        </div>
    </div>
</nav>