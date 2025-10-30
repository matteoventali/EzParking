<?php
    require_once "./config.php";
    require_once "./functions.php";

    // We must be logged in to access this page
    if ( !verify_session() )
        header("Location: " . $starting_page);
    else if ( $_SESSION['role'] != 'user' ) // Redirect the user to the correct homepage
        header("Location: " . $homepage);

    // Get access to the name of the user
    $name = $_SESSION['user']['name'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Book Central Park Garage</title>
    <link rel="stylesheet" href="../css/navbar.css" />
    <link rel="stylesheet" href="../css/insert_spot.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <?php
    
    $nav = generate_navbar('user');
    echo $nav;
    ?>

    <div class="container">
        <div class="form-wrapper">
            <div class="form-header">
                <h1>Add New Parking Spot</h1>
                <p>Fill in the details to list your parking space</p>
            </div>

            <form id="parkingForm" class="parking-form">
                <div class="form-group">
                    <label for="parkingName">
                        Parking Name

                    </label>
                    <input type="text" id="parkingName" name="parkingName" placeholder="Enter parking spot name"
                        required>
                </div>

                <div class="form-group">
                    <label for="reputationThreshold">
                        Reputation Threshold

                    </label>
                    <div class="input-with-icon">
                        <input type="number" id="reputationThreshold" name="reputationThreshold" placeholder="0" min="0"
                            max="100" required>
                        <span class="input-suffix">/ 100</span>
                    </div>
                    <small class="input-hint">Minimum reputation score required to access this parking</small>
                </div>

                <div class="form-group">
                    <label for="hourlyRate">
                        Hourly Rate
                    </label>
                    <div class="input-with-icon">
                        <span class="input-prefix">$</span>
                        <input type="number" id="hourlyRate" name="hourlyRate" placeholder="0.00" min="0" step="0.10"
                            required>
                    </div>
                    <small class="input-hint">Cost per hour for parking slot</small>
                </div>

                <div class="form-group dropdown" id="filtersDropdown">
                    <button class="dropdown-toggle" id="dropdownToggle" aria-haspopup="true"
                        aria-controls="dropdownPanel" aria-expanded="false" style="height: 50px;" type="button">
                        Select Label 
                    </button>

                    <div class="dropdown-panel" id="dropdownPanel" role="menu" aria-label="Filter options" hidden>
                        <div class="filters" role="group" aria-label="Parking filters">
                            <label class="filter-item">
                                <input type="checkbox" name="filters[]" value="low_price">
                                <span class="filter-label-text">Label1</span>
                            </label>

                            <label class="filter-item">
                                <input type="checkbox" name="filters[]" value="high_rating">
                                <span class="filter-label-text">Label2</span>
                            </label>

                            <label class="filter-item">
                                <input type="checkbox" name="filters[]" value="low_price">
                                <span class="filter-label-text">Label3</span>
                            </label>

                            <label class="filter-item">
                                <input type="checkbox" name="filters[]" value="high_rating">
                                <span class="filter-label-text">Label4</span>
                            </label>
                        </div>

                        <div style="margin-top:0.6rem">
                            <!-- Pills container: aggiornato dinamicamente -->
                            <div class="label-pills" id="activePills" aria-live="polite" aria-atomic="true"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group map-placeholder">
                    <label>
                        Location Map
                    </label>
                    <div class="map-container">
                        <div class="map-placeholder-content">
                            
                              <div id="map" style="height: 500px; width: 100%; border-radius: 10px;"></div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="manage_garage.php"><button type="button" class="btn btn-secondary" id="cancelBtn">
                            Cancel
                        </button></a>
                    <button type="submit" class="btn btn-primary">
                        Add Parking Spot
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php
    $footer = file_get_contents(FOOTER);
    echo $footer;
    ?>

    <script>

        document.addEventListener('DOMContentLoaded', function () {
            const searchSection = document.getElementById('searchSection');
            const parkingList = document.getElementById('parkingList');
            const activePills = document.getElementById('activePills');
            const loader = document.querySelector('.loader-section');

            // --- Generic dropdown logic  ---
            const dropdowns = document.querySelectorAll('.dropdown');
            dropdowns.forEach(drop => {
                const toggle = drop.querySelector('.dropdown-toggle');
                const panel = drop.querySelector('.dropdown-panel');

                toggle.addEventListener('click', function (e) {
                    const isOpen = drop.classList.contains('open');
                    // Close other open dropdowns
                    document.querySelectorAll('.dropdown.open').forEach(d => {
                        if (d !== drop) {
                            d.classList.remove('open');
                            const t = d.querySelector('.dropdown-toggle');
                            if (t) t.setAttribute('aria-expanded', 'false');
                        }
                    });

                    if (isOpen) {
                        drop.classList.remove('open');
                        toggle.setAttribute('aria-expanded', 'false');
                    } else {
                        drop.classList.add('open');
                        toggle.setAttribute('aria-expanded', 'true');
                    }
                    e.stopPropagation();
                });

                if (panel) panel.addEventListener('click', e => e.stopPropagation());
            });

            document.addEventListener('click', function () {
                document.querySelectorAll('.dropdown.open').forEach(d => {
                    d.classList.remove('open');
                    const t = d.querySelector('.dropdown-toggle');
                    if (t) t.setAttribute('aria-expanded', 'false');
                });
            });

            // --- Pills refresh ---
            function refreshPills() {
                if (!activePills) return;
                activePills.innerHTML = '';

                const checkedFilters = Array.from(document.querySelectorAll('.dropdown#filtersDropdown input[type="checkbox"]:checked'));
                checkedFilters.forEach(inp => {
                    const labelText = inp.closest('label')?.querySelector('.filter-label-text')?.textContent || inp.value;
                    const pill = document.createElement('div');
                    pill.className = 'pill';
                    pill.textContent = labelText;
                    activePills.appendChild(pill);
                });

                const selectedDistance = document.querySelector('.dropdown#distanceDropdown input[name="distance"]:checked');
                if (selectedDistance) {
                    const labelText = selectedDistance.closest('label')?.querySelector('.filter-label-text')?.textContent || selectedDistance.value;
                    const pill = document.createElement('div');
                    pill.className = 'pill';
                    pill.textContent = `Distance: ${labelText}`;
                    activePills.appendChild(pill);
                }

                activePills.setAttribute('aria-hidden', activePills.children.length === 0 ? 'true' : 'false');
            }

            document.querySelectorAll('#filtersDropdown input[type="checkbox"]').forEach(cb => cb.addEventListener('change', refreshPills));
            document.querySelectorAll('#distanceDropdown input[name="distance"]').forEach(r => {
                r.addEventListener('change', function () {
                    const dd = document.getElementById('distanceDropdown');
                    if (dd) {
                        dd.classList.remove('open');
                        const t = dd.querySelector('.dropdown-toggle');
                        if (t) t.setAttribute('aria-expanded', 'false');
                    }
                    refreshPills();
                });
            });

            refreshPills(); // init

            // --- Search / loader logic---
            if (!searchSection || !parkingList) return;

            let busy = false;
            let loaderTimeoutId = null;
            let showCardsTimeoutId = null;

            searchBtn.addEventListener('click', function () {
                // do not refresh again if already loaded
                if (searchSection.classList.contains('is-raised') || busy) return;

                busy = true;

                // close open dropdowns
                document.querySelectorAll('.dropdown.open').forEach(d => {
                    d.classList.remove('open');
                    const t = d.querySelector('.dropdown-toggle');
                    if (t) t.setAttribute('aria-expanded', 'false');
                });

                // show loader
                if (loader) {
                    // solleva la search bar
                    searchSection.classList.add('is-raised');
                    loader.style.display = 'block';
                    loader.setAttribute('aria-hidden', 'false');
                }

                // clean old timeouts
                if (loaderTimeoutId) clearTimeout(loaderTimeoutId);
                if (showCardsTimeoutId) clearTimeout(showCardsTimeoutId);

                // after four seconds close loader and show results
                loaderTimeoutId = setTimeout(() => {
                    if (loader) {
                        loader.style.display = 'none';
                        loader.setAttribute('aria-hidden', 'true');
                    }

                    showCardsTimeoutId = setTimeout(() => {
                        parkingList.style.display = 'grid';
                        parkingList.classList.add('visible');

                        busy = false;
                    }, 280);
                }, 2500);
            });
        });


    </script>

</body>

</html>