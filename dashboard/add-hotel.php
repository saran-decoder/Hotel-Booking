<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>TNBooking Dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <?php include "temp/head.php" ?>

        <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" rel="stylesheet" />
        <style>
            .form-section {
                background-color: white;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
                max-width: 900px;
                margin: 30px auto;
            }
            .form-section h5 {
                font-weight: 600;
                margin-bottom: 25px;
            }
            .form-check {
                margin-right: 15px;
                margin-bottom: 10px;
            }
            
            .dropzone-container {
                position: relative;
            }
            
            .dropzone {
                border: 2px dashed #ccc;
                border-radius: 6px;
                text-align: center;
                padding: 40px 20px;
                color: #888;
                cursor: pointer;
                transition: background-color 0.3s ease;
                position: relative;
            }

            .dropzone.dragover {
                background-color: #e9ecef;
            }

            #previewArea {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                margin-top: 15px;
            }

            .preview-item {
                position: relative;
                width: 100px;
                height: 70px;
            }

            .preview-item img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border: 1px solid #ddd;
                border-radius: 4px;
            }

            .remove-btn {
                position: absolute;
                top: -8px;
                right: -8px;
                width: 20px;
                height: 20px;
                background: #dc3545;
                color: white;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 12px;
                cursor: pointer;
                border: none;
                z-index: 10;
            }

            .remove-btn:hover {
                background: #bb2d3b;
            }

            .dropzone-text {
                pointer-events: none;
            }
            
            #fileInput {
                position: absolute;
                width: 100%;
                height: 100%;
                top: 0;
                left: 0;
                opacity: 0;
                cursor: pointer;
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
                        <h4>Add New Hotel</h4>
                        <form id="hotelForm">
                            <!-- Hotel Details -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label">Hotel Name</label>
                                    <input type="text" class="form-control" placeholder="Enter hotel name" required />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Location Name</label>
                                    <input type="text" class="form-control" placeholder="Downtown, New York" />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Map Coordinates</label>
                                    <input type="text" class="form-control" placeholder="e.g., 12.34, 56.78" />
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Full Address</label>
                                <input type="text" class="form-control" placeholder="Enter complete address" />
                            </div>

                            <!-- Slideshow Images -->
                            <div class="mb-3">
                                <label class="form-label">Slideshow Images</label>
                                <div class="dropzone-container">
                                    <div class="dropzone" id="uploadArea">
                                        <span class="dropzone-text">Click or drag and drop images here<br />PNG, JPG or WEBP. Max: 10MB</span>
                                        <input type="file" id="fileInput" multiple accept="image/png, image/jpeg, image/webp">
                                        <div id="previewArea" class="mt-3"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label class="form-label">Hotel Description</label>
                                <textarea class="form-control" rows="4" placeholder="Describe the hotel, its location, and unique features"></textarea>
                            </div>

                            <!-- Amenities -->
                            <div class="mb-3">
                                <label class="form-label d-block">Amenities</label>
                                <div class="d-flex flex-wrap">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="wifi" />
                                        <label class="form-check-label" for="wifi">Free WiFi</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="ac" />
                                        <label class="form-check-label" for="ac">AC/Cooling</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="pool" />
                                        <label class="form-check-label" for="pool">Swimming Pool</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="restaurant" />
                                        <label class="form-check-label" for="restaurant">Restaurant</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="parking" />
                                        <label class="form-check-label" for="parking">Parking</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="gym" />
                                        <label class="form-check-label" for="gym">Fitness Center</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="spa" />
                                        <label class="form-check-label" for="spa">Spa</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Stay Info -->
                            <div class="mb-3">
                                <label class="form-label d-block">Stay Information</label>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="form-label">Adults</label>
                                        <input type="number" class="form-control" value="2" min="0" />
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Children</label>
                                        <input type="number" class="form-control" value="0" min="0" />
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-outline-secondary me-2">Cancel</button>
                                <button type="submit" class="btn btn-primary">Next</button>
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
        </script>
        
    </body>
</html>