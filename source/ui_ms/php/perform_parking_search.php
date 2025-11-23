<?php
    require_once "./config.php";
    require_once "./functions.php";

    header("Content-Type", "text/html");
    $html_result = "";

    // We must be logged in to access this page
    if (!verify_session())
        $html_result = '<p class="error">Unauthorized.</p>';
    else if ($_SESSION['role'] != 'user')
        $html_result = '<p class="error">Unauthorized.</p>';

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
            $payload["radius"] = floatval($_POST["distance"]);

        if (isset($_POST["filters"]) && !empty($_POST["filters"]))
            $payload["labels"] = $_POST["filters"];

        // Perform the request for the search
        $api_url = compose_url($protocol, $socket_park_ms, '/search');
        $response = perform_rest_request('POST', $api_url, $payload, null);

        // Reading the template of the card
        $card_template = file_get_contents('../html/parking_spot_card_search.html');
        
        if ($response["body"]["code"] != "0")
            $html_result = "<p style='text-align:center'> No parking slot has been found <p>";
        else 
        {
            // For each parking spot found create a card
            foreach ($response["body"]["results"] as $spot) 
            {
                // Replace the info
                $card = str_replace("%PARKING_NAME%", $spot["name"], $card_template);
                $card = str_replace("%LOCATION%", "Lat:" . $spot["latitude"] . " Long:" . $spot["longitude"], $card);
                $card = str_replace("%LOCATION%", "Lat:" . $spot["latitude"] . " Long:" . $spot["longitude"], $card);

                if (floatval($spot["distance_meters"]) > 1000)
                    $card = str_replace("%DISTANCE%", round(floatval($spot["distance_meters"]) / 1000, 2) . " km", $card);
                else
                    $card = str_replace("%DISTANCE%", $spot["distance_meters"] . " m", $card);

                $card = str_replace("%THRESHOLD%", $spot["rep_treshold"], $card);
                $card = str_replace("%PRICE%", $spot["slot_price"], $card);
                $slot = $spot["next_slot"];

                $card = str_replace("%FIRST_SLOT%", $slot["slot_date"] . " from " . $slot["start_time"] . " to " . $slot["end_time"], $card);
                
                if ( $spot["resident_id"] == $_SESSION["user"]["id"] ) 
                {
                    // The parking spot belongs to the user, hide the book button
                    $card = str_replace("%BUTTON%", "", $card);
                } else {
                    $card = str_replace("%BUTTON%", '<div class="card-actions">
                        <button class="book-btn" onclick="location.href = \'../php/book_parking.php?id=%PARKING_ID%\'">Book Now</button>
                    </div>', $card);
                }
                $card = str_replace("%PARKING_ID%", $spot["parking_spot_id"], $card);

                $html_result .= $card;
            }
        }
    }

    echo $html_result;
?>
