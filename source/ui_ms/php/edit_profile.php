<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profile</title>
  <link rel="stylesheet" href="../css/edit_profile.css">
  <link rel="stylesheet" href="../css/style.css">
</head>

<body>
  <?php
    require_once './config.php';
    $navbar = file_get_contents(NAVBAR);
    echo $navbar;
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
    <script src="../js/dropdown.js" crossorigin="anonymous"></script>
  <?php
    $footer = file_get_contents(FOOTER);
    echo $footer;
  ?>
  

</body>
</html>
