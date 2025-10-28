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
  <style>
    /* Lista scrollabile */
    #slotOptionsContainer {
      max-height: 220px;
      overflow-y: auto;
      border: 1px solid #ccc;
      padding: 10px;
      border-radius: 8px;
      background-color: #f8f9fa;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    /* Slot disponibili */
    .slot-pill {
      padding: 8px 14px;
      border-radius: 25px;
      background-color: #9671c3ff;
      cursor: pointer;
      display: inline-block;
      margin: 2px 0;
      font-weight: 500;
      transition: background-color 0.2s, transform 0.1s, color 0.2s;
    }

    .slot-pill:hover {
      background-color: #5e25a5;
      transform: scale(1.03);
    }

    .slot-pill.selected {
      background-color: #5e25a5;
      color: white;
    }

    /* Barra calendario con pulsanti */
    .calendar-wrapper {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 10px;
    }

    .calendar-wrapper button {
      padding: 6px 12px;
      font-size: 16px;
      cursor: pointer;
      border-radius: 5px;
      border: 1px solid #888;
      background-color: #fff;
      transition: background-color 0.2s;
    }

    .calendar-wrapper button:hover {
      background-color: #eee;
    }
  </style>
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
          <label>Time Slot (Select one)</label>
          <div class="slot-options" id="slotOptionsContainer">
            <!-- Gli slot saranno generati dinamicamente da JS -->
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

<script>
const slotData = {
  "2025-10-28": [
    {time: "08:00-09:00", price: 2.5},
    {time: "09:00-10:00", price: 2.5},
    {time: "11:00-12:00", price: 2.5},
    {time: "12:00-13:00", price: 2.5},
    {time: "13:00-14:00", price: 2.5},
    {time: "14:00-15:00", price: 2.5},
    {time: "15:00-16:00", price: 2.5}
  ],
  "2025-10-29": [
    {time: "09:00-10:00", price: 2.5},
    {time: "10:00-11:00", price: 2.5},
    {time: "12:00-13:00", price: 2.5},
    {time: "13:00-14:00", price: 2.5},
    {time: "14:00-15:00", price: 2.5},
    {time: "15:00-16:00", price: 2.5}
  ],
  "2025-10-30": [
    {time: "08:00-09:00", price: 2.5},
    {time: "09:00-10:00", price: 2.5},
    {time: "10:00-11:00", price: 2.5},
    {time: "11:00-12:00", price: 2.5},
    {time: "13:00-14:00", price: 2.5},
    {time: "14:00-15:00", price: 2.5},
    {time: "15:00-16:00", price: 2.5}
  ],
  "2025-10-31": [] // giorno senza posti disponibili
};

const dateInput = document.getElementById("date");
const slotContainer = document.getElementById("slotOptionsContainer");
const prevDayBtn = document.getElementById("prevDay");
const nextDayBtn = document.getElementById("nextDay");

function updateSlots(date) {
  const slots = slotData[date];
  slotContainer.innerHTML = "";

  if (!slots || slots.length === 0) {
    slotContainer.textContent = "No parking spot available";
    updateTotalCost();
    return;
  }

  slots.forEach((slot, index) => {
    const slotId = `slot-${index}`;

    const input = document.createElement("input");
    input.type = "checkbox"; // checkbox ma user può selezionare solo 1
    input.id = slotId;
    input.name = "time_slot[]";
    input.value = slot.time;
    input.dataset.duration = 1;

    const label = document.createElement("label");
    label.htmlFor = slotId;
    label.className = "slot-pill";
    label.textContent = `${slot.time} (€${slot.price})`;

    input.addEventListener("change", () => {
      // Deseleziona tutti gli altri slot
      document.querySelectorAll('input[name="time_slot[]"]').forEach(el => {
        if (el !== input) el.checked = false;
      });
      // Aggiorna classi
      document.querySelectorAll('.slot-pill').forEach(l => l.classList.remove('selected'));
      if (input.checked) label.classList.add('selected');
      updateTotalCost();
    });

    slotContainer.appendChild(input);
    slotContainer.appendChild(label);
  });

  updateTotalCost();
}

function updateTotalCost() {
  const selectedSlots = document.querySelectorAll('input[name="time_slot[]"]:checked');
  const hours = selectedSlots.length;
  const cost = hours * 2.5;
  const totalCost = document.getElementById("totalCost");
  totalCost.textContent = hours > 0
    ? `Total cost: €${cost.toFixed(2)} (${hours} hour${hours !== 1 ? "s" : ""})`
    : `Total cost: €0.00`;
}

function changeDate(days) {
  const date = new Date(dateInput.value);
  date.setDate(date.getDate() + days);
  const newDateStr = date.toISOString().split('T')[0];
  dateInput.value = newDateStr;
  updateSlots(newDateStr);
}

prevDayBtn.addEventListener("click", () => changeDate(-1));
nextDayBtn.addEventListener("click", () => changeDate(1));
dateInput.addEventListener("change", () => updateSlots(dateInput.value));

// inizializza
updateSlots(dateInput.value);
</script>

</body>
</html>
