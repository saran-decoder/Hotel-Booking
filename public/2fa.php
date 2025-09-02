<?php

    include "../libs/load.php";

    if (
        Session::get('session_token') &&
        Session::get('session_type')  == 'user' &&
        Session::get('username') &&
        Session::get('sms_verified') == 'verified'
    ) {
        header("Location: " . $_SERVER["REQUEST_URI"] . "../");
		exit;
    } elseif (
        !Session::get('session_token') &&
        !Session::get('session_type')  == 'user' &&
        !Session::get('username') &&
        !Session::get('sms_verified') == 'verified'
    ) {
        header("Location: " . $_SERVER["REQUEST_URI"] . "../");
		exit;
    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>TNBooking.in - Admin 2FA</title>

        <link rel="shortcut icon" href="public/assets/images/favicon.png" type="image/x-icon" />
        
        <!-- Bootstrap CSS -->
        <link href="public/assets/css/libs/5.3.3/bootstrap.min.css" rel="stylesheet" />
        <!-- SweetAlert2 -->
        <link rel="stylesheet" href="public/assets/css/libs/sweetalert2.min.css" />
        <link rel="stylesheet" href="public/assets/css/2fa.css" />
    </head>
    <body>
        <div class="container otp-container py-5">
            <div class="row g-0 shadow-lg rounded overflow-hidden">
                <!-- Left Branding Section -->
                <div class="col-12 col-md-5 left-panel d-none d-md-flex flex-column justify-content-center p-4">
                    <h2 class="brand-text">TNBooking.in</h2>
                    <p class="brand-subtext">Secure your account with OTP verification. Your security is our priority.</p>
                </div>

                <!-- OTP Form Section -->
                <div class="col-12 col-md-7 p-0">
                    <div class="otp-box p-4 p-md-5">
                        <h2 class="otp-title text-center mb-3">OTP Verification</h2>
                        <p class="text-center otp-subtitle text-muted">Enter the 6-digit code sent to your registered email.</p>

                        <form id="otpForm">
                            <input type="hidden" id="contact" value="<?= Session::get('contact') ?>">

                            <div class="d-flex justify-content-center mb-4 flex-wrap">
                                <?php for ($i=0; $i<6; $i++): ?>
                                    <input type="text" maxlength="1"
                                        class="form-control form-control-sm otp-input mx-1 text-center"
                                        style="max-width:45px;" pattern="[0-9]*" inputmode="numeric" required />
                                <?php endfor; ?>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary btn-lg" id="verifyBtn">Verify OTP</button>
                            </div>

                            <div class="text-center">
                                <p class="text-muted">
                                    Didn't receive the code? 
                                    <a href="#" id="resendOtp" class="resend-link">Resend OTP</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- JS -->
        <script src="public/assets/js/libs/jquery-3.7.1.min.js"></script>
        <script src="public/assets/js/libs/bootstrap.bundle.min.js"></script>
        <script src="public/assets/js/libs/sweetalert2.all.min.js"></script>

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

                    let contact = $("#contact").val();

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
                        url: "public/../api/auth/verify2",
                        type: "POST",
                        data: { contact: contact, otp: otp },
                        success: function(res){
                            Swal.close();
                            if(res.status){
                                Swal.fire("Success","OTP verified!","success").then(()=>{
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire("Error","Invalid or expired OTP.","error");
                                inputs.val("");
                                inputs.first().focus();
                            }
                        },
                        // In your AJAX error handler, add:
                        error: function(xhr, status, errorThrown){
                            Swal.close();
                            console.log("Raw response:", xhr.responseText); // Add this line
                            
                            let msg = "Something went wrong!";
                            
                            // Check if API returned JSON with a message
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            } else if (xhr.responseText) {
                                try {
                                    let res = JSON.parse(xhr.responseText);
                                    if (res.message) {
                                        msg = res.message;
                                    }
                                } catch(e) {
                                    msg = xhr.responseText;
                                }
                            } else {
                                msg = errorThrown;
                            }

                            Swal.fire("Error", msg, "error");
                        }
                    });
                });

                // Resend OTP
                $("#resendOtp").on("click", function(e){
                    e.preventDefault();
                    let btn = $(this);

                    btn.addClass("text-muted").css("pointer-events","none").text("Resend available in 30s");
                    let seconds = 30;
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
                        url: "public/../api/auth/resend2",
                        type: "POST",
                        data: { contact: $("#contact").val() },
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