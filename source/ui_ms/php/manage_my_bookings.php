<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>My Bookings Dashboard</title>
    <link rel="stylesheet" href="../css/navbar.css" />
    <link rel="stylesheet" href="../css/manage_my_bookings.css" />
    <link rel="stylesheet" href="../css/popup.css" />
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
        <section class="dashboard-header">
            <h1>My Bookings</h1>
            <p>View and manage all your current and past parking reservations.</p>
        </section>

        <a href="../php/search_parking.php" class="add-new-button">
            <i class="fas fa-calendar-plus"></i> Book New Parking
        </a>

        <section class="booking-list-container">
            <div class="booking-card confirmed" data-booking-id="B101">
                <div class="card-details">
                    <h2 class="garage-name">Central Park Garage</h2>
                    <p class="car-license">Vehicle Plate: **RM123AB**</p>
                    <p class="garage-address"><i class="fas fa-location-dot"></i> Via Colonna, 15, Rome</p>
                </div>
                <div class="card-status">
                    <span class="status-indicator">Confirmed</span>
                    <button class="delete-booking-btn" title="Cancel Reservation">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>

            <div class="booking-card pending" data-booking-id="B101">
                <div class="card-details">
                    <h2 class="garage-name">Central Park Garage</h2>
                    <p class="car-license">Vehicle Plate: **RM123AB**</p>
                    <p class="garage-address"><i class="fas fa-location-dot"></i> Via Colonna, 15, Rome</p>
                </div>
                <div class="card-status">
                    <span class="status-indicator">Pending</span>
                    <button class="delete-booking-btn" title="Cancel Reservation">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>


            <div class="booking-card cancelled" data-booking-id="B101">
                <div class="card-details">
                    <h2 class="garage-name">Central Park Garage</h2>
                    <p class="car-license">Vehicle Plate: **RM123AB**</p>
                    <p class="garage-address"><i class="fas fa-location-dot"></i> Via Colonna, 15, Rome</p>
                </div>
                <div class="card-status">
                    <span class="status-indicator">Cancelled</span>
                    
                </div>
            </div>


            <div class="booking-card completed" data-booking-id="B101">
                <div class="card-details">
                    <h2 class="garage-name">Central Park Garage</h2>
                    <p class="car-license">Vehicle Plate: **RM123AB**</p>
                    <p class="garage-address"><i class="fas fa-location-dot"></i> Via Colonna, 15, Rome</p>
                </div>
                <div class="card-status">
                    <span class="status-indicator">Completed</span>
                    <button class="delete-booking-btn" title="Cancel Reservation">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
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
                <button class="btn btn-confirm" id="confirmBtn">Confirm</button>
            </div>
        </div>
    </div>

    <?php
    $footer = file_get_contents(FOOTER);
    echo $footer;
    ?>
</body>


<script src="../js/popup.js"></script>




</html>