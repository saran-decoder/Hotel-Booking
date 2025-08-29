<?php
    include "../libs/load.php";

    if (!Session::get('email_verified') == 'verified') {
        header("Location: 2fa");
        exit;
    }

    if (
        !Session::get('session_token') || 
		Session::get('session_type') != 'admin' && 
		!Session::get('username') || 
		Session::get('email_verified') != 'verified'
    ) {
		header("Location: logout?logout");
		exit;
	}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>TNBooking - Admin Settings</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        
        <?php include "temp/head.php" ?>
        
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <?php include "temp/sideheader.php" ?>

                <!-- Main Content -->
                <div class="main-content p-0 overflow-hidden">
                    <!-- Top Navbar -->
                    <?php include "temp/header.php" ?>

                    <!-- Page Header -->
                    <div class="p-4">
                        <div class="dashboard-header">Admin Settings</div>
                        <div class="dashboard-subtext">Manage your account and system preferences</div>
                    </div>

                    <!-- Main Content -->
                    <div class="px-4">
                        <!-- Profile Settings -->
                        <div class="settings-section">
                            <div class="settings-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
                                    <path d="M19.7928 21.875V19.7917C19.7928 18.6866 19.3538 17.6268 18.5724 16.8454C17.791 16.064 16.7312 15.625 15.6261 15.625H9.37614C8.27107 15.625 7.21126 16.064 6.42986 16.8454C5.64846 17.6268 5.20947 18.6866 5.20947 19.7917V21.875" stroke="#3B82F6" stroke-width="2.52218" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12.5016 11.4586C14.8028 11.4586 16.6683 9.5931 16.6683 7.29191C16.6683 4.99072 14.8028 3.12524 12.5016 3.12524C10.2004 3.12524 8.33496 4.99072 8.33496 7.29191C8.33496 9.5931 10.2004 11.4586 12.5016 11.4586Z" stroke="#3B82F6" stroke-width="2.52218" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg> Profile Settings
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="profile-pic">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 35 35" fill="none">
                                        <path d="M27.2411 30.6057V27.7773C27.2411 26.277 26.6451 24.8381 25.5842 23.7772C24.5233 22.7164 23.0845 22.1204 21.5842 22.1204H13.0988C11.5985 22.1204 10.1596 22.7164 9.09876 23.7772C8.03789 24.8381 7.44189 26.277 7.44189 27.7773V30.6057" stroke="white" stroke-width="2.12134" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M17.3415 16.4635C20.4657 16.4635 22.9984 13.9308 22.9984 10.8066C22.9984 7.68234 20.4657 5.14966 17.3415 5.14966C14.2173 5.14966 11.6846 7.68234 11.6846 10.8066C11.6846 13.9308 14.2173 16.4635 17.3415 16.4635Z" stroke="white" stroke-width="2.12134" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="form-label text-dark m-0 ms-4" id="phone">N/A</div>
                                    <div class="form-label text-sm text-gray-500 m-0 ms-4" id="email">N/A</div>
                                </div>
                            </div>
                        </div>

                        <!-- User Management -->
                        <div class="settings-section">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="settings-title m-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                                        <path d="M14.6678 19.25V17.4167C14.6678 16.4442 14.2815 15.5116 13.5939 14.8239C12.9062 14.1363 11.9736 13.75 11.0011 13.75H5.50114C4.52868 13.75 3.59605 14.1363 2.90841 14.8239C2.22078 15.5116 1.83447 16.4442 1.83447 17.4167V19.25" stroke="#3B82F6" stroke-width="2.12134" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M14.666 2.86743C15.4523 3.07127 16.1486 3.53042 16.6457 4.17282C17.1428 4.81522 17.4125 5.6045 17.4125 6.41676C17.4125 7.22903 17.1428 8.01831 16.6457 8.66071C16.1486 9.30311 15.4523 9.76226 14.666 9.9661" stroke="#3B82F6" stroke-width="2.12134" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M20.167 19.25V17.4166C20.1664 16.6042 19.896 15.815 19.3982 15.1729C18.9005 14.5308 18.2036 14.0722 17.417 13.8691" stroke="#3B82F6" stroke-width="2.12134" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M8.25114 10.0833C10.2762 10.0833 11.9178 8.44171 11.9178 6.41667C11.9178 4.39162 10.2762 2.75 8.25114 2.75C6.22609 2.75 4.58447 4.39162 4.58447 6.41667C4.58447 8.44171 6.22609 10.0833 8.25114 10.0833Z" stroke="#3B82F6" stroke-width="2.12134" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg> Employee Management
                                </div>
                                <button class="btn text-primary">Add New Employee</button>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Jane Cooper</td>
                                            <td>jane@hotelmanager.com</td>
                                            <td>Booking Manager</td>
                                            <td><span class="status-badge status-active">Active</span></td>
                                            <td>
                                                <button class="action-btn edit-btn">Edit</button>
                                                <button class="action-btn disable-btn">Disable</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Robert Fox</td>
                                            <td>roben@hotelmanager.com</td>
                                            <td>Hotel Manager</td>
                                            <td><span class="status-badge status-active">Active</span></td>
                                            <td>
                                                <button class="action-btn edit-btn">Edit</button>
                                                <button class="action-btn disable-btn">Disable</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Security Settings -->
                        <div class="settings-section">
                            <div class="settings-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M19.9995 13C19.9995 18 16.4995 20.5 12.3395 21.95C12.1217 22.0238 11.885 22.0202 11.6695 21.94C7.49951 20.5 3.99951 18 3.99951 13V5.99996C3.99951 5.73474 4.10487 5.48039 4.29241 5.29285C4.47994 5.10532 4.7343 4.99996 4.99951 4.99996C6.99951 4.99996 9.49951 3.79996 11.2395 2.27996C11.4514 2.09896 11.7209 1.99951 11.9995 1.99951C12.2782 1.99951 12.5477 2.09896 12.7595 2.27996C14.5095 3.80996 16.9995 4.99996 18.9995 4.99996C19.2647 4.99996 19.5191 5.10532 19.7066 5.29285C19.8942 5.48039 19.9995 5.73474 19.9995 5.99996V13Z" stroke="#3B82F6" stroke-width="2.12134" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg> Security Settings
                            </div>
                            
                            <form id="passwordForm">
                                <div class="mb-4">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" class="form-control" name="current_password" placeholder="••••••••">
                                    <div class="invalid-feedback">Please enter your current password</div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label">New Password</label>
                                    <input type="password" class="form-control" name="new_password" placeholder="••••••••" minlength="8">
                                    <div class="invalid-feedback">Password must be at least 8 characters</div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" name="confirm_password" placeholder="••••••••">
                                    <div class="invalid-feedback">Passwords do not match</div>
                                </div>
                                
                                <div class="w-100 text-center">
                                    <button type="submit" class="btn-primary">Update Password</button>
                                </div>
                            </form>

                            <hr class="mt-5 mb-4">
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="form-label text-dark">Two-Factor Authentication</div>
                                    <div class="text-sm text-gray-500">Secure your account with two-factor authentication</div>
                                    <span class="me-2">Currently: <strong class="text-success">Enabled</strong></span>
                                </div>
                                <div>
                                    <button class="btn btn-danger">Disable 2FA</button>
                                </div>
                            </div>
                        </div>

                        <!-- Notification Preferences -->
                        <div class="settings-section">
                            <div class="settings-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M10.2686 21C10.4441 21.304 10.6966 21.5565 11.0006 21.732C11.3046 21.9075 11.6495 21.9999 12.0006 21.9999C12.3516 21.9999 12.6965 21.9075 13.0005 21.732C13.3045 21.5565 13.557 21.304 13.7326 21" stroke="#3B82F6" stroke-width="2.12134" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M3.26273 15.326C3.1321 15.4692 3.04589 15.6472 3.01459 15.8385C2.98329 16.0298 3.00825 16.226 3.08644 16.4034C3.16463 16.5807 3.29267 16.7316 3.45499 16.8375C3.61731 16.9434 3.80691 16.9999 4.00073 17H20.0007C20.1945 17.0001 20.3842 16.9438 20.5466 16.8381C20.709 16.7324 20.8372 16.5817 20.9156 16.4045C20.994 16.2273 21.0192 16.0311 20.9882 15.8398C20.9571 15.6485 20.8712 15.4703 20.7407 15.327C19.4107 13.956 18.0007 12.499 18.0007 8C18.0007 6.4087 17.3686 4.88258 16.2434 3.75736C15.1182 2.63214 13.592 2 12.0007 2C10.4094 2 8.88331 2.63214 7.75809 3.75736C6.63288 4.88258 6.00073 6.4087 6.00073 8C6.00073 12.499 4.58973 13.956 3.26273 15.326Z" stroke="#3B82F6" stroke-width="2.12134" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg> Notification Preferences
                            </div>
                            
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="form-label">Email Notifications</div>
                                        <div class="text-sm text-gray-500">Receive booking and system alerts via email</div>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" checked>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="form-label">SMS Notifications</div>
                                        <div class="text-sm text-gray-500">Receive urgent alerts via SMS</div>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" checked>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="w-100 text-center">
                                <button class="btn-primary">Save Preferences</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include "temp/footer.php" ?>

        <script>
            // Form validation
            $(document).ready(function() {
                // Live validation for password fields
                $('#passwordForm input[type="password"]').on('input', function() {
                    validateField($(this));
                });
                
                // Password form validation and submission
                $('#passwordForm').on('submit', function(e) {
                    e.preventDefault();
                    let isValid = true;
                    
                    // Validate all fields
                    $('#passwordForm input[type="password"]').each(function() {
                        if (!validateField($(this))) {
                            isValid = false;
                        }
                    });
                    
                    if (isValid) {
                        // Get form data
                        const oldPassword = $('input[name="current_password"]').val();
                        const newPassword = $('input[name="new_password"]').val();
                        const confirmPassword = $('input[name="confirm_password"]').val();
                        
                        // Disable submit button and show loading
                        const submitBtn = $('#passwordForm button[type="submit"]');
                        const originalText = submitBtn.text();
                        submitBtn.prop('disabled', true).text('Updating...');
                        
                        // AJAX call to change password API
                        $.ajax({
                            url: '../api/admin/change',
                            method: 'POST',
                            data: {
                                old: oldPassword,
                                new: newPassword,
                                conf: confirmPassword
                            },
                            dataType: 'json',
                            success: function(response) {
                                submitBtn.prop('disabled', false).text(originalText);
                                
                                if (response.success) {
                                    // Show success message
                                    showToast('Success', response.message, 'success');
                                    
                                    // Reset form
                                    $('#passwordForm')[0].reset();
                                    
                                    // Remove any validation classes
                                    $('#passwordForm .form-control').removeClass('is-invalid is-valid');
                                } else {
                                    // Show error message
                                    showToast('Error', response.message, 'error');
                                }
                            },
                            error: function(xhr, status, error) {
                                submitBtn.prop('disabled', false).text(originalText);
                                
                                // Show error message from API response if available
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    showToast('Error', xhr.responseJSON.message, 'error');
                                } else {
                                    showToast('Error', 'An error occurred while updating password. Please try again.', 'error');
                                }
                                
                                console.error('Password update error:', error);
                            }
                        });
                    }
                });
                
                // Validate individual field
                function validateField(field) {
                    const fieldName = field.attr('name');
                    const value = field.val();
                    let isValid = true;
                    let errorMessage = '';
                    
                    // Clear previous validation state
                    field.removeClass('is-invalid is-valid');
                    field.next('.invalid-feedback').remove();
                    
                    switch(fieldName) {
                        case 'current_password':
                            if (!value.trim()) {
                                isValid = false;
                                errorMessage = 'Please enter your current password';
                            }
                            break;
                            
                        case 'new_password':
                            if (!value.trim()) {
                                isValid = false;
                                errorMessage = 'Please enter a new password';
                            } else if (value.length < 8) {
                                isValid = false;
                                errorMessage = 'Password must be at least 8 characters';
                            }
                            break;
                            
                        case 'confirm_password':
                            const newPassword = $('input[name="new_password"]').val();
                            if (!value.trim()) {
                                isValid = false;
                                errorMessage = 'Please confirm your new password';
                            } else if (value !== newPassword) {
                                isValid = false;
                                errorMessage = 'Passwords do not match';
                            }
                            break;
                    }
                    
                    if (!isValid) {
                        field.addClass('is-invalid');
                        field.after(`<div class="invalid-feedback">${errorMessage}</div>`);
                    } else {
                        field.addClass('is-valid');
                    }
                    
                    return isValid;
                }
                
                // Handle edit user buttons
                $('.edit-btn').on('click', function() {
                    const row = $(this).closest('tr');
                    const name = row.find('td:first').text();
                    alert(`Edit user: ${name}`);
                });
                
                // Handle disable user buttons
                $('.disable-btn').on('click', function() {
                    const row = $(this).closest('tr');
                    const name = row.find('td:first').text();
                    
                    if (confirm(`Are you sure you want to disable ${name}?`)) {
                        const statusBadge = row.find('.status-badge');
                        statusBadge.text('Inactive');
                        statusBadge.removeClass('status-active').addClass('status-inactive');
                        
                        $(this).text('Enable')
                            .removeClass('disable-btn')
                            .addClass('edit-btn');
                        
                        showToast('Success', `${name} has been disabled.`, 'success');
                    }
                });
            });

            function loadAdmin() {
                $.get("../api/admin/list", function (response) {
                    if (response.success && response.data) {
                        $("#email").html(response.data.email);
                        $("#phone").html(response.data.phone);
                    } else {
                        $("#email").html('N/A');
                        $("#phone").html('N/A');
                    }
                }, "json").fail(function(xhr, status, error) {
                    console.error("Error loading admin:", error);
                    $("#email").html('N/A');
                    $("#phone").html('N/A');
                });
            }

            loadAdmin();
        </script>
    </body>
</html>