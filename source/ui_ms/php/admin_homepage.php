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
        include './functions.php';
        $nav = generate_navbar('admin');
        echo $nav;
    ?>

    <section class="hero">
        <h1>Welcome Admin Pierluca</h1>
        <p>Use the buttons below to access to your dashboard, search, book, or manage your reservations. Find and reserve your parking space in seconds.</p>

        
        <div class="actions" style="display:flex; gap:0.8rem; flex-wrap:wrap; justify-content:center;">
            <button onclick="location.href='../php/manage_user.php';">
                <i class="fas fa-map-marker-alt" style="margin-right:8px;"></i>
                Manage Users
            </button>

        
            <button onclick="location.href='../php/admin_dashboard.php';">
                <i class="fas fa-history" style="margin-right:8px;"></i>
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
        require_once './config.php';
        $footer = file_get_contents(FOOTER);
        echo $footer;
    ?>
</body>


</html>
