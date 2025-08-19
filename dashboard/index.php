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
                                    <form action="#!">
                                        <div class="row gy-3 gy-md-4 overflow-hidden">
                                            <div class="col-12">
                                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" required />
                                            </div>
                                            <div class="col-12">
                                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                                <input type="password" class="form-control" name="password" id="password" placeholder="••••••••" required />
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
                                            <p class="m-0 text-secondary text-center mt-5"><a href="#!" class="link-primary text-decoration-none">Back to Website</a></p>
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
    </body>
</html>