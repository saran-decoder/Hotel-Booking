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
        <title>TNBooking - Add Rooms</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <?php include "temp/head.php" ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            .is-invalid {
                border-color: #dc3545 !important;
            }
            .invalid-feedback {
                display: none;
                width: 100%;
                margin-top: 0.25rem;
                font-size: 0.875em;
                color: #dc3545;
            }
            .was-validated .form-control:invalid ~ .invalid-feedback,
            .form-control.is-invalid ~ .invalid-feedback {
                display: block;
            }
            .preview-item {
                position: relative;
                display: inline-block;
                margin: 5px;
                width: 100px;
                height: 100px;
            }
            .preview-item img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 4px;
            }
            .remove-btn {
                position: absolute;
                top: -10px;
                right: -10px;
                width: 24px;
                height: 24px;
                border-radius: 50%;
                background: #dc3545;
                color: white;
                border: none;
                font-size: 16px;
                line-height: 1;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .dropzone {
                border: 2px dashed #dee2e6;
                border-radius: 5px;
                padding: 20px;
                text-align: center;
                cursor: pointer;
                position: relative;
                transition: all 0.3s;
            }
            .dropzone.dragover {
                border-color: #007bff;
                background-color: rgba(0, 123, 255, 0.05);
            }
            .dropzone-text {
                color: #6c757d;
            }
            #fileInput {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                opacity: 0;
                cursor: pointer;
            }
            .form-check {
                margin-right: 1.5rem;
                margin-bottom: 0.5rem;
            }
            .amenity-icon {
                width: 20px;
                margin-right: 5px;
                color: #007BFF;
            }
        </style>
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
                    
                    <div class="card m-5 p-4">
                        <h4>Add New Room</h4>
                        <form id="roomForm" novalidate>
                            <input type="hidden" id="hotelID" value="<?= $_GET['id'] ?>">
                            <div class="row">
                                <!-- Room Type -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Room Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="roomType" required>
                                        <option value="" disabled selected>Select room type</option>
                                        <option value="standard">Standard</option>
                                        <option value="deluxe">Deluxe</option>
                                        <option value="suite">Suite</option>
                                        <option value="family">Family</option>
                                        <option value="executive">Executive</option>
                                    </select>
                                    <div class="invalid-feedback">Please select a room type.</div>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Guests Allowed <span class="text-danger">*</span></label>
                                    <input type="number" id="guestsAllowed" class="form-control" placeholder="e.g., 2" min="1" required />
                                    <div class="invalid-feedback">Please provide a valid number of guests.</div>
                                </div>
                            </div>

                            <!-- Slideshow Images -->
                            <div class="mb-3">
                                <label class="form-label">Room Images <span class="text-danger">*</span></label>
                                <div class="dropzone-container">
                                    <div class="dropzone" id="uploadArea">
                                        <span class="dropzone-text">
                                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i><br />
                                            Click or drag and drop images here<br />PNG, JPG or WEBP. Max: 10MB
                                        </span>
                                        <input type="file" id="fileInput" multiple accept="image/png, image/jpeg, image/webp">
                                        <div id="previewArea" class="mt-3"></div>
                                    </div>
                                    <div class="invalid-feedback" id="imageValidation">Please upload at least one image.</div>
                                </div>
                            </div>

                            <!-- Room Description -->
                            <div class="mb-3">
                                <label class="form-label">Room Description <span class="text-danger">*</span></label>
                                <textarea id="roomDescription" class="form-control" rows="4" placeholder="Describe the room features and amenities" required></textarea>
                                <div class="invalid-feedback">Please provide a room description.</div>
                            </div>
                            
                            <!-- Price per Night -->
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Price per Night (₹) <span class="text-danger">*</span></label>
                                <input type="number" id="pricePerNight" class="form-control" placeholder="0.00" step="0.01" min="0" required>
                                <div class="invalid-feedback">Please provide a valid price.</div>
                            </div>

                            <!-- Amenities -->
                            <div class="mb-3">
                                <label class="form-label d-block">Amenities</label>
                                <div class="d-flex flex-wrap">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="wifi" id="wifi" />
                                        <label class="form-check-label" for="wifi">
                                            <i class="fas fa-wifi amenity-icon"></i> Free WiFi
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="air_conditioning" id="air" />
                                        <label class="form-check-label" for="air">
                                            <i class="fas fa-snowflake amenity-icon"></i> Air Conditioning
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="swimming_pool" id="pool" />
                                        <label class="form-check-label" for="pool">
                                            <i class="fas fa-swimming-pool amenity-icon"></i> Swimming Pool
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="restaurant" id="restaurant" />
                                        <label class="form-check-label" for="restaurant">
                                            <i class="fas fa-utensils amenity-icon"></i> Restaurant
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="parking" id="parking" />
                                        <label class="form-check-label" for="parking">
                                            <i class="fas fa-parking amenity-icon"></i> Parking
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="fitness_center" id="gym" />
                                        <label class="form-check-label" for="gym">
                                            <i class="fas fa-dumbbell amenity-icon"></i> Fitness Center
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="bar" id="bar" />
                                        <label class="form-check-label" for="bar">
                                            <i class="fas fa-glass-martini-alt amenity-icon"></i> Bar
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="others" />
                                        <label class="form-check-label" for="others">
                                            <i class="fas fa-ellipsis-h amenity-icon"></i> Others
                                        </label>
                                    </div>

                                    <!-- Hidden input box for custom amenity -->
                                    <div id="customAmenityInput" style="display: none; margin-top: 8px; width: 100%;">
                                        <input type="text" id="customAmenity" name="customAmenity" class="form-control" placeholder="Enter custom amenity" />
                                        <div class="invalid-feedback">Please provide a custom amenity name.</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex justify-content-end">
                                <a href="hotels-rooms" type="button" class="btn btn-outline-secondary me-2 align-content-center">Cancel</a>
                                <button type="submit" class="btn btn-primary">Add Room</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php include "temp/footer.php" ?>

        <script>
            $(document).ready(function () {
                const dropzone = $('#uploadArea');
                const fileInput = $('#fileInput');
                const previewArea = $('#previewArea');
                const dropzoneText = $('.dropzone-text');
                let filesArray = [];

                // Make file input cover the entire dropzone
                fileInput.css({
                    'width': dropzone.outerWidth(),
                    'height': dropzone.outerHeight()
                });

                // Handle drag events
                dropzone.on('dragover', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.addClass('dragover');
                });

                dropzone.on('dragleave', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.removeClass('dragover');
                });

                dropzone.on('drop', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.removeClass('dragover');

                    const files = e.originalEvent.dataTransfer.files;
                    if (files.length > 0) {
                        handleFiles(files);
                    }
                });

                // Handle file selection via input
                fileInput.on('change', function () {
                    const files = fileInput[0].files;
                    if (files.length > 0) {
                        handleFiles(files);
                    }
                });

                // Function to handle file uploads and previews
                function handleFiles(files) {
                    for (let file of files) {
                        if (!file.type.startsWith('image/')) {
                            showToast('Error', 'Only image files are allowed.', 'error');
                            continue;
                        }

                        if (file.size > 10 * 1024 * 1024) {
                            showToast('Error', 'File size must be less than 10MB.', 'error');
                            continue;
                        }

                        // Check if file already exists
                        if (filesArray.some(f => f.name === file.name && f.size === file.size && f.lastModified === file.lastModified)) {
                            continue;
                        }

                        // Add file to our array
                        filesArray.push(file);

                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const previewItem = $('<div class="preview-item">');
                            const img = $('<img>').attr('src', e.target.result);
                            const removeBtn = $('<button class="remove-btn" title="Remove image">&times;</button>');
                            
                            removeBtn.on('click', function(e) {
                                e.stopPropagation(); // Prevent triggering the dropzone click
                                // Remove the file from our array
                                const index = filesArray.findIndex(f => 
                                    f.name === file.name && 
                                    f.size === file.size && 
                                    f.lastModified === file.lastModified
                                );
                                if (index !== -1) {
                                    filesArray.splice(index, 1);
                                }
                                // Remove the preview item
                                previewItem.remove();
                                // Update the file input
                                updateFileInput();
                                
                                // Show dropzone text if no images left
                                if (filesArray.length === 0) {
                                    dropzoneText.show();
                                }
                                
                                // Validate images
                                validateImages();
                            });

                            previewItem.append(img);
                            previewItem.append(removeBtn);
                            previewArea.append(previewItem);
                            
                            // Hide dropzone text when we have images
                            if (filesArray.length > 0) {
                                dropzoneText.hide();
                            }
                            
                            // Validate images
                            validateImages();
                        };
                        reader.readAsDataURL(file);
                    }
                    // Update the file input with our files array
                    updateFileInput();
                }

                // Function to update the file input with our files array
                function updateFileInput() {
                    // Create a new DataTransfer object
                    const dataTransfer = new DataTransfer();
                    
                    // Add each file to the DataTransfer object
                    filesArray.forEach(file => {
                        dataTransfer.items.add(file);
                    });
                    
                    // Update the file input with the new files
                    fileInput[0].files = dataTransfer.files;
                }
                
                // Function to validate images
                function validateImages() {
                    const imageValidation = $('#imageValidation');
                    if (filesArray.length === 0) {
                        dropzone.addClass('is-invalid');
                        imageValidation.show();
                    } else {
                        dropzone.removeClass('is-invalid');
                        imageValidation.hide();
                    }
                }

                // Handle window resize to adjust file input size
                $(window).resize(function() {
                    fileInput.css({
                        'width': dropzone.outerWidth(),
                        'height': dropzone.outerHeight()
                    });
                });
                
                // Handle others checkbox
                $('#others').change(function () {
                    if ($(this).is(':checked')) {
                        $('#customAmenityInput').slideDown(); // Show input box
                        $('#customAmenity').prop('required', true);
                    } else {
                        $('#customAmenityInput').slideUp();   // Hide input box
                        $('#customAmenity').prop('required', false);
                        $('#customAmenity').removeClass('is-invalid');
                    }
                });
                
                // Form validation
                $('#roomForm').on('submit', function(e) {
                    e.preventDefault();
                    
                    // Reset validation state
                    $(this).removeClass('was-validated');
                    $('.form-control').removeClass('is-invalid');
                    $('.form-select').removeClass('is-invalid');
                    $('#customAmenity').removeClass('is-invalid');
                    
                    // Validate all fields
                    let isValid = true;
                    
                    // Check required fields
                    const requiredFields = [
                        '#roomType', 
                        '#guestsAllowed', 
                        '#roomDescription', 
                        '#pricePerNight'
                    ];
                    
                    requiredFields.forEach(field => {
                        const $field = $(field);
                        if (!$field.val() || $field.val() === "") {
                            $field.addClass('is-invalid');
                            isValid = false;
                            
                            // Special handling for select elements
                            if (field === '#roomType') {
                                $field.next('.invalid-feedback').text('Please select a room type.');
                            }
                        }
                    });
                    
                    // Validate numeric fields
                    const numericFields = [
                        {id: '#guestsAllowed', min: 1, message: 'Please provide a valid number of guests (minimum 1).'},
                        {id: '#pricePerNight', min: 0.01, message: 'Please provide a valid price (minimum ₹0.01).'}
                    ];
                    
                    numericFields.forEach(field => {
                        const $field = $(field.id);
                        const value = parseFloat($field.val());
                        
                        if (isNaN(value) || value < field.min) {
                            $field.addClass('is-invalid');
                            $field.next('.invalid-feedback').text(field.message);
                            isValid = false;
                        }
                    });
                    
                    // Validate room description length
                    const description = $('#roomDescription').val().trim();
                    if (description.length < 10) {
                        $('#roomDescription').addClass('is-invalid');
                        $('#roomDescription').next('.invalid-feedback').text('Description must be at least 10 characters long.');
                        isValid = false;
                    }
                    
                    // Validate images
                    if (filesArray.length === 0) {
                        $('#imageValidation').show();
                        dropzone.addClass('is-invalid');
                        isValid = false;
                    } else if (filesArray.length > 10) {
                        $('#imageValidation').text('Maximum 10 images allowed.').show();
                        dropzone.addClass('is-invalid');
                        isValid = false;
                    }
                    
                    // Validate custom amenity if others is checked
                    if ($('#others').is(':checked')) {
                        const customAmenity = $('#customAmenity').val().trim();
                        if (!customAmenity) {
                            $('#customAmenity').addClass('is-invalid');
                            isValid = false;
                        } else if (customAmenity.length < 2) {
                            $('#customAmenity').addClass('is-invalid');
                            $('#customAmenity').next('.invalid-feedback').text('Custom amenity must be at least 2 characters long.');
                            isValid = false;
                        }
                    }
                    
                    if (!isValid) {
                        // Add was-validated class to show validation messages
                        $(this).addClass('was-validated');
                        
                        // Scroll to first invalid field
                        $('html, body').animate({
                            scrollTop: $('.is-invalid').first().offset().top - 100
                        }, 500);
                        
                        return false;
                    }
                    
                    // If all validations pass, proceed with form submission
                    submitForm();
                });

                // Real-time validation for inputs
                $('input, textarea, select').on('input change', function() {
                    const $this = $(this);
                    $this.removeClass('is-invalid');
                    
                    // Special handling for numeric validation
                    if ($this.attr('type') === 'number' && $this.val()) {
                        const min = parseFloat($this.attr('min')) || 0;
                        const value = parseFloat($this.val());
                        
                        if (isNaN(value) || value < min) {
                            $this.addClass('is-invalid');
                        }
                    }
                    
                    // Special handling for description
                    if (this.id === 'roomDescription') {
                        const description = $this.val().trim();
                        if (description.length > 0 && description.length < 10) {
                            $this.addClass('is-invalid');
                            $this.next('.invalid-feedback').text('Description must be at least 10 characters long.');
                        }
                    }
                    
                    // Special handling for custom amenity
                    if (this.id === 'customAmenity' && $('#others').is(':checked')) {
                        const customAmenity = $this.val().trim();
                        if (customAmenity.length > 0 && customAmenity.length < 2) {
                            $this.addClass('is-invalid');
                            $this.next('.invalid-feedback').text('Custom amenity must be at least 2 characters long.');
                        }
                    }
                });

                // Add validation for room type selection
                $('#roomType').on('change', function() {
                    if ($(this).val()) {
                        $(this).removeClass('is-invalid');
                    }
                });
                
                // Function to show toast notification
                function showToast(title, message, type = 'info') {
                    // Remove any existing toasts first
                    $('.toast-container').remove();
                    
                    // Create toast container if it doesn't exist
                    if ($('.toast-container').length === 0) {
                        $('body').append('<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1090;"></div>');
                    }
                    
                    const toastId = 'toast-' + Date.now();
                    const toastHtml = `
                        <div id="${toastId}" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="toast-header">
                                <strong class="me-auto">${title}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                            <div class="toast-body">
                                ${message}
                            </div>
                        </div>
                    `;
                    
                    $('.toast-container').append(toastHtml);
                    
                    // Add appropriate styling based on type
                    const toastElement = $('#' + toastId);
                    if (type === 'success') {
                        toastElement.find('.toast-header').addClass('bg-success text-white');
                    } else if (type === 'error') {
                        toastElement.find('.toast-header').addClass('bg-danger text-white');
                    } else if (type === 'warning') {
                        toastElement.find('.toast-header').addClass('bg-warning text-dark');
                    } else {
                        toastElement.find('.toast-header').addClass('bg-info text-white');
                    }
                    
                    // Initialize and show the toast
                    const toast = new bootstrap.Toast(toastElement[0], {
                        autohide: true,
                        delay: 5000
                    });
                    toast.show();
                    
                    // Remove toast from DOM after it's hidden
                    toastElement.on('hidden.bs.toast', function () {
                        $(this).remove();
                    });
                }
                
                // Function to submit form via AJAX
                function submitForm() {
                    // Collect form data
                    const formData = new FormData();
                    formData.append('hotelID', $('#hotelID').val());
                    formData.append('roomType', $('#roomType').val());
                    formData.append('guestsAllowed', $('#guestsAllowed').val());
                    formData.append('roomDescription', $('#roomDescription').val().trim());
                    formData.append('pricePerNight', $('#pricePerNight').val());
                    
                    // Get selected amenities
                    const amenities = [];
                    $('input[name="amenities[]"]:checked').each(function() {
                        amenities.push($(this).val());
                    });
                    
                    // Add custom amenity if others is checked
                    if ($('#others').is(':checked') && $('#customAmenity').val().trim()) {
                        amenities.push($('#customAmenity').val().trim());
                    }
                    
                    formData.append('amenities', JSON.stringify(amenities));
                    
                    // Add files
                    for (let i = 0; i < filesArray.length; i++) {
                        formData.append('images[]', filesArray[i]);
                    }
                    
                    // Show loading state
                    const submitBtn = $('#roomForm').find('button[type="submit"]');
                    const originalText = submitBtn.html();
                    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
                    
                    // Send AJAX request
                    $.ajax({
                        url: '../api/hotel/add-room',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            try {
                                const data = typeof response === 'string' ? JSON.parse(response) : response;
                                
                                if (data.success) {
                                    // Success toast
                                    showToast('Success', data.message || 'Room added successfully!', 'success');
                                    
                                    // Reset form after a short delay
                                    setTimeout(function() {
                                        $('#roomForm')[0].reset();
                                        filesArray = [];
                                        previewArea.empty();
                                        dropzoneText.show();
                                        validateImages();
                                        // Reset amenities checkboxes
                                        $('input[name="amenities[]"]').prop('checked', false);
                                        $('#others').prop('checked', false);
                                        $('#customAmenityInput').hide();
                                        window.location.href = 'hotel';
                                    }, 1500);
                                } else {
                                    // Error toast
                                    showToast('Error', data.message || 'Failed to add room. Please try again.', 'error');
                                }
                            } catch (e) {
                                showToast('Error', 'Invalid response from server. Please try again.', 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            // Error toast
                            let errorMsg = 'An error occurred while adding the room. Please try again.';
                            
                            try {
                                const response = JSON.parse(xhr.responseText);
                                if (response && response.message) {
                                    errorMsg = response.message;
                                }
                            } catch (e) {
                                // Use default error message
                            }
                            
                            showToast('Error', errorMsg, 'error');
                        },
                        complete: function() {
                            // Reset button state
                            submitBtn.prop('disabled', false).html(originalText);
                        }
                    });
                }
            });
        </script>
        
    </body>
</html>