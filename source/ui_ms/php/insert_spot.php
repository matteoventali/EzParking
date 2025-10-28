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

        <div class="calendar-wrapper">
          <button type="button" id="prevDay">&lt;</button>
          <input type="date" id="date" name="date" required value="<?php echo date('Y-m-d'); ?>">
          <button type="button" id="nextDay">&gt;</button>
        </div>

        <div class="time-slot-selection">
          <br>
          <label>Time Slot</label>
          <div class="slot-options" id="slotOptionsContainer">
            <!-- Gli slot saranno generati dinamicamente da JS -->
          </div>
        </div>

        <div>
          <br>
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

<script ></script>

</body>
</html>
