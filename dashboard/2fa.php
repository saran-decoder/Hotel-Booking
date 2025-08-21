<?php

    include "../libs/load.php";

    // Redirect to login page if 'contact' or 'session_status' not set
    if (!Session::get('username')) {
        header("Location: index");
        exit;
    } elseif (Session::get('email_verified')) {
        header("Location: index");
		exit;
    }

    // Redirect to welcome page if already logged in and verified as patient
    if (
        Session::get('session_token') &&
        Session::get('session_type') === 'admin' &&
        Session::get('email_verified') === 'verified'
    ) {
        header("Location: welcome");
        exit;
    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>TNBooking.in - Admin 2FA</title>

        <!-- Bootstrap CSS -->
        <link href="assets/css/libs/5.3.3/bootstrap.min.css" rel="stylesheet" />
        <!-- SweetAlert2 -->
        <link rel="stylesheet" href="assets/css/libs/sweetalert2.min.css" />
        <link rel="stylesheet" href="assets/css/2fa.css" />
    </head>
    <body>
        <div class="container-fluid otp-container">
            <div class="row w-100 shadow-lg rounded overflow-hidden" style="max-width: 1000px;">
                <!-- Left Branding Section -->
                <div class="col-md-5 left-panel d-none d-md-flex flex-column justify-content-center">
                    <h2 class="brand-text">TNBooking.in</h2>
                    <p class="brand-subtext">Secure your account with OTP verification. Your security is our priority.</p>
                </div>

                <!-- OTP Form Section -->
                <div class="col-md-7 p-0">
                    <div class="otp-box p-5">
                        <h2 class="otp-title text-center">OTP Verification</h2>
                        <p class="text-center otp-subtitle text-muted">Enter the 6-digit code sent to your registered email.</p>

                        <form id="otpForm">
                            <!-- hidden username -->
                            <input type="hidden" id="username" value="<?= htmlspecialchars(Session::get('username')) ?>">

                            <div class="d-flex justify-content-center mb-4">
                                <?php for ($i=0; $i<6; $i++): ?>
                                    <input type="text" maxlength="1" class="form-control otp-input mx-1 text-center" pattern="[0-9]*" inputmode="numeric" required />
                                <?php endfor; ?>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary btn-lg" id="verifyBtn">Verify OTP</button>
                            </div>

                            <div class="text-center">
                                <p class="text-muted">Didn't receive the code? 
                                    <a href="#" id="resendOtp" class="resend-link">Resend OTP</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- JS -->
        <script src="assets/js/libs/jquery-3.7.1.min.js"></script>
        <script src="assets/js/libs/bootstrap.bundle.min.js"></script>
        <script src="assets/js/libs/sweetalert2.all.min.js"></script>

        <script>
            $(function(){
                const inputs = $(".otp-input");

                // Auto-focus logic
                inputs.on("input", function(){
                    this.value = this.value.replace(/[^0-9]/g,"");
                    if(this.value && $(this).next(".otp-input").length){
                        $(this).next().focus();
                    }
                }).on("keydown", function(e){
                    if(e.key==="Backspace" && !this.value && $(this).prev(".otp-input").length){
                        $(this).prev().focus();
                    }
                });

                // Submit OTP
                $("#otpForm").on("submit", function(e){
                    e.preventDefault();

                    let otp = "";
                    inputs.each(function(){ otp += $(this).val(); });

                    if(otp.length !== 6){
                        Swal.fire("Error","Please enter all 6 digits of the OTP.","error");
                        return;
                    }

                    let username = $("#username").val();

                    // Show loading modal
                    Swal.fire({
                        title: 'Verifying OTP...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: "../api/auth/verify",
                        type: "POST",
                        data: { username: username, otp: otp },
                        success: function(res){
                            Swal.close();
                            if(res.status){
                                Swal.fire("Success","OTP verified!","success").then(()=>{
                                    window.location.href="index";
                                });
                            } else {
                                Swal.fire("Error","Invalid or expired OTP.","error");
                                inputs.val("");
                                inputs.first().focus();
                            }
                        },
                        error: function(){
                            Swal.close();
                            Swal.fire("Error","Server error. Please try again.","error");
                        }
                    });
                });

                // Resend OTP
                $("#resendOtp").on("click", function(e){
                    e.preventDefault();
                    let btn = $(this);

                    btn.addClass("text-muted").css("pointer-events","none").text("Resend available in 10s");
                    let seconds = 10;
                    let countdown = setInterval(()=>{
                        seconds--;
                        btn.text(`Resend available in ${seconds}s`);
                        if(seconds<=0){
                            clearInterval(countdown);
                            btn.removeClass("text-muted").css("pointer-events","auto").text("Resend OTP");
                        }
                    },1000);

                    // Show loading modal
                    Swal.fire({
                        title: 'Resending OTP...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: "../api/auth/resend",
                        type: "POST",
                        data: { username: $("#username").val() },
                        success: function(res){
                            Swal.close();
                            if(res.status){
                                Swal.fire("Success","New OTP sent!","success");
                            } else {
                                Swal.fire("Error","Failed to resend OTP.","error");
                            }
                        },
                        error: function(){
                            Swal.close();
                            Swal.fire("Error","Something went wrong.","error");
                        }
                    });
                });
            });
        </script>
    </body>
</html>