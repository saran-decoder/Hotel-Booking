const ctx = document.getElementById("revenueChart");
new Chart(ctx, {
    type: "line",
    data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [
            {
                label: "Revenue",
                data: [3000, 2500, 4600, 2100, 1800, 2500, 3100, 3500, 4000, 4200, 4600, 5800],
                borderColor: "#3B82F6",
                backgroundColor: "rgba(59, 130, 246, 0.3)",
                fill: true,
                tension: 0.4,
            },
        ],
    },
});

const pieCtx = document.getElementById("bookingPieChart");
new Chart(pieCtx, {
    type: "pie",
    data: {
        labels: ["Confirmed", "Pending", "Cancelled"],
        datasets: [
            {
                data: [60, 25, 15],
                backgroundColor: ["#3B82F6", "#FBBF24", "#EF4444"],
            },
        ],
    },
});

// hotel and rooms drag and drop & others input showing
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