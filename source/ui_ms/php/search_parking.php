<?php
    require_once "./config.php";
    require_once "./functions.php";

    // We must be logged in to access this page
    if (!verify_session())
        header("Location: " . $starting_page);
    else if ($_SESSION['role'] != 'user') // Redirect the user to the correct homepage
        header("Location: " . $homepage);

    if ( count($_POST) ) // If there is at least one parameter we must perform the search
    {
        if ( isset($_POST["query"]) && !empty($_POST["query"]) )
        {
            // Trying to get the coordinates for the address requested
            $resp = get_coordinates_from_address($_POST["query"]);
            $lat = floatval($resp['latitude']);
            $long = floatval($resp['longitude']);
        }
        else
        {
            $lat = floatval($_POST["latitude"]);
            $long = floatval($_POST["longitude"]);
        }
        
        // Setting the parameter for the search
        $payload = [
            "latitude" => $lat,
            "longitude" => $long,
            "user_reputation" => $_SESSION["user"]["score"],
            "radius" => isset($_POST["distance"]) ? intval($_POST["distance"]) : null,
            "labels" => isset($_POST["filters"]) ? $_POST["filters"] : []
        ];

        var_dump($payload);

        // Perform the request for the search
        $api_url = compose_url($protocol, $socket_park_ms, '/search');
        $response = perform_rest_request('POST', $api_url, $payload, null);

        var_dump($response);
    }
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

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

<body style="background: white;" onload="set_hidden_fields();">
    <?php
        $nav = generate_navbar($_SESSION['role']);
        echo $nav;
    ?>


  <!-- Search form: invia q, filters[] e distance via GET -->
  <section class="search-section" id="searchSection" aria-label="Search parking">
    <form id="searchForm" class="search-wrapper" role="search" aria-label="Search parking" action="/search" method="GET" novalidate>
      <div class="search-input" role="search">
        <input id="searchText" name="q" type="text" placeholder="Find a parking spot..." aria-label="Search parking spots">
      </div>

        <div class="controls">
        <!-- Filters dropdown -->
        <div class="dropdown" id="filtersDropdown">
          <button
            class="dropdown-toggle"
            id="dropdownToggle"
            aria-haspopup="true"
            aria-controls="dropdownPanel"
            aria-expanded="false"
            type="button">
            Filters ⌄
            </button>

            <div class="dropdown-panel" id="dropdownPanel" role="menu" aria-label="Filter options" hidden>
            <div class="filters" role="group" aria-label="Parking filters">
                <label class="filter-item">
                <input type="checkbox" name="filters[]" value="low_price">
                <span class="filter-label-text">Low Price</span>
                </label>

                <label class="filter-item">
                <input type="checkbox" name="filters[]" value="high_rating">
                <span class="filter-label-text">High Rating</span>
                </label>
            </div>

            <div style="margin-top:0.6rem">
                <!-- Pills container: aggiornato dinamicamente -->
                <div class="label-pills" id="activePills" aria-live="polite" aria-atomic="true"></div>
            </div>
            </div>
        </div>

        <!-- Distance dropdown (same style as filters) -->
        <div class="dropdown" id="distanceDropdown">
          <button
            class="dropdown-toggle"
            id="distanceToggle"
            aria-haspopup="true"
            aria-controls="distancePanel"
            aria-expanded="false"
            type="button">
            Distance ⌄
            </button>

            <div class="dropdown-panel" id="distancePanel" role="menu" aria-label="Distance options" hidden>
            <div class="filters" role="radiogroup" aria-label="Distance filters">
                <label class="filter-item">
                <input type="radio" name="distance" value="5">
                <span class="filter-label-text">5 km</span>
                </label>

                <label class="filter-item">
                <input type="radio" name="distance" value="10">
                <span class="filter-label-text">10 km</span>
                </label>

                <label class="filter-item">
                <input type="radio" name="distance" value="15">
                <span class="filter-label-text">15 km</span>
                </label>

                <label class="filter-item">
                <input type="radio" name="distance" value="30">
                <span class="filter-label-text">30 km</span>
                </label>

                <label class="filter-item">
                <input type="radio" name="distance" value="more30">
                <span class="filter-label-text">&gt; 30 km</span>
                </label>
            </div>
            </div>
        </div>

        <!-- Hidden section for the latitude and longitude -->
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

  <main class="parking-container" id="parkingList" aria-live="polite" style="display: none;">
    <!-- sample cards -->
    <article class="parking-card" data-price="2.5"  data-rating="4">
      <div>
        <h3>Central Park Garage</h3>
        <div class="meta-row">
          <div><span class="meta-strong">Location:</span> 41.9028, 12.4964</div>          
          <div><span class="meta-strong">Distance from me:</span> 1km </div>
          <div><span class="meta-strong">Rating Threshold:</span> 4 / 5</div>
          <div><span class="meta-strong">Price per hour:</span> €2.50/hour</div>
          <div><span class="meta-strong">Time Slot Availability:</span> 8:20-9:20</div>
        </div>
      </div>
      <div class="card-actions">
        <button class="book-btn"><a style="text-decoration:none; color:white;" href="book_parking.php">Book Now</a></button>
      </div>
    </article>

    <article class="parking-card" data-price="1.8" data-rating="3">
      <div>
        <h3>Riverside Parking</h3>
        <div class="meta-row">
          <div><span class="meta-strong">Location:</span> 41.9028, 12.4964</div>          
          <div><span class="meta-strong">Distance from me:</span> 1km </div>
          <div><span class="meta-strong">Rating Threshold:</span> 3 / 5</div>
          <div><span class="meta-strong">Price per hour:</span> €1.80/hour</div>
          <div><span class="meta-strong">Time Slot Availability:</span> 8:20-9:20</div>          
        </div>
      </div>
      <div class="card-actions">
        <button class="book-btn">Book Now</button>
      </div>
    </article>

    <article class="parking-card" data-price="3.2" data-rating="5">
      <div>
        <h3>City Center Lot</h3>
        <div class="meta-row">
          <div><span class="meta-strong">Location:</span> 41.9028, 12.4964</div>          
          <div><span class="meta-strong">Distance from me:</span> 1km </div>
          <div><span class="meta-strong">Rating Threshold:</span> 5 / 5</div>
          <div><span class="meta-strong">Price per hour:</span> €3.20/hour</div>
          <div><span class="meta-strong">Time Slot Availability:</span> 8:20-9:20</div>
        </div>
      </div>
      <div class="card-actions">
        <button class="book-btn">Book Now</button>
      </div>
    </article>

    <!-- sample cards -->
    <article class="parking-card" data-price="2.5" data-rating="4">
      <div>
        <h3>Central Park Garage</h3>
        <div class="meta-row">
          <div><span class="meta-strong">Location:</span> 41.9028, 12.4964</div>          
          <div><span class="meta-strong">Distance from me:</span> 1km </div>
          <div><span class="meta-strong">Rating Threshold:</span> 4 / 5</div>
          <div><span class="meta-strong">Price per hour:</span> €2.50/hour</div>
          <div><span class="meta-strong">Time Slot Availability:</span> 8:20-9:20</div>
        </div>
      </div>
      <div class="card-actions">
        <button class="book-btn">Book Now</button>
      </div>
    </article>

    <article class="parking-card" data-price="1.8" data-rating="3">
      <div>
        <h3>Riverside Parking</h3>
        <div class="meta-row">
          <div><span class="meta-strong">Location:</span> 41.9028, 12.4964</div>          
          <div><span class="meta-strong">Distance from me:</span> 1km </div>
          <div><span class="meta-strong">Rating Threshold:</span> 3 / 5</div>
          <div><span class="meta-strong">Price per hour:</span> €1.80/hour</div>
          <div><span class="meta-strong">Time Slot Availability:</span> 8:20-9:20</div>
        </div>
      </div>
      <div class="card-actions">
        <button class="book-btn">Book Now</button>
      </div>
    </article>

    <article class="parking-card" data-price="3.2"data-rating="5">
      <div>
        <h3>City Center Lot</h3>
        <div class="meta-row">
          <div><span class="meta-strong">Location:</span> 41.9028, 12.4964</div>          
          <div><span class="meta-strong">Distance from me:</span> 1km </div>
          <div><span class="meta-strong">Rating Threshold:</span> 5 / 5</div>
          <div><span class="meta-strong">Price per hour:</span> €3.20/hour</div>
          <div><span class="meta-strong">Time Slot Availability:</span> 8:20-9:20</div>
        </div>
      </div>
      <div class="card-actions">
        <button class="book-btn">Book Now</button>
      </div>
    </article>
    <!-- sample cards -->
    <article class="parking-card" data-price="2.5" data-capacity="150" data-rating="4">
      <div>
        <h3>Central Park Garage</h3>
        <div class="meta-row">
          <div><span class="meta-strong">Location:</span> 41.9028, 12.4964</div>          
          <div><span class="meta-strong">Distance from me:</span> 1km </div>
          <div><span class="meta-strong">Capacity:</span> 150 spots</div>
          <div><span class="meta-strong">Rating Threshold:</span> 4 / 5</div>
          <div><span class="meta-strong">Price per hour:</span> €2.50/hour</div>
          <div><span class="meta-strong">Time Slot Availability:</span> 8:20-9:20</div>
        </div>
      </div>
      <div class="card-actions">
        <button class="book-btn">Book Now</button>
      </div>
    </article>

    <article class="parking-card" data-price="1.8" data-rating="3">
      <div>
        <h3>Riverside Parking</h3>
        <div class="meta-row">
          <div><span class="meta-strong">Location:</span> 41.9028, 12.4964</div>          
          <div><span class="meta-strong">Distance from me:</span> 1km </div>
          <div><span class="meta-strong">Rating Threshold:</span> 3 / 5</div>
          <div><span class="meta-strong">Price per hour:</span> €1.80/hour</div>
          <div><span class="meta-strong">Time Slot Availability:</span> 8:20-9:20</div>
        </div>
      </div>
      <div class="card-actions">
        <button class="book-btn">Book Now</button>
      </div>
    </article>

    <article class="parking-card" data-price="3.2" data-rating="5">
      <div>
        <h3>City Center Lot</h3>
        <div class="meta-row">
          <div><span class="meta-strong">Location:</span> 41.9028, 12.4964</div>          
          <div><span class="meta-strong">Distance from me:</span> 1km </div>
          <div><span class="meta-strong">Rating Threshold:</span> 5 / 5</div>
          <div><span class="meta-strong">Price per hour:</span> €3.20/hour</div>
          <div><span class="meta-strong">Time Slot Availability:</span> 8:20-9:20</div>
        </div>
      </div>
      <div class="card-actions">
        <button class="book-btn">Book Now</button>
      </div>
    </article>
    <!-- sample cards -->
    <article class="parking-card" data-price="2.5" data-rating="4">
      <div>
        <h3>Central Park Garage</h3>
        <div class="meta-row">
          <div><span class="meta-strong">Location:</span> 41.9028, 12.4964</div>          
          <div><span class="meta-strong">Distance from me:</span> 1km </div>
          <div><span class="meta-strong">Rating Threshold:</span> 4 / 5</div>
          <div><span class="meta-strong">Price per hour:</span> €2.50/hour</div>
          <div><span class="meta-strong">Time Slot Availability:</span> 8:20-9:20</div>
        </div>
      </div>
      <div class="card-actions">
        <button class="book-btn">Book Now</button>
      </div>
    </article>

    <article class="parking-card" data-price="1.8" data-rating="3">
      <div>
        <h3>Riverside Parking</h3>
        <div class="meta-row">
          <div><span class="meta-strong">Location:</span> 41.9028, 12.4964</div>          
          <div><span class="meta-strong">Distance from me:</span> 1km </div>
          <div><span class="meta-strong">Rating Threshold:</span> 3 / 5</div>
          <div><span class="meta-strong">Price per hour:</span> €1.80/hour</div>
          <div><span class="meta-strong">Time Slot Availability:</span> 8:20-9:20</div>
        </div>
      </div>
      <div class="card-actions">
        <button class="book-btn">Book Now</button>
      </div>
    </article>

    <article class="parking-card" data-price="3.2" data-rating="5">
      <div>
        <h3>City Center Lot</h3>
        <div class="meta-row">
          <div><span class="meta-strong">Location:</span> 41.9028, 12.4964</div>          
          <div><span class="meta-strong">Distance from me:</span> 1km </div>
          <div><span class="meta-strong">Rating Threshold:</span> 5 / 5</div>
          <div><span class="meta-strong">Price per hour:</span> €3.20/hour</div>
          <div><span class="meta-strong">Time Slot Availability:</span> 8:20-9:20</div>
        </div>
      </div>
      <div class="card-actions">
        <button class="book-btn">Book Now</button>
      </div>
    </article>
  </main>
  <?php
  $footer = file_get_contents(FOOTER);
  echo $footer;
  ?>

  <script src="../js/search_parking.js"></script>
</body>

</html>