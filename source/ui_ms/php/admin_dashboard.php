<?php
    require_once "./config.php";
    require_once "./functions.php";

    // We must be logged in to access this page
    if ( !verify_session() )
        header("Location: " . $starting_page);
    else if ( $_SESSION['role'] != 'admin') // We must be admin to access this page
        header("Location: user_dashboard.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/table.css">
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="../css/navbar.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./fontawesome-free-6.4.0-web/css/all.css">
  <link rel="website icon" type="png" href="/Img/lego-icon-12.ico">
</head>

<body>
    <?php 
        $nav = generate_navbar($_SESSION['role']);
        echo $nav;
    ?>

  <main class="dashboard-grid">

    <!-- Admin Info Section -->
    <div class="dashboard-card user-data-card">
      <div class="user-header">
        <img src="../images/account.svg" alt="Admin Avatar" class="user-avatar">
        <div>
          <div>
            <h2 class="user-name"><?php echo strtoupper($_SESSION['user']['name'] . ' ' . $_SESSION['user']['surname']); ?></h2>
          </div>
        </div>
      </div>

      <div class="user-info">
        <div class="info-item">
          <i class="fas fa-user"></i>
          <span><strong>Name:</strong><?php echo $_SESSION['user']['name']; ?></span>
        </div>
        <div class="info-item">
          <i class="fas fa-user"></i>
          <span><strong>Surname:</strong><?php echo $_SESSION['user']['surname']; ?></span>
        </div>
        <div class="info-item">
          <i class="fas fa-envelope"></i>
          <span><strong>Email:</strong><?php echo $_SESSION['user']['email']; ?></span>
        </div>
        <div class="info-item">
          <i class="fas fa-phone"></i>
          <span><strong>Phone:</strong><?php echo $_SESSION['user']['phone']; ?></span>
        </div>

        <div class="info-item">
          <!--<i class="fas fa-id-badge"></i>-->
          <i class="fas "></i>
          <span><strong>Role:</strong><?php echo strtoupper($_SESSION['role']); ?></span>
        </div>
      </div>

      <button class="edit-btn" onclick="window.location.href='edit_profile.php'">
        <i class="fas fa-user-edit"></i> Edit Profile
      </button>


      <button class="edit-btn" onclick="window.location.href='manage_user.php'">
        <i class="fas fa-user-edit"></i> Manage Users
      </button>
    </div>

  </main>

  <?php
    require_once './config.php';
    $footer = file_get_contents(FOOTER);
    echo $footer;
  ?>

<script src="../js/users_table.js" crossorigin="anonymous"></script>
</body>
</html>
