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

    if (!isset($_GET['id'])) {
        header("Location: hotels-rooms");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>TNBooking - Edit Hotel</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <?php include "temp/head.php" ?>
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
                    <?php include "temp/header.php" ?>
                    <div class="card m-5 p-4">
                        <h4 id="pageTitle">Edit Hotel</h4>
                        <form id="hotelForm" novalidate>
                            <input type="hidden" id="hotelId" name="hotelId" />
                            <!-- Hotel Details -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label">Hotel Name <span class="text-danger">*</span></label>
                                    <input type="text" id="hotelName" name="hotelName" class="form-control" required />
                                    <div class="invalid-feedback">Please provide a hotel name.</div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Location Name <span class="text-danger">*</span></label>
                                    <input type="text" id="locationName" name="locationName" class="form-control" required />
                                    <div class="invalid-feedback">Please provide a location name.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Hotel Map Link <span class="text-danger">*</span></label>
                                    <input type="url" id="mapCoordinates" name="mapCoordinates" class="form-control" required />
                                    <div class="invalid-feedback">Please provide a valid map URL.</div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Full Address <span class="text-danger">*</span></label>
                                <input type="text" id="fullAddress" name="fullAddress" class="form-control" required />
                                <div class="invalid-feedback">Please provide a full address.</div>
                            </div>
                            <!-- Slideshow Images -->
                            <div class="mb-3">
                                <label class="form-label">Slideshow Images <span class="text-danger">*</span></label>
                                <div class="dropzone-container">
                                    <div class="dropzone" id="uploadArea">
                                        <span class="dropzone-text">
                                            Click or drag and drop images here<br />
                                            PNG, JPG or WEBP. Max: 10MB
                                        </span>
                                        <input type="file" id="fileInput" multiple accept="image/png, image/jpeg, image/webp" />
                                        <div id="previewArea" class="mt-3"></div>
                                    </div>
                                    <div class="invalid-feedback" id="imageValidation">Please upload at least one image.</div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Hotel Description <span class="text-danger">*</span></label>
                                <textarea id="hotelDescription" name="hotelDescription" class="form-control" rows="4" required></textarea>
                                <div class="invalid-feedback">Please provide a hotel description.</div>
                            </div>
                            <!-- Amenities -->
                            <div class="mb-3">
                                <label class="form-label d-block">Amenities</label>
                                <div class="d-flex flex-wrap" id="amenityChecks">
                                    <div class="form-check me-4 mb-2">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="wifi" id="wifi" />
                                        <label class="form-check-label" for="wifi">Free WiFi</label>
                                    </div>
                                    <div class="form-check me-4 mb-2">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="air_conditioning" id="air" />
                                        <label class="form-check-label" for="air">Air Conditioning</label>
                                    </div>
                                    <div class="form-check me-4 mb-2">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="swimming_pool" id="pool" />
                                        <label class="form-check-label" for="pool">Swimming Pool</label>
                                    </div>
                                    <div class="form-check me-4 mb-2">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="restaurant" id="restaurant" />
                                        <label class="form-check-label" for="restaurant">Restaurant</label>
                                    </div>
                                    <div class="form-check me-4 mb-2">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="parking" id="parking" />
                                        <label class="form-check-label" for="parking">Parking</label>
                                    </div>
                                    <div class="form-check me-4 mb-2">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="fitness_center" id="gym" />
                                        <label class="form-check-label" for="gym">Fitness Center</label>
                                    </div>
                                    <div class="form-check me-4 mb-2">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="bar" id="bar" />
                                        <label class="form-check-label" for="bar">Bar</label>
                                    </div>
                                    <div class="form-check me-4 mb-2">
                                        <input class="form-check-input" type="checkbox" value="others" id="others" />
                                        <label class="form-check-label" for="others">Others</label>
                                    </div>
                                    <div id="customAmenityInput" style="display: none; margin-top: 8px; width: 100%;">
                                        <input type="text" id="customAmenity" name="customAmenity" class="form-control" placeholder="Enter custom amenity" />
                                        <div class="invalid-feedback">Please provide a custom amenity name.</div>
                                    </div>
                                </div>
                            </div>
                            <!-- Buttons -->
                            <div class="d-flex justify-content-end">
                                <a href="hotels-rooms.php" type="button" class="btn btn-outline-secondary me-2 align-content-center">Cancel</a>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php include "temp/footer.php" ?>

        <script>
            $(document).ready(function () {
                // --------- Image data ---------
                let existingImages = [];
                let deletedExistingImages = [];
                let newFilesArray = [];

                // Helpers
                function getParam(name) {
                    const url = new URL(window.location.href);
                    return url.searchParams.get(name);
                }
                
                const hotelId = getParam("id");
                if (hotelId) {
                    $("#hotelId").val(hotelId);
                    $("#pageTitle").text("Edit Hotel");
                    loadHotelData(hotelId);
                } else {
                    $("#pageTitle").text("Add New Hotel");
                }

                // --------- Load Existing Data (for Edit) ---------
                function loadHotelData(hotelId) {
                    $.getJSON("../api/hotel/get?id=" + hotelId, function (data) {
                        // Check if data is an array and get the first item
                        const hotelData = Array.isArray(data) && data.length > 0 ? data[0] : data;
                        
                        if (!hotelData) {
                            showToast("Error", "Failed to load hotel data.", "error");
                            return;
                        }
                        
                        $("#hotelName").val(hotelData.name);
                        $("#locationName").val(hotelData.location_name);
                        $("#mapCoordinates").val(hotelData.coordinates);
                        $("#fullAddress").val(hotelData.address);
                        $("#hotelDescription").val(hotelData.description);

                        // Parse amenities from JSON string
                        let amenities = [];
                        try {
                            amenities = JSON.parse(hotelData.amenities);
                        } catch (e) {
                            console.error("Error parsing amenities:", e);
                        }

                        // Set amenities checkboxes
                        if (Array.isArray(amenities)) {
                            amenities.forEach(function (amen) {
                                if ($('#amenityChecks input[value="' + amen + '"]').length) {
                                    $('#amenityChecks input[value="' + amen + '"]').prop("checked", true);
                                } else {
                                    $("#others").prop("checked", true);
                                    $("#customAmenityInput").show();
                                    $("#customAmenity").val(amen);
                                }
                            });
                        }
                        
                        // Parse and set images
                        let images = [];
                        try {
                            images = JSON.parse(hotelData.images);
                        } catch (e) {
                            console.error("Error parsing images:", e);
                        }
                        
                        if (Array.isArray(images)) {
                            existingImages = images.slice();
                            for (let img of existingImages) {
                                addImagePreview("../"+img, "existing");
                            }
                            validateImages();
                        }
                    }).fail(function() {
                        showToast("Error", "Failed to load hotel data.", "error");
                    });
                }

                // --------- Image Upload UI ---------
                const dropzone = $("#uploadArea");
                const fileInput = $("#fileInput");
                const previewArea = $("#previewArea");
                const dropzoneText = $(".dropzone-text");

                // Make file input cover the entire dropzone
                fileInput.css({ width: dropzone.outerWidth(), height: dropzone.outerHeight() });

                dropzone.on("dragover", function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.addClass("dragover");
                });
                
                dropzone.on("dragleave", function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.removeClass("dragover");
                });
                
                dropzone.on("drop", function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.removeClass("dragover");
                    handleFileInput(e.originalEvent.dataTransfer.files);
                });
                
                fileInput.on("change", function () {
                    handleFileInput(fileInput[0].files);
                });

                function handleFileInput(files) {
                    for (let file of files) {
                        if (!file.type.startsWith("image/")) {
                            showToast("Error", "Only image files are allowed.", "error");
                            continue;
                        }
                        if (file.size > 10 * 1024 * 1024) {
                            showToast("Error", "Image file too large", "error");
                            continue;
                        }
                        // Avoid duplicate (by name/size)
                        if (newFilesArray.some((f) => f.name === file.name && f.size === file.size && f.lastModified === file.lastModified)) continue;
                        newFilesArray.push(file);
                        addImagePreview(URL.createObjectURL(file), "new", file);
                    }
                    validateImages();
                }

                function addImagePreview(src, type, fileObj) {
                    const previewItem = $('<div class="preview-item">');
                    const img = $("<img>").attr("src", src);
                    const removeBtn = $('<button class="remove-btn" title="Remove image">&times;</button>');
                    previewItem.append(img).append(removeBtn);
                    previewArea.append(previewItem);
                    dropzoneText.hide();

                    removeBtn.on("click", function () {
                        if (type === "existing") {
                            // Remove from existingImages/save for deletion
                            deletedExistingImages.push(src);
                            existingImages = existingImages.filter((img) => img !== src);
                        } else if (type === "new") {
                            // Remove from newFilesArray
                            if (fileObj) {
                                newFilesArray = newFilesArray.filter((f) => !(f.name === fileObj.name && f.size === fileObj.size && f.lastModified === fileObj.lastModified));
                            }
                        }
                        previewItem.remove();
                        // Show placeholder if no images left
                        if (previewArea.children().length === 0) dropzoneText.show();
                        validateImages();
                    });
                    validateImages();
                }

                function validateImages() {
                    const imageValidation = $("#imageValidation");
                    if (existingImages.length === 0 && newFilesArray.length === 0) {
                        dropzone.addClass("is-invalid");
                        imageValidation.show();
                    } else {
                        dropzone.removeClass("is-invalid");
                        imageValidation.hide();
                    }
                }

                // Handle amenity "others"
                $("#others").change(function () {
                    if ($(this).is(":checked")) {
                        $("#customAmenityInput").slideDown();
                        $("#customAmenity").prop("required", true);
                    } else {
                        $("#customAmenityInput").slideUp();
                        $("#customAmenity").prop("required", false).removeClass("is-invalid");
                    }
                });

                // Validation
                $("#hotelForm").on("submit", function (e) {
                    e.preventDefault();
                    $(this).removeClass("was-validated");
                    $(".form-control").removeClass("is-invalid");
                    
                    let isValid = true;
                    
                    // Validate required fields
                    $("#hotelName, #locationName, #fullAddress, #hotelDescription").each(function () {
                        if (!$(this).val().trim()) {
                            $(this).addClass("is-invalid");
                            isValid = false;
                        }
                    });
                    
                    // Validate URL format
                    const mapUrl = $("#mapCoordinates").val().trim();
                    if (mapUrl) {
                        try {
                            new URL(mapUrl);
                        } catch (e) {
                            $("#mapCoordinates").addClass("is-invalid");
                            isValid = false;
                        }
                    } else {
                        $("#mapCoordinates").addClass("is-invalid");
                        isValid = false;
                    }
                    
                    // Validate images
                    if (existingImages.length === 0 && newFilesArray.length === 0) {
                        $("#imageValidation").show();
                        dropzone.addClass("is-invalid");
                        isValid = false;
                    }
                    
                    // Validate custom amenity if others is checked
                    if ($("#others").is(":checked") && !$("#customAmenity").val().trim()) {
                        $("#customAmenity").addClass("is-invalid");
                        isValid = false;
                    }
                    
                    if (!isValid) {
                        $("html, body").animate({ scrollTop: $(".is-invalid").first().offset().top - 100 }, 500);
                        return false;
                    }
                    
                    submitForm();
                });

                // Real-time input validation
                $("input, textarea").on("input", function () {
                    $(this).removeClass("is-invalid");
                    
                    if ($(this).attr("id") === "mapCoordinates" && $(this).val().trim()) {
                        try {
                            new URL($(this).val().trim());
                            $(this).removeClass("is-invalid");
                        } catch (e) {
                            $(this).addClass("is-invalid");
                        }
                    }
                });

                // --------- Submit Form ---------
                function submitForm() {
                    const formData = new FormData();
                    const id = $("#hotelId").val();
                    
                    if (id) {
                        formData.append("hotelId", id);
                    }
                    
                    formData.append("hotelName", $("#hotelName").val().trim());
                    formData.append("locationName", $("#locationName").val().trim());
                    formData.append("mapCoordinates", $("#mapCoordinates").val().trim());
                    formData.append("fullAddress", $("#fullAddress").val().trim());
                    formData.append("hotelDescription", $("#hotelDescription").val().trim());
                    
                    // Gather checked amenities
                    const amenities = [];
                    $('input[name="amenities[]"]:checked').each(function () {
                        amenities.push($(this).val());
                    });
                    
                    if ($("#others").is(":checked") && $("#customAmenity").val().trim()) {
                        amenities.push($("#customAmenity").val().trim());
                    }
                    
                    formData.append("amenities", JSON.stringify(amenities));
                    
                    // Images - new uploads
                    for (let i = 0; i < newFilesArray.length; i++) {
                        formData.append("images[]", newFilesArray[i]);
                    }
                    
                    // Existing images to delete (on update)
                    if (deletedExistingImages.length > 0) {
                        formData.append("imagesToDelete", JSON.stringify(deletedExistingImages));
                    }
                    
                    // Determine the correct API endpoint
                    const urlApi = id ? "../api/hotel/edit" : "../api/hotel/add";
                    
                    const submitBtn = $("#hotelForm").find('button[type="submit"]');
                    const originalText = submitBtn.html();
                    submitBtn.prop("disabled", true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
                    
                    $.ajax({
                        url: urlApi,
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            if (response.success) {
                                showToast("Success", id ? "Hotel updated successfully!" : "Hotel added successfully!", "success");
                                setTimeout(function () {
                                    // Redirect to hotels list instead of add-rooms
                                    window.location.href = "hotels-rooms.php";
                                }, 1500);
                            } else {
                                showToast("Error", response.message || (id ? "Failed to update hotel." : "Failed to add hotel."), "error");
                            }
                        },
                        error: function (xhr, status, error) {
                            showToast("Error", "An error occurred during the operation: " + error, "error");
                        },
                        complete: function () {
                            submitBtn.prop("disabled", false).html(originalText);
                        },
                    });
                }

                // ---------- Toast UI ----------
                function showToast(title, message, type = "info") {
                    $(".toast-container").remove();
                    if ($(".toast-container").length === 0) {
                        $("body").append('<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index:1090;"></div>');
                    }
                    const toastId = "toast-" + Date.now();
                    const toastHtml = `
                        <div id="${toastId}" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="toast-header">
                                <strong class="me-auto">${title}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                            <div class="toast-body">${message}</div>
                        </div>
                    `;
                    $(".toast-container").append(toastHtml);
                    const toastElement = $("#" + toastId);
                    if (type === "success") toastElement.find(".toast-header").addClass("bg-success text-white");
                    else if (type === "error") toastElement.find(".toast-header").addClass("bg-danger text-white");
                    else if (type === "warning") toastElement.find(".toast-header").addClass("bg-warning text-dark");
                    else toastElement.find(".toast-header").addClass("bg-info text-white");
                    const toast = new bootstrap.Toast(toastElement[0], { autohide: true, delay: 5000 });
                    toast.show();
                    toastElement.on("hidden.bs.toast", function () {
                        $(this).remove();
                    });
                }
            });
        </script>
    </body>
</html>