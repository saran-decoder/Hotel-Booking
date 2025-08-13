<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>TN.Booking.in - Find Your Perfect Stay</title>

        <?php include "temp/head.php" ?>

        <!-- Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
        <style>
            .progress-step {
                display: flex;
                justify-content: space-between;
                margin: 40px 0 20px;
                text-align: center;
                font-size: 14px;
            }

            .progress-step div {
                flex: 1;
                color: #bbb;
            }

            .progress-step .active {
                color: #0d6efd;
                font-weight: 600;
            }

            .progress-step .circle-number {
                display: inline-block;
                width: 24px;
                height: 24px;
                line-height: 24px;
                border-radius: 50%;
                background-color: #0d6efd;
                color: white;
                margin-bottom: 5px;
            }

            .form-box {
                background-color: #f0f0f0;
                border-radius: 6px;
                padding: 14px 18px;
                cursor: pointer;
            }

            .form-select {
                /* border-start-start-radius: 0 !important;
                border-end-start-radius: 0 !important; */
                border: 1px solid #ddd;
            }

            .guest-box {
                background-color: #f1f1f1;
                padding: 12px 15px;
                border-radius: 6px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                color: #555;
                cursor: pointer;
            }

            .btn-disabled {
                background-color: #cfd2d6;
                color: white;
                pointer-events: none;
            }

            .btn-primary {
                background-color: #0d6efd;
                border-color: #0d6efd;
            }

            .small-label {
                font-size: 12px;
                color: #888;
            }

            .calendar-day {
                padding: 10px 0;
                border-radius: 6px;
                cursor: pointer;
            }

            .calendar-day:hover:not(.disabled) {
                background-color: #eee;
            }

            .calendar-day.today {
                font-weight: bold;
                background-color: #f0f7ff;
            }

            .calendar-day.selected {
                background-color: #0d6efd;
                color: white;
            }

            .calendar-day.disabled {
                color: #ccc;
                cursor: not-allowed;
                background-color: #f9f9f9;
            }

            .guest-dropdown {
                position: absolute;
                top: 90%;
                left: 0;
                width: 100%;
                background: white;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                padding: 15px;
                z-index: 1000;
                display: none;
            }

            .guest-counter {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 15px;
            }

            .counter-btn {
                width: 30px;
                height: 30px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: #f0f0f0;
                border: none;
                cursor: pointer;
            }

            .counter-value {
                font-weight: bold;
                min-width: 30px;
                text-align: center;
            }

            .calendar-container {
                background-color: white;
                border-radius: 8px;
                padding: 24px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            }

            .calendar-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 16px;
            }

            .calendar-days {
                display: grid;
                grid-template-columns: repeat(7, 1fr);
                text-align: center;
                color: #999;
                font-size: 14px;
                margin-bottom: 8px;
            }

            .calendar-dates {
                display: grid;
                grid-template-columns: repeat(7, 1fr);
                text-align: center;
                font-size: 16px;
                color: #333;
                gap: 4px;
            }

            .calendar-dates div {
                padding: 10px 0;
                border-radius: 6px;
            }

            .calendar-dates div:hover:not(.disabled) {
                background-color: #eee;
                cursor: pointer;
            }

            .calendar-dates .today {
                font-weight: bold;
            }
        </style>
    </head>

    <body>
        <!-- Navbar -->
        <?php include "temp/header.php" ?>

        <!-- Step Bar -->
        <div class="container mt-4">
            <div class="progress-step">
                <div class="step active">
                    <span class="circle-number">1</span><br />
                    Select Dates
                </div>
                <div class="step">
                    <span class="circle-number">2</span><br />
                    Choose Hotel
                </div>
                <div class="step">
                    <span class="circle-number">3</span><br />
                    Select Room
                </div>
                <div class="step">
                    <span class="circle-number">4</span><br />
                    Confirm Booking
                </div>
            </div>
        </div>

        <!-- Booking Card -->
        <div class="container mb-5">
            <div class="card p-4 shadow-sm mx-auto" style="max-width: 700px;">
                <h5 class="fw-bold mb-3">Select Your Stay Dates</h5>

                <!-- Destination -->
                <div class="bg-light p-3 rounded mb-4">
                    <label class="form-label">Destination</label>
                    <div class="input-group">
                        <i class="fas fa-map-marker-alt position-absolute align-self-center ms-3"></i>
                        <select class="form-select" id="destinationSelect">
                            <option value="" selected disabled>Select destination</option>
                            <option value="New York">New York</option>
                            <option value="London">London</option>
                            <option value="Paris">Paris</option>
                            <option value="Tokyo">Tokyo</option>
                            <option value="Dubai">Dubai</option>
                            <option value="Sydney">Sydney</option>
                        </select>
                    </div>
                </div>

                <!-- Calendar -->
                <div class="card mb-4 p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3 calendar-header">
                        <strong>Select Dates</strong>
                        <div>
                            <button class="btn" id="prevMonth"><i class="fas fa-chevron-left"></i></button>
                            <span class="mx-2" id="currentMonth">July 2025</span>
                            <button class="btn" id="nextMonth"><i class="fas fa-chevron-right"></i></button>
                        </div>
                    </div>
                    <div class="row text-center text-muted mb-2 calendar-days">
                        <div class="col p-1">Sun</div>
                        <div class="col p-1">Mon</div>
                        <div class="col p-1">Tue</div>
                        <div class="col p-1">Wed</div>
                        <div class="col p-1">Thu</div>
                        <div class="col p-1">Fri</div>
                        <div class="col p-1">Sat</div>
                    </div>
                    <div class="row text-center calendar-dates" id="calendarDates">
                        <!-- Calendar dates will be generated by JavaScript -->
                    </div>
                </div>

                <!-- Check-in and Check-out -->
                <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="form-box" id="checkInBox">
                            <div class="small-label">Check-in</div>
                            <div id="checkInDate">Select date</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-box" id="checkOutBox">
                            <div class="small-label">Check-out</div>
                            <div id="checkOutDate">Select date</div>
                        </div>
                    </div>
                </div>

                <!-- Guests -->
                <div class="mb-4">
                    <label class="form-label">Guests</label>
                    <div class="guest-box" id="guestSelector">
                        <span> <i class="fas fa-user me-2"></i><span id="guestDisplay">1 Adults, 0 Children</span> </span>
                        <span><i class="fas fa-chevron-right"></i></span>
                    </div>
                    <div class="guest-dropdown" id="guestDropdown">
                        <div class="guest-counter">
                            <div>Adults</div>
                            <div class="d-flex align-items-center">
                                <button class="counter-btn" id="decreaseAdults">-</button>
                                <span class="counter-value mx-2" id="adultCount">1</span>
                                <button class="counter-btn" id="increaseAdults">+</button>
                            </div>
                        </div>
                        <div class="guest-counter">
                            <div>Children</div>
                            <div class="d-flex align-items-center">
                                <button class="counter-btn" id="decreaseChildren">-</button>
                                <span class="counter-value mx-2" id="childCount">0</span>
                                <button class="counter-btn" id="increaseChildren">+</button>
                            </div>
                        </div>
                        <button class="btn btn-primary w-100 mt-2" id="applyGuests">Apply</button>
                    </div>
                </div>

                <!-- Continue Button -->
                <button class="btn btn-primary w-100 py-2" id="continueBtn">Continue to Hotels</button>
            </div>
        </div>

        <!-- Footer -->
        <?php include "temp/footer.php" ?>

        <script>
            // Initialize variables
            const today = new Date();
            today.setHours(0, 0, 0, 0); // Set to beginning of day
            
            let currentDate = new Date();
            currentDate.setDate(1); // Set to first day of current month
            let checkInDate = null;
            let checkOutDate = null;
            let adultsCount = 1;
            let childrenCount = 0;

            // DOM elements
            const calendarDates = document.getElementById("calendarDates");
            const currentMonthEl = document.getElementById("currentMonth");
            const prevMonthBtn = document.getElementById("prevMonth");
            const nextMonthBtn = document.getElementById("nextMonth");
            const checkInBox = document.getElementById("checkInBox");
            const checkOutBox = document.getElementById("checkOutBox");
            const checkInDateEl = document.getElementById("checkInDate");
            const checkOutDateEl = document.getElementById("checkOutDate");
            const guestSelector = document.getElementById("guestSelector");
            const guestDropdown = document.getElementById("guestDropdown");
            const guestDisplay = document.getElementById("guestDisplay");
            const adultCountEl = document.getElementById("adultCount");
            const childCountEl = document.getElementById("childCount");
            const decreaseAdults = document.getElementById("decreaseAdults");
            const increaseAdults = document.getElementById("increaseAdults");
            const decreaseChildren = document.getElementById("decreaseChildren");
            const increaseChildren = document.getElementById("increaseChildren");
            const applyGuests = document.getElementById("applyGuests");
            const continueBtn = document.getElementById("continueBtn");
            const destinationSelect = document.getElementById("destinationSelect");

            // Initialize calendar
            function renderCalendar() {
                calendarDates.innerHTML = "";

                // Set month and year display
                currentMonthEl.textContent = currentDate.toLocaleString("default", { month: "long", year: "numeric" });

                // Get first day of month and total days in month
                const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1).getDay();
                const daysInMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();

                // Add empty cells for days before the first day of the month
                for (let i = 0; i < firstDay; i++) {
                    const emptyCell = document.createElement("div");
                    emptyCell.className = "";
                    calendarDates.appendChild(emptyCell);
                }

                // Add cells for each day of the month
                for (let day = 1; day <= daysInMonth; day++) {
                    const dateCell = document.createElement("div");
                    dateCell.className = "calendar-day";
                    dateCell.textContent = day;

                    const cellDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);
                    
                    // Disable past dates
                    if (cellDate < today) {
                        dateCell.classList.add("disabled");
                    } else {
                        // Check if this is today
                        if (cellDate.getTime() === today.getTime()) {
                            dateCell.classList.add("today");
                        }

                        // Check if this date is selected
                        if (checkInDate && cellDate.getTime() === checkInDate.getTime()) {
                            dateCell.classList.add("selected");
                        } else if (checkOutDate && cellDate.getTime() === checkOutDate.getTime()) {
                            dateCell.classList.add("selected");
                        } else if (checkInDate && checkOutDate && cellDate > checkInDate && cellDate < checkOutDate) {
                            dateCell.style.backgroundColor = "#e6f0ff";
                        }

                        dateCell.addEventListener("click", () => selectDate(day));
                    }

                    calendarDates.appendChild(dateCell);
                }
            }

            // Handle date selection
            function selectDate(day) {
                const selectedDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);
                
                // Don't allow selection of past dates
                if (selectedDate < today) {
                    return;
                }

                if (!checkInDate || (checkInDate && checkOutDate)) {
                    // Start new selection
                    checkInDate = selectedDate;
                    checkOutDate = null;
                    checkInDateEl.textContent = formatDate(checkInDate);
                    checkOutDateEl.textContent = "Select date";
                } else if (selectedDate > checkInDate) {
                    // Complete the range
                    checkOutDate = selectedDate;
                    checkOutDateEl.textContent = formatDate(checkOutDate);
                } else {
                    // Select earlier date as check-in (but not before today)
                    if (selectedDate >= today) {
                        checkInDate = selectedDate;
                        checkOutDate = null;
                        checkInDateEl.textContent = formatDate(checkInDate);
                        checkOutDateEl.textContent = "Select date";
                    }
                }

                // Enable continue button if all required fields are filled
                updateContinueButton();
                renderCalendar();
            }

            // Format date as "Month Day, Year"
            function formatDate(date) {
                return date.toLocaleString("default", { month: "short", day: "numeric", year: "numeric" });
            }

            // Update guest display
            function updateGuestDisplay() {
                let displayText = `${adultsCount} Adult${adultsCount !== 1 ? "s" : ""}`;
                if (childrenCount > 0) {
                    displayText += `, ${childrenCount} Child${childrenCount !== 1 ? "ren" : ""}`;
                }
                guestDisplay.textContent = displayText;
                updateContinueButton();
            }

            // Update continue button state
            function updateContinueButton() {
                if (checkInDate && checkOutDate && destinationSelect.value) {
                    continueBtn.classList.remove("btn-disabled");
                    continueBtn.classList.add("btn-primary");
                    continueBtn.disabled = false;
                } else {
                    continueBtn.classList.add("btn-disabled");
                    continueBtn.classList.remove("btn-primary");
                    continueBtn.disabled = true;
                }
            }

            // Update step progress
            function updateStepProgress(activeStep) {
                const steps = document.querySelectorAll(".progress-step .step");
                
                steps.forEach((step, index) => {
                    if (index + 1 === activeStep) {
                        step.classList.add("active");
                    } else {
                        step.classList.remove("active");
                    }
                });
            }

            // Event listeners
            prevMonthBtn.addEventListener("click", () => {
                currentDate.setMonth(currentDate.getMonth() - 1);
                renderCalendar();
            });

            nextMonthBtn.addEventListener("click", () => {
                currentDate.setMonth(currentDate.getMonth() + 1);
                renderCalendar();
            });

            guestSelector.addEventListener("click", (e) => {
                e.stopPropagation();
                guestDropdown.style.display = guestDropdown.style.display === "block" ? "none" : "block";
            });

            decreaseAdults.addEventListener("click", (e) => {
                e.stopPropagation();
                if (adultsCount > 1) {
                    adultsCount--;
                    adultCountEl.textContent = adultsCount;
                }
            });

            increaseAdults.addEventListener("click", (e) => {
                e.stopPropagation();
                adultsCount++;
                adultCountEl.textContent = adultsCount;
            });

            decreaseChildren.addEventListener("click", (e) => {
                e.stopPropagation();
                if (childrenCount > 0) {
                    childrenCount--;
                    childCountEl.textContent = childrenCount;
                }
            });

            increaseChildren.addEventListener("click", (e) => {
                e.stopPropagation();
                childrenCount++;
                childCountEl.textContent = childrenCount;
            });

            applyGuests.addEventListener("click", (e) => {
                e.stopPropagation();
                updateGuestDisplay();
                guestDropdown.style.display = "none";
            });

            // Destination select change handler
            destinationSelect.addEventListener("change", () => {
                updateContinueButton();
            });

            // Continue button click handler
            continueBtn.addEventListener("click", (e) => {
                e.preventDefault();
                
                // Validate all required fields
                if (!destinationSelect.value) {
                    alert("Please select a destination");
                    return;
                }
                
                if (!checkInDate || !checkOutDate) {
                    alert("Please select both check-in and check-out dates");
                    return;
                }
                
                // Proceed to next step
                updateStepProgress(2); // Move to step 2 (Choose Hotel)
                
                // In a real application, you would submit the form or redirect here
                console.log("Proceeding to hotel selection with:", {
                    destination: destinationSelect.value,
                    checkIn: checkInDate,
                    checkOut: checkOutDate,
                    adults: adultsCount,
                    children: childrenCount
                });
                
                // For demo purposes, show an alert
                alert("Proceeding to hotel selection step!");
            });

            // Close guest dropdown when clicking outside
            document.addEventListener("click", (e) => {
                if (!guestSelector.contains(e.target) && !guestDropdown.contains(e.target)) {
                    guestDropdown.style.display = "none";
                }
            });

            // Initialize
            renderCalendar();
            updateGuestDisplay();
            updateContinueButton();
        </script>
    </body>
</html>