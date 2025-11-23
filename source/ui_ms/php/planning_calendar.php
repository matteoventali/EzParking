<?php 
    require_once "./config.php";
    require_once "./functions.php";

    // We must be logged in to access this page
    if (!verify_session())
        header("Location: " . $starting_page);
    else if ($_SESSION['role'] != 'user') // Redirect the user to the correct homepage
        header("Location: " . $homepage);


    
?>

<!DOCTYPE html>
<html lang='en'>
  <head>
    <meta charset='utf-8' />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>View Calendar</title>
    <link rel="stylesheet" href="../css/navbar.css" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/planning_calendar.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js'></script>
    <script src="../js/planning_calendar.js"></script>
     
    <script>
    </script>
  </head>
  <body style="background: white;">
    <?php
        $nav = generate_navbar($_SESSION["role"]);
        echo $nav;
    ?>
    <div class="calendar-container">
        <div id="calendar" style="height: 100%;"></div>
    </div>
    <?php
        $footer = file_get_contents(FOOTER);
        echo $footer;
    ?>
  </body>
</html>