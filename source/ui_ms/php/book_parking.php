<?php
    require_once "./config.php";
    require_once "./functions.php";

    // We must be logged in to access this page
    if (!verify_session())
        header("Location: " . $starting_page);
    else if ($_SESSION['role'] != 'user') // Redirect the user to the correct homepage
        header("Location: " . $homepage);

    // If we haven't the id of the parking redirecting on the homepage
    if ( ! isset($_GET["id"]) || ! is_numeric($_GET["id"]) )
        header("Location: " . $homepage);

    // Gets all the info of the parking 
    $api_url = compose_url($protocol, $socket_park_ms, "/parking_spots/" . $_GET["id"]);
    $response_park = perform_rest_request('GET', $api_url, null, null);

    if ( $response_park["body"]["code"] != "0" )
        header("Location: " . $homepage);
    $spot = $response_park["body"]["parking_spot"];

    // Convert the location in address
    $address = get_address_from_coordinates(floatval($spot["latitude"]), floatval($spot["longitude"]));
    
    // Setting the information to be visualizaed client side
    $slots = [];
    foreach ( $spot["time_slots"] as $slot )
    {
        $date = $slot['slot_date'];
        $timeRange = "{$slot['start_time']}-{$slot['end_time']}";
        $duration = calculate_duration($slot['start_time'], $slot['end_time']);

        // Slot in the corresponding data
        if ( !isset($slots[$date]) )
            $slots[$date] = [];
        

        $slots[$date][] = [
            "id" => $slot['id'],
            "time" => $timeRange,
            "duration" => $duration
        ];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Book Central Park Garage</title>

    <link rel="stylesheet" href="../css/navbar.css" />
    <link rel="stylesheet" href="../css/book_parking.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <?php
        $nav = generate_navbar($_SESSION["role"]);
        echo $nav;
    ?>

  <main>
    <section class="card garage-info">
      <h1><?php echo $spot["name"]; ?></h1>
      <div class="garage-details-single-column">
        <div class="garage-column">
          <p><strong>📍 Location: </strong><?php echo $address; ?></p>
        </div>
        <div class="garage-column">
          <p><strong>⭐ Rating Threshold: </strong><?php echo $spot["rep_treshold"]; ?>/ 5</p>
        </div>
        <div class="garage-column">
          <p><strong>💰 Price: </strong>€<?php echo $spot["slot_price"]; ?> / hour</p>
        </div>
      </div>
    </section>

    <section class="card booking-form">
      <h2>Book Your Spot</h2>
      <form action="../php/perform_payment.php" method="POST" id="bookingForm">

        <div class="calendar-wrapper">
          <button type="button" id="prevDay" class="nav-day-btn"><i class="fas fa-chevron-left"></i></button>
          <input type="date" id="date" name="date" required value="<?php echo date('Y-m-d'); ?>">
          <button type="button" id="nextDay" class="nav-day-btn"><i class="fas fa-chevron-right"></i></button>
        </div>

        <div class="time-slot-selection">
          <br>
          <h3>Available Time Slots</h3>
          <div class="slot-options" id="slotOptionsContainer">
          </div>
        </div>

        <div class="vehicle-section">
          <br>
          <label for="plate">Vehicle License Plate</label>
          <input type="text" id="plate" name="plate" placeholder="e.g. AB123CD" maxlength="8" required>
        </div>

        <div class="payment-method-selection">
          <h3>Payment Method</h3>
          <div class="payment-options">
            <input type="radio" id="applepay" name="payment_method" value="applepay" required>
            <label for="applepay"><i class="fab fa-apple-pay"></i> Apple Pay</label>

            <input type="radio" id="googlepay" name="payment_method" value="googlepay">
            <label for="googlepay"><i class="fab fa-google-pay"></i> Google Pay</label>

            <input type="radio" id="paypal" name="payment_method" value="paypal">
            <label for="paypal"><i class="fab fa-paypal"></i> PayPal</label>

            <input type="radio" id="creditcard" name="payment_method" value="creditcard">
            <label for="creditcard"><i class="fa-solid fa-credit-card"></i> Credit Card</label>
          </div>
        </div>

        <div class="price-display" id="totalCost">Total cost: €0.00</div>

        <button type="submit" id="bookNowButton" class="book-button">Book Now</button>
      </form>
    </section>
  </main>

    <?php
        $footer = file_get_contents(FOOTER);
        echo $footer;
    ?>
    <script>
        const slotData = <?php echo json_encode($slots, JSON_PRETTY_PRINT); ?>;
        const pricePerHour = <?php echo $spot["slot_price"]; ?>;
    </script>
    <script src="../js/book_parking.js"></script>
</body>
</html>
