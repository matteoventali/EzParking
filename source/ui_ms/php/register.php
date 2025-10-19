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
    <link rel="stylesheet" href="./fontawesome-free-6.4.0-web/css/all.css">
    <link rel="website icon" type="png" href="/Img/lego-icon-12.ico">
</head>
<body>
    <?php include './functions.php';
      $nav = generate_navbar('');
      echo $nav;
     ?>
    <div class="login-container">
        <h2>Sign Up</h2>
        <form id="login-form">
            <div class="input-group">
                <label for="name">Name</label>
                <input type="text" id="name" class="login-input" name="name" required>
            </div>
            <div class="input-group">
                <label for="lastname">Last Name</label>
                <input type="text" id="lastname" class="login-input"name="lastname" required>
            </div>
            <div class="input-group">
                <label for="date">Date of birth</label>
                <input type="date" id="date" class="login-input" name="date" required>
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" class="login-input" name="email" placeholder="example@youremail.it" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" class="login-input" name="password" required>
            </div>
            <button type="submit" class="login-button">Register</button>
            <p class="error-message" id="error-message"></p>
        </form>
    </div>
    <script src="../js/script.js"></script>
    <?php
    require_once './config.php';
      $footer = file_get_contents(FOOTER);
      echo $footer;
    ?>
</body>
</html>