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


  <section class="search-section" aria-label="Search parking">
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

            <!-- optional pills to show active selected labels (visual) -->
            <div style="margin-top:0.6rem">
              <div class="label-pills" id="activePills" aria-hidden="true"></div>
            </div>
          </div>
        </div>

        <button class="search-btn" id="searchBtn" type="button">Search</button>
      </div>
    </div>
  </section>

  <main class="parking-container" id="parkingList" aria-live="polite">
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
  <script>
    // Minimal JS for dropdown toggle + close-on-outside + pills visual
    (function(){
      const dropdown = document.getElementById('filtersDropdown');
      const toggle = document.getElementById('dropdownToggle');
      const panel = document.getElementById('dropdownPanel');
      const searchBtn = document.getElementById('searchBtn');
      const activePills = document.getElementById('activePills');

      function setOpen(isOpen){
        if(isOpen){
          dropdown.classList.add('open');
          toggle.setAttribute('aria-expanded','true');
        } else {
          dropdown.classList.remove('open');
          toggle.setAttribute('aria-expanded','false');
        }
      }

      // toggle on click
      toggle.addEventListener('click', function(e){
        e.stopPropagation();
        setOpen(!dropdown.classList.contains('open'));
      });

      // keep open when clicking inside panel (so you can check boxes)
      panel.addEventListener('click', function(e){ e.stopPropagation(); });

      // close on outside click
      document.addEventListener('click', function(){ setOpen(false); });

      // close on escape
      document.addEventListener('keydown', function(e){
        if(e.key === 'Escape') setOpen(false);
      });

      // update pills visual and keep selections (for demo)
      const checkboxes = panel.querySelectorAll('input[type="checkbox"]');
      function refreshPills(){
        activePills.innerHTML = '';
        checkboxes.forEach(cb => {
          if(cb.checked){
            const pill = document.createElement('span');
            pill.className = 'pill';
            pill.textContent = cb.parentElement.querySelector('.filter-label-text').textContent;
            activePills.appendChild(pill);
          }
        });
      }
      checkboxes.forEach(cb => cb.addEventListener('change', refreshPills));

      // Example: search button -> you can hook real filtering logic here
      searchBtn.addEventListener('click', function(){
        // For demo: just close dropdown and refresh pills
        setOpen(false);
        refreshPills();
        // Here you could filter cards based on selected checkboxes + search text
      });

    })();
  </script>
</body>
</html>
