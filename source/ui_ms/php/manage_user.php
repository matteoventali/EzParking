<?php
    require_once "./config.php";
    require_once "./functions.php";

    // We must be logged in to access this page
    if ( !verify_session() )
        header("Location: " . $starting_page);
    else if ( $_SESSION['role'] != 'admin') // We must be admin to access this page
        header("Location: user_dashboard.php"); // We report it to the user dashboard

    // Loading the list of users invoking the microservices
    $api_url = compose_url($protocol, $socket_account_ms, '/users');
    $response = perform_rest_request('GET', $api_url, null, $_SESSION['session_token']);
    $user_list = $response['status'] == 200 ? $response['body']['users'] : array();

    $json_list = json_encode($user_list);
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

    <script>
        // Starting point of the filling data process into the table
        let usersData = <?php echo $json_list; ?>
    </script>
</head>

<body>
    <?php
        $nav = generate_navbar('admin');
        echo $nav;
    ?>

    <main class="dashboard-grid" style="margin-bottom: 3rem;">
    <!-- Registered Users Section -->
    <div class="container">
        <h1>Manage Users</h1>

        <div class="search-container">
        <input type="text" id="userSearchInput" placeholder="Search user ...">
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
        $footer = file_get_contents(FOOTER);
        echo $footer;
    ?>

    <script src="../js/users_table.js" crossorigin="anonymous"></script>
</body>
</html>
