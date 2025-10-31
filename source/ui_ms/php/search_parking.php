<?php
    require_once "./config.php";
    require_once "./functions.php";

    // We must be logged in to access this page
    if (!verify_session())
        header("Location: " . $starting_page);
    else if ($_SESSION['role'] != 'user') // Redirect the user to the correct homepage
        header("Location: " . $homepage);

    if (count($_POST)) // If there is at least one parameter we must perform the search
    {
        if (isset($_POST["query"]) && !empty($_POST["query"])) {
            // Trying to get the coordinates for the address requested
            $resp = get_coordinates_from_address($_POST["query"]);
            $lat = floatval($resp['latitude']);
            $long = floatval($resp['longitude']);
        } else {
            $lat = floatval($_POST["latitude"]);
            $long = floatval($_POST["longitude"]);
        }

        // Setting the parameter for the search
        $payload = [
            "latitude" => $lat,
            "longitude" => $long,
            "user_reputation" => $_SESSION["user"]["score"]
        ];

        if (isset($_POST["distance"]) && !empty($_POST["distance"]))
            $payload["distance"] = floatval($_POST["distance"]);

        if (isset($_POST["filters"]) && !empty($_POST["filters"]))
            $payload["labels"] = $_POST["filters"];

        // Perform the request for the search
        $api_url = compose_url($protocol, $socket_park_ms, '/search');
        $response = perform_rest_request('POST', $api_url, $payload, null);

        // Reading the template of the card
        $card_template = file_get_contents('../html/parking_spot_card_search.html');
        $html_result = "";

        var_dump($response["body"]["results"]);

        // For each parking spot found create a card
        foreach ( $response["body"]["results"] as $spot)
        {   
            // Replace the info
            $html_result .= $card_template;
        }
            
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Available Parking Spots — Search & Filters</title>
    <link rel="stylesheet" href="../css/navbar.css" />
    <link rel="stylesheet" href="../css/search_parking.css" />
    <link rel="stylesheet" href="../css/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <script type="text/javascript">
        function set_hidden_fields() {
            // Gets the coordinates of the user from the sessionStorage
            const saved_lat = sessionStorage.getItem("user_latitude");
            const saved_lon = sessionStorage.getItem("user_longitude");

            // Setting the hidden fields of the form
            document.getElementById('latitude_hidden').value = saved_lat;
            document.getElementById('longitude_hidden').value = saved_lon;
        }
    </script>
</head>

<body style="background: white;" onload="set_hidden_fields();">
    <?php
        $nav = generate_navbar($_SESSION['role']);
        echo $nav;
    ?>

    <section class="search-section" id="searchSection" aria-label="Search parking">
        <form id="searchForm" class="search-wrapper" role="search" action="search_parking.php" method="POST">
            <div class="search-input">
                <input id="searchText" name="query" type="text" placeholder="Find a parking spot... (Address, City)">
            </div>

            <div class="controls">
                <!-- Filters select -->
                <label for="filtersSelect">Filters:</label>
                <select id="filtersSelect" name="filters" style="min-width:150px;">
                    <option value="">-- Select --</option>
                    <option value="low_price">Low Price</option>
                    <option value="high_rating">High Rating</option>
                </select>

                <!-- Distance select -->
                <label for="distanceSelect">Distance:</label>
                <select id="distanceSelect" name="distance" style="min-width:120px;">
                    <option value="">-- Select --</option>
                    <option value="5">5 km</option>
                    <option value="10">10 km</option>
                    <option value="15">15 km</option>
                    <option value="30">30 km</option>
                    <option value="more30">&gt; 30 km</option>
                </select>

                <!-- Hidden fields -->
                <input name="latitude" type="hidden" id="latitude_hidden">
                <input name="longitude" type="hidden" id="longitude_hidden">

                <button class="search-btn" id="searchBtn" type="submit">Search</button>
            </div>
        </form>
    </section>


    <main class="parking-container" id="parkingList" aria-live="polite">
        <?php echo $html_result; ?>
    </main>

    <?php
        $footer = file_get_contents(FOOTER);
        echo $footer;
    ?>
</body>

</html>