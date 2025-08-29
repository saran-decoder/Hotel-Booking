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
                                <button class="btn text-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">Add New Employee</button>
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
                                        <!-- Employee data will be loaded dynamically -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Add Employee Modal -->
                        <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addEmployeeModalLabel">Add New Employee</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="addEmployeeForm">
                                            <div class="mb-3">
                                                <label for="employeeName" class="form-label">Name</label>
                                                <input type="text" class="form-control" id="employeeName" name="name" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="employeeEmail" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="employeeEmail" name="email" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="employeeRole" class="form-label">Role</label>
                                                <input type="text" class="form-control" id="employeeRole" name="role" required>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn btn-primary" id="saveEmployeeBtn">Save Employee</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Edit Employee Modal -->
                        <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editEmployeeModalLabel">Edit Employee</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="editEmployeeForm">
                                            <input type="hidden" id="editEmployeeId" name="id">
                                            <div class="mb-3">
                                                <label for="editEmployeeName" class="form-label">Name</label>
                                                <input type="text" class="form-control" id="editEmployeeName" name="name" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="editEmployeeEmail" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="editEmployeeEmail" name="email" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="editEmployeeRole" class="form-label">Role</label>
                                                <input type="text" class="form-control" id="editEmployeeRole" name="role" required>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn btn-primary" id="updateEmployeeBtn">Update Employee</button>
                                    </div>
                                </div>
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
                    </div>
                </div>
            </div>
        </div>

        <?php include "temp/footer.php" ?>

        <script>
            $(document).ready(function() {
                // Load employees function
                function loadEmployees() {
                    $.get("../api/admin/list-emp", function(response) {
                        console.log("API Response:", response); // Debug log
                        
                        if (response.success && response.data) {
                            const tbody = $('.table tbody');
                            tbody.empty();
                            
                            if (response.data.length === 0) {
                                tbody.append('<tr><td colspan="5" class="text-center">No employees found</td></tr>');
                                return;
                            }
                            
                            response.data.forEach(function(employee) {
                                // Determine the status class based on employee status
                                const statusClass = employee.status && employee.status.toLowerCase() === 'active' 
                                    ? 'status-active' 
                                    : 'status-inactive';
                                
                                // Determine button text based on status
                                const buttonText = employee.status && employee.status.toLowerCase() === 'active' 
                                    ? 'Disable' 
                                    : 'Enable';
                                
                                // Determine button class based on status
                                const buttonClass = employee.status && employee.status.toLowerCase() === 'active' 
                                    ? 'disable-btn' 
                                    : 'enable-btn';
                                
                                const row = `
                                    <tr>
                                        <td>${employee.name || 'N/A'}</td>
                                        <td>${employee.email || 'N/A'}</td>
                                        <td>${employee.role || 'N/A'}</td>
                                        <td><span class="status-badge ${statusClass}">${employee.status || 'Unknown'}</span></td>
                                        <td>
                                            <button class="action-btn edit-btn" data-id="${employee.id}">Edit</button>
                                            <button class="action-btn ${buttonClass}" data-id="${employee.id}">${buttonText}</button>
                                        </td>
                                    </tr>
                                `;
                                tbody.append(row);
                            });
                        } else {
                            $('.table tbody').html('<tr><td colspan="5" class="text-center">Error loading employees: ' + (response.message || 'Unknown error') + '</td></tr>');
                        }
                    }, "json").fail(function(xhr, status, error) {
                        console.error("Error loading employees:", error);
                        $('.table tbody').html('<tr><td colspan="5" class="text-center">Error loading employees. Please check console for details.</td></tr>');
                    });
                }

                // Load admin function
                function loadAdmin() {
                    $.get("../api/admin/list", function(response) {
                        console.log("Admin API Response:", response); // Debug log
                        
                        if (response.success && response.data) {
                            $("#email").html(response.data.email || 'N/A');
                            $("#phone").html(response.data.phone || 'N/A');
                        } else {
                            $("#email").html('N/A');
                            $("#phone").html('N/A');
                            console.error("Error loading admin:", response.message);
                        }
                    }, "json").fail(function(xhr, status, error) {
                        console.error("Error loading admin:", error);
                        $("#email").html('N/A');
                        $("#phone").html('N/A');
                    });
                }

                // Load employees and admin on page load
                loadAdmin();
                loadEmployees();

                // Rest of your existing code (form validation, button handlers, etc.)
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
                $(document).on('click', '.edit-btn', function() {
                    const row = $(this).closest('tr');
                    const id = $(this).data('id');
                    const name = row.find('td:eq(0)').text();
                    const email = row.find('td:eq(1)').text();
                    const role = row.find('td:eq(2)').text();
                    
                    // Populate edit modal with current data
                    $('#editEmployeeId').val(id);
                    $('#editEmployeeName').val(name);
                    $('#editEmployeeEmail').val(email);
                    $('#editEmployeeRole').val(role);
                    $('#editEmployeePassword').val('');
                    
                    // Show edit modal
                    $('#editEmployeeModal').modal('show');
                });
                
                // Handle disable/enable user buttons
                $(document).on('click', '.disable-btn, .enable-btn', function() {
                    const row = $(this).closest('tr');
                    const id = $(this).data('id');
                    const name = row.find('td:eq(0)').text();
                    const isDisable = $(this).hasClass('disable-btn');
                    const action = isDisable ? 'disable' : 'enable';
                    
                    Swal.fire({
                        title: 'Are you sure?',
                        text: `Do you want to ${action} ${name}?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: `Yes, ${action} it!`
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // AJAX call to disable/enable employee API
                            $.ajax({
                                url: `../api/admin/${action}`,
                                method: 'POST',
                                data: {
                                    id: id
                                },
                                dataType: 'json',
                                success: function(response) {
                                    if (response.success) {
                                        const statusBadge = row.find('.status-badge');
                                        
                                        if (isDisable) {
                                            statusBadge.text('Inactive');
                                            statusBadge.removeClass('status-active').addClass('status-inactive');
                                            
                                            $(this).text('Enable')
                                                .removeClass('disable-btn')
                                                .addClass('enable-btn');
                                        } else {
                                            statusBadge.text('Active');
                                            statusBadge.removeClass('status-inactive').addClass('status-active');
                                            
                                            $(this).text('Disable')
                                                .removeClass('enable-btn')
                                                .addClass('disable-btn');
                                        }
                                        
                                        Swal.fire(
                                            'Success!',
                                            `${name} has been ${action}d.`,
                                            'success'
                                        );
                                        
                                        // Refresh the table to ensure consistency
                                        loadEmployees();
                                    } else {
                                        Swal.fire(
                                            'Error!',
                                            response.message,
                                            'error'
                                        );
                                    }
                                },
                                error: function(xhr, status, error) {
                                    // Show error message from API response if available
                                    if (xhr.responseJSON && xhr.responseJSON.message) {
                                        Swal.fire(
                                            'Error!',
                                            xhr.responseJSON.message,
                                            'error'
                                        );
                                    } else {
                                        Swal.fire(
                                            'Error!',
                                            `An error occurred while ${action}ing employee. Please try again.`,
                                            'error'
                                        );
                                    }
                                    
                                    console.error(`Employee ${action} error:`, error);
                                }
                            });
                        }
                    });
                });
                
                // Handle add employee form submission
                $('#saveEmployeeBtn').on('click', function() {
                    const form = $('#addEmployeeForm');
                    const name = $('#employeeName').val().trim();
                    const email = $('#employeeEmail').val().trim();
                    const role = $('#employeeRole').val();
                    
                    // Basic validation
                    if (!name || !email || !role) {
                        showToast('Error', 'Please fill in all fields', 'error');
                        return;
                    }
                    
                    // Disable save button and show loading
                    const saveBtn = $(this);
                    const originalText = saveBtn.text();
                    saveBtn.prop('disabled', true).text('Saving...');
                    
                    // AJAX call to add employee API
                    $.ajax({
                        url: '../api/admin/add',
                        method: 'POST',
                        data: {
                            name: name,
                            email: email,
                            role: role
                        },
                        dataType: 'json',
                        success: function(response) {
                            saveBtn.prop('disabled', false).text(originalText);
                            
                            if (response.success) {
                                // Show success message
                                showToast('Success', response.message, 'success');
                                
                                // Reset form and hide modal
                                form[0].reset();
                                $('#addEmployeeModal').modal('hide');
                                
                                // Refresh employee table
                                loadEmployees();
                            } else {
                                // Show error message
                                showToast('Error', response.message, 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            saveBtn.prop('disabled', false).text(originalText);
                            
                            // Show error message from API response if available
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                showToast('Error', xhr.responseJSON.message, 'error');
                            } else {
                                showToast('Error', 'An error occurred while adding employee. Please try again.', 'error');
                            }
                            
                            console.error('Employee add error:', error);
                        }
                    });
                });
                
                // Handle edit employee form submission
                $('#updateEmployeeBtn').on('click', function() {
                    const form = $('#editEmployeeForm');
                    const id = $('#editEmployeeId').val();
                    const name = $('#editEmployeeName').val().trim();
                    const email = $('#editEmployeeEmail').val().trim();
                    const role = $('#editEmployeeRole').val();
                    const password = $('#editEmployeePassword').val();
                    
                    // Basic validation
                    if (!id || !name || !email || !role) {
                        showToast('Error', 'Please fill in all required fields', 'error');
                        return;
                    }
                    
                    // Validate password if provided
                    if (password && password.length < 8) {
                        showToast('Error', 'Password must be at least 8 characters', 'error');
                        return;
                    }
                    
                    // Disable update button and show loading
                    const updateBtn = $(this);
                    const originalText = updateBtn.text();
                    updateBtn.prop('disabled', true).text('Updating...');
                    
                    // Prepare data for API call
                    const data = {
                        id: id,
                        name: name,
                        email: email,
                        role: role
                    };
                    
                    // Add password to data if provided
                    if (password) {
                        data.password = password;
                    }
                    
                    // AJAX call to edit employee API
                    $.ajax({
                        url: '../api/admin/edit',
                        method: 'POST',
                        data: data,
                        dataType: 'json',
                        success: function(response) {
                            updateBtn.prop('disabled', false).text(originalText);
                            
                            if (response.success) {
                                // Show success message
                                showToast('Success', response.message, 'success');
                                
                                // Hide modal
                                $('#editEmployeeModal').modal('hide');
                                
                                // Refresh employee table
                                loadEmployees();
                            } else {
                                // Show error message
                                showToast('Error', response.message, 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            updateBtn.prop('disabled', false).text(originalText);
                            
                            // Show error message from API response if available
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                showToast('Error', xhr.responseJSON.message, 'error');
                            } else {
                                showToast('Error', 'An error occurred while updating employee. Please try again.', 'error');
                            }
                            
                            console.error('Employee update error:', error);
                        }
                    });
                });
            });
        </script>
    </body>
</html>