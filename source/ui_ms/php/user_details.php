<?php
    require_once "./config.php";
    require_once "./functions.php";

    // We must be logged in to access this page
    if ( !verify_session() )
        header("Location: " . $starting_page);
    else if ( $_SESSION['role'] != 'admin') // We must be admin to access this page
        header("Location: user_dashboard.php");
    else if ( !isset($_GET['id']) || !is_numeric($_GET['id']) ) // We must have access to the user id to perform the loading of data
        header("Location: manage_user.php" );

    // Loading the user data trough a REST request
    $api_url = compose_url($protocol, $socket_account_ms, '/users/' . $_GET['id']);
    $response = perform_rest_request('GET', $api_url, null, $_SESSION['session_token']);
    $user = $response['status'] == 200 ? $response['body']['user'] : null;

    if ( $user == null )
        // Return to the manage user page
        header("Location: manage_user.php");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <link rel="stylesheet" href="../css/homepage.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/style.css">
    
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@400;700&display=swap" rel="stylesheet">
    
    <script type="text/javascript">
        function enable_disable_user()
        {
            alert("To be implemented");
        }
    </script>
</head>

<body>
    <?php 
        $nav = generate_navbar($_SESSION['role']);
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
                <h2 class="user-name"><?php echo $user["name"] . " " . $user["surname"]; ?></h2>
            </div>
          
        </div>
      </div>

      <div class="user-info">
        <div class="info-item">
          <i class="fas fa-phone"></i>
          <span><strong>Name: </strong><?php echo $user["name"];?></span>
        </div>
        <div class="info-item">
          <i class="fas fa-phone"></i>
          <span><strong>Surname: </strong><?php echo $user["surname"];?></span>
        </div>
        <div class="info-item">
          <i class="fas fa-phone"></i>
          <span><strong>Email: </strong><?php echo $user["email"];?></span>
        </div>
        <div class="info-item">
          <i class="fas fa-phone"></i>
          <span><strong>Phone: </strong><?php echo $user["phone"];?></span>
        </div>
        <div class="info-item">
          <i class="fas"></i>
          <span><strong>Role: </strong><?php echo strtoupper($user['role']); ?></span>
        </div>
      </div>

        <?php
            $label = $user['status'] ? 'Disable' : 'Enable';

            // We show the enable/disable button only if we're seeing a user profile.
            // An admin cannot be disabled/enabled by another admin
            $button = '<button id="button_enable" class="edit-btn" onclick="enable_disable_user();">
                            <i class="fas fa-user-edit"></i> ' . $label . ' Profile
                        </button>';
            if ( $user['role'] == 'user' )
                echo $button;
        ?>
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
      $footer = file_get_contents(FOOTER);
      echo $footer;
    ?>
</body>
</html>
