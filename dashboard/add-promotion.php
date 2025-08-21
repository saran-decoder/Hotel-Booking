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
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Add New Promotion - TNBooking.in</title>

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
                        <h4>Add New Promotion</h4>
                        <form id="promotionForm" method="POST" action="process_promotion.php">
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label for="promotionName" class="form-label required-field">Promotion Name</label>
                                    <input type="text" class="form-control" id="promotionName" name="promotionName" placeholder="Promotion Name" required>
                                </div>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="discount" class="form-label required-field">Discount</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="discount" name="discount" placeholder="Set Discount Percent" min="1" max="100" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="couponCode" class="form-label required-field">Coupon Code</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="couponCode" name="couponCode" placeholder="Set Coupon Code" required>
                                        <button class="btn btn-outline-secondary" type="button" id="generateCode">Generate</button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="startDate" class="form-label required-field">Start Date</label>
                                    <input type="date" class="form-control datepicker" id="startDate" name="startDate" placeholder="Select Date" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="endDate" class="form-label required-field">End Date</label>
                                    <input type="date" class="form-control datepicker" id="endDate" name="endDate" placeholder="Select Date" required>
                                </div>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="status" class="form-label required-field">Status</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="" selected disabled>Select</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="scheduled">Scheduled</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="usageLimit" class="form-label">Usage Limit (Optional)</label>
                                    <input type="number" class="form-control" id="usageLimit" name="usageLimit" placeholder="Set Limit" min="1">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="description" class="form-label">Description (Optional)</label>
                                <textarea class="form-control description-textarea" id="description" name="description" placeholder="Write a Description..."></textarea>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button" class="btn btn-outline-secondary me-md-2" id="resetForm">Reset</button>
                                <button type="submit" class="btn btn-primary btn-submit">Add Promotion</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php include "temp/footer.php" ?>
        
    </body>
</html>