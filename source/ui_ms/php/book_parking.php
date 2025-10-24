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
    include './functions.php';
    $nav = generate_navbar('user');
    echo $nav;
  ?>

  <main>
    <section class="card garage-info">
      <h1>Central Park Garage</h1>
      <div class="garage-details-single-column">
        <div class="garage-column">
          <p><strong>📍 Location:</strong> Get OpenStreetMap</p>
        </div>

        <div class="garage-column">
          <p><strong>⭐ Rating Threshold:</strong> 4 / 5</p>
        </div>

        <div class="garage-column">
          <p><strong>💰 Price:</strong> €2.50 / hour</p>
        </div>
      </div>
    </section>

    <section class="card booking-form">
      <h2>Book Your Spot</h2>
      <form action="../RES/book_parking.php" method="POST" id="bookingForm">
        <div>
          <label for="date">Date</label>
          <input type="date" id="date" name="date" required>
        </div>

       <div class="time-slot-selection">
          <label>Time Slot (Select one or more)</label>
          <div class="slot-options">
            <input type="checkbox" id="slot-08-09" name="time_slot[]" value="08:00-09:00" data-duration="1">
            <label for="slot-08-09" class="slot-pill">08:00 - 09:00 (€2.50)</label>

            <input type="checkbox" id="slot-09-10" name="time_slot[]" value="09:00-10:00" data-duration="1">
            <label for="slot-09-10" class="slot-pill">09:00 - 10:00 (€2.50)</label>

            <input type="checkbox" id="slot-10-11" name="time_slot[]" value="10:00-11:00" data-duration="1" disabled>
            <label for="slot-10-11" class="slot-pill disabled-slot">10:00 - 11:00 (Full)</label>

            <input type="checkbox" id="slot-11-12" name="time_slot[]" value="11:00-12:00" data-duration="1">
            <label for="slot-11-12" class="slot-pill">11:00 - 12:00 (€2.50)</label>

            <input type="checkbox" id="slot-12-13" name="time_slot[]" value="12:00-13:00" data-duration="1" disabled>
            <label for="slot-12-13" class="slot-pill disabled-slot">12:00 - 13:00 (Full)</label>

          </div>
        </div>
        <div>
          <label for="plate">Vehicle License Plate</label>
          <input type="text" id="plate" name="plate" placeholder="e.g. AB123CD" maxlength="8" required>
        </div>

        <div class="payment-method-selection">
            <label>Payment Method</label>
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

        <div class="price-display" id="totalCost">Total cost: €2.50 (1 hour)</div>

        <button type="submit" id="bookNowButton">Book Now</button>
      </form>
    </section>
  </main>

  <?php
    $footer = file_get_contents(FOOTER);
    echo $footer;
  ?>

<script src="../js/book_parking.js"></script>
</body>
</html>