<?php
    require_once './functions.php';
    require_once './config.php';

    // Verifying the session
    if ( verify_session() )
        header("Location: " . $homepage);
    
    // Informative variables
    $ok_message = $error_message = null;
    
    // Triggering the login only after a request for it
    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
        // Reading the fields
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // Preparing the data for the microservice
        $payload = [
            'email' => $email,
            'password' => $password
        ];

        try 
        {
            // Execute the request
            $api_url = compose_url($protocol, $socket_account_ms, '/auth/login');
            $response = perform_rest_request('POST', $api_url, $payload);

            // Success in the login
            if ($response["body"]["code"] === "0") 
            {
                $ok_message = $response["body"]["desc"];
                
                // Starting the session and saving the token received
                session_start();
                $_SESSION['session_token'] = $response["body"]["user"]["session_token"];
                $_SESSION['role'] = $response["body"]["user"]["role"];
                $_SESSION['user'] = $response["body"]["user"];
                $latitude   = $_POST["latitude"];
                $longitude  = $_POST["longitude"];

                // Updating last login e timestamp in the notification microservices
                // only if it is logged an user
                if ( $_SESSION['role'] == 'user' && strlen($latitude) > 0 && strlen($longitude) > 0 )
                {
                    // Updating the position
                    $payload = [
                        'lat' => $latitude,
                        'lon' => $longitude
                    ];
                    $api_url = compose_url($protocol, $socket_notification_ms, '/user/' . $_SESSION["user"]["id"]);
                    $response = perform_rest_request('PUT', $api_url, $payload, null);
                }

                // Redirecting the user into the homepage
                header("Location: " . $homepage);
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
    <title>Login</title>
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
    
    <div class="login-container">
        <h2 style="margin-bottom: 1rem;">Login into your account</h2>
        <form id="login-form" method="post" action="login.php">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="text" id="email" class="login-input"  name="email" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" class="login-input" name="password" required>
            </div>
            
            <input type="hidden" id="latitude"  name="latitude" required>
            <input type="hidden" id="longitude" name="longitude" required>
            
            <button type="submit" class="login-button" >Login</button>
            <p class="error-message" id="error-message">
                <?php if(isset($error_message)) echo $error_message; else echo '';  ?>
            </p>
            <p class="error-message" style="color:green" id="ok-message">
                <?php if(isset($ok_message)) echo $ok_message; else echo ''; ?>
            </p>
        </form>
        <p>You don't have an account? <a href="register.php">Register now!</a></p>
    </div>

    <?php
        $footer = file_get_contents(FOOTER);
        echo $footer;
    ?>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        // Filling the hidden fields with lat and long
                        document.getElementById("latitude").value = position.coords.latitude;
                        document.getElementById("longitude").value = position.coords.longitude;
                    },
                    function (error) {
                        // Show an alert to the user
                        alert("Unable to retrieve your location. Proceeding without location data. You won't receive location-based notifications.");
                    }
                );
            } else {
                alert("Unable to retrieve your location. Proceeding without location data. You won't receive location-based notifications.");
            }
        });
    </script>
</body>
</html>