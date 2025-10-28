<?php
    require_once "./config.php";
    require_once "./functions.php";

    // We must be logged in to access this page
    if ( !verify_session() )
        header("Location: " . $starting_page);
    else if ( $_SESSION['role'] != 'user' ) // Redirect the user to the correct homepage
        header("Location: " . $homepage);

    // Get the parking spots of the user currently logged in
    
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
    include './functions.php';
    $nav = generate_navbar('user');
    echo $nav;
  ?>

  <main>
    <section class="dashboard-header">
        <h1>My Parking Spots</h1>
        <p>Manage your owned spots and check their current status.</p>
    </section>

    <a href="./add_new_garage.php" class="add-new-button">
        <i class="fas fa-plus"></i> Add New Parking
    </a>

    <section class="garage-list-container">

        <a href="./manage_garage.php?id=101" class="garage-card available">
            <div class="card-details">
                <h2 class="garage-name">Central Park Garage</h2>
                <p class="garage-address"><i class="fas fa-location-dot"></i> Via Colonna, 15, Rome</p>
            </div>
            <div class="card-status">
                <span class="status-indicator">Available</span>
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>

        <a href="./manage_garage.php?id=102" class="garage-card full">
            <div class="card-details">
                <h2 class="garage-name">Termini Hub Parking</h2>
                <p class="garage-address"><i class="fas fa-location-dot"></i> Piazza dei Cinquecento, 32, Rome</p>
            </div>
            <div class="card-status">
                <span class="status-indicator">Full</span>
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>

        <a href="./manage_garage.php?id=103" class="garage-card available">
            <div class="card-details">
                <h2 class="garage-name">Flaminio Garage S.p.A.</h2>
                <p class="garage-address"><i class="fas fa-location-dot"></i> Lungotevere Flaminio, 4, Rome</p>
            </div>
            <div class="card-status">
                <span class="status-indicator">Available</span>
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>

        <a href="./manage_garage.php?id=104" class="garage-card full">
            <div class="card-details">
                <h2 class="garage-name">West End Parking</h2>
                <p class="garage-address"><i class="fas fa-location-dot"></i> Viale Vaticano, 10, Rome</p>
            </div>
            <div class="card-status">
                <span class="status-indicator">Full</span>
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>

        <a href="./manage_garage.php?id=105" class="garage-card available">
            <div class="card-details">
                <h2 class="garage-name">Airport Terminal 3</h2>
                <p class="garage-address"><i class="fas fa-location-dot"></i> Via dell'Aeroporto, Fiumicino</p>
            </div>
            <div class="card-status">
                <span class="status-indicator">Available</span>
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>

        <a href="./manage_garage.php?id=106" class="garage-card available">
            <div class="card-details">
                <h2 class="garage-name">The Downtown Tower</h2>
                <p class="garage-address"><i class="fas fa-location-dot"></i> Corso Vittorio Emanuele, 1, Rome</p>
            </div>
            <div class="card-status">
                <span class="status-indicator">Available</span>
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>

        <a href="./manage_garage.php?id=107" class="garage-card full">
            <div class="card-details">
                <h2 class="garage-name">Historic Center Lot</h2>
                <p class="garage-address"><i class="fas fa-location-dot"></i> Piazza Navona, 5, Rome</p>
            </div>
            <div class="card-status">
                <span class="status-indicator">Full</span>
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>

    </section>
  </main>

  <?php
    $footer = file_get_contents(FOOTER);
    echo $footer;
  ?>
</body>
</html>
