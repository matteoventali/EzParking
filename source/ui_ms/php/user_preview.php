<?php
	require_once "./config.php";
	require_once "./functions.php";

	// We must be logged in to access this page
	if (!verify_session())
		header("Location: " . $starting_page);
	else if ($_SESSION['role'] != 'user') // We must be normal user to access this page
		header("Location: admin_dashboard.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>User Preview</title>
	<link rel="stylesheet" href="../css/homepage.css">
	<link rel="stylesheet" href="../css/dashboard.css">
	<link rel="stylesheet" href="../css/style.css">
	<link rel="stylesheet" href="../css/navbar.css">
	<link rel="stylesheet" href="../css/popup.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@400;700&display=swap" rel="stylesheet">
	<script src="../js/stars.js"></script>
</head>

<body style="background: linear-gradient(135deg, #f3ecff, #e8dcff);;">
	<?php
		$nav = generate_navbar($_SESSION['role']);
		echo $nav;
	?>

	<main class="dashboard-grid" style="flex-direction: column;">
		<!-- User Data Section -->
		<div class="dashboard-card user-data-card">
			<div class="user-header">
				<img src="../images/account.svg" alt="User Avatar" class="user-avatar">
				<div>
					<h2 class="user-name">
						Name Surname
					</h2>
				</div>
			</div>

			<div class="user-info">
				<div class="info-item">
					<i class="fas fa-phone"></i>
					<span><strong>Email: </strong></span>
				</div>
				<div class="info-item">
					<i class="fas fa-phone"></i>
					<span><strong>Phone: </strong></span>
				</div>
			</div>
		</div>

        <div class="dashboard-card">
            <div class="section-title">Booking Request Details</div>
            <div>
                <h1 class="request-info">Parking Spot Name</h1>
                <div class="request-column">
                    <div>
                        <p><strong>Location: </strong>Parking Address</p>
                    </div>
                    <div>
                        <p><strong>Date: </strong>YYYY-MM-DD</p>
                    </div>
                    <div>
                        <p><strong>Slot: </strong>hh:mm - hh:mm</p>
                    </div>
                    <div>
                        <p><strong>Total cost: </strong>10 €</p>
                    </div>                    
                </div>                
            </div>
        </div>

		<!-- Statistics Section -->
		<div class="dashboard-card statistics-card">
			<div class="section-title">User's Statistics</div>
			<div class="stats-grid">
				<div class="stat-box">
					<div class="stat-value" id="reputation">⭐ 0/5</div>
					<div class="stat-label">Reputation Level</div>
				</div>

				<div class="stat-box">
					<div class="stat-value" id="totalReservations">34</div>
					<div class="stat-label">Total Reservations</div>
				</div>
			</div>
		</div>

		<!-- Received Reviews -->
		<div class="dashboard-card review-card">
			<div class="section-title">Received Reviews</div>
			<div class="review-box">
                <div class="review-column">
                    <div class="review-header">
                    <span><strong>User:</strong> Name</span>
                    <span><strong>Rating:</strong> Stars</span>
                    </div>
                    <p class="review-text">Review's text</p>
                </div>

                <div class="review-column">
                    <div class="review-header">
                    <span><strong>User:</strong> Name</span>
                    <span><strong>Rating:</strong> Stars</span>
                    </div>
                    <p class="review-text">Review's text</p>
                </div>				
			</div>
		</div>

	</main>
	
	<?php
		$footer = file_get_contents(FOOTER);
		echo $footer;
	?>

	<script src="../js/review_popup.js"></script>
</body>
</html>