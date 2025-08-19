<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>TNBooking.in — Admin 2FA</title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
        
        <!-- SweetAlert2 CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />

        <style>
            body {
                background-color: #f8f9fa;
                font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            }
            .otp-container {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }
            .otp-box {
                background: #fff;
                padding: 2.5rem;
                border-radius: 1rem;
                border-end-end-radius: 1rem;
                border-start-end-radius: 1rem;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
            }
            .otp-input {
                width: 3.5rem;
                height: 3.5rem;
                text-align: center;
                font-size: 1.5rem;
                font-weight: bold;
                margin: 0 0.4rem;
                border: 2px solid #dee2e6;
                border-radius: 0.5rem;
                transition: all 0.3s;
            }
            .otp-input:focus {
                border-color: #0d6efd;
                box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
                transform: translateY(-2px);
            }
            .left-panel {
                background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
                color: white;
                padding: 3rem 2.5rem;
                border-radius: 1rem 0 0 1rem;
            }
            .btn-primary {
                padding: 0.8rem;
                font-weight: 600;
                border-radius: 0.5rem;
                background: linear-gradient(to right, #0d6efd, #0a58ca);
                border: none;
                transition: all 0.3s;
            }
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
            }
            .resend-link {
                color: #6c757d;
                transition: color 0.3s;
            }
            .resend-link:hover {
                color: #0d6efd;
            }
            .brand-text {
                font-size: 2.2rem;
                font-weight: 800;
                letter-spacing: 1px;
                margin-bottom: 1.5rem;
            }
            .brand-subtext {
                font-size: 1.1rem;
                line-height: 1.6;
                opacity: 0.9;
            }
            .otp-title {
                font-size: 1.8rem;
                font-weight: 700;
                color: #0a58ca;
                margin-bottom: 1rem;
            }
            .otp-subtitle {
                font-size: 1.1rem;
                margin-bottom: 2rem;
            }
        </style>
    </head>
    <body>
        <div class="container-fluid otp-container">
            <div class="row w-100 shadow-lg rounded overflow-hidden" style="max-width: 1000px;">
                <!-- Left Branding Section -->
                <div class="col-md-5 left-panel d-none d-md-flex flex-column justify-content-center">
                    <h2 class="brand-text">TNBooking.in</h2>
                    <p class="brand-subtext">Secure your account with our enhanced One-Time Password verification system. Your security is our priority.</p>
                </div>

                <!-- OTP Form Section -->
                <div class="col-md-7 p-5">
                    <div class="otp-box">
                        <h2 class="otp-title text-center">OTP Verification</h2>
                        <p class="text-center otp-subtitle text-muted">Enter the 6-digit code sent to your registered email or phone number.</p>

                        <form id="otpForm">
                            <div class="d-flex justify-content-center mb-4">
                                <input type="text" maxlength="1" class="form-control otp-input" pattern="[0-9]*" inputmode="numeric" required />
                                <input type="text" maxlength="1" class="form-control otp-input" pattern="[0-9]*" inputmode="numeric" required />
                                <input type="text" maxlength="1" class="form-control otp-input" pattern="[0-9]*" inputmode="numeric" required />
                                <input type="text" maxlength="1" class="form-control otp-input" pattern="[0-9]*" inputmode="numeric" required />
                                <input type="text" maxlength="1" class="form-control otp-input" pattern="[0-9]*" inputmode="numeric" required />
                                <input type="text" maxlength="1" class="form-control otp-input" pattern="[0-9]*" inputmode="numeric" required />
                            </div>

                            <div class="d-grid mb-4">
                                <button type="button" class="btn btn-primary btn-lg" id="verifyBtn">Verify OTP</button>
                            </div>

                            <div class="text-center">
                                <p class="text-muted">Didn't receive the code? <a href="#" class="resend-link">Resend OTP</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- SweetAlert2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            const inputs = document.querySelectorAll(".otp-input");
            const verifyBtn = document.getElementById("verifyBtn");

            inputs.forEach((input, index) => {
              input.addEventListener("input", (e) => {
                // Only allow numbers
                e.target.value = e.target.value.replace(/[^0-9]/g, '');

                if (e.target.value && index < inputs.length - 1) {
                  inputs[index + 1].focus();
                }
              });

              input.addEventListener("keydown", (e) => {
                if (e.key === "Backspace" && !input.value && index > 0) {
                  inputs[index - 1].focus();
                }
              });
            });

            // Handle verify button click with SweetAlert
            verifyBtn.addEventListener("click", function() {
              let otp = "";
              inputs.forEach(input => otp += input.value);

              if (otp.length === inputs.length) {
                // Show success alert if OTP is complete
                Swal.fire({
                  title: 'Success!',
                  text: 'Your OTP has been successfully verified.',
                  icon: 'success',
                  confirmButtonText: 'Continue',
                  confirmButtonColor: '#0d6efd',
                  customClass: {
                    popup: 'rounded-3'
                  }
                });
              } else {
                // Show error alert if OTP is incomplete
                Swal.fire({
                  title: 'Incomplete OTP',
                  text: 'Please enter all 6 digits of the OTP before verifying.',
                  icon: 'error',
                  confirmButtonText: 'OK',
                  confirmButtonColor: '#0d6efd',
                  customClass: {
                    popup: 'rounded-3'
                  }
                });
              }
            });

            // Add resend OTP functionality
            document.querySelector('.resend-link').addEventListener('click', function(e) {
              e.preventDefault();

              // Disable resend link for 30 seconds
              this.classList.add('text-muted');
              this.style.pointerEvents = 'none';
              this.textContent = 'Resend available in 10 seconds';

              // Countdown timer
            let seconds = 10;
            const countdown = setInterval(() => {
                seconds--;
                this.textContent = `Resend available in ${seconds} seconds`;

                if (seconds <= 0) {
                    clearInterval(countdown);
                    this.textContent = 'Resend OTP';
                    this.classList.remove('text-muted');
                    this.style.pointerEvents = 'auto';

                    // Show confirmation
                    Swal.fire({
                    title: 'OTP Resent!',
                    text: 'A new OTP has been sent to your registered email/phone.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#0d6efd',
                    customClass: {
                        popup: 'rounded-3'
                    }
                    });
                }
            }, 1000);

            });
        </script>
    </body>
</html>