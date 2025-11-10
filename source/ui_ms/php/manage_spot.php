<?php
    require_once "./config.php";
    require_once "./functions.php";

    // We must be logged in to access this page
    if ( !verify_session() )
        header("Location: " . $starting_page);
    else if ($_SESSION['role'] != 'user' || !isset($_GET["id"]) && !isset($_POST["id"])) // Redirect the user to the correct homepage
        header("Location: " . $homepage);

    if ( isset($_GET["id"]) )
        $id = $_GET["id"];
    else if ( isset($_POST["id"]) )
        $id = $_POST["id"];
    
    // Fill the page with the parking spot's data
    $api_url = compose_url($protocol, $socket_park_ms, '/parking_spots/' . $id);
    $response = perform_rest_request('GET', $api_url, null, null);

    // Variables of the page
    $name = $address = $slot_price = $rep = null;

    // Informative variables
    $ok_message = $error_message = null;

    if ( ! $response["status"] == 200 )
        header("Location: " . $homepage);
    else
    {
        // Filling the variables
        $name = $response["body"]["parking_spot"]["name"];
        $slot_price = $response["body"]["parking_spot"]["slot_price"];
        $rep = $response["body"]["parking_spot"]["rep_treshold"];

         // Convert the location in address
        $address = get_address_from_coordinates(floatval($response["body"]["parking_spot"]["latitude"]), 
                                                floatval($response["body"]["parking_spot"]["longitude"]));
    }

    // Checking if there is a trying to insert a new slot for the parking spot
    if ( count($_POST) )
    {
        // Preparing the request to the microservice
        $api_url = compose_url($protocol, $socket_park_ms, '/time_slots/' . $id);
        $payload = [
            "start_time" => $_POST["start_time"],
            "end_time" => $_POST["end_time"],
            "slot_date" => $_POST["slot_date"]
        ];
        $response = perform_rest_request('POST', $api_url, $payload, null);

        if ( $response["status"] === 201 )
            $ok_message = $response["body"]["desc"];
        else 
            $error_message = $response["body"]["desc"];
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Parking Management</title>
    <link rel="stylesheet" href="../css/navbar.css" />
    <link rel="stylesheet" href="../css/manage_single_card.css" />
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
   <?php
        $nav = generate_navbar($_SESSION['role']);
        echo $nav;
    ?>

    <main class="container">
        <section class="parking-info">
            <h1 class="parking-name"><?php if (isset($name)) echo $name; ?></h1>
            <div class="details">
                <p><strong>Address: </strong><?php if (isset($address)) echo $address; ?></p>
                <p><strong>Slot Price: </strong><?php if (isset($slot_price)) echo $slot_price; ?> €/h</p>
                <p><strong>Reputation Threshold :</strong><?php if (isset($rep)) echo $rep; ?> / 5</p>
            </div>
        </section>

        <section class="timeslot-section">
            <form id="add-timeslot-form" class="add-form" action="manage_spot.php" method="post">
                <input name="id" type="hidden" id="id" value="<?php echo $id;?>" required />
                <h3>Add New Time Slot</h3>
                <div class="form-group">
                    <label for="slot_date">Date</label>
                    <input name="slot_date" type="date" id="slot_date" required />
                </div>
                <div class="form-group">
                    <label for="start_time">Start Time</label>
                    <input name="start_time" type="time" id="start_time" required />
                </div>
                <div class="form-group">
                    <label for="end_time">End Time</label>
                    <input name="end_time" type="time" id="end_time" required />
                </div>
                <button type="submit" class="btn-primary">Add Slot</button>
                <p class="error-message" id="error-message">
                    <?php if(isset($error_message)) echo $error_message; else echo '';  ?>
                </p>
                <p class="error-message" style="color:green" id="ok-message">
                    <?php if(isset($ok_message)) echo $ok_message; else echo ''; ?>
                </p>
            </form>
        </section>
    </main>
  
    <?php
        $footer = file_get_contents(FOOTER);
        echo $footer;
    ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const dateInput = document.getElementById("slot_date");

            // Get today's date in YYYY-MM-DD format
            const today = new Date().toISOString().split("T")[0];

            // Set the minimum selectable date
            dateInput.setAttribute("min", today);
        });
    </script>
</body>
</html>