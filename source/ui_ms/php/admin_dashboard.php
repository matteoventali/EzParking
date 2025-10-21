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
      include './functions.php';
      $nav = generate_navbar('admin');
      echo $nav;
     ?>

  <main class="dashboard-grid">

    <!-- Admin Info Section -->
    <div class="dashboard-card user-data-card">
      <div class="user-header">
        <img src="../images/account.svg" alt="Admin Avatar" class="user-avatar">
        <div>
          <h2 class="user-name">Federico De Lullo</h2>
          <p class="user-email">admin@ezparking.com</p>
          <p class="user-role"><strong>Role:</strong> Admin</p>
        </div>
      </div>

      <div class="user-info">
        <div class="info-item">
          <i class="fas fa-user"></i>
          <span><strong>Name:</strong> Federico</span>
        </div>
        <div class="info-item">
          <i class="fas fa-user"></i>
          <span><strong>Surname:</strong> De Lullo</span>
        </div>
        <div class="info-item">
          <i class="fas fa-envelope"></i>
          <span><strong>Email:</strong> admin@ezparking.com</span>
        </div>
        <div class="info-item">
          <i class="fas fa-phone"></i>
          <span><strong>Phone:</strong> +39 333 1234567</span>
        </div>

        <div class="info-item">
          <i class="fas fa-id-badge"></i>
          <span class="role"><strong>Role:</strong> Admin</span>
        </div>
      </div>

      <button class="edit-btn" onclick="window.location.href='edit_profile.php'">
        <i class="fas fa-user-edit"></i> Edit Profile
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
