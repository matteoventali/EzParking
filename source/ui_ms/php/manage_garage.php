<?php
    require_once "./config.php";
    require_once "./functions.php";

    // We must be logged in to access this page
    if ( !verify_session() )
        header("Location: " . $starting_page);
    else if ( $_SESSION['role'] != 'user' ) // Redirect the user to the correct homepage
        header("Location: " . $homepage);

    // Get the parking spots of the user currently logged in
    $api_url = compose_url($protocol, $socket_park_ms, '/parking_spots/users/' . $_SESSION['user']['id']);
    $response = perform_rest_request('GET', $api_url, null, null);

    // Checking if all is correct
    if  ( $response["body"]["code"] == 0  )
    {
        // Reading the template of the parking spot card
        $template = file_get_contents('../html/parking_spot_card.html');

        // Generating the list of parking spots
        $parking_spots_html = '';
        foreach ( $response["body"]["parking_spots"] as $spot )
        {
            $spot_html = $template;
            $spot_html = str_replace("%PARK_ID%", $spot["spot_id"], $spot_html);
            $spot_html = str_replace("%PARK_NAME%", $spot["spot_name"], $spot_html);
            $spot_html = str_replace("%PARK_COORD%", 'Lat:' . $spot["latitude"] . " Lon:" . $spot['longitude'], $spot_html);

            // Status
            if ( $spot["available"] )
            {
                $spot_html = str_replace("%PARK_STATUS%", 'available', $spot_html);
                $spot_html = str_replace("%PARK_STATUS_DESC%", strtoupper('available'), $spot_html);
            }
            else if ( $spot["available"] == null )
            {
                $spot_html = str_replace("%PARK_STATUS%", 'no-time-slot', $spot_html);
                $spot_html = str_replace("%PARK_STATUS_DESC%", strtoupper('no-time-slot'), $spot_html);
            }
            else
            {
                $spot_html = str_replace("%PARK_STATUS%", 'full', $spot_html);
                $spot_html = str_replace("%PARK_STATUS_DESC%", strtoupper('full'), $spot_html);
            }
                
            $parking_spots_html .= $spot_html;
        }
    }
    else
    {
        // The user has no parking spots yet
        $parking_spots_html = '<p class="no-garages-message">You have no parking spots yet. Click the "Add New Parking" button to create one.</p>';
    }  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>My Garages Dashboard</title>
    <link rel="stylesheet" href="../css/navbar.css" />
    <link rel="stylesheet" href="../css/manage_garage.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php
        $nav = generate_navbar($_SESSION['role']);
        echo $nav;
    ?>

  <main>
    <section class="dashboard-header">
        <h1>My Parking Spots</h1>
        <p>Manage your owned spots and check their current status.</p>
    </section>

    <a href="./insert_spot.php" class="add-new-button">
        <i class="fas fa-plus"></i> Add New Parking
    </a>

    <a href="./manage_request.php" class="add-new-button">
         See pending requests 
    </a>

    <section class="garage-list-container">
        <?php
            echo $parking_spots_html;
        ?>
    </section>
  </main>

    <?php
        $footer = file_get_contents(FOOTER);
        echo $footer;
    ?>
</body>
</html>
