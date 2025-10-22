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

    // Here we are logged
    
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
  <link rel="stylesheet" href="./fontawesome-free-6.4.0-web/css/all.css">
  <link rel="website icon" type="png" href="/Img/lego-icon-12.ico">  
</head>

<body>
    <?php 
        $nav = generate_navbar($_SESSION["role"]);
        echo $nav;
    ?>

  <main class="dashboard-grid">

    <div class="dashboard-card edit-profile-card">
      <div class="section-title">Edit Your Profile</div>

      <form class="edit-user-form" action="save_profile.php" method="post">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="Federico">

        <label for="surname">Surname</label>
        <input type="text" id="surname" name="surname" value="De Lullo">

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="delullo.1935510@studenti.uniroma1.it">

        <label for="phone">Phone</label>
        <input type="tel" id="phone" name="phone" value="+39 333 1234567">

        <label for="password">Insert old Password</label>
        <input type="password" id="password" name="password" value="">

        <label for="password">Insert new Password</label>
        <input type="password" id="password" name="password" value="">

        <label for="password">Confirm new Password</label>
        <input type="password" id="password" name="password" value="">

        <button type="submit" class="save-btn">
          <i class="fas fa-check"></i> Save Changes
        </button>
      </form>
    </div>

  </main>

    <?php
        $footer = file_get_contents(FOOTER);
        echo $footer;
    ?>
</body>
</html>
