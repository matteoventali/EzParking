<?php
    require_once "./config.php";
    require_once "./functions.php";

    // We must be logged in to access this page
    if (!verify_session())
        header("Location: " . $starting_page);
    else if ($_SESSION['role'] != 'user') // Redirect the user to the correct homepage
        header("Location: " . $homepage);

    // Gets the available labels to populate the search select
    $labels = get_labels_content();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Available Parking Spots — Search & Filters</title>
    <link rel="stylesheet" href="../css/navbar.css" />
    <link rel="stylesheet" href="../css/search_parking.css" />
    <link rel="stylesheet" href="../css/style.css" />
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@100..900&display=swap" rel="stylesheet">    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">    
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>    

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script type="text/javascript">
        function set_hidden_fields() 
        {
            // Gets the coordinates of the user from the sessionStorage
            const saved_lat = sessionStorage.getItem("user_latitude");
            const saved_lon = sessionStorage.getItem("user_longitude");

            // Setting the hidden fields of the form
            document.getElementById('latitude_hidden').value = saved_lat;
            document.getElementById('longitude_hidden').value = saved_lon;
        }
    </script>
</head>

<body onload="set_hidden_fields();">
    <?php
        $nav = generate_navbar($_SESSION['role']);
        echo $nav;
    ?>

    <section class="search-section" id="searchSection" aria-label="Search parking">
        <form id="searchForm" class="search-wrapper" role="search" action="search_parking.php" method="POST">
            <div class="search-input">
                <input id="searchText" name="query" type="text" placeholder="Find a parking spot... (Address, City)">
            </div>

            <div class="controls">
                <div class="dropdown" id="filtersDropdown">
                    <div class="dropdown-toggle">Filters<i class="fa-solid fa-angle-down"></i></div>

                    <div class="dropdown-panel">
                        <div class="filters">
                            <?php echo $labels; ?>                            
                        </div>
                    </div>
                </div>

                <div class="label-pills" id="activeFilters"></div>
                    
                <!-- Distance select -->
                <select class="dropdown-toggle" id="distanceSelect" name="distance" style="min-width:120px;">
                    <option value="">Distance</option>
                    <option value="500">500 m</option>
                    <option value="1000">1 km</option>
                    <option value="5000">5 km</option>
                    <option value="10000">10 km</option>
                </select>

                <!-- Hidden fields -->
                <input name="latitude" type="hidden" id="latitude_hidden">
                <input name="longitude" type="hidden" id="longitude_hidden">

                <button class="search-btn" id="searchBtn" type="submit">Search</button>
            </div>
        </form>
    </section>

    <div class="loader-section" style="display: none;">
        <?php
            $loader = file_get_contents(LOADER);
            echo $loader;
        ?>
    </div>

    <main class="parking-container" id="parkingList" style="display: none;" aria-live="polite">
    </main>

    <?php
        $footer = file_get_contents(FOOTER);
        echo $footer;
    ?>

    <script src="../js/search_parking.js"></script>

</body>

</html>