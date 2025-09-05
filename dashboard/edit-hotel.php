<?php
    include "../libs/load.php";
    
    if (
        !Session::get('session_token') || 
		Session::get('session_type') != 'admin' && 
		!Session::get('username')
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
                                <div class="col-md-6">
                                    <label class="form-label">Hotel Name <span class="text-danger">*</span></label>
                                    <input type="text" id="hotelName" name="hotelName" class="form-control" required />
                                    <div class="invalid-feedback">Please provide a hotel name.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Minimum Price <span class="text-danger">*</span></label>
                                    <input type="number" id="minimumPrice" name="minimumPrice" class="form-control" required />
                                    <div class="invalid-feedback">Please provide a Minimum Price.</div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">City Name <span class="text-danger">*</span></label>
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
                                        <label class="form-check-label" for="wifi">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="23" viewBox="0 0 22 23" fill="none">
                                                <path d="M11.0011 18.9756H11.0094" stroke="#007BFF" stroke-width="2.16667" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M1.83325 8.72694C4.35419 6.47215 7.61773 5.22559 10.9999 5.22559C14.3821 5.22559 17.6456 6.47215 20.1666 8.72694" stroke="#007BFF" stroke-width="2.16667" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M4.58252 12.4299C6.29603 10.7503 8.59979 9.80957 10.9992 9.80957C13.3986 9.80957 15.7023 10.7503 17.4159 12.4299" stroke="#007BFF" stroke-width="2.16667" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M7.79089 15.7018C8.64765 14.862 9.79953 14.3916 10.9992 14.3916C12.1989 14.3916 13.3508 14.862 14.2076 15.7018" stroke="#007BFF" stroke-width="2.16667" stroke-linecap" round" stroke-linejoin="round"/>
                                            </svg> Free WiFi
                                        </label>
                                    </div>
                                    <div class="form-check me-4 mb-2">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="air_conditioning" id="air" />
                                        <label class="form-check-label" for="air">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="23" viewBox="0 0 22 23" fill="none">
                                                <path d="M12.8339 4.26497V13.9266C13.5329 14.3302 14.0792 14.9532 14.3881 15.6989C14.697 16.4446 14.7512 17.2714 14.5423 18.0511C14.3334 18.8307 13.873 19.5197 13.2327 20.011C12.5923 20.5024 11.8077 20.7687 11.0005 20.7687C10.1934 20.7687 9.40877 20.5024 8.76841 20.011C8.12804 19.5197 7.66771 18.8307 7.45880 18.0511C7.24989 17.2714 7.30409 16.4446 7.61297 15.6989C7.92186 14.9532 8.46818 14.3302 9.16720 13.9266V4.26497C9.16720 3.77874 9.36035 3.31243 9.70417 2.96861C10.0480 2.62479 10.5143 2.43164 11.0005 2.43164C11.4868 2.43164 11.9531 2.62479 12.2969 2.96861C12.6407 3.31243 12.8339 3.77874 12.8339 4.26497Z" stroke="#007BFF" stroke-width="2.16667" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg> Air Conditioning
                                        </label>
                                    </div>
                                    <div class="form-check me-4 mb-2">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="swimming_pool" id="pool" />
                                        <label class="form-check-label" for="pool">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17" fill="none">
                                                <path d="M8.82526 3.93091L12.75 12.3076C10.7639 12.3076 10.1595 11.26 8.087 10.2124C6.42903 9.37428 4.11477 9.51397 3.42395 9.68857L6.1491 7.76193C6.45756 7.54385 6.61179 7.43482 6.66108 7.26883C6.71037 7.10284 6.64064 6.9273 6.50118 6.57623L6.50118 6.57622L6.13646 5.65805C6.00794 5.33451 5.94368 5.17273 5.83711 5.04731C5.75819 4.95444 5.66296 4.87678 5.55612 4.81816C5.41183 4.73899 5.24044 4.70858 4.89765 4.64776L1.91793 4.11909C1.29052 4.00777 0.833374 3.46238 0.833374 2.82517C0.833374 2.02465 1.54304 1.41011 2.33534 1.52454L6.36481 2.10651C7.00291 2.19866 7.32195 2.24474 7.59760 2.37679C7.82835 2.48732 8.03574 2.6411 8.20853 2.82979C8.41495 3.0552 8.55172 3.3471 8.82526 3.93091Z" stroke="#007BFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <ellipse cx="16.4166" cy="6.80762" rx="2.75" ry="2.75" stroke="#007BFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M0.833374 14.2182C1.80097 10.9809 6.12117 12.4667 9.54171 14.2182C12.9622 15.9697 16.4167 17.0842 18.25 14.2182" stroke="#007BFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg> Swimming Pool
                                        </label>
                                    </div>
                                    <div class="form-check me-4 mb-2">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="restaurant" id="restaurant" />
                                        <label class="form-check-label" for="restaurant">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="23" viewBox="0 0 22 23" fill="none">
                                                <path d="M2.75073 2.13867V8.55534C2.75073 9.56367 3.57573 10.3887 4.58407 10.3887H8.25073C8.73696 10.3887 9.20328 10.1955 9.54709 9.8517C9.89091 9.50788 10.0841 9.04157 10.0841 8.55534V2.13867" stroke="#007BFF" stroke-width="2.16667" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M6.41724 2.13867V20.472" stroke="#007BFF" stroke-width="2.16667" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M19.2492 14.0553V2.13867C18.0337 2.13867 16.8679 2.62156 16.0083 3.4811C15.1488 4.34064 14.6659 5.50643 14.6659 6.72201V12.222C14.6659 13.2303 15.4909 14.0553 16.4992 14.0553H19.2492ZM19.2492 14.0553V20.472" stroke="#007BFF" stroke-width="2.16667" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg> Restaurant
                                        </label>
                                    </div>
                                    <div class="form-check me-4 mb-2">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="parking" id="parking" />
                                        <label class="form-check-label" for="parking">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="19" viewBox="0 0 21 19" fill="none">
                                                <path d="M19.9024 17.8906V7.45039C19.9024 6.23769 19.9024 5.63134 19.5872 5.16175C19.272 4.69215 18.7138 4.46696 17.5975 4.01658L12.0975 1.79759C11.4251 1.52628 11.0888 1.39062 10.7358 1.39062C10.3827 1.39062 10.0465 1.52628 9.37399 1.79759L3.87399 4.01658C2.75767 4.46696 2.19951 4.69215 1.8843 5.16175C1.56909 5.63134 1.56909 6.23769 1.56909 7.45039V17.8906" stroke="#007BFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M14.4024 16.0566V17.89M7.06909 16.0566V17.89" stroke="#007BFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M6.61072 11.4733L6.83304 10.584C7.16651 9.25015 7.33324 8.58322 7.83055 8.19493C8.32786 7.80664 9.01532 7.80664 10.3902 7.80664H11.0812C12.4561 7.80664 13.1436 7.80664 13.6409 8.19493C14.1382 8.58322 14.3049 9.25015 14.6384 10.584L14.8607 11.4733" stroke="#007BFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M15.3191 11.4736H6.15238C5.64612 11.4736 5.23572 11.884 5.23572 12.3903V15.1403C5.23572 15.6466 5.64612 16.057 6.15238 16.057H15.3191C15.8253 16.057 16.2357 15.6466 16.2357 15.1403V12.3903C16.2357 11.884 15.8253 11.4736 15.3191 11.4736Z" stroke="#007BFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M7.52734 13.7556V13.7656" stroke="#007BFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M13.9441 13.7556V13.7656" stroke="#007BFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg> Parking
                                        </label>
                                    </div>
                                    <div class="form-check me-4 mb-2">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="fitness_center" id="gym" />
                                        <label class="form-check-label" for="gym">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="19" viewBox="0 0 21 19" fill="none">
                                                <path d="M19.9024 17.8906V7.45039C19.9024 6.23769 19.9024 5.63134 19.5872 5.16175C19.272 4.69215 18.7138 4.46696 17.5975 4.01658L12.0975 1.79759C11.4251 1.52628 11.0888 1.39062 10.7358 1.39062C10.3827 1.39062 10.0465 1.52628 9.37399 1.79759L3.87399 4.01658C2.75767 4.46696 2.19951 4.69215 1.8843 5.16175C1.56909 5.63134 1.56909 6.23769 1.56909 7.45039V17.8906" stroke="#007BFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M14.4024 16.0566V17.89M7.06909 16.0566V17.89" stroke="#007BFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M6.61072 11.4733L6.83304 10.584C7.16651 9.25015 7.33324 8.58322 7.83055 8.19493C8.32786 7.80664 9.01532 7.80664 10.3902 7.80664H11.0812C12.4561 7.80664 13.1436 7.80664 13.6409 8.19493C14.1382 8.58322 14.3049 9.25015 14.6384 10.584L14.8607 11.4733" stroke="#007BFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M15.3191 11.4736H6.15238C5.64612 11.4736 5.23572 11.884 5.23572 12.3903V15.1403C5.23572 15.6466 5.64612 16.057 6.15238 16.057H15.3191C15.8253 16.057 16.2357 15.6466 16.2357 15.1403V12.3903C16.2357 11.884 15.8253 11.4736 15.3191 11.4736Z" stroke="#007BFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M7.52734 13.7556V13.7656" stroke="#007BFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M13.9441 13.7556V13.7656" stroke="#007BFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg> Fitness Center
                                        </label>
                                    </div>
                                    <div class="form-check me-4 mb-2">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="bar" id="bar" />
                                        <label class="form-check-label" for="bar">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21" fill="none">
                                                <path d="M7.01908 13.427L3.63818 9.50637C1.90349 7.49478 1.03615 6.48899 1.42095 5.66881C1.80574 4.84863 3.14495 4.84863 5.82339 4.84863H9.67157C12.35 4.84863 13.6892 4.84863 14.074 5.66881C14.4588 6.48899 13.5915 7.49478 11.8568 9.50637L8.47588 13.427C8.13764 13.8192 7.96852 14.0153 7.74748 14.0153C7.52643 14.0153 7.35731 13.8192 7.01908 13.427Z" stroke="#007BFF" stroke-width="1.5" stroke-linecap="round"/>
                                                <path d="M7.28914 4.84831L6.92979 2.6922C6.8689 2.32683 6.61103 2.02505 6.25963 1.90791L4.08081 1.18164" stroke="#007BFF" stroke-width="1.5" stroke-linecap="round"/>
                                                <path d="M7.74744 14.0156V19.5156M6.37244 19.5156H9.12244" stroke="#007BFF" stroke-width="1.5" stroke-linecap="round"/>
                                                <path d="M14.0359 7.44585C14.6263 8.10223 15.4844 8.5153 16.4394 8.5153C18.2204 8.5153 19.6641 7.07888 19.6641 5.30697C19.6641 3.53505 18.2204 2.09863 16.4394 2.09863C14.8149 2.09863 13.471 3.29383 13.2474 4.84863" stroke="#007BFF" stroke-width="1.5" stroke-linecap="round"/>
                                            </svg> Bar
                                        </label>
                                    </div>
                                    <div class="form-check me-4 mb-2">
                                        <input class="form-check-input" type="checkbox" value="others" id="others" />
                                        <label class="form-check-label" for="others">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24" fill="none">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10
                                                    10-4.48 10-10S17.52 2 12 2zm1 17.93c-2.83.48-5.64-.37-7.71-2.44C3.66 15.35 2.81 12.54
                                                    3.29 9.71A8.97 8.97 0 0 1 12 4c2.21 0 4.21.90 5.65 2.35a8.975 8.975 0 0 1-4.65 13.58z"
                                                    stroke="#007BFF" stroke-width="1.5" stroke-linecap="round"/>
                                            </svg> Others
                                        </label>
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
                        $("#minimumPrice").val(hotelData.price);

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
                    $("#hotelName, #minimumPrice, #locationName, #fullAddress, #hotelDescription").each(function () {
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
                    formData.append("minimumPrice", $("#minimumPrice").val().trim());
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