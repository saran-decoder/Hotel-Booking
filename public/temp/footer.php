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

<!-- Auth Modal (OTP Login/Signup) -->
<div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content position-relative">
            <button type="button" class="btn-close position-absolute end-0 mt-3 me-3" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-header border-0">
                <h5 class="modal-title w-100 text-center" id="modalTitle">Login with Mobile Number</h5>
            </div>
            <div class="modal-body">
                <!-- Step 1: Enter Mobile -->
                <form id="mobileForm" autocomplete="off">
                    <div class="mb-3">
                        <label for="loginPhone" class="form-label">Mobile Number</label>
                        <input type="text" class="form-control" id="loginPhone" required pattern="[0-9]{10}" />
                        <div class="invalid-feedback">Please enter a valid 10-digit mobile number.</div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Send OTP</button>
                </form>

                <!-- Step 2: Enter OTP -->
                <form id="otpForm" class="d-none" autocomplete="off">
                    <p class="text-muted">Enter the 6-digit OTP sent to your number</p>
                    <div class="d-flex justify-content-center mb-3">
                        <input type="text" maxlength="1" class="otp-input form-control mx-1" style="max-width:45px;" />
                        <input type="text" maxlength="1" class="otp-input form-control mx-1" style="max-width:45px;" />
                        <input type="text" maxlength="1" class="otp-input form-control mx-1" style="max-width:45px;" />
                        <input type="text" maxlength="1" class="otp-input form-control mx-1" style="max-width:45px;" />
                        <input type="text" maxlength="1" class="otp-input form-control mx-1" style="max-width:45px;" />
                        <input type="text" maxlength="1" class="otp-input form-control mx-1" style="max-width:45px;" />
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <a href="#" id="resendOtp" class="small">Resend OTP</a>
                        <span id="otpTimer" class="small text-muted"></span>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Verify OTP</button>
                </form>

                <!-- Step 3: Additional Details for New Users -->
                <form id="userDetailsForm" class="d-none" autocomplete="off">
                    <h6 class="mb-3">Complete Your Profile</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="dob" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="dob" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <div class="input-group">
                                <i class="fas fa-venus-mars position-absolute align-self-center" style="margin-left: 10px;"></i>
                                <select class="form-select h-auto" id="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" id="city" required />
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Complete Registration</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .otp-input {
        text-align: center;
        font-size: 1.5rem;
        font-weight: bold;
    }
</style>

<!-- jQuery -->
<script src="public/assets/js/libs/jquery-3.7.1.min.js"></script>
<script src="public/assets/js/libs/bootstrap.bundle.min.js"></script>
<script src="public/assets/js/libs/sweetalert2.all.min.js"></script>

<script>
    $(document).ready(function () {
        let phone = "";
        let isNewUser = false;

        // Auto-focus OTP inputs
        $(document).on("input", ".otp-input", function(){
            this.value = this.value.replace(/[^0-9]/g, "");
            if(this.value && $(this).next(".otp-input").length){
                $(this).next().focus();
            }
        }).on("keydown", ".otp-input", function(e){
            if(e.key === "Backspace" && !this.value && $(this).prev(".otp-input").length){
                $(this).prev().focus();
            }
        });

        // Step 1: Send OTP
        $("#mobileForm").on("submit", function (e) {
            e.preventDefault();
            phone = $("#loginPhone").val().trim();

            if (!/^[0-9]{10}$/.test(phone)) {
                $("#loginPhone").addClass("is-invalid");
                return;
            } else {
                $("#loginPhone").removeClass("is-invalid");
            }

            // Check if user exists
            $.ajax({
                url: "public/../api/auth/check-user",
                type: "POST",
                data: { phone: phone },
                dataType: "json",
                success: function (res) {
                    if (res.exists) {
                        // Existing user - proceed with login
                        isNewUser = false;
                        $("#modalTitle").text("Login with Mobile Number");
                        sendOTP();
                    } else {
                        // New user - will need to collect details after OTP
                        isNewUser = true;
                        $("#modalTitle").text("Create Your Account");
                        sendOTP();
                    }
                },
                error: function () {
                    // If check fails, assume new user and proceed
                    isNewUser = true;
                    $("#modalTitle").text("Create Your Account");
                    sendOTP();
                }
            });
        });

        function sendOTP() {
            $.ajax({
                url: "public/../api/auth/resend2",
                type: "POST",
                data: { contact: phone },
                dataType: "json",
                success: function (res) {
                    if (res.status === "success") {
                        $("#mobileForm").addClass("d-none");
                        $("#otpForm").removeClass("d-none");
                        showToast("Success", res.message || "OTP sent", "success");
                        startTimer();
                    } else {
                        showToast("Error", res.message || "Failed to send OTP", "error");
                    }
                },
                error: function () {
                    showToast("Error", "Failed to send OTP. Try again.", "error");
                }
            });
        }

        // Step 2: Verify OTP
        $("#otpForm").on("submit", function (e) {
            e.preventDefault();
            let otp = "";
            $(".otp-input").each(function(){ otp += $(this).val(); });

            if (otp.length !== 6) {
                showToast("Error","Please enter all 6 digits","error");
                return;
            }

            $.ajax({
                url: "public/../api/auth/verify2",
                type: "POST",
                data: { contact: phone, otp: otp },
                dataType: "json",
                success: function (res) {
                    if (res.status === true) {
                        if (isNewUser) {
                            // For new users, show details form
                            $("#otpForm").addClass("d-none");
                            $("#userDetailsForm").removeClass("d-none");
                            $("#modalTitle").text("Complete Your Profile");
                        } else {
                            // For existing users, login and redirect
                            $.ajax({
                                url: "public/../api/auth/user",
                                type: "POST",
                                data: { phone: phone },
                                dataType: "json",
                                success: function (res) {
                                    if (res.message) {
                                        showToast("Success", res.message || "Login Successful", "success");
                                        setTimeout(() => window.location.reload(), 1500);
                                    } else {
                                        showToast("Error", res.message || "Login failed", "error");
                                    }
                                },
                                error: function () {
                                    showToast("Error", "Login failed. Please try again.", "error");
                                }
                            });
                        }
                    } else {
                        showToast("Error", res.message || "Invalid OTP", "error");
                        $(".otp-input").val("");
                        $(".otp-input").first().focus();
                    }
                },
                error: function () {
                    showToast("Error", "OTP verification failed.", "error");
                }
            });
        });

        // Step 3: Submit User Details for New Users
        $("#userDetailsForm").on("submit", function (e) {
            e.preventDefault();
            
            const name = $("#name").val().trim();
            const email = $("#email").val().trim();
            const dob = $("#dob").val();
            const gender = $("#gender").val();
            const city = $("#city").val().trim();

            // Basic validation
            if (!name || !email || !dob || !gender || !city) {
                showToast("Error", "Please fill all required fields", "error");
                return;
            }

            console.log("Submitting registration:", {name, phone, email, dob, gender, city});

            // First, register the user
            $.ajax({
                url: "public/../api/auth/user-signup",
                type: "POST",
                data: {
                    name: name,
                    phone: phone,
                    email: email,
                    dob: dob,
                    gender: gender,
                    city: city
                },
                dataType: "json",
                success: function (signupRes) {
                    console.log("Signup response:", signupRes);
                    
                    if (signupRes.message === "Successfully Registered") {
                        // After successful registration, login the user
                        $.ajax({
                            url: "public/../api/auth/user",
                            type: "POST",
                            data: { phone: phone },
                            dataType: "json",
                            success: function (loginRes) {
                                console.log("Login response:", loginRes);
                                
                                if (loginRes.message === "Authenticated") {
                                    showToast("Success", "Registration completed successfully!", "success");
                                    setTimeout(() => window.location.reload(), 1500);
                                } else {
                                    showToast("Error", loginRes.message || "Registration successful but login failed", "error");
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error("Login error:", error);
                                showToast("Error", "Registration successful but login failed. Please login manually.", "error");
                                setTimeout(() => window.location.reload(), 2000);
                            }
                        });
                    } else if (signupRes.message === "User already exists with this phone or email") {
                        showToast("Error", "This phone number or email is already registered", "error");
                    } else {
                        showToast("Error", signupRes.message || "Registration failed", "error");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Signup error:", error, xhr.responseText);
                    showToast("Error", "Registration failed. Please try again.", "error");
                }
            });
        });

        // Resend OTP
        $("#resendOtp").on("click", function (e) {
            e.preventDefault();
            sendOTP();
        });

        // OTP Countdown Timer
        function startTimer() {
            let timeLeft = 60;
            let timer = setInterval(() => {
                $("#otpTimer").text("Resend available in " + timeLeft + "s");
                timeLeft--;
                if (timeLeft < 0) {
                    clearInterval(timer);
                    $("#otpTimer").text("You can resend OTP now.");
                }
            }, 1000);
        }

        // Function to show toast notification
        function showToast(title, message, type = 'info') {
            // Remove any existing toasts first
            $('.toast-container').remove();
            
            // Create toast container if it doesn't exist
            if ($('.toast-container').length === 0) {
                $('body').append('<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1090;"></div>');
            }
            
            const toastId = 'toast-' + Date.now();
            const toastHtml = `
                <div id="${toastId}" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <strong class="me-auto">${title}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        ${message}
                    </div>
                </div>
            `;
            
            $('.toast-container').append(toastHtml);
            
            // Add appropriate styling based on type
            const toastElement = $('#' + toastId);
            if (type === 'success') {
                toastElement.find('.toast-header').addClass('bg-success text-white');
            } else if (type === 'error') {
                toastElement.find('.toast-header').addClass('bg-danger text-white');
            } else if (type === 'warning') {
                toastElement.find('.toast-header').addClass('bg-warning text-dark');
            } else {
                toastElement.find('.toast-header').addClass('bg-info text-white');
            }
            
            // Initialize and show the toast
            const toast = new bootstrap.Toast(toastElement[0], {
                autohide: true,
                delay: 5000
            });
            toast.show();
            
            // Remove toast from DOM after it's hidden
            toastElement.on('hidden.bs.toast', function () {
                $(this).remove();
            });
        }
    });
</script>