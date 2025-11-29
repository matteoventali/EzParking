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

    // Informative variables
    $ok_message = $error_message = null;

    // Check if we must perform the insert operation
    if ( count($_POST) )
    {
        // Preparing the payload for the request
        $payload = [
            "name" => $_POST["parkingName"],
            "latitude" => floatval($_POST["latitude"]), 
            "longitude" => floatval($_POST["longitude"]),
            "slot_price"=> floatval($_POST["hourlyRate"]),
            "rep_treshold" => intval($_POST["reputationThreshold"]),
            "user_id" => $_SESSION["user"]["id"]
        ];

        // If there are labels adding it
        if ( isset($_POST["filters"]) )
            $payload["labels"] = $_POST["filters"];
        
        try
        {
            // Perform the request to the microservice
            $api_url = compose_url($protocol, $socket_park_ms, '/parking_spots');
            $response = perform_rest_request('POST', $api_url, $payload, null);    
            
            if ($response['status'] === 201)
            {
                $ok_message = $response["body"]["desc"];
                header("Location: manage_garage.php");
                exit();
            }
            else 
                $error_message = $response["body"]["desc"];
        } 
        catch (Exception $e) 
        {
            $error_message = "Error contacting API: " . $e->getMessage();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Book Central Park Garage</title>
    <link rel="stylesheet" href="../css/navbar.css" />
    <link rel="stylesheet" href="../css/insert_spot.css" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/search_parking.css" />    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Map setup --> 
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        crossorigin=""
    />
    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        crossorigin=""></script>
    <script src="../js/map_insert.js" type="text/javascript"></script>
    <script type="text/javascript">
        function checkPosition()
        {
            // Check if the position is clicked or not
            if ( document.getElementById('latitude').value === '' || document.getElementById('longitude').value === '' )
            {
                alert("Please select a location on the map.");
                return false;
            }
            else
                return true;
        }
    </script>
    <script src="../js/search_parking.js"></script>
    
</head>

<body style="background: linear-gradient(135deg, #f3ecff, #f6f4faff); -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;">
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

            <form id="addForm" onsubmit="return checkPosition();" action="insert_spot.php" class="parking-form" method="POST">
                <p class="error-message" id="error-message" style="text-align: center";>
                    <?php if(isset($error_message)) echo $error_message; else echo '';  ?>
                </p>
                <p class="error-message" style="color:green; text-align:center" id="ok-message">
                    <?php if(isset($ok_message)) echo $ok_message; else echo ''; ?>
                </p>
            
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

                <label> Parking Type:</label>
                <div class="dropdown" id="filtersDropdown" style="width: 100%;">
                    <div class="dropdown-toggle" style="width: 100%; justify-content: space-between"  >Select type of parking<i class="fa-solid fa-angle-down"></i></div>

                    <div class="dropdown-panel">
                        <div class="filters">
                            <?php echo $labels; ?>                            
                        </div>
                    </div>
                </div>

                <div class="label-pills" id="activeFilters"></div>                 

                <label style="margin-top: 1rem;"> Location Map </label>
                <div id="map" style="height: 500px; width: 100%; border-radius: 10px;"></div>

                <input type="hidden" id="latitude" name="latitude" required>
                <input type="hidden" id="longitude" name="longitude" required>
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