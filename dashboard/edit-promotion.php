<?php
    include "../libs/load.php";

    // Only admin can access
    if (
        !Session::get('session_token') || 
        Session::get('session_type') != 'admin' || 
        !Session::get('username')
    ) {
        header("Location: logout?logout");
        exit;
    }

    // Get promotion ID from URL
    $promotion_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if (!$promotion_id) {
        header("Location: promotions");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Edit Promotion - TNBooking.in</title>
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

                    <div class="card m-5 p-4">
                        <h4>Edit Promotion</h4>
                        <form id="promotionForm">
                            <input type="hidden" id="promotionId" name="promotionId" value="<?= $promotion_id; ?>">
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="hotel" class="form-label required-field">Select Hotel</label>
                                    <select class="form-select" id="hotel" name="hotel" required>
                                        <option value="" disabled>Loading Hotels...</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="promotionName" class="form-label required-field">Promotion Name</label>
                                    <input type="text" class="form-control" id="promotionName" name="promotionName" 
                                           placeholder="Promotion Name" required />
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="discount" class="form-label required-field">Discount</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="discount" name="discount" 
                                               placeholder="Set Discount Percent" min="1" max="100" required />
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="couponCode" class="form-label required-field">Coupon Code</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="couponCode" name="couponCode" 
                                               placeholder="Set Coupon Code" required />
                                        <button class="btn btn-outline-secondary" type="button" id="generateCode">Generate</button>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="startDate" class="form-label required-field">Start Date</label>
                                    <input type="date" class="form-control" id="startDate" name="startDate" required />
                                </div>
                                <div class="col-md-6">
                                    <label for="endDate" class="form-label required-field">End Date</label>
                                    <input type="date" class="form-control" id="endDate" name="endDate" required />
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="status" class="form-label required-field">Status</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="active">Active</option>
                                        <option value="expired">Expired</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="usageLimit" class="form-label">Usage Limit (Optional)</label>
                                    <input type="number" class="form-control" id="usageLimit" name="usageLimit" 
                                           placeholder="Set Limit" min="1" />
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="description" class="form-label">Description (Optional)</label>
                                <textarea class="form-control description-textarea" id="description" name="description" 
                                          placeholder="Write a Description..."></textarea>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="promotions" class="btn btn-outline-secondary me-md-2">Cancel</a>
                                <button type="submit" class="btn btn-primary btn-submit">Update Promotion</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php include "temp/footer.php" ?>

        <script>
            $(document).ready(function () {
                // First, load the promotion data
                const promotionId = $("#promotionId").val();
                
                // Fetch promotion details
                $.getJSON("../api/promotion/get?id=" + promotionId, function(response) {
                    
                    if (response && response.length > 0) {
                        // Extract the first promotion from the array
                        const promotion = response[0];
                        
                        // Populate form fields with existing data
                        $("#promotionName").val(promotion.promotion_name);
                        $("#discount").val(promotion.discount);
                        $("#couponCode").val(promotion.coupon_code);
                        $("#startDate").val(promotion.start_date);
                        $("#endDate").val(promotion.end_date);
                        $("#status").val(promotion.status);
                        $("#usageLimit").val(promotion.usage_limit || '');
                        $("#description").val(promotion.description || '');
                        
                        // Load hotels and select the current one
                        $.getJSON("../api/hotel/list", function (hotelResponse) {
                            let $hotel = $("#hotel");
                            $hotel.empty().append('<option value="" disabled>Select Hotel</option>');
                            if (hotelResponse) {
                                $.each(hotelResponse, function (i, hotel) {
                                    $hotel.append('<option value="' + hotel.id + '">' + hotel.hotel_name + "</option>");
                                });
                                // Select the current hotel
                                $hotel.val(promotion.hotel_id);
                            } else {
                                $hotel.append("<option disabled>No Hotels Found</option>");
                            }
                        });
                    } else {
                        showToast('Error', 'Failed to load promotion data: No promotion found with this ID', 'error');
                    }
                }).fail(function(xhr, status, error) {
                    console.error("API Error:", status, error);
                    showToast('Error', 'Failed to load promotion data. Please check your connection.', 'error');
                });

                // Generate random coupon code
                $("#generateCode").click(function () {
                    let code = "TN" + Math.random().toString(36).substr(2, 6).toUpperCase();
                    $("#couponCode").val(code);
                });

                // Validation function
                function validateForm() {
                    const requiredFields = [
                        'hotel', 'promotionName', 'discount', 
                        'couponCode', 'startDate', 'endDate', 'status'
                    ];
                    
                    for (const field of requiredFields) {
                        const value = $(`#${field}`).val();
                        if (!value) {
                            showToast('Error', `Please fill in the ${field.replace(/([A-Z])/g, ' $1').toLowerCase()} field`, 'error');
                            $(`#${field}`).focus();
                            return false;
                        }
                    }
                    
                    const startDate = new Date($("#startDate").val());
                    const endDate = new Date($("#endDate").val());
                    
                    if (endDate <= startDate) {
                        showToast('Error', 'End date must be after start date', 'error');
                        return false;
                    }
                    
                    const discount = parseInt($("#discount").val());
                    if (discount < 1 || discount > 100) {
                        showToast('Error', 'Discount must be between 1% and 100%', 'error');
                        return false;
                    }
                    
                    return true;
                }

                // Submit form via AJAX - UPDATED VERSION
                $("#promotionForm").submit(function (e) {
                    e.preventDefault();
                    
                    if (!validateForm()) {
                        return false;
                    }
                    
                    // Use FormData instead of JSON
                    const formData = new FormData();
                    formData.append('promotionId', $("#promotionId").val());
                    formData.append('hotel', $("#hotel").val());
                    formData.append('promotionName', $("#promotionName").val());
                    formData.append('discount', $("#discount").val());
                    formData.append('couponCode', $("#couponCode").val());
                    formData.append('startDate', $("#startDate").val());
                    formData.append('endDate', $("#endDate").val());
                    formData.append('status', $("#status").val());
                    
                    // Append optional fields only if they have values
                    const usageLimit = $("#usageLimit").val();
                    const description = $("#description").val();
                    
                    if (usageLimit) formData.append('usageLimit', usageLimit);
                    if (description) formData.append('description', description);
                    
                    // Show loading state
                    const submitBtn = $(".btn-submit");
                    const originalText = submitBtn.text();
                    submitBtn.prop('disabled', true).text('Updating...');
                    
                    $.ajax({
                        url: "../api/promotion/edit",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: "json",
                        success: function (res) {
                            if (res && res.success) {
                                showToast('Success', 'Promotion updated successfully!', 'success');
                                setTimeout(function() {
                                    window.location.href = "promotions";
                                }, 1500);
                            } else {
                                showToast('Error', res ? res.message : "Unknown error occurred", 'error');
                            }
                        },
                        error: function (xhr, status, error) {
                            console.log("Full error response:", xhr.responseText);
                            
                            // Try to parse the response even if it's not pure JSON
                            try {
                                // Extract JSON from the response if there are PHP warnings
                                const jsonStart = xhr.responseText.indexOf('{');
                                const jsonEnd = xhr.responseText.lastIndexOf('}') + 1;
                                if (jsonStart >= 0 && jsonEnd > jsonStart) {
                                    const jsonStr = xhr.responseText.substring(jsonStart, jsonEnd);
                                    const response = JSON.parse(jsonStr);
                                    if (response.success) {
                                        showToast('Success', 'Promotion updated successfully!', 'success');
                                        setTimeout(function() {
                                            window.location.href = "promotions";
                                        }, 1500);
                                        return;
                                    } else {
                                        showToast('Error', response.message, 'error');
                                        return;
                                    }
                                }
                            } catch (e) {
                                console.error("Could not extract JSON from response:", e);
                            }
                            
                            // If we couldn't extract JSON, show a generic error
                            showToast('Error', 'Server error occurred. Please check the console for details.', 'error');
                        },
                        complete: function() {
                            // Restore button state
                            submitBtn.prop('disabled', false).text(originalText);
                        }
                    });
                });
            });
        </script>
    </body>
</html>