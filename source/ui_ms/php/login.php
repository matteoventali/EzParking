<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        <h2>Login into your account</h2>
        <form id="login-form">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="text" id="email" class="login-input"  name="email" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" class="login-input" name="password" required>
            </div>
            <button type="submit" class="login-button" >Login</button>
            <p class="error-message" id="error-message"></p>
        </form>
        <p>You don't have an account? <a href="register.html">Register</a></p>
    </div>
    <script src="../js/script.js"></script>
    <script src="../js/dropdown.js" crossorigin="anonymous"></script>
    <?php
        $footer = file_get_contents(FOOTER);
        echo $footer;
    ?>
</body>
</html>