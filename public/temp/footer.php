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
                        <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#loginPanel" type="button" role="tab" aria-controls="loginPanel" aria-selected="true">Login</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#registerPanel" type="button" role="tab" aria-controls="registerPanel" aria-selected="false">
                            Register
                        </button>
                    </li>
                </ul>
            </div>
            <div class="modal-body">
                <div class="tab-content">
                    <!-- Login Tab -->
                    <div class="tab-pane fade show active" id="loginPanel" role="tabpanel">
                        <form id="loginForm" class="needs-validation" novalidate autocomplete="off">
                            <div class="mb-3">
                                <label for="loginPhone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="loginPhone" required autofocus />
                                <div class="invalid-feedback">Please enter your phone number.</div>
                            </div>
                            <div class="mb-2 position-relative">
                                <label for="loginPassword" class="form-label">Password</label>
                                <input type="password" class="form-control" id="loginPassword" required />
                                <button type="button" class="btn btn-link text-decoration-none p-0 position-absolute end-0 top-50 translate-middle-y" onclick="togglePassword('loginPassword')">
                                    <i class="bi bi-eye" style="position: absolute; right: 12px; top: 4px;"></i>
                                </button>
                                <div class="invalid-feedback">Please enter your password.</div>
                            </div>
                            <div class="mb-2 text-end">
                                <a href="#" class="text-decoration-none small">Forgot password?</a>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                    <!-- Register Tab -->
                    <div class="tab-pane fade" id="registerPanel" role="tabpanel">
                        <form id="registerForm" class="needs-validation" novalidate autocomplete="off">
                            <div class="mb-3">
                                <label for="registerName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="registerName" required />
                                <div class="invalid-feedback">Please enter your full name.</div>
                            </div>
                            <div class="mb-3">
                                <label for="registerEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="registerEmail" required />
                                <div class="invalid-feedback">Please enter a valid email address.</div>
                            </div>
                            <div class="mb-3">
                                <label for="registerPhone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="registerPhone" required pattern="[0-9]{10}" />
                                <div class="invalid-feedback">Please enter a valid 10-digit phone number.</div>
                            </div>
                            <div class="mb-3 position-relative">
                                <label for="registerPassword" class="form-label">Password</label>
                                <input type="password" class="form-control" id="registerPassword" required minlength="8" />
                                <button type="button" class="btn btn-link text-decoration-none p-0 position-absolute end-0 top-50 translate-middle-y" onclick="togglePassword('registerPassword')">
                                    <i class="bi bi-eye" style="position: absolute; right: 12px; top: -7px;"></i>
                                </button>
                                <div class="form-text">Must be at least 8 characters</div>
                                <div class="invalid-feedback">Password must be at least 8 characters.</div>
                            </div>
                            <div class="mb-3 position-relative">
                                <label for="registerConfirmPassword" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="registerConfirmPassword" required />
                                <button type="button" class="btn btn-link text-decoration-none p-0 position-absolute end-0 top-50 translate-middle-y" onclick="togglePassword('registerConfirmPassword')">
                                    <i class="bi bi-eye" style="position: absolute; right: 12px; top: 5px;"></i>
                                </button>
                                <div class="invalid-feedback">Passwords must match.</div>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="termsCheck" required />
                                <label class="form-check-label" for="termsCheck"> I agree to <a href="#" class="text-decoration-none">Terms of Service</a> and <a href="#" class="text-decoration-none">Privacy Policy</a> </label>
                                <div class="invalid-feedback">You must agree to the terms and conditions.</div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="public/assets/js/libs/jquery-3.7.1.min.js"></script>
<!-- Bootstrap JS Bundle with Popper -->
<script src="public/assets/js/libs/bootstrap.bundle.min.js"></script>

<!-- Custom JavaScript -->
<script src="public/assets/js/script.js"></script>
<script src="public/assets/js/libs/sweetalert2.all.min.js"></script>

<script>
    // Password Eye Toggle
    function togglePassword(id) {
        const input = document.getElementById(id);
        const btn = input.nextElementSibling;
        const icon = btn.querySelector("i");
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("bi-eye");
            icon.classList.add("bi-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("bi-eye-slash");
            icon.classList.add("bi-eye");
        }
    }

    // Form validation using Bootstrap
    $(document).ready(function() {
        // Login form validation
        $('#loginForm').on('submit', function(e) {
            e.preventDefault();
            
            // Reset validation states
            $(this).removeClass('was-validated');
            
            // Get form values
            const user = $("#loginPhone").val().trim();
            const password = $("#loginPassword").val().trim();
            
            let isValid = true;

            // Phone validation
            if (user === "" || !/^[0-9]{10}$/.test(user)) {
                $("#loginPhone").addClass("is-invalid");
                isValid = false;
            } else {
                $("#loginPhone").removeClass("is-invalid");
            }
            
            // Password validation
            if (password.length < 6) {
                $("#loginPassword").addClass("is-invalid");
                isValid = false;
            } else {
                $("#loginPassword").removeClass("is-invalid");
            }

            if (!isValid) {
                $(this).addClass('was-validated');
                return;
            }

            // AJAX Login
            $.ajax({
                url: "public/../api/auth/user",
                type: "POST",
                data: { user, password },
                dataType: "json",
                beforeSend: function () {
                    Swal.fire({
                        title: 'Logging in...',
                        text: 'Please wait while we verify your credentials',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function (response) {
                    Swal.close();
                    if (response) {
                        window.location.reload();
                    } else {
                        showError(response.message || "Invalid login. Please check your credentials.");
                    }
                },
                error: function (xhr, status, error) {
                    Swal.close();
                    try {
                        const response = JSON.parse(xhr.responseText);
                        showError(response.message || "Please try again later.");
                    } catch (e) {
                        showError(xhr.message || "An unexpected error occurred. Please try again later.");
                    }
                }
            });
        });

        // Register form validation
        $('#registerForm').on('submit', function(e) {
            e.preventDefault();
            
            const password = $('#registerPassword').val();
            const confirmPassword = $('#registerConfirmPassword').val();
            
            // Reset validation states
            $(this).removeClass('was-validated');
            $('#registerConfirmPassword')[0].setCustomValidity("");
            $('#registerConfirmPassword').removeClass('is-invalid');
            
            // Custom validation for password match
            if (password !== confirmPassword) {
                $('#registerConfirmPassword')[0].setCustomValidity("Passwords must match");
                $('#registerConfirmPassword').addClass('is-invalid');
                $(this).addClass('was-validated');
                return;
            }
            
            if (!this.checkValidity()) {
                e.stopPropagation();
                $(this).addClass('was-validated');
                return;
            }
            
            // Get form data
            const formData = {
                name: $('#registerName').val().trim(),
                email: $('#registerEmail').val().trim(),
                phone: $('#registerPhone').val().trim(),
                password: password
            };
            
            // AJAX call to registration API
            $.ajax({
                url: "public/../api/auth/user-signup",
                type: "POST",
                data: formData,
                dataType: "json",
                beforeSend: function() {
                    // Show loading indicator
                    Swal.fire({
                        title: 'Creating Account...',
                        text: 'Please wait while we create your account',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    Swal.close();
                    
                    if (response) {
                        // Registration successful
                        Swal.fire({
                            icon: 'success',
                            title: 'Registration Successful!',
                            text: response.message || 'Your account has been created successfully',
                            timer: 3000
                        }).then(() => {
                            // Close modal and optionally redirect or switch to login tab
                            bootstrap.Modal.getInstance(document.getElementById("authModal")).hide();
                            
                            // Optionally switch to login tab
                            $('#login-tab').tab('show');
                            
                            // Clear form
                            $('#registerForm')[0].reset();
                        });
                    } else {
                        // Registration failed
                        Swal.fire({
                            icon: 'error',
                            title: 'Registration Failed',
                            text: response.message || 'Please try again later'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    
                    try {
                        const response = JSON.parse(xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Registration Failed',
                            text: response.message || 'Please try again later'
                        });
                    } catch (e) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Registration Failed',
                            text: xhr.message || 'An unexpected error occurred. Please try again later.'
                        });
                    }
                    
                }
            });
        });

        // Reset custom validation when inputs change
        $('#registerPassword, #registerConfirmPassword').on('input', function() {
            $('#registerConfirmPassword')[0].setCustomValidity("");
            $('#registerConfirmPassword').removeClass('is-invalid');
        });

        // Reset validation when switching tabs
        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            $('#loginForm, #registerForm').removeClass('was-validated');
            $('#loginForm input, #registerForm input').removeClass('is-invalid');
        });

        function showError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Login Failed',
                text: message,
                timer: 3000
            });
        }
    });
</script>