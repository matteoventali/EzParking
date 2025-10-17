<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/table.css">
  <link rel="stylesheet" href="../css/dashboard.css">

</head>

<body>
    <?php
      require_once './config.php';
      $navbar = file_get_contents(NAVBAR);
      echo $navbar;
    ?>

  <!-- === MAIN CONTENT === -->
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
          <i class="fas fa-lock"></i>
          <span><strong>Password:</strong> ********</span>
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

    <!-- Registered Users Section -->
    <div class="container">
      <h1>Manage Users</h1>

      <div class="search-container">
        <input type="text" id="userSearchInput" placeholder="Cerca utente...">
      </div>

      <table id="userTable">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Surname</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Action</th>

          </tr>
        </thead>
        <tbody id="userTableBody"></tbody>
      </table>

        <div class="pagination">
          <button id="userPrevBtn">←</button>
          <span id="userPageInfo"></span>
          <button id="userNextBtn">→</button>
        </div>
    </div>

    <div class="container">
      <h1>List of Parking Spots</h1>

      <div class="search-container">
        <input type="text" id="parkingSearchInput" placeholder="Cerca parcheggio...">
      </div>

        <table id="parkingTable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Location</th>
              <th>Capacity</th>
              <th>Reputation Threshold</th>
              <th>Slot Price (€)</th>
              <th>User ID</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="parkingTableBody">
        
          </tbody>
        </table>

        <div class="pagination">
          <button id="parkingPrevBtn">←</button>
          <span id="parkingPageInfo"></span>
          <button id="parkingNextBtn">→</button>
        </div>
    </div>

  </main>

  <!-- === FOOTER === -->
  <?php
    $footer = file_get_contents(FOOTER);
    echo $footer;
  ?>

<script src="../js/dropdown.js" crossorigin="anonymous"></script>
<script src="../js/users_table.js" crossorigin="anonymous"></script>
<script src="../js/parking_table.js"></script>
</body>
</html>
