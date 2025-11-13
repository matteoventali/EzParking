<?php
    require_once './functions.php';
    require_once './config.php';

    // Verifying the session
    if ( verify_session() )
        header("Location: " . $homepage);
    
    // Informative variables
    $ok_message = $error_message = null;

    // Triggering the registration only after a request for it
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        // Reading the fields
        $name = trim($_POST['name'] ?? '');
        $surname = trim($_POST['lastname'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // Preparing the data for the microservice
        $payload = [
            'name' => $name,
            'surname' => $surname,
            'phone' => $phone,
            'email' => $email,
            'password' => $password
        ];

        try
        {
            // Execute the request
            $api_url = compose_url($protocol, $socket_account_ms, '/auth/signup');
            $response = perform_rest_request('POST', $api_url, $payload);

            // Success in the registration
            if ($response['status'] === 201) 
            {   
                $ok_message = $response["body"]["desc"];

                // Getting the last id
                $last_id = $response["body"]["user"]["id"];

                // Synchronizing all the microservices
                // 1. Park_ms
                $payload = [
                    'id' => $last_id,
                    'name' => $name,
                    'surname' => $surname
                ];
                $api_url = compose_url($protocol, $socket_park_ms, '/users');
                $response = perform_rest_request('POST', $api_url, $payload);

                // 2. Notification_ms

                // 3. Payment_ms
            }
            else 
                $error_message = $response["body"]["desc"];
        } catch (Exception $e) 
        {
            $error_message = "Error contacting API: " . $e->getMessage();
        }
    }
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="../css/style.css">
    
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@400;700&display=swap" rel="stylesheet">
    <script src="../js/form_check.js" defer></script>
</head>
<body>
    <?php 
        $nav = generate_navbar('guest');
        echo $nav;
     ?>
    <div class="login-container">
        <h2>Sign Up</h2>
        <form id="register-form" action="register.php" method="post">
            <div class="input-group">
                <label for="name">Name</label>
                <input type="text" id="name" class="login-input" name="name" required>
            </div>
            <div class="input-group">
                <label for="lastname">Last Name</label>
                <input type="text" id="lastname" class="login-input" name="lastname" required>
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" class="login-input" name="email" placeholder="example@youremail.it" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" class="login-input" name="password" required>
            </div>
            <div class="input-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" class="login-input" name="phone" required>
            </div>
            <div class="input-group">
                <label for="card">Credit Card Number</label>
                <input type="text" id="card" class="login-input" name="card" required>
            </div>                     
            <button type="submit" class="login-button">Register</button>
            <p class="error-message" id="error-message">
                <?php if(isset($error_message)) echo $error_message; else echo '';  ?>
            </p>
            <p class="error-message" style="color:green" id="ok-message">
                <?php if(isset($ok_message)) echo $ok_message; else echo ''; ?>
            </p>
        </form>
    </div>    
    <?php
        $footer = file_get_contents(FOOTER);
        echo $footer;
    ?>    
</body>
</html>