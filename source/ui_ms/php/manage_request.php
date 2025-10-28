<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>My Garages Dashboard</title>
    <link rel="stylesheet" href="../css/navbar.css" />
    <link rel="stylesheet" href="../css/homepage.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<style>
    :root {
        --brand: #5e25a5;
        --bg: #f7f6fb;
        --muted: #6b6b7a;
        --card: #ffffff;
        --accept: #1fa66a;
        --reject: #e04949;
        --glass: rgba(94, 37, 165, 0.08);
        font-size: 16px;
        color-scheme: light;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    html,
    body {
        height: 100%;
        margin: 0;
        font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        color: #111
    }

    body {
        background: linear-gradient(180deg, var(--bg), #ffffff);
        padding: 24px 0
    }

    main.container {
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 24px;
        display: flex;
        flex-direction: column;
        gap: 24px;
        margin-top: 100px;
    }

    body,
    main {
        margin: 0;
        padding: 0;
    }

    .panel {
        background: var(--card);
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 6px 18px rgba(15, 15, 20, 0.06);
        width: 100%
    }

    header.top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px
    }

    h1 {
        font-size: 1.25rem;
        margin: 0
    }

    .subtitle {
        color: var(--muted);
        font-size: 0.95rem
    }

    .controls {
        display: flex;
        gap: 10px;
        align-items: center
    }

    .search {
        display: flex;
        align-items: center;
        background: var(--glass);
        border-radius: 10px;
        padding: 8px 10px;
        gap: 8px
    }

    .search input {
        border: 0;
        background: transparent;
        outline: none;
        font-size: 0.95rem;
        width: 200px
    }

    .stats {
        display: flex;
        gap: 12px;
        align-items: center
    }

    .stat {
        background: linear-gradient(90deg, rgba(94, 37, 165, 0.06), transparent);
        padding: 8px 12px;
        border-radius: 10px;
        font-size: 0.9rem
    }

    .list {
        display: grid;
        gap: 12px;
        margin-top: 14px
    }

    .card {
        display: flex;
        gap: 16px;
        align-items: center;
        padding: 12px;
        border-radius: 12px;
        border: 1px solid rgba(94, 37, 165, 0.06);
        background: linear-gradient(180deg, var(--card), #fbfbff)
    }

    .avatar {
        width: 56px;
        height: 56px;
        border-radius: 10px;
        flex: 0 0 56px;
        background: linear-gradient(135deg, var(--brand), #8b56d8);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.05rem
    }

    .meta {
        flex: 1;
        min-width: 0
    }

    .meta .title {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: center
    }

    .parking {
        font-weight: 600
    }

    .address {
        color: var(--muted);
        font-size: 0.92rem;
        margin-top: 4px
    }

    .badges {
        display: flex;
        gap: 8px;
        margin-top: 8px;
        flex-wrap: wrap
    }

    .badge {
        padding: 6px 8px;
        border-radius: 999px;
        font-size: 0.8rem;
        background: rgba(94, 37, 165, 0.06);
        color: var(--brand);
        font-weight: 600
    }

    .actions {
        display: flex;
        flex-direction: column;
        gap: 8px;
        align-items: end
    }

    .btn {
        border: 0;
        padding: 8px 12px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        min-width: 110px
    }

    .btn.accept {
        background: linear-gradient(90deg, var(--accept), #17a055);
        color: white
    }

    .btn.reject {
        background: linear-gradient(90deg, var(--reject), #d63a3a);
        color: white
    }

    .status {
        font-weight: 700;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 0.85rem
    }

    .status.pending {
        background: #fff6e8;
        color: #a36a00
    }

    .status.accepted {
        background: rgba(31, 166, 106, 0.12);
        color: var(--accept)
    }

    .status.rejected {
        background: rgba(224, 73, 73, 0.1);
        color: var(--reject)
    }

    .empty {
        padding: 24px;
        border-radius: 10px;
        text-align: center;
        color: var(--muted);
        background: linear-gradient(90deg, rgba(94, 37, 165, 0.02), transparent)
    }

    @media (max-width:800px) {
        main.container {
            padding: 0 12px
        }

        .card {
            flex-direction: column;
            align-items: stretch
        }

        .actions {
            flex-direction: row;
            justify-content: space-between
        }

        .avatar {
            width: 48px;
            height: 48px
        }

        .btn {
            min-width: 48%;
            padding: 10px
        }

        .search input {
            width: 120px
        }
    }
@media (max-width: 800px) {
  .garage-list-container {
    display: flex;
    flex-direction: column;
    gap: 16px;
    padding: 0 12px;
  }

  .garage-card {
    flex-direction: column;
    align-items: flex-start;
    padding: 16px;
  }

  .card-details {
    width: 100%;
    margin-bottom: 10px;
  }

  .card-status {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .status-indicator {
    font-size: 0.85rem;
    padding: 6px 10px;
  }

  .add-new-button {
    width: 100%;
    text-align: center;
    padding: 12px 0;
    margin-bottom: 12px;
  }
}



</style>

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
                    <div class="subtitle">Manage incoming parking reservation requests</div>
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
                    <div class="avatar">P1</div>
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
                    <div class="avatar">P1</div>
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
                    <div class="avatar">P1</div>
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
                    <div class="avatar">P1</div>
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
                    <div class="avatar">P1</div>
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
                    <div class="avatar">P2</div>
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
                    <div class="avatar">P3</div>
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

    <script>
        const list = document.getElementById('requests-list');
        const totalCount = document.getElementById('total-count');
        const pendingCount = document.getElementById('pending-count');
        const empty = document.getElementById('empty');

        function updateStats() {
            const cards = [...list.querySelectorAll('.card')];
            totalCount.textContent = cards.length;
            const pend = cards.filter(c => c.querySelector('.status').classList.contains('pending')).length;
            pendingCount.textContent = pend;
            empty.hidden = cards.length > 0;
        }
        list.addEventListener('click', e => {
            const btn = e.target.closest('button');
            if (!btn) return;
            const card = btn.closest('.card');
            const statusEl = card.querySelector('.status');
            const action = btn.dataset.action;
            if (action === 'accept') {
                statusEl.textContent = 'ACCEPTED';
                statusEl.classList.remove('pending');
                statusEl.classList.remove('rejected');
                statusEl.classList.add('accepted');
                card.style.opacity = '0.98';
            }
            if (action === 'reject') {
                statusEl.textContent = 'REJECTED';
                statusEl.classList.remove('pending');
                statusEl.classList.remove('accepted');
                statusEl.classList.add('rejected');
                card.style.opacity = '0.7';
            }
            updateStats();
        });
        document.getElementById('search').addEventListener('input', e => {
            const q = e.target.value.trim().toLowerCase();
            const cards = [...list.querySelectorAll('.card')];
            cards.forEach(c => {
                const text = (c.querySelector('.parking').textContent + ' ' + c.querySelector('.address').textContent).toLowerCase();
                c.style.display = text.includes(q) ? 'flex' : 'none';
            });
            updateStats();
        });
        updateStats();
    </script>
</body>

</html>