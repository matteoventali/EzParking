<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Book Central Park Garage</title>
    <link rel="stylesheet" href="../css/navbar.css" />
    <link rel="stylesheet" href="../css/insert_spot.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <?php
    include './functions.php';
    $nav = generate_navbar('user');
    echo $nav;
    ?>

    <div class="container">
        <div class="form-wrapper">
            <div class="form-header">
                <h1>Add New Parking Spot</h1>
                <p>Fill in the details to list your parking space</p>
            </div>

            <form id="parkingForm" class="parking-form">
                <div class="form-group">
                    <label for="parkingName">
                        Parking Name
                        
                    </label>
                    <input type="text" id="parkingName" name="parkingName" placeholder="Enter parking spot name"
                        required>
                </div>

                <div class="form-group">
                    <label for="reputationThreshold">
                        Reputation Threshold
                        
                    </label>
                    <div class="input-with-icon">
                        <input type="number" id="reputationThreshold" name="reputationThreshold" placeholder="0" min="0"
                            max="100" required>
                        <span class="input-suffix">/ 100</span>
                    </div>
                    <small class="input-hint">Minimum reputation score required to access this parking</small>
                </div>

                <div class="form-group">
                    <label for="hourlyRate">
                        Hourly Rate
                        
                    </label>
                    <div class="input-with-icon">
                        <span class="input-prefix">$</span>
                        <input type="number" id="hourlyRate" name="hourlyRate" placeholder="0.00" min="0" step="0.01"
                            required>
                    </div>
                    <small class="input-hint">Cost per hour for parking slot</small>
                </div>

                <div class="form-group">
                    <label for="label">
                        Label
                        
                    </label>
                    <div class="custom-select">
                        <select id="label" name="label" required>
                            <option value="" disabled selected>Select a label</option>
                            <option value="label1">Label 1</option>
                            <option value="label2">Label 2</option>
                            <option value="label3">Label 3</option>
                            <option value="label4">Label 4</option>
                            <option value="label5">Label 5</option>
                        </select>
                        <div class="select-arrow">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="3">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                    </div>
                    <small class="input-hint">Choose a category label for this parking spot</small>
                </div>

                <div class="form-group map-placeholder">
                    <label>
                        Location Map
                    </label>
                    <div class="map-container">
                        <div class="map-placeholder-content">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <p>Map integration placeholder</p>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="manage_garage.php"><button type="button" class="btn btn-secondary" id="cancelBtn">
                        Cancel
                    </button></a>
                    <button type="submit" class="btn btn-primary">
                        Add Parking Spot
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php
    $footer = file_get_contents(FOOTER);
    echo $footer;
    ?>

    <script>
        // Form validation and submission handling
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('parkingForm');
            

            // Form submission handler
            form.addEventListener('submit', (e) => {
                e.preventDefault();

                // Get form data
                const formData = {
                    parkingName: document.getElementById('parkingName').value,
                    reputationThreshold: parseInt(document.getElementById('reputationThreshold').value),
                    hourlyRate: parseFloat(document.getElementById('hourlyRate').value),
                    label: document.getElementById('label').value
                };

                // Validate form data
                if (validateForm(formData)) {
                    console.log('Parking spot data:', formData);

                    // Show success feedback
                    showFeedback('Parking spot added successfully!', 'success');

                    // Reset form after delay
                    setTimeout(() => {
                        form.reset();
                    }, 1500);
                }
            });

            // Cancel button handler


            // Real-time validation for reputation threshold
            const reputationInput = document.getElementById('reputationThreshold');
            reputationInput.addEventListener('input', (e) => {
                const value = parseInt(e.target.value);
                if (value < 0) e.target.value = 0;
                if (value > 100) e.target.value = 100;
            });

            // Real-time validation for hourly rate
            const hourlyRateInput = document.getElementById('hourlyRate');
            hourlyRateInput.addEventListener('input', (e) => {
                const value = parseFloat(e.target.value);
                if (value < 0) e.target.value = 0;
            });
        });

        // Form validation function
        function validateForm(data) {
            if (!data.parkingName || data.parkingName.trim() === '') {
                showFeedback('Please enter a parking name', 'error');
                return false;
            }

            if (isNaN(data.reputationThreshold) || data.reputationThreshold < 0 || data.reputationThreshold > 100) {
                showFeedback('Reputation threshold must be between 0 and 100', 'error');
                return false;
            }

            if (isNaN(data.hourlyRate) || data.hourlyRate < 0) {
                showFeedback('Please enter a valid hourly rate', 'error');
                return false;
            }

            if (!data.label || data.label.trim() === '') {
                showFeedback('Please select a label', 'error');
                return false;
            }

            return true;
        }

        

    </script>

</body>

</html>