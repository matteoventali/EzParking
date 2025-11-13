<?php
    require_once './config.php';
    require_once './functions.php';
    
    // Checking the session status
    if ( !verify_session() )
    {
        // Redirecting the user to the start page
        header("Location: " . $starting_page);
        exit();
    }

    // Informative variables
    $ok_message = $error_message = null;
    
    // Dispatching if we have to launch the edit profile request
    if ( isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['phone']) && isset($_POST['password']) )
    {
        // Reading the fields
        $name = trim($_POST['name'] ?? '');
        $surname = trim($_POST['surname'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // Preparing the data for the microservice
        $payload = [
            'name' => $name,
            'surname' => $surname,
            'phone' => $phone,
            'password' => $password
        ];

        try 
        {
            // Execute the request
            $api_url = compose_url($protocol, $socket_account_ms, '/pdata');
            $response = perform_rest_request('PUT', $api_url, $payload, $_SESSION['session_token']);

            var_dump($response);

            // Success in the edit profile
            if ($response["body"]["code"] === "0") 
            {
                $ok_message = $response["body"]["desc"];
            }
            else 
                $error_message = $response["body"]["desc"];
        } catch (Exception $e) 
        {
            $error_message = "Error contacting API: " . $e->getMessage();
        }
    }

    // We have to load the user data
    $url = compose_url($protocol, $socket_account_ms, '/pdata');
    $new_data = perform_rest_request('GET', $url, null, $_SESSION['session_token']);
    $_SESSION['user'] = array_merge($_SESSION['user'], $new_data['body']['user']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../css/edit_profile.css">
    <link rel="stylesheet" href="../css/style.css">

    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@400;700&display=swap" rel="stylesheet">
    <script src="../js/form_check.js" defer></script>
</head>

<body>
    <?php 
        $nav = generate_navbar($_SESSION["role"]);
        echo $nav;
    ?>

  <main class="dashboard-grid">

    <div class="dashboard-card edit-profile-card">
      <div class="section-title">Edit Your Profile</div>

      <form id="edit-form" class="edit-user-form" action="edit_profile.php" method="post">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?php echo $_SESSION['user']['name'];?>" required>

        <label for="surname">Surname</label>
        <input type="text" id="surname" name="surname" value="<?php echo $_SESSION['user']['surname'];?>" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" disabled value="<?php echo $_SESSION['user']['email'];?>" required>

        <label for="phone">Phone</label>
        <input type="tel" id="phone" name="phone" value="<?php echo $_SESSION['user']['phone'];?>" required>

        <label for="card">Credit Card Number</label>
        <input type="text" id="card" name="card" value="" required>    
        
        <label for="password">Insert new Password</label>
        <input type="password" id="new" name="password" value="" required>

        <label for="password">Confirm new Password</label>
        <input type="password" id="confirm" name="password" value="" required>   

        <button id="edit-button" type="submit" class="save-btn" >
          <i class="fas fa-check"></i> Save Changes
        </button>

        <p class="error-message" style="text-align:center;" id="error-message">
            <?php if(isset($error_message)) echo $error_message; else echo '';  ?>
        </p>
        <p class="error-message" style="color:green; text-align:center;" id="ok-message">
            <?php if(isset($ok_message)) echo $ok_message; else echo ''; ?>
        </p>
      </form>
    </div>

  </main>

    <?php
        $footer = file_get_contents(FOOTER);
        echo $footer;
    ?>
</body>
</html>
