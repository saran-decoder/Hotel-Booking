<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>404 - Page Not Found</title>
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
            .error-container {
                background-color: #fff;
                border-radius: 15px;
                box-shadow: 0 10px 30px rgba(0, 105, 255, 0.15);
                overflow: hidden;
                max-width: 900px;
                margin: 0 auto;
                border-top: 5px solid #0066ff;
            }
            .error-icon {
                background: linear-gradient(45deg, #0066ff, #0047b3);
                color: white;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 8rem;
            }
            .error-content {
                padding: 40px;
            }
            .error-code {
                font-size: 5rem;
                font-weight: 800;
                color: #0066ff;
                margin-bottom: 0;
                text-shadow: 2px 2px 4px rgba(0, 102, 255, 0.2);
            }
            .error-title {
                font-size: 2.5rem;
                color: #1a1a1a;
                margin-bottom: 20px;
                font-weight: 700;
            }
            .error-message {
                color: #4d4d4d;
                margin-bottom: 30px;
                font-size: 1.1rem;
                line-height: 1.6;
            }
            .action-buttons .btn {
                margin-right: 10px;
                margin-bottom: 10px;
                padding: 12px 25px;
                border-radius: 30px;
                font-weight: 600;
                transition: all 0.3s ease;
            }
            .btn-primary {
                background: linear-gradient(to right, #0066ff, #0047b3);
                border: none;
                box-shadow: 0 4px 8px rgba(0, 102, 255, 0.3);
            }
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 12px rgba(0, 102, 255, 0.4);
                background: linear-gradient(to right, #0047b3, #003380);
            }
            .btn-outline-primary {
                border: 2px solid #0066ff;
                color: #0066ff;
                font-weight: 600;
            }
            .btn-outline-primary:hover {
                background-color: #0066ff;
                color: white;
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0, 102, 255, 0.3);
            }
            .technical-details {
                background-color: #f0f8ff;
                border-left: 4px solid #0066ff;
                padding: 15px;
                border-radius: 4px;
                margin-top: 25px;
                font-family: monospace;
                font-size: 0.9rem;
                display: none;
            }
            .technical-details pre {
                margin-bottom: 0;
                color: #0047b3;
            }
            .toggle-details {
                color: #0066ff;
                cursor: pointer;
                font-size: 0.9rem;
                display: inline-block;
                margin-top: 15px;
                font-weight: 600;
            }
            .toggle-details:hover {
                text-decoration: underline;
            }
            .contact-support {
                margin-top: 30px;
                padding-top: 20px;
                border-top: 1px solid #e6f2ff;
                color: #4d4d4d;
            }
            .contact-support a {
                color: #0066ff;
                font-weight: 600;
                text-decoration: none;
            }
            .contact-support a:hover {
                text-decoration: underline;
            }
            .server-status {
                display: flex;
                align-items: center;
                margin-top: 15px;
                color: #4d4d4d;
            }
            .status-indicator {
                width: 12px;
                height: 12px;
                border-radius: 50%;
                background-color: #00cc66;
                margin-right: 8px;
                animation: pulse 2s infinite;
            }
            @keyframes pulse {
                0% {
                    opacity: 1;
                }
                50% {
                    opacity: 0.5;
                }
                100% {
                    opacity: 1;
                }
            }
            @media (max-width: 768px) {
                .error-icon {
                    font-size: 5rem;
                    padding: 20px 0;
                }
                .error-content {
                    padding: 30px;
                }
                .error-code {
                    font-size: 4rem;
                }
                .error-title {
                    font-size: 2rem;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="error-container">
                <div class="row">
                    <div class="col-md-4 d-none d-md-block">
                        <div class="error-icon">
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="error-content">
                            <h1 class="error-code">404</h1>
                            <h2 class="error-title">Page Not Found</h2>
                            <p class="error-message">
                                Oops! The page you're looking for seems to have wandered off into the digital void. It might have been moved, deleted, or never existed in the first place.
                            </p>

                            <div class="action-buttons">
                                <button class="btn btn-primary" onclick="window.location.href='/hotel-booking/'"><i class="fas fa-home me-2"></i>Go Home </button>
                                <button class="btn btn-outline-primary" onclick="window.history.back()"><i class="fas fa-arrow-left me-2"></i>Go Back</button>
                            </div>

                            <a class="toggle-details" onclick="toggleDetails()"> <i class="fas fa-info-circle me-1"></i> Show Technical Details </a>

                            <div class="technical-details" id="technicalDetails">
                                <pre>Error 404: Page Not Found
                                Timestamp: <span id="timestamp"></span>
                                Requested URL: <span id="requested-url"></span>
                                Server: web-server-03
                                Status: Resource Not Found</pre>
                            </div>

                            <div class="server-status">
                                <div class="status-indicator"></div>
                                <span>We've logged this issue and will investigate</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Set current timestamp
            document.getElementById("timestamp").textContent = new Date().toISOString();

            // Set the requested URL
            document.getElementById("requested-url").textContent = window.location.href;

            // Toggle technical details
            function toggleDetails() {
                const details = document.getElementById("technicalDetails");
                const toggleLink = document.querySelector(".toggle-details");

                if (details.style.display === "none" || details.style.display === "") {
                    details.style.display = "block";
                    toggleLink.innerHTML = '<i class="fas fa-times-circle me-1"></i> Hide Technical Details';
                } else {
                    details.style.display = "none";
                    toggleLink.innerHTML = '<i class="fas fa-info-circle me-1"></i> Show Technical Details';
                }
            }
        </script>
    </body>
</html>