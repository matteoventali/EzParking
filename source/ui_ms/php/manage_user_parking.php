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
    <?php include './functions.php';
      $nav = generate_navbar('admin');
      echo $nav;
     ?>

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

  <?php
    require_once './config.php';
    $footer = file_get_contents(FOOTER);
    echo $footer;
  ?>

<script src="../js/users_table.js" crossorigin="anonymous"></script>
</body>
</html>
