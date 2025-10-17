<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="../css/style.css">
<!--<link rel="stylesheet" href="../css/navbar.css">-->
</head>
<body>
  <?php
  require_once './config.php';
    $navbar = file_get_contents(NAVBAR);
    echo $navbar;
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
    <script src="../js/dropdown.js" crossorigin="anonymous"></script>
    <?php
      $footer = file_get_contents(FOOTER);
      echo $footer;
    ?>
</body>
</html>