<?php
	require_once "./config.php";
	require_once "./functions.php";

	// We must be logged in to access this page
	if (!verify_session())
		header("Location: " . $starting_page);
	else if ($_SESSION['role'] != 'user') // We must be normal user to access this page
		header("Location: admin_dashboard.php");

	// Updating the user informations in the session storage to get access also to the score of the user
	$url = compose_url($protocol, $socket_account_ms, '/pdata');
	$new_data = perform_rest_request('GET', $url, null, $_SESSION['session_token']);
	$_SESSION['user'] = array_merge($_SESSION['user'], $new_data['body']['user']);

	// Gets the completed reservations that involves the current user
	$api_url = compose_url($protocol, $socket_park_ms, '/reservations/' . $_SESSION["user"]["id"] . '/completed');
	$reservations_completed = perform_rest_request('GET', $api_url, null, null);
	$reservation_as_driver = $reservation_as_resident = null;
	if ( $reservations_completed["status"] == 200 && $reservations_completed["body"]["code"] === "0" )
	{
		$reservation_as_driver = $reservations_completed["body"]["as_driver"];
		$reservation_as_resident = $reservations_completed["body"]["as_resident"];
	}

	// Gets the review that involves the current user
	$api_url = compose_url($protocol, $socket_account_ms, '/reviews');
	$response = perform_rest_request('GET', $api_url, null, $_SESSION['session_token']);
	$written_reviews = $received_reviews = null;
	if ( $response["status"] == 200 && $response["body"]["code"] === "0" )
	{
		$written_reviews = $response["body"]["written_reviews"];
		$received_reviews = $response["body"]["received_reviews"];
	}

	// We must allow the adding of a review only if the reservation has not already reviewed by us.
	// Computing the difference
	$reviewed_ids = array_column($written_reviews, "reservation_id");
	$rewiable_reservations = array();
	foreach( $reservation_as_driver as $res )
	{
		if ( in_array($res["reservation_id"], $reviewed_ids) )
			continue;
			
		array_push($rewiable_reservations, $res);	
	}
	foreach( $reservation_as_resident as $res )
	{
		if ( in_array($res["reservation_id"], $reviewed_ids) )
			continue;

		array_push($rewiable_reservations, $res);
	}

	// Populating the rewiable reservations section
	$rewiable_html = '';
	if ( count($rewiable_reservations) > 0 )
	{
		// Reading the template
		$li_template = file_get_contents('../html/li_reservation.html');

		foreach ( $rewiable_reservations as $res )
		{
			$li = str_replace("%ID%", $res["reservation_id"], $li_template);
			$li = str_replace("%SPOT_NAME%", $res["parking_spot"]["spot_name"], $li);
			$li = str_replace("%DATE%", $res["slot"]["slot_date"], $li);
			$li = str_replace("%START%", $res["slot"]["start_time"], $li);
			$li = str_replace("%END%", $res["slot"]["end_time"], $li);
			$li = str_replace("%PLATE%", $res["car_plate"], $li);
			
			if ( $res["role"] == 'resident')
			{
				$li = str_replace("%ROLE%", strtoupper('driver'), $li);

				// We show the name of the driver
				$li = str_replace("%NAME%", $res["driver_name"] . " " . $res["driver_surname"], $li);
			}
			else if ( $res["role"] == 'driver' )
			{
				$li = str_replace("%ROLE%", strtoupper('resident'), $li);

				// We show the name of the resident
				$li = str_replace("%NAME%", $res["parking_spot"]["owner_name"] . " " . $res["parking_spot"]["owner_surname"], $li);	
			}
			
			$rewiable_html .= $li . "\n";
		}
	}
	else
		$rewiable_html = '<p>No rewiable reservations are present!<p>';

	// Populating the received reviews
	$received_html = '';
	if ( count($received_reviews) > 0 )
	{
		// Reading the template
		$card_template = file_get_contents('../html/received_review.html');

		foreach ( $received_reviews as $res )
		{
			$card = str_replace("%NAME%", $res["other_side_name"] . " " . $res["other_side_surname"], $card_template);
			$card = str_replace("%ID%", $res["id"], $card);
			$card = str_replace("%STAR%", $res["star"], $card);
			$card = str_replace("%TEXT%", $res["review_description"], $card);
			$card = str_replace("%DATE%", $res["review_date"], $card);
			
			$received_html .= $card . "\n";
		}
	}
	else
		$received_html = '<p>You haven\'t received any review!<p>';

	// Get the stats of the current user
	$api_url = compose_url($protocol, $socket_park_ms, '/users/' . $_SESSION["user"]["id"] . '/statistics');
	$response_stats = perform_rest_request('GET', $api_url, null, null);
	$api_url = compose_url($protocol, $socket_payment_ms, '/payments/user/'. $_SESSION["user"]["id"] . '/earnings');
	$response_payments = perform_rest_request('GET', $api_url, null, null);

	$spot_counter 		= 'N.A.';
	$res_counter 		= 'N.A.';
	$booked_counter 	= 'N.A.';
	$active_counter 	= 'N.A.';
	$total_earnings		= 'N.A.';
	$list_transactions  = null;
	$color = null;
	$prefix = '';

	if ( $response_stats["status"] == 200 && $response_stats["body"]["code"] === "0" )
	{
		$spot_counter 		= $response_stats["body"]["statistics"]["spot_counter"];
		$res_counter 		= $response_stats["body"]["statistics"]["res_counter"];
		$booked_counter 	= $response_stats["body"]["statistics"]["booked_counter"];
		$active_counter 	= $response_stats["body"]["statistics"]["active_counter"];
	}

	if ( $response_payments["status"] == 200 && $response_payments["body"]["code"] === "0" )
	{
		$total_earnings = floatval($response_payments["body"]["earnings"]);
		$list_transactions = $response_payments["body"]["payments_list"];

		if ( $total_earnings > 0 )
		{
			$color = 'green';
			$prefix = '+';
		}
		else if ( $total_earnings < 0 )
			$color = 'red';
	}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>User Dashboard</title>
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

<body>
	<?php
		$nav = generate_navbar($_SESSION['role']);
		echo $nav;
	?>

	<main class="dashboard-grid">
		<!-- User Data Section -->
		<div class="dashboard-card user-data-card">
			<div class="user-header">
				<img src="../images/account.svg" alt="User Avatar" class="user-avatar">
				<div>
					<h2 class="user-name">
						<?php echo strtoupper($_SESSION['user']['name'] . ' ' . $_SESSION['user']['surname']); ?>
					</h2>
				</div>
			</div>

			<div class="user-info">
				<div class="info-item">
					<i class="fas fa-phone"></i>
					<span><strong>Name: </strong><?php echo $_SESSION['user']['name']; ?></span>
				</div>
				<div class="info-item">
					<i class="fas fa-phone"></i>
					<span><strong>Surname: </strong><?php echo $_SESSION['user']['surname']; ?></span>
				</div>
				<div class="info-item">
					<i class="fas fa-phone"></i>
					<span><strong>Email: </strong><?php echo $_SESSION['user']['email']; ?></span>
				</div>
				<div class="info-item">
					<i class="fas fa-phone"></i>
					<span><strong>Phone: </strong><?php echo $_SESSION['user']['phone']; ?></span>
				</div>
				<div class="info-item">
					<i class="fas fa-envelope"></i>
					<span><strong>Credit Card Number: </strong><?php echo $_SESSION['user']['cc_number']; ?></span>
				</div>
				<div class="info-item">
					<i class="fas "></i>
					<span><strong>Role: </strong><?php echo strtoupper($_SESSION['role']); ?></span>
				</div>
			</div>

			<button class="edit-btn" onclick="window.location.href='edit_profile.php'">
				<i class="fas fa-user-edit"></i> Edit Profile
			</button>
		</div>

		<!-- Statistics Section -->
		<div class="dashboard-card statistics-card">
			<div class="section-title">User's Statistics</div>
			<div class="stats-grid">
				<div class="stat-box">
					<div class="stat-value" id="reputation">⭐ <?php echo $_SESSION['user']['score']; ?>/5</div>
					<div class="stat-label">Reputation Level</div>
				</div>

				<div class="stat-box">
					<div class="stat-value" id="ownedSpots"><?php echo $spot_counter; ?></div>
					<div class="stat-label">Parking Spots Owned</div>
				</div>

				<div class="stat-box">
					<div class="stat-value" id="totalReservations"><?php echo $res_counter; ?></div>
					<div class="stat-label">Total Reservations</div>
				</div>

				<div class="stat-box">
					<div class="stat-value" id="activeReservations"><?php echo $active_counter; ?></div>
					<div class="stat-label">Active Reservations</div>
				</div>

				<div class="stat-box">
					<div class="stat-value" id="occupiedSpots"><?php echo $booked_counter; ?></div>
					<div class="stat-label">Owned Spots Currently Booked</div>
				</div>

				<button class="stat-box stat-button" onclick='openTransactionsPopup(<?php echo json_encode($list_transactions); ?>)'>
					<div class="stat-value" id="occupiedSpots" <?php if (isset($color)) echo "style=\"color:$color\"" ?>>
						<?php echo $prefix . $total_earnings; ?>
					</div>
					<div class="stat-label">Total Earnings</div>
				</button>
			</div>
		</div>

		<!-- Received Reviews -->
		<div class="dashboard-card review-card">
			<div class="section-title">Received Reviews</div>
			<div class="review-box">
				<?php echo $received_html; ?>
			</div>
		</div>

		<!-- Reservations Section -->
		<div class="dashboard-card reservations-card">
			<div class="section-title">Reviewable Reservations</div>

			<div class="reservation-list-container">
				<ul class="reservation-list">
					<?php echo $rewiable_html; ?>
				</ul>
			</div>
		</div>

		<!-- Review Popup -->
		<div id="reviewModal" class="modal-overlay">
			<div class="modal-backdrop" onclick="closeReviewPopup()"></div>

			<div class="modal-content">
				<button class="modal-close-btn" onclick="closeReviewPopup()">×</button>

				<h3 class="modal-title" id="reviewTitle">Leave a Review</h3>

				<p class="modal-message">Tell us about your xperience.</p>

				<!-- Rating Stars -->
				<div class="rating-stars"
					style="text-align:center; margin-bottom:15px; font-size:32px; cursor:pointer;">
					<span onclick="setRating(1)">★</span>
					<span onclick="setRating(2)">★</span>
					<span onclick="setRating(3)">★</span>
					<span onclick="setRating(4)">★</span>
					<span onclick="setRating(5)">★</span>
				</div>

				<!-- Textarea -->
				<textarea id="reviewText" placeholder="Write your opinion about this parking..."
					style="width:100%; height:120px; padding:10px; border-radius:8px; border:1px solid #ccc; margin-bottom:1rem; resize:none;"></textarea>

				<!-- Buttons -->
				<div class="modal-buttons">
					<button class="btn btn-cancel" onclick="closeReviewPopup()">Cancel</button>
					<button class="btn btn-submit-review" onclick="submitReview()">Submit</button>
				</div>
			</div>
		</div>

		<!-- Transactions Popup -->
<div id="transactionsModal" class="modal-overlay">
    <div class="modal-backdrop" onclick="closeTransactionsPopup()"></div>

    <div class="modal-content">
        <button class="modal-close-btn" onclick="closeTransactionsPopup()">×</button>

        <h3 class="modal-title">Transactions</h3>

        <div id="transactionsContainer" 
             style="max-height:350px; overflow-y:auto; margin-top:15px; padding-right:8px;">
            <!-- Filled by JS -->
        </div>
	</div>
</div>

	</main>
	
	<?php
		$footer = file_get_contents(FOOTER);
		echo $footer;
	?>

	<script src="../js/dashboard_popup.js"></script>
</body>
</html>