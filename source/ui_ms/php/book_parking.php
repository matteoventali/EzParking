<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Book Central Park Garage</title>
  <link rel="stylesheet" href="../css/navbar.css" />
  <link rel="stylesheet" href="../css/book_parking.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
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
      <div class="garage-details">
        <div class="garage-column">
          <p><strong>📍 Location:</strong> 41.9028, 12.4964</p>
          <p><strong>🚗 Capacity:</strong> 150 spots</p>
        </div>

        <div class="garage-column">
          <p><strong>⭐ Rating Threshold:</strong> 4 / 5</p>
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

        <div>
          <label>Time Interval</label>
          <div class="time-range">
            <input type="time" id="start_time" name="start_time" required>
            <input type="time" id="end_time" name="end_time" required>
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
                <label for="applepay">Apple Pay</label><br>

                <input type="radio" id="googlepay" name="payment_method" value="googlepay">
                <label for="googlepay">Google Pay</label><br>

                <input type="radio" id="paypal" name="payment_method" value="paypal">
                <label for="paypal">PayPal</label><br>

                <input type="radio" id="creditcard" name="payment_method" value="creditcard">
                <label for="creditcard">Credit Card</label>
            </div>
        </div>
        <div class="price-display" id="totalCost">Total cost: €0.00</div>

        <button type="submit" id="bookNowButton">Book Now</button>
      </form>
    </section>
  </main>

  <?php
    $footer = file_get_contents(FOOTER);
    echo $footer;
  ?>

 <script src="../js/book_parking.js"></script>  </body>
</html>