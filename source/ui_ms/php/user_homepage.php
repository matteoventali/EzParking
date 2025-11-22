<?php
    require_once "./config.php";
    require_once "./functions.php";

    // We must be logged in to access this page
    if ( !verify_session() )
        header("Location: " . $starting_page);
    else if ( $_SESSION['role'] != 'user' ) // Redirect the user to the correct homepage
        header("Location: " . $homepage);

    // Get access to the name of the user
    $name = $_SESSION['user']['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>EzParking - Dashboard</title>

    <link rel="stylesheet" href="../css/homepage.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/navbar.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@400;700&display=swap" rel="stylesheet">

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
    <script src="../js/map.js" type="text/javascript"></script>
</head>
<body>
    <?php
        $nav = generate_navbar($_SESSION['role']);
        echo $nav;
    ?>

    <section class="hero">
        <h1>Welcome <?php echo $name; ?></h1>
        <p>Use the buttons below to search, book, or manage your reservations. Find and reserve your parking space in seconds.</p>


        <div class="actions" style="display:flex; gap:0.8rem; flex-wrap:wrap; justify-content:center;">
            <button onclick="location.href='../php/search_parking.php';">
                <i class="fas fa-map-marker-alt" ></i>
                Book Parking Spots
            </button>

            <button onclick="location.href='../php/manage_garage.php';">
                <i class="fas fa-calendar-check" ></i>
                Manage your Parking Spot
            </button>

            <button onclick="location.href='../php/manage_my_bookings.php';">
                <i class="fas fa-ticket-alt"></i>
                My Reservations
            </button>

            <button onclick="location.href='../php/plannig_calendar.php';">
                <i class="fas fa-history" ></i>
                Calendar
            </button>
        </div>
    </section>


    <section class="features">
        <h2>Find Nearest Parking Spots</h2>
        <div id="map" style="height: 500px; width: 100%; border-radius: 10px;"></div>
    </section>

    <?php
        $footer = file_get_contents(FOOTER);
        echo $footer;
    ?>
</body>
</html>
