<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EzParking</title>
    <link rel="stylesheet" href="../css/homepage.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@400;700&display=swap" rel="stylesheet">
    <link rel="website icon" type="png" href="/Img/lego-icon-12.ico">
</head>
<body>
    <?php 
        include './functions.php';
        $nav = generate_navbar('guest');
        echo $nav;
    ?>

    <section class="hero">
    <h1>Find and book your parking spot in just seconds</h1>
        <p>With EzParking, saying goodbye to parking stress is easy. Discover, book, and park the smart way.</p>
        <button onclick="location.href = '../php/register.php';">Register and Book Now!</button>
    </section>

    <section class="features">
    <h2>How It Works</h2>
    <div class="cards">
        <div class="card">
        <i class="fas fa-map-marker-alt"></i>
        <h3>1. Search</h3>
        <p>Find available parking spots near your destination in real time.</p>
        </div>
        <div class="card">
        <i class="fas fa-calendar-check"></i>
        <h3>2. Book</h3>
        <p>Select your preferred date and time to reserve your spot in advance.</p>
        </div>
        <div class="card">
        <i class="fas fa-car"></i>
        <h3>3. Park</h3>
        <p>Navigate to your reserved spot and park hassle-free with EzParking.</p>
        </div>
    </div>
    </section>


    <?php
        require_once './config.php';
        $footer = file_get_contents(FOOTER);
        echo $footer;
    ?>
</body>
</html>