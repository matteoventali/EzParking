<?php
    require_once "./config.php";
    require_once "./functions.php";

    // We must be logged in to access this page
    if ( !verify_session() )
        header("Location: " . $starting_page);
    else if ( $_SESSION['role'] != 'user' ) // Redirect the user to the correct homepage
        header("Location: " . $homepage);

    // Get the bookings of the user logged in
    $api_url = compose_url($protocol, $socket_park_ms, '/reservations/users/' . $_SESSION["user"]["id"]);
    $response = perform_rest_request('GET', $api_url, null, null);

    // Output variable
    $html = '';

    // Checking errors
    if ( $response["status"] == 200 && $response["body"]["code"] == "0" )
    {
        // Reading the template of the card
        $card_template = file_get_contents('../html/my_booking_card.html');

        // Preparing the content
        foreach ( $response["body"]["reservations"] as $reservation )
        {
            // Replace the info
            $card = str_replace("%PARKING_NAME%", $reservation["spot_name"], $card_template);
            $card = str_replace("%PLATE%", $reservation["plate"], $card);
            $card = str_replace("%LATITUDE%", $reservation["spot_latitude"], $card);
            $card = str_replace("%LONGITUDE%", $reservation["spot_longitude"], $card);

            if ( $reservation["status"] === "pending" )
                $card = str_replace("%BUTTON%", '<button class="delete-booking-btn" onclick="setCurrentReservation(%ID%)" title="Cancel Reservation">
                                                        <i class="fas fa-trash-alt"></i></button>', $card);
            else
                $card = str_replace("%BUTTON%", '', $card);
            
            $card = str_replace("%STATUS%", $reservation["status"], $card);
            $card = str_replace("%ID%", $reservation["id"], $card);
            $card = str_replace("%RESIDENT%", $reservation["resident_name"] . " " . $reservation["resident_surname"], $card);

            $card = str_replace("%DATE%", $reservation["slot_date"], $card);
            $card = str_replace("%START%", $reservation["start_time"], $card);
            $card = str_replace("%END%", $reservation["end_time"], $card);

            $html .= $card; 
        }
    }
    else
        $html = '<p>No reservation has been found</p>';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>My Bookings Dashboard</title>
    <link rel="stylesheet" href="../css/navbar.css" />
    <link rel="stylesheet" href="../css/manage_my_bookings.css" />
    <link rel="stylesheet" href="../css/popup.css" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@100..900&display=swap" rel="stylesheet">    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">    
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>

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
</head>

<body style="background: linear-gradient(135deg, #f3ecff, #f6f4faff);">
    <?php
        $nav = generate_navbar($_SESSION["role"]);
        echo $nav;
    ?>

    <main>
        <section class="dashboard-header">
            <h1>My Bookings</h1>
            <p>View and manage all your current and past parking reservations.</p>
        </section>

        <a href="../php/search_parking.php" class="add-new-button">
            <i class="fas fa-calendar-plus"></i> Book New Parking
        </a>

        <section class="booking-list-container">
            <?php echo $html; ?>
        </section>
    </main>


    <!-- POPUP -->
    <div id="deleteModal" class="modal-overlay">
        <div class="modal-backdrop"></div>
        <div class="modal-content">
            <div class="modal-icon">
                <svg class="icon-trash" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>

            <h3 class="modal-title">Confirm Deletion</h3>

            <p class="modal-message">
                Are you sure you want to cancel this booking? This action cannot be undone.
            </p>

            <div class="modal-buttons">
                <button class="btn btn-cancel" id="cancelBtn">Cancel</button>
                <button class="btn btn-confirm" id="confirmBtn" onclick="performDelete();">Confirm</button>
            </div>
        </div>
    </div>

    <!-- MAP -->
    <div id="mapModal" class="modal-overlay">
        <div class="modal-backdrop"></div>
            <div class="modal-content map-modal-content">
            <button class="modal-close-btn" id="closeMapBtn">&times;</button>
            <div id="mapContainer" style="height: 500px; width: 100%; border-radius: 10px;"></div>
        </div>
    </div>

    <?php
        $footer = file_get_contents(FOOTER);
        echo $footer;
    ?>
    <script src="../js/popup.js"></script>
</body>
</html>