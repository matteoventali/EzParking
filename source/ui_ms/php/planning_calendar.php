<?php 
    require_once "./config.php";
    require_once "./functions.php";

    // We must be logged in to access this page
    if (!verify_session())
        header("Location: " . $starting_page);
    else if ($_SESSION['role'] != 'user') // Redirect the user to the correct homepage
        header("Location: " . $homepage);

    // Retrieving the info about slots related to the parking of the user
    $api_url = compose_url($protocol, $socket_park_ms, '/time_slots/users/' . $_SESSION["user"]["id"]);
    $response_slot = perform_rest_request('GET', $api_url, null, null);

    // Retrieving the info about reservations made by the user
    $api_url = compose_url($protocol, $socket_park_ms, '/reservations/users/' . $_SESSION["user"]["id"]);
    $response_reservation = perform_rest_request('GET', $api_url, null, null);

    // Composing the arrays
    $array_free_spots = array();
    $array_busy_spots = array();
    $array_reservations = array();
    if ( $response_slot["status"] == 200 && $response_slot["body"]["code"] === "0" )
    {
        foreach( $response_slot["body"]["parking_spots"] as $spot )
        {
            // For each available slot of the parking
            foreach( $spot["available_slots"] as $slot )
            {
                $event = [
                    "id" => $slot["id"],
                    "title" => "Free slot for " . $spot["name"],
                    "start" => $slot["slot_date"] . "T" . $slot["start_time"],
                    "end"   => $slot["slot_date"] . "T" . $slot["end_time"]
                ];

                array_push($array_free_spots, $event);
            }
            
            // For each busy slot of the parking
            foreach( $spot["taken_slots"] as $slot )
            {
                $event = [
                    "id" => $slot["slot"]["id"],
                    "title" => "Reserved slot for " . $spot["name"],
                    "start" => $slot["slot"]["slot_date"] . "T" . $slot["slot"]["start_time"],
                    "end"   => $slot["slot"]["slot_date"] . "T" . $slot["slot"]["end_time"]
                ];

                array_push($array_busy_spots, $event);
            }
        }
    }
    if ( $response_reservation["status"] == 200 && $response_reservation["body"]["code"] === "0" )
    {
        foreach($response_reservation["body"]["reservations"] as $res)
        {
            $event = [
                "id" => $res["id"],
                "title" => "Reservation for " . $res["spot_name"],
                "start" => $res["slot_date"] . "T" . $res["start_time"],
                "end"   => $res["slot_date"] . "T" . $res["end_time"]
            ];

            array_push($array_reservations, $event);
        }
    }
?>

<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta charset='utf-8' />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>View Calendar</title>
        <link rel="stylesheet" href="../css/navbar.css" />
        <link rel="stylesheet" href="../css/style.css" />
        <link rel="stylesheet" href="../css/planning_calendar.css" />
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js'></script>
        
        <script>
            freeSlots = <?php echo json_encode($array_free_spots); ?>;
            busySlots = <?php echo json_encode($array_busy_spots); ?>;
            reservations = <?php echo json_encode($array_reservations); ?>;
        </script>

        <script src="../js/planning_calendar.js"></script>
    </head>
    <body style="background: white;">
        <?php
            $nav = generate_navbar($_SESSION["role"]);
            echo $nav;
        ?>
        <div class="calendar-container">
            <div id="calendar" style="height: 100%;"></div>
        </div>
        <?php
            $footer = file_get_contents(FOOTER);
            echo $footer;
        ?>
    </body>
</html>