<?php
require_once "./config.php";
require_once "./functions.php";

// We must be logged in to access this page
if (!verify_session())
    header("Location: " . $starting_page);
else if ($_SESSION['role'] != 'admin') // Redirect the user to the correct homepage
    header("Location: " . $homepage);

// Get access to the name of the user
$name = $_SESSION['user']['name'];
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EzParking - Dashboard</title>

    <link rel="stylesheet" href="../css/homepage.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/real-time-data.css">
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

    <!-- HTML (English content) -->
    <section class="features">
        <h2 class="features-title">Real-time statistics</h2>

        <div class="cards" id="realtime-cards">
            <!-- Card 1: online users -->
            <article class="card stat-card" aria-labelledby="c1-title">
                <div class="card-head">
                    <svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <defs>
                            <linearGradient id="g-user" x1="0" x2="1">
                                <stop offset="0" stop-color="#7b48d7" />
                                <stop offset="1" stop-color="#c08cff" />
                            </linearGradient>
                        </defs>
                        <path fill="url(#g-user)"
                            d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm6 2h-1.2a6 6 0 0 0-9.6 0H6a2 2 0 0 0-2 2v1a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-1a2 2 0 0 0-2-2z" />
                    </svg>

                    <div class="card-info">
                        <h3 id="c1-title">Users online</h3>
                        <div class="card-value" aria-live="polite">18</div>
                    </div>
                </div>

                <svg class="sparkline" viewBox="0 0 100 30" preserveAspectRatio="none" aria-hidden="true">
                    <polyline points="0,22 10,18 20,14 30,10 40,12 50,8 60,11 70,7 80,10 90,6 100,8" fill="none"
                        stroke="rgba(123,72,215,0.18)" stroke-width="8" stroke-linecap="round" />
                    <polyline points="0,22 10,18 20,14 30,10 40,12 50,8 60,11 70,7 80,10 90,6 100,8" fill="none"
                        stroke="#7b48d7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>

                <div class="card-meta">Updated: <time datetime="2025-10-25T10:23:05Z">25 Oct 2025 — 10:23</time></div>
            </article>

            <!-- Card 2: active bookings -->
            <article class="card stat-card" aria-labelledby="c2-title">
                <div class="card-head">
                    <svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <defs>
                            <linearGradient id="g-book" x1="0" x2="1">
                                <stop offset="0" stop-color="#2bb7a9" />
                                <stop offset="1" stop-color="#66e1c8" />
                            </linearGradient>
                        </defs>
                        <path fill="url(#g-book)" d="M7 2h10a1 1 0 0 1 1 1v18l-6-3-6 3V3a1 1 0 0 1 1-1z" />
                    </svg>

                    <div class="card-info">
                        <h3 id="c2-title">Active bookings</h3>
                        <div class="card-value">34</div>
                    </div>
                </div>

                <svg class="sparkline" viewBox="0 0 100 30" preserveAspectRatio="none" aria-hidden="true">
                    <polyline points="0,25 10,20 20,18 30,16 40,12 50,14 60,10 70,8 80,6 90,8 100,4" fill="none"
                        stroke="rgba(43,183,169,0.15)" stroke-width="8" stroke-linecap="round" />
                    <polyline points="0,25 10,20 20,18 30,16 40,12 50,14 60,10 70,8 80,6 90,8 100,4" fill="none"
                        stroke="#2bb7a9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>

                <div class="card-meta">Updated: <time datetime="2025-10-25T10:23:05Z">25 Oct 2025 — 10:23</time></div>
            </article>

            <!-- Card 3: occupancy -->
            <article class="card stat-card" style="height:138.16px" aria-labelledby="c3-title">
                <div class="card-head">
                    <svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <defs>
                            <linearGradient id="g-oc" x1="0" x2="1">
                                <stop offset="0" stop-color="#ffb74d" />
                                <stop offset="1" stop-color="#ff8a65" />
                            </linearGradient>
                        </defs>
                        <path fill="url(#g-oc)" d="M11 2v9h9A9 9 0 0 0 11 2zM2 12a9 9 0 0 0 9 9v-9H2z" />
                    </svg>

                    <div class="card-info">
                        <h3 id="c3-title">Occupancy</h3>
                        <div class="card-value">72<span class="small">%</span></div>
                    </div>
                </div>

                <div class="occupancy-bar" aria-hidden="true" role="presentation">
                    <div class="occupancy-fill" style="--target:72%; width:72%"></div>
                </div>

                <div class="card-meta">Updated: <time datetime="2025-10-25T10:23:05Z">25 Oct 2025 — 10:23</time></div>
            </article>

    
        </div>

        <!-- Recent bookings (styled like a card) -->
        <aside class="recent card recent-card" aria-labelledby="recent-title">
            <h3 id="recent-title">Recent bookings</h3>
            <ul class="recent-list" role="list">
                <li>
                    <div class="r-left">
                        <strong>Mario Rossi</strong>
                        <div class="r-sub">Seat P2 • 09:52</div>
                    </div>
                    <div class="r-right status on">in progress</div>
                </li>
                <li>
                    <div class="r-left">
                        <strong>Luca B.</strong>
                        <div class="r-sub">Seat P5 • 09:18</div>
                    </div>
                    <div class="r-right status done">completed</div>
                </li>
                <li>
                    <div class="r-left">
                        <strong>Giulia F.</strong>
                        <div class="r-sub">Seat P8 • 08:40</div>
                    </div>
                    <div class="r-right status reserved">reserved</div>
                </li>
            </ul>
        </aside>
    </section>






    <?php
    $footer = file_get_contents(FOOTER);
    echo $footer;
    ?>
</body>


</html>