<?php
    require_once "./config.php";
    require_once "./functions.php";

    // We must be logged in to access this page
    if ( !verify_session() )
        header("Location: " . $starting_page);
    else if ( $_SESSION['role'] != 'user') // We must be normal user to access this page
        header("Location: admin_dashboard.php");

    // Updating the user informations in the session storage to get access also to the score of the user
    $url = compose_url($protocol, $socket_account_ms, '/pdata');
    $new_data = perform_rest_request('GET', $url, null, $_SESSION['session_token']);
    $_SESSION['user'] = array_merge($_SESSION['user'], $new_data['body']['user']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../css/homepage.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <?php
        $nav = generate_navbar($_SESSION['role']);
        echo $nav;
    ?>

  <main class="dashboard-grid">

    <!-- User Data Section -->
    <div class="dashboard-card user-data-card">
      <div class="user-header">
        <img src="../images/account.svg" alt="User Avatar" class="user-avatar">
        <div>
          <h2 class="user-name"><?php echo strtoupper($_SESSION['user']['name'] . ' ' . $_SESSION['user']['surname']); ?></h2>
        </div>
      </div>
    
      <div class="user-info">
        <div class="info-item">
          <i class="fas fa-phone"></i>
          <span><strong>Name: </strong><?php echo $_SESSION['user']['name']; ?></span>
        </div>
        <div class="info-item">
          <i class="fas fa-phone"></i>
          <span><strong>Surname: </strong><?php echo $_SESSION['user']['surname']; ?></span>
        </div>
        <div class="info-item">
          <i class="fas fa-phone"></i>
          <span><strong>Email: </strong><?php echo $_SESSION['user']['email']; ?></span>
        </div>
        <div class="info-item">
          <i class="fas fa-phone"></i>
          <span><strong>Phone: </strong><?php echo $_SESSION['user']['phone']; ?></span>
        </div>
        <div class="info-item">
          <i class="fas "></i>
          <span><strong>Role: </strong><?php echo strtoupper($_SESSION['role']); ?></span>
        </div>
      </div>

      <button class="edit-btn" onclick="window.location.href='edit_profile.php'">
        <i class="fas fa-user-edit"></i> Edit Profile
      </button>
    </div>

    <!-- TO BE REMOVED!!! -->
    <!-- Reputation Section -->
    <div class="dashboard-card reputation-card">
      <div class="section-title">Reputation</div>
      <div class="reputation-score">⭐ <?php echo $_SESSION['user']['score'];?>/5</div>
      <p style="margin-top: 0.5rem; color:#666; font-size:0.95rem;">
        Keep contributing to improve your score!
      </p>
    </div>

    <!-- Reservations Section -->
    <div class="dashboard-card reservations-card">
      <div class="section-title">Your Parking Reservations</div>

      <div class="reservation-list-container">
        <ul class="reservation-list">
          <li onclick="window.location.href='reservation_details.php?id=1'">
            <strong>Parking Lot A</strong><br>
            <small>Date: 2025-10-18 | Time: 09:00 | Cost: €5 | Status: Confirmed</small>
          </li>
          <li onclick="window.location.href='reservation_details.php?id=2'">
            <strong>Parking Garage B</strong><br>
            <small>Date: 2025-10-22 | Time: 11:00 | Cost: €7 | Status: Pending</small>
          </li>
          <li onclick="window.location.href='reservation_details.php?id=3'">
            <strong>Open Lot C</strong><br>
            <small>Date: 2025-10-29 | Time: 15:30 | Cost: €4 | Status: Cancelled</small>
          </li>
          <li onclick="window.location.href='reservation_details.php?id=4'">
            <strong>Central Parking</strong><br>
            <small>Date: 2025-11-03 | Time: 08:30 | Cost: €6 | Status: Confirmed</small>
          </li>
          <li onclick="window.location.href='reservation_details.php?id=5'">
            <strong>Underground Lot D</strong><br>
            <small>Date: 2025-11-12 | Time: 17:00 | Cost: €5 | Status: Pending</small>
          </li>
        </ul>
      </div>
    </div>

  </main>
    <?php
        $footer = file_get_contents(FOOTER);
        echo $footer;
    ?>
</body>
</html>
