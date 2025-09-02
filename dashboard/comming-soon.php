<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Coming Soon</title>
        <link rel="shortcut icon" href="public/assets/images/favicon.png" type="image/x-icon" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
        <style>
            body {
                background: linear-gradient(135deg, #f0f8ff 0%, #e6f2ff 100%);
                height: 100vh;
                display: flex;
                align-items: center;
                font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            }
            .coming-container {
                background-color: #fff;
                border-radius: 15px;
                box-shadow: 0 10px 30px rgba(0, 105, 255, 0.15);
                overflow: hidden;
                max-width: 900px;
                margin: 0 auto;
                border-top: 5px solid #0066ff;
            }
            .coming-icon {
                background: linear-gradient(45deg, #0066ff, #0047b3);
                color: white;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 8rem;
            }
            .coming-content {
                padding: 40px;
                text-align: center;
            }
            .coming-title {
                font-size: 3rem;
                font-weight: 800;
                color: #0066ff;
                margin-bottom: 15px;
            }
            .coming-subtitle {
                font-size: 1.5rem;
                color: #1a1a1a;
                margin-bottom: 20px;
                font-weight: 600;
            }
            .coming-message {
                color: #4d4d4d;
                margin-bottom: 30px;
                font-size: 1.1rem;
                line-height: 1.6;
            }
            .countdown {
                font-size: 1.8rem;
                font-weight: bold;
                color: #0047b3;
                margin-bottom: 25px;
            }
            .subscribe input {
                border-radius: 30px 0 0 30px;
                border: 2px solid #0066ff;
                padding: 12px 15px;
                width: 70%;
            }
            .subscribe button {
                border-radius: 0 30px 30px 0;
                border: none;
                padding: 12px 20px;
                background: linear-gradient(to right, #0066ff, #0047b3);
                color: white;
                font-weight: 600;
                transition: all 0.3s ease;
            }
            .subscribe button:hover {
                transform: translateY(-2px);
                background: linear-gradient(to right, #0047b3, #003380);
            }
            @media (max-width: 768px) {
                .coming-icon {
                    font-size: 5rem;
                    padding: 20px 0;
                }
                .coming-content {
                    padding: 30px;
                }
                .coming-title {
                    font-size: 2.2rem;
                }
                .coming-subtitle {
                    font-size: 1.3rem;
                }
                .countdown {
                    font-size: 1.2rem;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="coming-container">
                <div class="row">
                    <div class="col-md-4 d-none d-md-block">
                        <div class="coming-icon">
                            <i class="fa fa-hourglass-half" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="coming-content">
                            <h1 class="coming-title">Coming Soon</h1>
                            <h2 class="coming-subtitle">We’re Launching Something Exciting!</h2>
                            <p class="coming-message">
                                Our website is under construction. We’re working hard to give you the best experience. Stay tuned for updates and be the first to know when we launch!
                            </p>

                            <div class="action-buttons">
                                <button class="btn btn-primary" onclick="window.location.reload()"><i class="fas fa-redo-alt me-2"></i>Try Again</button>
                                <button class="btn btn-outline-primary" onclick="window.history.back()"><i class="fas fa-arrow-left me-2"></i>Go Back</button>
                            </div>
                            
                            <!-- <div class="countdown" id="countdown">Launching in: --d --h --m --s</div>

                            <div class="subscribe d-flex justify-content-center">
                                <input type="email" placeholder="Enter your email" />
                                <button><i class="fas fa-bell me-1"></i>Notify Me</button>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- <script>
            // Countdown Timer (example: 30 days from now)
            const launchDate = new Date();
            launchDate.setDate(launchDate.getDate() + 30);

            function updateCountdown() {
                const now = new Date();
                const diff = launchDate - now;

                if (diff <= 0) {
                    document.getElementById("countdown").textContent = "We Are Live!";
                    return;
                }

                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
                const minutes = Math.floor((diff / (1000 * 60)) % 60);
                const seconds = Math.floor((diff / 1000) % 60);

                document.getElementById("countdown").textContent =
                    `Launching in: ${days}d ${hours}h ${minutes}m ${seconds}s`;
            }

            setInterval(updateCountdown, 1000);
        </script> -->
    </body>
</html>