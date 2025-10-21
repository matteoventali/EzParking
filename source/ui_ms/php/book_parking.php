<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Available Parking Spots — Search & Filters</title>
  <link rel="stylesheet" href="../css/navbar.css" />
  <link rel="stylesheet" href="../css/book_parking.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <?php include './functions.php';
      $nav = generate_navbar('admin');
      echo $nav;
     ?>


  <section class="search-section"id="searchSection" aria-label="Search parking">
    <div class="search-wrapper">
      <div class="search-input" role="search">
        <input id="searchText" type="text" placeholder="Find a parking spot..." aria-label="Search parking spots">
      </div>

      <div class="controls">
        <div class="dropdown" id="filtersDropdown">
          <button
            class="dropdown-toggle"
            id="dropdownToggle"
            aria-haspopup="true"
            aria-expanded="false"
            type="button"
          >
            Filters ⌄
          </button>

          <div class="dropdown-panel" id="dropdownPanel" role="menu" aria-label="Filter options">
            <div class="filters" role="group" aria-label="Parking filters">
              <label class="filter-item">
                <input type="checkbox" name="filter" value="low_price">
                <span class="filter-label-text">Low Price</span>
              </label>

              <label class="filter-item">
                <input type="checkbox" name="filter" value="high_capacity">
                <span class="filter-label-text">High Capacity</span>
              </label>

              <label class="filter-item">
                <input type="checkbox" name="filter" value="high_rating">
                <span class="filter-label-text">High Rating</span>
              </label>

              <label class="filter-item">
                <input type="checkbox" name="filter" value="nearby">
                <span class="filter-label-text">Nearby</span>
              </label>
            </div>

            <div style="margin-top:0.6rem">
              <div class="label-pills" id="activePills" aria-hidden="true"></div>
            </div>
          </div>
        </div>

        <!-- NEW: Distance dropdown (same style as filters) -->
        <div class="dropdown" id="distanceDropdown">
          <button
            class="dropdown-toggle"
            id="distanceToggle"
            aria-haspopup="true"
            aria-expanded="false"
            type="button"
          >
            Distance ⌄
          </button>

          <div class="dropdown-panel" id="distancePanel" role="menu" aria-label="Distance options">
            <div class="filters" role="group" aria-label="Distance filters">
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

        <button class="search-btn" id="searchBtn" type="button">Search</button>
      </div>
          </div>
  </section>

  <main class="parking-container" id="parkingList" aria-live="polite" style="display: none;">
    <!-- sample cards -->
    <article class="parking-card" data-price="2.5" data-capacity="150" data-rating="4">
      <div>
        <h3>Central Park Garage</h3>
        <div class="meta-row">
          <div><span class="meta-strong">Location:</span> 41.9028, 12.4964</div>
          <div><span class="meta-strong">Capacity:</span> 150 spots</div>
          <div><span class="meta-strong">Rating Threshold:</span> 4 / 5</div>
          <div><span class="meta-strong">Price per Slot:</span> €2.50/hour</div>
        </div>
      </div>
      <div class="card-actions">
        <button class="book-btn">Book Now</button>
      </div>
    </article>

    <article class="parking-card" data-price="1.8" data-capacity="80" data-rating="3">
      <div>
        <h3>Riverside Parking</h3>
        <div class="meta-row">
          <div><span class="meta-strong">Location:</span> 45.4642, 9.19</div>
          <div><span class="meta-strong">Capacity:</span> 80 spots</div>
          <div><span class="meta-strong">Rating Threshold:</span> 3 / 5</div>
          <div><span class="meta-strong">Price per Slot:</span> €1.80/hour</div>
        </div>
      </div>
      <div class="card-actions">
        <button class="book-btn">Book Now</button>
      </div>
    </article>

    <article class="parking-card" data-price="3.2" data-capacity="100" data-rating="5">
      <div>
        <h3>City Center Lot</h3>
        <div class="meta-row">
          <div><span class="meta-strong">Location:</span> 40.8518, 14.2681</div>
          <div><span class="meta-strong">Capacity:</span> 100 spots</div>
          <div><span class="meta-strong">Rating Threshold:</span> 5 / 5</div>
          <div><span class="meta-strong">Price per Slot:</span> €3.20/hour</div>
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
          <div><span class="meta-strong">Capacity:</span> 150 spots</div>
          <div><span class="meta-strong">Rating Threshold:</span> 4 / 5</div>
          <div><span class="meta-strong">Price per Slot:</span> €2.50/hour</div>
        </div>
      </div>
      <div class="card-actions">
        <button class="book-btn">Book Now</button>
      </div>
    </article>

    <article class="parking-card" data-price="1.8" data-capacity="80" data-rating="3">
      <div>
        <h3>Riverside Parking</h3>
        <div class="meta-row">
          <div><span class="meta-strong">Location:</span> 45.4642, 9.19</div>
          <div><span class="meta-strong">Capacity:</span> 80 spots</div>
          <div><span class="meta-strong">Rating Threshold:</span> 3 / 5</div>
          <div><span class="meta-strong">Price per Slot:</span> €1.80/hour</div>
        </div>
      </div>
      <div class="card-actions">
        <button class="book-btn">Book Now</button>
      </div>
    </article>

    <article class="parking-card" data-price="3.2" data-capacity="100" data-rating="5">
      <div>
        <h3>City Center Lot</h3>
        <div class="meta-row">
          <div><span class="meta-strong">Location:</span> 40.8518, 14.2681</div>
          <div><span class="meta-strong">Capacity:</span> 100 spots</div>
          <div><span class="meta-strong">Rating Threshold:</span> 5 / 5</div>
          <div><span class="meta-strong">Price per Slot:</span> €3.20/hour</div>
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
          <div><span class="meta-strong">Capacity:</span> 150 spots</div>
          <div><span class="meta-strong">Rating Threshold:</span> 4 / 5</div>
          <div><span class="meta-strong">Price per Slot:</span> €2.50/hour</div>
        </div>
      </div>
      <div class="card-actions">
        <button class="book-btn">Book Now</button>
      </div>
    </article>

    <article class="parking-card" data-price="1.8" data-capacity="80" data-rating="3">
      <div>
        <h3>Riverside Parking</h3>
        <div class="meta-row">
          <div><span class="meta-strong">Location:</span> 45.4642, 9.19</div>
          <div><span class="meta-strong">Capacity:</span> 80 spots</div>
          <div><span class="meta-strong">Rating Threshold:</span> 3 / 5</div>
          <div><span class="meta-strong">Price per Slot:</span> €1.80/hour</div>
        </div>
      </div>
      <div class="card-actions">
        <button class="book-btn">Book Now</button>
      </div>
    </article>

    <article class="parking-card" data-price="3.2" data-capacity="100" data-rating="5">
      <div>
        <h3>City Center Lot</h3>
        <div class="meta-row">
          <div><span class="meta-strong">Location:</span> 40.8518, 14.2681</div>
          <div><span class="meta-strong">Capacity:</span> 100 spots</div>
          <div><span class="meta-strong">Rating Threshold:</span> 5 / 5</div>
          <div><span class="meta-strong">Price per Slot:</span> €3.20/hour</div>
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
          <div><span class="meta-strong">Capacity:</span> 150 spots</div>
          <div><span class="meta-strong">Rating Threshold:</span> 4 / 5</div>
          <div><span class="meta-strong">Price per Slot:</span> €2.50/hour</div>
        </div>
      </div>
      <div class="card-actions">
        <button class="book-btn">Book Now</button>
      </div>
    </article>

    <article class="parking-card" data-price="1.8" data-capacity="80" data-rating="3">
      <div>
        <h3>Riverside Parking</h3>
        <div class="meta-row">
          <div><span class="meta-strong">Location:</span> 45.4642, 9.19</div>
          <div><span class="meta-strong">Capacity:</span> 80 spots</div>
          <div><span class="meta-strong">Rating Threshold:</span> 3 / 5</div>
          <div><span class="meta-strong">Price per Slot:</span> €1.80/hour</div>
        </div>
      </div>
      <div class="card-actions">
        <button class="book-btn">Book Now</button>
      </div>
    </article>

    <article class="parking-card" data-price="3.2" data-capacity="100" data-rating="5">
      <div>
        <h3>City Center Lot</h3>
        <div class="meta-row">
          <div><span class="meta-strong">Location:</span> 40.8518, 14.2681</div>
          <div><span class="meta-strong">Capacity:</span> 100 spots</div>
          <div><span class="meta-strong">Rating Threshold:</span> 5 / 5</div>
          <div><span class="meta-strong">Price per Slot:</span> €3.20/hour</div>
        </div>
      </div>
      <div class="card-actions">
        <button class="book-btn">Book Now</button>
      </div>
    </article>
  </main>
    <?php
    require_once './config.php';
    $footer = file_get_contents(FOOTER);
    echo $footer;
  ?>

  <script src="../js/book_parking.js"></script>
</body>
</html>
