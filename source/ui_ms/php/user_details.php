<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Dashboard</title>
  <link rel="stylesheet" href="../css/homepage.css">
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="../css/style.css">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

  <link rel="stylesheet" href="../css/navbar.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./fontawesome-free-6.4.0-web/css/all.css">
  <link rel="website icon" type="png" href="/Img/lego-icon-12.ico">
</head>

<body>
    <?php include './functions.php';
      $nav = generate_navbar('user');
      echo $nav;
     ?>

  <main class="dashboard-grid">

    <div class="dashboard-card user-data-card">
      <div class="user-header">
        <div class="avatar-wrapper">
            <img src="../images/account.svg" alt="User Avatar" class="user-avatar">
            
        </div>
        <div>
            <div class="user-name-wrapper">
                <h2 class="user-name">Federico De Lullo</h2>
                <img src="../images/active.svg" alt="Active Status" class="status-icon-inline">
            </div>
          
        </div>
      </div>

      <div class="user-info">
        <div class="info-item">
          <i class="fas fa-phone"></i>
          <span><strong>Name: </strong>Federico</span>
        </div>
        <div class="info-item">
          <i class="fas fa-phone"></i>
          <span><strong>Surname: </strong>De Lullo</span>
        </div>
        <div class="info-item">
          <i class="fas fa-phone"></i>
          <span><strong>Email: </strong>delullo.1935510@studenti.uniroma1.it</span>
        </div>
        <div class="info-item">
          <i class="fas fa-phone"></i>
          <span><strong>Phone: </strong> +39 333 1234567</span>
        </div>
        <div class="info-item">
          <i class="fas"></i>
          <span><strong>Role: </strong> Resident</span>
        </div>
      </div>

      <button class="edit-btn" onclick="window.location.href='active_deactice_user.php'">
        <i class="fas fa-user-edit"></i> Activate/Deactivate Profile
      </button>
    </div>

    <div class="dashboard-card reputation-card">
      <div class="section-title">Reputation</div>
      <div class="reputation-score">⭐ 4.2/5</div>
      <p style="margin-top: 0.5rem; color:#666; font-size:0.95rem;">
        Keep contributing to improve your score!
      </p>
    </div>


    

  </main>
    <?php
      require_once './config.php';
      $footer = file_get_contents(FOOTER);
      echo $footer;
    ?>

</body>
</html>
