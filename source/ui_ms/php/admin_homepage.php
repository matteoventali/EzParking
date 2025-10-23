<?php
    require_once "./config.php";
    require_once "./functions.php";

    // We must be logged in to access this page
    if ( !verify_session() )
        header("Location: " . $starting_page);
    else if ( $_SESSION['role'] != 'admin' ) // Redirect the user to the correct homepage
        header("Location: " . $homepage);

    // Get access to the name of the user
    $name = $_SESSION['user']['name'];
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>EzParking - Dashboard</title>

    <link rel="stylesheet" href="../css/homepage.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/navbar.css">

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./fontawesome-free-6.4.0-web/css/all.css">
    <link rel="website icon" type="png" href="/Img/lego-icon-12.ico">

</head>
<body>
    <?php
        $nav = generate_navbar($_SESSION['role']);
        echo $nav;
    ?>

    <section class="hero">
        <h1>Welcome <?php echo $name; ?></h1>
        <p>Use the buttons below to access to your dashboard or manage accounts.</p>

        <div class="actions" style="display:flex; gap:0.8rem; flex-wrap:wrap; justify-content:center;">
            <button onclick="location.href='../php/manage_user.php';">
                <i class="fas fa-map-marker-alt"></i>
                Manage Users
            </button>


            <button onclick="location.href='../php/admin_dashboard.php';">
                <i class="fas fa-history"></i>
                Account
            </button>
        </div>
    </section>


    <section class="features">
        <!-- ADD MAP WITH API  -->
        <h2>Find Nearest Parking Spots</h2>
        Aggiungi una mappa interattiva qui con le posizioni dei parcheggi più vicini all'utente.
    </section>


    <?php
        $footer = file_get_contents(FOOTER);
        echo $footer;
    ?>
</body>


</html>
