<?php
    require_once "./config.php";
    require_once "./functions.php";

    // We must be logged in to access this page
    if ( !verify_session() )
        header("Location: " . $starting_page);
    else if ( $_SESSION['role'] != 'user' ) // Redirect the user to the correct homepage
        header("Location: " . $homepage);

    // Get the request for parking spot of the user logged in
    $api_url = compose_url($protocol, $socket_park_ms, '/requests/' . $_SESSION["user"]["id"]);
    $response = perform_rest_request('GET', $api_url, null, null);

    // Output variable
    $html = '';
    $count = 0;

    // Checking errors
    if ( $response["status"] == 200 && $response["body"]["code"] == "0" )
    {
        // Reading the template of the card
        $card_template = file_get_contents('../html/request_spot_card.html');
        $count = count( $response["body"]["requests"] );
        
        foreach( $response["body"]["requests"] as $req )
        {
            // Replace the info
            $card = str_replace("%SPOT_NAME%", $req["parking_spot_name"], $card_template);
            $card = str_replace("%PLATE%", $req["car_plate"], $card);
            $card = str_replace("%DATE%", $req["slot_date"], $card);
            $card = str_replace("%START%", $req["start_time"], $card);
            $card = str_replace("%END%", $req["end_time"], $card);
            $card = str_replace("%ID%", $req["reservation_id"], $card);
            $card = str_replace("%LATITUDE%", $req["latitude"], $card);
            $card = str_replace("%LONGITUDE%", $req["longitude"], $card);
            $card = str_replace("%STATUS%", strtoupper($req["status"]), $card);
         
            $html .= $card;
        }
    }
    else
        $html = '<p style="text-align: center">No request has been found</p>';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>My Garages Dashboard</title>
    <link rel="stylesheet" href="../css/navbar.css" />
    <link rel="stylesheet" href="../css/manage_request.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>


<body>
    <?php
        $nav = generate_navbar($_SESSION["role"]);
        echo $nav;
    ?>


    <main class="container">
        <section class="panel" aria-labelledby="queue-title">
            <header class="top">
                <div>
                    <h1 id="queue-title">Reservation Requests Queue</h1>
                    <div class="subtitle">Manage incoming  req requests</div>
                </div>
                <div class="controls">
                    <div class="search" role="search">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M21 21l-4.35-4.35" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="1.5"></circle>
                        </svg>
                        <input id="search" placeholder="Search by parking or street" aria-label="Search requests">
                    </div>
                    <div class="stats" aria-hidden="true">
                        <div class="stat">Total <strong id="total-count"><?php echo $count; ?></strong></div>
                    </div>
                </div>
            </header>
            <div class="list" id="requests-list">
                <?php echo $html; ?>
            </div>
        </section>
    </main>

    <?php
        $footer = file_get_contents(FOOTER);
        echo $footer;
    ?>

    <script src="../js/manage_request.js"></script>
</body>

</html>