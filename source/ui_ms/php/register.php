<?php
    require_once './functions.php';
    require_once './config.php';

    // Verifying the session
    if ( verify_session() )
        header("Location: " . $homepage);
    
    // Informative variables
    $ok_message = $error_message = null;

    $name = '';
    $surname = '';
    $phone = '';
    $email = '';
    $password = '';
    $card = '';

    // Triggering the registration only after a request for it
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        // Reading the fields
        $name = trim($_POST['name'] ?? '');
        $surname = trim($_POST['lastname'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $card = trim($_POST['card'] ?? '');

        // Preparing the data for the microservice
        $payload = [
            'name' => $name,
            'surname' => $surname,
            'phone' => $phone,
            'email' => $email,
            'password' => $password,
            'cc_number' => $card
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
                    'surname' => $surname,
                ];
                $api_url = compose_url($protocol, $socket_park_ms, '/users');
                $response_park = perform_rest_request('POST', $api_url, $payload);

                // 2. Notification_ms
                $payload = [
                    'id' => $last_id,
                    'name' => $name,
                    'surname' => $surname,
                    'email' => $email, 
                    'phone' => $phone
                ];
                $api_url = compose_url($protocol, $socket_notification_ms, '/notifications/users');
                $response_notification = perform_rest_request('POST', $api_url, $payload);

                // 3. Payment_ms
                $payload = [
                    'id' => $last_id,
                    'name' => $name,
                    'surname' => $surname
                ];
                $api_url = compose_url($protocol, $socket_payment_ms, '/payments/users');
                $response_payment = perform_rest_request('POST', $api_url, $payload);

                // Send a notification email
                if ( $response_notification["body"]["code"] === "0" && $response_payment["body"]["code"] === "0" 
                                && $response_park["body"]["code"] === "0" ) 
                {
                    $payload = [
                        'user_id' => $last_id,
                    ];
                    $api_url = compose_url($protocol, $socket_notification_ms, '/notifications/registration_successfull');
                    $response_email = perform_rest_request('POST', $api_url, $payload, null);
                }
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
<body style="background: linear-gradient(to right, #5e25a5, rgba(113,89,182));">
    <?php 
        $nav = generate_navbar('guest');
        echo $nav;
    ?>
    <div class="login-container" style="margin-bottom: 2rem; margin-top:2rem">
        <h2>Sign Up</h2>
        <form id="register-form" action="register.php" method="post">
            <div class="input-group">
                <label for="name">Name</label>
                <input type="text" id="name" class="login-input" name="name" value="<?php if (isset($error_message) > 0) echo $name;?>" required>
            </div>
            <div class="input-group">
                <label for="lastname">Last Name</label>
                <input type="text" id="lastname" class="login-input" name="lastname" value="<?php if (isset($error_message) > 0) echo $surname;?>" required>
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" class="login-input" name="email" placeholder="example@youremail.it" value="<?php if (isset($error_message) > 0) echo $email;?>" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" class="login-input" name="password" required>
            </div>
            <div class="input-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" class="login-input" name="phone" value="<?php if (isset($error_message) > 0) echo $phone;?>" required>
            </div>
            <div class="input-group">
                <label for="card">Credit Card Number</label>
                <input type="text" id="card" class="login-input" maxlength=16 name="card" value="<?php if (isset($error_message) > 0) echo $card;?>" required>
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