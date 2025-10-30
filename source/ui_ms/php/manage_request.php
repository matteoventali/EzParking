<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>My Garages Dashboard</title>
    <link rel="stylesheet" href="../css/navbar.css" />
    <link rel="stylesheet" href="../css/manage_request.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>


<body>
    <?php
    include './functions.php';
    $nav = generate_navbar('user');
    echo $nav;
    ?>


    <main class="container">
        <section class="panel" aria-labelledby="queue-title">
            <header class="top">
                <div>
                    <h1 id="queue-title">Reservation Requests Queue</h1>
                    <div class="subtitle">Manage incoming  reservation requests</div>
                </div>
                <div class="controls">
                    <div class="search" role="search">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M21 21l-4.35-4.35" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="1.5"></circle>
                        </svg>
                        <input id="search" placeholder="Search by parking or street" aria-label="Search requests">
                    </div>
                    <div class="stats" aria-hidden="true">
                        <div class="stat">Total <strong id="total-count">3</strong></div>
                        <div class="stat">Pending <strong id="pending-count">3</strong></div>
                    </div>
                </div>
            </header>
            <div class="list" id="requests-list">
                <article class="card" data-id="1">
                    
                    <div class="meta">
                        <div class="title">
                            <div>
                                <div class="parking">Central Park Garage</div>
                                <div class="address">42 Park Lane, Suite 1</div>
                            </div>
                            <div class="status pending" aria-live="polite">PENDING</div>
                        </div>
                        <div class="badges">
                            <div class="badge">Time slot: 09:00 - 11:00</div>
                            <div class="badge">Booking date: 2025-11-03</div>
                        </div>
                    </div>
                    <div class="actions">
                        <button class="btn accept" data-action="accept">Accept</button>
                        <button class="btn reject" data-action="reject">Reject</button>
                    </div>
                </article>


                <article class="card" data-id="1">
                   
                    <div class="meta">
                        <div class="title">
                            <div>
                                <div class="parking">Central Park Garage</div>
                                <div class="address">42 Park Lane, Suite 1</div>
                            </div>
                            <div class="status pending" aria-live="polite">PENDING</div>
                        </div>
                        <div class="badges">
                            <div class="badge">Time slot: 09:00 - 11:00</div>
                            <div class="badge">Booking date: 2025-11-03</div>
                        </div>
                    </div>
                    <div class="actions">
                        <button class="btn accept" data-action="accept">Accept</button>
                        <button class="btn reject" data-action="reject">Reject</button>
                    </div>
                </article>



                <article class="card" data-id="1">
                   
                    <div class="meta">
                        <div class="title">
                            <div>
                                <div class="parking">Central Park Garage</div>
                                <div class="address">42 Park Lane, Suite 1</div>
                            </div>
                            <div class="status pending" aria-live="polite">PENDING</div>
                        </div>
                        <div class="badges">
                            <div class="badge">Time slot: 09:00 - 11:00</div>
                            <div class="badge">Booking date: 2025-11-03</div>
                        </div>
                    </div>
                    <div class="actions">
                        <button class="btn accept" data-action="accept">Accept</button>
                        <button class="btn reject" data-action="reject">Reject</button>
                    </div>
                </article>




                <article class="card" data-id="1">
                  
                    <div class="meta">
                        <div class="title">
                            <div>
                                <div class="parking">Central Park Garage</div>
                                <div class="address">42 Park Lane, Suite 1</div>
                            </div>
                            <div class="status pending" aria-live="polite">PENDING</div>
                        </div>
                        <div class="badges">
                            <div class="badge">Time slot: 09:00 - 11:00</div>
                            <div class="badge">Booking date: 2025-11-03</div>
                        </div>
                    </div>
                    <div class="actions">
                        <button class="btn accept" data-action="accept">Accept</button>
                        <button class="btn reject" data-action="reject">Reject</button>
                    </div>
                </article>





                <article class="card" data-id="1">
                  
                    <div class="meta">
                        <div class="title">
                            <div>
                                <div class="parking">Central Park Garage</div>
                                <div class="address">42 Park Lane, Suite 1</div>
                            </div>
                            <div class="status pending" aria-live="polite">PENDING</div>
                        </div>
                        <div class="badges">
                            <div class="badge">Time slot: 09:00 - 11:00</div>
                            <div class="badge">Booking date: 2025-11-03</div>
                        </div>
                    </div>
                    <div class="actions">
                        <button class="btn accept" data-action="accept">Accept</button>
                        <button class="btn reject" data-action="reject">Reject</button>
                    </div>
                </article>

                <article class="card" data-id="2">
                  
                    <div class="meta">
                        <div class="title">
                            <div>
                                <div class="parking">Riverside Lot</div>
                                <div class="address">7 River St</div>
                            </div>
                            <div class="status pending" aria-live="polite">PENDING</div>
                        </div>
                        <div class="badges">
                            <div class="badge">Time slot: 13:00 - 15:00</div>
                            <div class="badge">Booking date: 2025-10-30</div>
                        </div>
                    </div>
                    <div class="actions">
                        <button class="btn accept" data-action="accept">Accept</button>
                        <button class="btn reject" data-action="reject">Reject</button>
                    </div>
                </article>
                <article class="card" data-id="3">
               
                    <div class="meta">
                        <div class="title">
                            <div>
                                <div class="parking">Harbor View Parking</div>
                                <div class="address">120 Marina Blvd</div>
                            </div>
                            <div class="status pending" aria-live="polite">PENDING</div>
                        </div>
                        <div class="badges">
                            <div class="badge">Time slot: 18:00 - 20:00</div>
                            <div class="badge">Booking date: 2025-11-01</div>
                        </div>
                    </div>
                    <div class="actions">
                        <button class="btn accept" data-action="accept">Accept</button>
                        <button class="btn reject" data-action="reject">Reject</button>
                    </div>
                </article>
            </div>
            <div id="empty" class="empty" hidden>No reservation requests at the moment</div>
        </section>
    </main>

    <?php
    $footer = file_get_contents(FOOTER);
    echo $footer;
    ?>

    <script src="../js/manage_request.js"></script>
</body>

</html>