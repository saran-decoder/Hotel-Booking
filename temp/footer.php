<footer>
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <h4 class="fw-bold mb-3">TNBooking.in</h4>
                <p class="text-white">Finding your perfect accommodation made easy. We connect travelers with the best stays around the world.</p>

                <div class="social-icons mt-4">
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-twitter-x"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-md-4">
                <div class="footer-links">
                    <h5>Company</h5>
                    <ul>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Blog</a></li>
                        <!-- <li><a href="#">Press</a></li>
                        <li><a href="#">Travel Agents</a></li> -->
                    </ul>
                </div>
            </div>

            <div class="col-lg-2 col-md-4">
                <div class="footer-links">
                    <h5>Support</h5>
                    <ul>
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Trust & Safety</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 col-md-4">
                <div class="footer-links">
                    <h5>Get the Best Deals in Your Inbox</h5>
                    <p class="mb-4">Subscribe to our newsletter and receive exclusive offers, travel tips, and destination inspiration.</p>

                    <form class="row g-2">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="email" class="form-control form-control-lg p-2" placeholder="Your Email" />
                                <button class="btn btn-primary btn-lg fs-6 p-2" type="submit">Subscribe</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <hr class="mt-5 mb-4" style="border-color: rgba(255, 255, 255, 0.1);" />

        <div class="row">
            <div class="col-md-6">
                <p class="mb-0 text-white">&copy; 2025 TNBooking.in. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

<!-- Auth Modal -->
<div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content position-relative">
            <button type="button" class="btn-close position-absolute end-0 mt-3 me-3" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-header border-0">
                <ul class="nav nav-tabs w-100 justify-content-center" id="authTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#loginPanel" type="button" role="tab" aria-controls="loginPanel" aria-selected="true" onclick="showTab('login')">Login</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#registerPanel" type="button" role="tab" aria-controls="registerPanel" aria-selected="false" onclick="showTab('register')">
                            Register
                        </button>
                    </li>
                </ul>
            </div>
            <div class="modal-body">
                <div class="tab-content">
                    <!-- Login Tab -->
                    <div class="tab-pane fade show active" id="loginPanel" role="tabpanel">
                        <form id="loginForm" autocomplete="off">
                            <div class="mb-3">
                                <label for="loginEmail" class="form-label">Email or Username</label>
                                <input type="text" class="form-control" id="loginEmail" required autofocus />
                            </div>
                            <div class="mb-2 position-relative">
                                <label for="loginPassword" class="form-label">Password</label>
                                <input type="password" class="form-control" id="loginPassword" required />
                                <button type="button" class="btn btn-link text-decoration-none p-0 position-absolute end-0 top-50 translate-middle-y" onclick="togglePassword('loginPassword')">
                                    <i class="bi bi-eye" style="position: absolute; right: 12px; top: 4px;"></i>
                                </button>
                            </div>
                            <div class="mb-2 text-end">
                                <a href="#" class="text-decoration-none small">Forgot password?</a>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                        <div class="text-center mt-3">
                            <span>Don’t have an account? <a href="#" class="text-decoration-none" onclick="showTab('register')">Register here</a></span>
                        </div>
                    </div>
                    <!-- Register Tab -->
                    <div class="tab-pane fade" id="registerPanel" role="tabpanel">
                        <form id="registerForm" autocomplete="off">
                            <div class="mb-3">
                                <label for="registerName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="registerName" required />
                            </div>
                            <div class="mb-3">
                                <label for="registerEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="registerEmail" required />
                            </div>
                            <div class="mb-3">
                                <label for="registerPhone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="registerPhone" required />
                            </div>
                            <div class="mb-3 position-relative">
                                <label for="registerPassword" class="form-label">Password</label>
                                <input type="password" class="form-control" id="registerPassword" required minlength="8" />
                                <button type="button" class="btn btn-link text-decoration-none p-0 position-absolute end-0 top-50 translate-middle-y" onclick="togglePassword('registerPassword')">
                                    <i class="bi bi-eye" style="position: absolute; right: 12px; top: -7px;"></i>
                                </button>
                                <div class="form-text">Must be at least 8 characters</div>
                            </div>
                            <div class="mb-3 position-relative">
                                <label for="registerConfirmPassword" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="registerConfirmPassword" required />
                                <button type="button" class="btn btn-link text-decoration-none p-0 position-absolute end-0 top-50 translate-middle-y" onclick="togglePassword('registerConfirmPassword')">
                                    <i class="bi bi-eye" style="position: absolute; right: 12px; top: 5px;"></i>
                                </button>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="termsCheck" required />
                                <label class="form-check-label" for="termsCheck"> I agree to <a href="#" class="text-decoration-none">Terms of Service</a> and <a href="#" class="text-decoration-none">Privacy Policy</a> </label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </form>
                        <div class="text-center mt-3">
                            <span>Already have an account? <a href="#" class="text-decoration-none" onclick="showTab('login')">Login here</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="assets/js/libs/jquery-3.7.1.min.js"></script>
<!-- Bootstrap JS Bundle with Popper -->
<script src="assets/js/libs/bootstrap.bundle.min.js"></script>

<!-- Custom JavaScript -->
<script src="assets/js/script.js"></script>

<script>
    // Password Eye Toggle
    function togglePassword(id) {
        const input = document.getElementById(id);
        const btn = input.nextElementSibling;
        const icon = btn.querySelector("i");
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }

    // Switch Tab Handler
    function showTab(tab) {
        const loginPanel = document.getElementById("loginPanel");
        const registerPanel = document.getElementById("registerPanel");
        const loginTab = document.getElementById("login-tab");
        const registerTab = document.getElementById("register-tab");
        if (tab === "login") {
            loginPanel.classList.add("show", "active");
            registerPanel.classList.remove("show", "active");
            loginTab.classList.add("active");
            registerTab.classList.remove("active");
        } else {
            registerPanel.classList.add("show", "active");
            loginPanel.classList.remove("show", "active");
            registerTab.classList.add("active");
            loginTab.classList.remove("active");
        }
    }

    // Simple Form Validation
    document.getElementById("loginForm").onsubmit = function (e) {
        e.preventDefault();
        // Your login logic (e.g. AJAX)
        alert("Login Successful!");
        bootstrap.Modal.getInstance(document.getElementById("authModal")).hide();
    };

    document.getElementById("registerForm").onsubmit = function (e) {
        e.preventDefault();
        const pwd = document.getElementById("registerPassword").value;
        const confirm = document.getElementById("registerConfirmPassword").value;
        if (pwd !== confirm) {
            alert("Passwords do not match!");
            return;
        }
        if (!document.getElementById("termsCheck").checked) {
            alert("You must agree to the terms!");
            return;
        }
        // Your register logic (e.g. AJAX)
        alert("Registered Successfully!");
        bootstrap.Modal.getInstance(document.getElementById("authModal")).hide();
    };
</script>