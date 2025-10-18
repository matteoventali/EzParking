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
    <!-- Registered Users Section -->
    <div class="container">
      <h1>Manage Users</h1>

      <div class="search-container">
        <input type="text" id="userSearchInput" placeholder="Cerca utente...">
      </div>

      <table id="userTable">
        <thead>
          <tr>
            <th>Email</th>
            <th>Status</th>
    
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
