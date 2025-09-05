<?php
include "../libs/load.php";

// In a real application, you would fetch this data from the database
$user_data = [
    'name' => 'Govindan',
    'email' => 'Govindan43@gmail.com',
    'phone' => '+91 98765 43210',
    'dob' => '15 Aug 1985',
    'member_since' => 'Jan 2022',
    'profile_image' => 'https://via.placeholder.com/90'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile - TN Booking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include "temp/head.php" ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --light-bg: #f8f9fa;
            --card-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .profile-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .sidebar {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--card-shadow);
            height: fit-content;
        }
        
        .sidebar-title {
            font-size: 14px;
            color: var(--secondary-color);
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .sidebar-nav .nav-item {
            margin-bottom: 5px;
        }
        
        .sidebar-nav .nav-link {
            color: #333;
            padding: 10px 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            transition: all 0.2s;
        }
        
        .sidebar-nav .nav-link:hover {
            background-color: #f0f0f0;
        }
        
        .sidebar-nav .nav-link.active {
            background-color: #e9f2ff;
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .sidebar-nav .nav-link i {
            margin-right: 10px;
            font-size: 16px;
        }
        
        .profile-card {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: var(--card-shadow);
        }
        
        .profile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .profile-title {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }
        
        .profile-img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #f0f0f0;
        }
        
        .info-label {
            font-size: 13px;
            color: var(--secondary-color);
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 15px;
            margin-bottom: 15px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #ddd;
        }
        
        .btn-primary {
            border-radius: 8px;
            padding: 10px 20px;
            background-color: var(--primary-color);
            border: none;
        }
        
        .auth-method {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        
        .auth-method-info {
            flex: 1;
        }
        
        .auth-method-title {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .auth-method-desc {
            font-size: 13px;
            color: var(--secondary-color);
        }
        
        .privacy-note {
            font-size: 13px;
            color: var(--secondary-color);
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <?php include "temp/header.php" ?>

    <div class="container py-4">
        <div class="profile-container">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-lg-3 mb-4">
                    <div class="sidebar">
                        <div class="sidebar-title">MY ACCOUNT</div>
                        <ul class="nav flex-column sidebar-nav">
                            <li class="nav-item">
                                <a class="nav-link active" href="#">
                                    <i class="bi bi-person"></i> Personal Info
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-key"></i> Change Password
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-journal-text"></i> My Bookings
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-tag"></i> Offers / Coupons
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-star"></i> My Reviews / Ratings
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-danger" href="#">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-lg-9">
                    <!-- Personal Info -->
                    <div class="profile-card mb-4">
                        <div class="profile-header">
                            <h2 class="profile-title">Personal Information</h2>
                            <button class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-pencil"></i> Edit Profile
                            </button>
                        </div>
                        
                        <div class="d-flex align-items-center mb-4">
                            <img src="<?= $user_data['profile_image'] ?>" class="profile-img me-4" alt="Profile">
                            <div>
                                <h4 class="mb-1"><?= $user_data['name'] ?></h4>
                                <p class="text-muted mb-0">Member since <?= $user_data['member_since'] ?></p>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="info-label">Full Name</div>
                                <div class="info-value"><?= $user_data['name'] ?></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-label">Phone</div>
                                <div class="info-value"><?= $user_data['phone'] ?></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-label">Email</div>
                                <div class="info-value"><?= $user_data['email'] ?></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-label">Date of Birth</div>
                                <div class="info-value"><?= $user_data['dob'] ?></div>
                            </div>
                        </div>
                        
                        <p class="privacy-note">
                            Your personal information is used to enhance your booking experience and is kept secure according to our privacy policy.
                        </p>
                    </div>

                    <!-- Security Settings -->
                    <div class="profile-card">
                        <h3 class="profile-title mb-4">Security Settings</h3>
                        
                        <!-- Change Password -->
                        <div class="mb-5">
                            <h5 class="section-title">Change Password</h5>
                            <form>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label class="form-label">Current Password</label>
                                            <input type="password" class="form-control" placeholder="Enter current password">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">New Password</label>
                                            <input type="password" class="form-control" placeholder="Enter new password">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Confirm New Password</label>
                                            <input type="password" class="form-control" placeholder="Confirm new password">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Update Password</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Two-Factor Authentication -->
                        <div>
                            <h5 class="section-title">Two-Factor Authentication</h5>
                            <p class="mb-4">Enable Two-Factor Authentication<br>Add an extra layer of security to your account by requiring both your password and a verification code.</p>
                            
                            <h6 class="mb-3">Verification Methods</h6>
                            
                            <!-- SMS Verification -->
                            <div class="auth-method">
                                <div class="auth-method-info">
                                    <div class="auth-method-title">SMS Verification</div>
                                    <div class="auth-method-desc">Receive a code via SMS to your registered phone number</div>
                                </div>
                                <button class="btn btn-outline-primary">Setup</button>
                            </div>
                            
                            <!-- Authenticator App -->
                            <div class="auth-method">
                                <div class="auth-method-info">
                                    <div class="auth-method-title">Authenticator App</div>
                                    <div class="auth-method-desc">Use an authentication app like Google Authenticator</div>
                                </div>
                                <button class="btn btn-outline-primary">Setup</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include "temp/footer.php" ?>
</body>
</html>