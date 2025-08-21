<?php

    include "../libs/load.php";

    if (
        Session::get('session_token') &&
        Session::get('session_type')  == 'admin' &&
        Session::get('username') &&
        Session::get('email_verified') == 'verified'
    ) {
		header("Location: welcome");
		exit;
	}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>TNBooking Dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <?php include "temp/head.php" ?>
    </head>
    <body>
        <div class="container-fluid">
            <!-- Registration 5 - Bootstrap Brain Component -->
            <section class="p-3 p-md-4 p-xl-5">
                <div class="container">
                    <div class="card border-light-subtle shadow-sm">
                        <div class="row g-0">
                            <div class="col-12 col-md-6 text-bg-primary">
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <div class="col-10 col-xl-8 py-3">
                                        <h2 class="h1 mb-4">TNBooking.in</h2>
                                        <p class="lead m-0">Manage your hotel properties, bookings, and promotions all in one place. Our admin dashboard gives you complete control over your hospitality business.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="card-body p-3 p-md-4 p-xl-5">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-5" style="justify-items: center;">
                                                <h2 class="h3 text-primary">TNBooking.in</h2>
                                                <h3 class="fs-6 fw-normal text-secondary m-0 text-center">Administrator Access – Sign in to manage bookings, hotels, and promotions. </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <form class="needs-validation" id="loginForm" novalidate>
                                        <div class="row gy-3 gy-md-4 overflow-hidden">
                                            <div class="col-12">
                                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" name="email" id="email" />
                                                <div class="invalid-feedback">Please enter your username.</div>
                                            </div>
                                            <div class="col-12">
                                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                                <div class="input-group" id="show_hide_password">
                                                    <input type="password" name="password" class="form-control" id="password" />
                                                    <a href="javascript:;" class="input-group-text bg-transparent"><i class="fa fa-eye"></i></a>
                                                </div>
                                                <div class="invalid-feedback">Password must be at least 6 characters.</div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-2 text-end">
                                                    <a href="#" class="text-decoration-none small">Forgot password?</a>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button class="btn bsb-btn-xl btn-primary" type="submit">Login</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="m-0 text-secondary text-center mt-5"><a href="../" class="link-primary text-decoration-none">Back to Website</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <?php include "temp/footer.php" ?>

        <script>
            $(document).ready(function () {
                // Toggle show/hide password
                $("#show_hide_password a").on('click', function (event) {
                    event.preventDefault();
                    const passwordField = $('#show_hide_password input');
                    const icon = $('#show_hide_password i');

                    if (passwordField.attr("type") === "text") {
                        passwordField.attr('type', 'password');
                        icon.addClass("fa-eye-slash").removeClass("fa-eye");
                    } else {
                        passwordField.attr('type', 'text');
                        icon.removeClass("fa-eye-slash").addClass("fa-eye");
                    }
                });

                // Form validation and submission
                $("#loginForm").on("submit", function (e) {
                    e.preventDefault();

                    const user = $("#email").val().trim();
                    const password = $("#password").val().trim();

                    let isValid = true;

                    // Email validation
                    if (user === "" || !/^\S+@\S+\.\S+$/.test(user)) {
                        $("#email").addClass("is-invalid");
                        isValid = false;
                    } else {
                        $("#email").removeClass("is-invalid");
                    }
                    
                    // Password validation
                    if (password.length < 6) {
                        $("#password").addClass("is-invalid");
                        $("#show_hide_password").addClass("is-invalid");
                        isValid = false;
                    } else {
                        $("#password").removeClass("is-invalid");
                        $("#show_hide_password").removeClass("is-invalid");
                    }

                    if (!isValid) return;

                    // AJAX Login
                    $.ajax({
                        url: "../api/auth/admin",
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
                            Swal.close(); // close the loader
                            if (response) {
                                window.location.href = "2fa";
                            } else {
                                showError("Invalid login. Please check email/password.");
                            }
                        },
                        error: function (xhr, status, error) {
                            Swal.close(); // close the loader
                            try {
                                const response = JSON.parse(xhr.responseText);
                                showError(response.message || "Please try again later.");
                            } catch (e) {
                                showError("An unexpected error occurred. Please try again later.");
                            }
                        }
                    });
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
    </body>
</html>