<?php
    require_once "./config.php";
    require_once "./functions.php";

    // We must be logged in to access this page
    if ( !verify_session() )
        header("Location: " . $starting_page);
    else if ( $_SESSION['role'] != 'user' ) // Redirect the user to the correct homepage
        header("Location: " . $homepage);

    // Gets the available labels to populate the add select
    $labels = get_labels_content();
?>

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

    <!-- Map setup --> 
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        crossorigin=""
    />
    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        crossorigin=""
    ></script>
    <script src="../js/map_insert.js" type="text/javascript"></script>
</head>

<body>
    <?php
        $nav = generate_navbar($_SESSION['role']);
        echo $nav;
    ?>

    <div class="container">
        <div class="form-wrapper">
            <div class="form-header">
                <h1>Add New Parking Spot</h1>
                <p>Fill in the details to list your parking space</p>
            </div>

            <form id="addForm" action="insert_spot.php" class="parking-form" method="POST">
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
                            max="5" required>
                        <span class="input-suffix">/ 5</span>
                    </div>
                    <small class="input-hint">Minimum reputation score required to access this parking</small>
                </div>

                <div class="form-group">
                    <label for="hourlyRate">
                        Hourly Rate
                    </label>
                    <div class="input-with-icon">
                        <span class="input-prefix">€ </span>
                        <input type="number" id="hourlyRate" name="hourlyRate" placeholder="0.00" min="0.00" step="0.01"
                            required>
                    </div>
                    <small class="input-hint">Cost per hour for parking slot</small>
                </div>

                <label for="filtersSelect">Filters:</label>
                <select id="filtersSelect" name="filters[]" style="min-width:150px"; multiple>
                    <?php echo $labels; ?>
                </select>

                <label> Location Map </label>
                <div id="map" style="height: 500px; width: 100%; border-radius: 10px;"></div>

                <input type="hidden" id="latitude" name="latitude">
                <input type="hidden" id="longitude" name="longitude">
                <p id="coordsDisplay" style="margin-top:10px; color:#5e25a5; font-weight:600;">
                    Click on the map to select a location.
                </p>

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
</body>

</html>