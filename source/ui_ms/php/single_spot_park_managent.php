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
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Parking Management</title>
    <link rel="stylesheet" href="../css/navbar.css" />
    <link rel="stylesheet" href="../css/manage_single_card.css" />
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
   <?php
        $nav = generate_navbar($_SESSION['role']);
        echo $nav;
    ?>

    <main class="container">
        <section class="parking-info">
            <h1 class="parking-name">Downtown Parking</h1>
            <div class="details">
                <p><strong>Address:</strong> 123 Main Street, Cityville</p>
                <p><strong>Slot Price:</strong> $3.50/hour</p>
                <p><strong>Reputation Threshold:</strong> 4.5 / 5</p>
            </div>
        </section>

        <section class="timeslot-section">
           

            <form id="add-timeslot-form" class="add-form">
                <h3>Add New Time Slot</h3>
                <div class="form-group">
                    <label for="start">Start Time</label>
                    <input type="time" id="start" required />
                </div>
                <div class="form-group">
                    <label for="end">End Time</label>
                    <input type="time" id="end" required />
                </div>
                <button type="submit" class="btn-primary">Add Slot</button>
            </form>
        </section>
    </main>
  
    <?php
        $footer = file_get_contents(FOOTER);
        echo $footer;
    ?>

</body>



</html>