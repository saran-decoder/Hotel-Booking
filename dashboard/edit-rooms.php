<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>TNBooking Dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <?php include "temp/head.php" ?>

        <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" rel="stylesheet" />

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
                        <h4>Add New Hotel</h4>
                        <form id="hotelForm">
                                    
                            <div class="row">
                                <!-- Room Type -->
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Room Type</label>
                                    <select class="form-select" required>
                                        <option value="" disabled selected>Standard, Deluxe, Suite, etc.</option>
                                        <option value="standard">Standard</option>
                                        <option value="deluxe">Deluxe</option>
                                        <option value="suite">Suite</option>
                                        <option value="family">Family</option>
                                        <option value="executive">Executive</option>
                                    </select>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Guests Allowed</label>
                                    <input type="text" class="form-control" placeholder="e.g., 2" />
                                </div>
                            </div>

                            <!-- Slideshow Images -->
                            <div class="mb-3">
                                <label class="form-label">Slideshow Images</label>
                                <div class="dropzone-container">
                                    <div class="dropzone" id="uploadArea">
                                        <span class="dropzone-text">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none">
                                                <path d="M23.3908 3.69238H6.15554C4.79571 3.69238 3.69336 4.79474 3.69336 6.15456V23.3898C3.69336 24.7496 4.79571 25.852 6.15554 25.852H23.3908C24.7506 25.852 25.853 24.7496 25.853 23.3898V6.15456C25.853 4.79474 24.7506 3.69238 23.3908 3.69238Z" stroke="#9CA3AF" stroke-width="1.84663" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M11.0799 13.5406C12.4397 13.5406 13.542 12.4382 13.542 11.0784C13.542 9.71857 12.4397 8.61621 11.0799 8.61621C9.72003 8.61621 8.61768 9.71857 8.61768 11.0784C8.61768 12.4382 9.72003 13.5406 11.0799 13.5406Z" stroke="#9CA3AF" stroke-width="1.84663" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M25.8528 18.4653L22.0537 14.6662C21.5919 14.2046 20.9658 13.9453 20.3129 13.9453C19.66 13.9453 19.0339 14.2046 18.5721 14.6662L7.38647 25.8519" stroke="#9CA3AF" stroke-width="1.84663" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg><br />
                                            Click or drag and drop images here<br />PNG, JPG or WEBP. Max: 10MB
                                        </span>
                                        <input type="file" id="fileInput" multiple accept="image/png, image/jpeg, image/webp">
                                        <div id="previewArea" class="mt-3"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Room Description -->
                            <div class="mb-3">
                                <label class="form-label">Room Description</label>
                                <textarea class="form-control" rows="4" placeholder="Describe the room features and amenities"></textarea>
                            </div>
                            
                            <!-- Price per Night -->
                            <div class="mb-3 col-6">
                                <label class="form-label">Price per Night (₹)</label>
                                <input type="number" class="form-control" placeholder="0.00" step="0.01" min="0">
                            </div>

                            <!-- Amenities -->
                            <div class="mb-3">
                                <label class="form-label d-block">Amenities</label>
                                <div class="d-flex flex-wrap">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="wifi" />
                                        <label class="form-check-label" for="wifi">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="23" viewBox="0 0 22 23" fill="none">
                                                <path d="M11.0011 18.9756H11.0094" stroke="#007BFF" stroke-width="2.16667" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M1.83325 8.72694C4.35419 6.47215 7.61773 5.22559 10.9999 5.22559C14.3821 5.22559 17.6456 6.47215 20.1666 8.72694" stroke="#007BFF" stroke-width="2.16667" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M4.58252 12.4299C6.29603 10.7503 8.59979 9.80957 10.9992 9.80957C13.3986 9.80957 15.7023 10.7503 17.4159 12.4299" stroke="#007BFF" stroke-width="2.16667" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M7.79089 15.7018C8.64765 14.862 9.79953 14.3916 10.9992 14.3916C12.1989 14.3916 13.3508 14.862 14.2076 15.7018" stroke="#007BFF" stroke-width="2.16667" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg> Free WiFi
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="air" />
                                        <label class="form-check-label" for="ac">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="23" viewBox="0 0 22 23" fill="none">
                                                <path d="M12.8339 4.26497V13.9266C13.5329 14.3302 14.0792 14.9532 14.3881 15.6989C14.697 16.4446 14.7512 17.2714 14.5423 18.0511C14.3334 18.8307 13.873 19.5197 13.2327 20.011C12.5923 20.5024 11.8077 20.7687 11.0005 20.7687C10.1934 20.7687 9.40877 20.5024 8.76841 20.011C8.12804 19.5197 7.66771 18.8307 7.4588 18.0511C7.24989 17.2714 7.30409 16.4446 7.61297 15.6989C7.92186 14.9532 8.46818 14.3302 9.1672 13.9266V4.26497C9.1672 3.77874 9.36035 3.31243 9.70417 2.96861C10.048 2.62479 10.5143 2.43164 11.0005 2.43164C11.4868 2.43164 11.9531 2.62479 12.2969 2.96861C12.6407 3.31243 12.8339 3.77874 12.8339 4.26497Z" stroke="#007BFF" stroke-width="2.16667" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg> Air Conditioning
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="pool" />
                                        <label class="form-check-label" for="pool">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17" fill="none">
                                                <path d="M8.82526 3.93091L12.75 12.3076C10.7639 12.3076 10.1595 11.26 8.087 10.2124C6.42903 9.37428 4.11477 9.51397 3.42395 9.68857L6.1491 7.76193C6.45756 7.54385 6.61179 7.43482 6.66108 7.26883C6.71037 7.10284 6.64064 6.9273 6.50118 6.57623L6.50118 6.57622L6.13646 5.65805C6.00794 5.33451 5.94368 5.17273 5.83711 5.04731C5.75819 4.95444 5.66296 4.87678 5.55612 4.81816C5.41183 4.73899 5.24044 4.70858 4.89765 4.64776L1.91793 4.11909C1.29052 4.00777 0.833374 3.46238 0.833374 2.82517C0.833374 2.02465 1.54304 1.41011 2.33534 1.52454L6.36481 2.10651C7.00291 2.19866 7.32195 2.24474 7.5976 2.37679C7.82835 2.48732 8.03574 2.6411 8.20853 2.82979C8.41495 3.0552 8.55172 3.3471 8.82526 3.93091Z" stroke="#007BFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <ellipse cx="16.4166" cy="6.80762" rx="2.75" ry="2.75" stroke="#007BFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M0.833374 14.2182C1.80097 10.9809 6.12117 12.4667 9.54171 14.2182C12.9622 15.9697 16.4167 17.0842 18.25 14.2182" stroke="#007BFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg> Swimming Pool
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="restaurant" />
                                        <label class="form-check-label" for="restaurant">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="23" viewBox="0 0 22 23" fill="none">
                                                <path d="M2.75073 2.13867V8.55534C2.75073 9.56367 3.57573 10.3887 4.58407 10.3887H8.25073C8.73696 10.3887 9.20328 10.1955 9.54709 9.8517C9.89091 9.50788 10.0841 9.04157 10.0841 8.55534V2.13867" stroke="#007BFF" stroke-width="2.16667" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M6.41724 2.13867V20.472" stroke="#007BFF" stroke-width="2.16667" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M19.2492 14.0553V2.13867C18.0337 2.13867 16.8679 2.62156 16.0083 3.4811C15.1488 4.34064 14.6659 5.50643 14.6659 6.72201V12.222C14.6659 13.2303 15.4909 14.0553 16.4992 14.0553H19.2492ZM19.2492 14.0553V20.472" stroke="#007BFF" stroke-width="2.16667" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg> Restaurant
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="parking" />
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
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="gym" />
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
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="bar" />
                                        <label class="form-check-label" for="bar">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21" fill="none">
                                                <path d="M7.01908 13.427L3.63818 9.50637C1.90349 7.49478 1.03615 6.48899 1.42095 5.66881C1.80574 4.84863 3.14495 4.84863 5.82339 4.84863H9.67157C12.35 4.84863 13.6892 4.84863 14.074 5.66881C14.4588 6.48899 13.5915 7.49478 11.8568 9.50637L8.47588 13.427C8.13764 13.8192 7.96852 14.0153 7.74748 14.0153C7.52643 14.0153 7.35731 13.8192 7.01908 13.427Z" stroke="#007BFF" stroke-width="1.5" stroke-linecap="round"/>
                                                <path d="M7.28914 4.84831L6.92979 2.6922C6.8689 2.32683 6.61103 2.02505 6.25963 1.90791L4.08081 1.18164" stroke="#007BFF" stroke-width="1.5" stroke-linecap="round"/>
                                                <path d="M7.74744 14.0156V19.5156M6.37244 19.5156H9.12244" stroke="#007BFF" stroke-width="1.5" stroke-linecap="round"/>
                                                <path d="M14.0359 7.44585C14.6263 8.10223 15.4844 8.5153 16.4394 8.5153C18.2204 8.5153 19.6641 7.07888 19.6641 5.30697C19.6641 3.53505 18.2204 2.09863 16.4394 2.09863C14.8149 2.09863 13.471 3.29383 13.2474 4.84863" stroke="#007BFF" stroke-width="1.5" stroke-linecap="round"/>
                                            </svg> Bar
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="others" />
                                        <label class="form-check-label" for="others">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24" fill="none">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10
                                                    10-4.48 10-10S17.52 2 12 2zm1 17.93c-2.83.48-5.64-.37-7.71-2.44C3.66 15.35 2.81 12.54
                                                    3.29 9.71A8.97 8.97 0 0 1 12 4c2.21 0 4.21.9 5.65 2.35a8.975 8.975 0 0 1-4.65 13.58z"
                                                    stroke="#007BFF" stroke-width="1.5" stroke-linecap="round"/>
                                            </svg>
                                            Others
                                        </label>
                                    </div>

                                    <!-- Hidden input box for custom amenity -->
                                    <div id="customAmenityInput" style="display: none; margin-top: 8px;">
                                        <input type="text" class="form-control" placeholder="Enter custom amenity" />
                                    </div>
                                </div>
                            </div>

                            <!-- Stay Information -->
                            <div class="mb-3">
                                <label class="form-label">Default Guests</label>
                                <div class="d-flex">
                                    <div class="me-3">
                                        <label class="form-label">Adults</label>
                                        <input type="number" class="form-control" value="2" min="0">
                                    </div>
                                    <div>
                                        <label class="form-label">Children</label>
                                        <input type="number" class="form-control" value="0" min="0">
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex justify-content-end">
                                <a href="hotels-rooms.php" type="button" class="btn btn-outline-secondary me-2 align-content-center">Cancel</a>
                                <button type="submit" class="btn btn-primary">Add Room</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php include "temp/footer.php" ?>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>

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
                            alert('Only image files are allowed.');
                            continue;
                        }

                        if (file.size > 10 * 1024 * 1024) {
                            alert('File size must be less than 10MB.');
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
                            });

                            previewItem.append(img);
                            previewItem.append(removeBtn);
                            previewArea.append(previewItem);
                            
                            // Hide dropzone text when we have images
                            if (filesArray.length > 0) {
                                dropzoneText.hide();
                            }
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

                // Handle window resize to adjust file input size
                $(window).resize(function() {
                    fileInput.css({
                        'width': dropzone.outerWidth(),
                        'height': dropzone.outerHeight()
                    });
                });
            });

            $(document).ready(function () {
                $('#others').change(function () {
                    if ($(this).is(':checked')) {
                        $('#customAmenityInput').slideDown(); // Show input box
                    } else {
                        $('#customAmenityInput').slideUp();   // Hide input box
                    }
                });
            });
        </script>
        
    </body>
</html>