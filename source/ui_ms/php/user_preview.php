<?php
	require_once "./config.php";
	require_once "./functions.php";

	// We must be logged in to access this page
	if (!verify_session())
		header("Location: " . $starting_page);
	else if ($_SESSION['role'] != 'user') // We must be normal user to access this page
		header("Location: admin_dashboard.php");

	// Checking if we have the data to perform the fetch operation
    if ( !isset($_GET['user_id']) || !isset($_GET['reservation_id']) || !is_numeric($_GET["user_id"])
		|| !is_numeric($_GET["reservation_id"]) )
    	header("Location: homepage.php");
    
	// Gets the user info from the account_ms
    $api_url = compose_url($protocol, $socket_account_ms, '/users/' . intval($_GET['user_id']));
    $response_user = perform_rest_request('GET', $api_url, null, $_SESSION['session_token']);

	// Gets the reservation info from the park_ms
	$api_url = compose_url($protocol, $socket_park_ms, '/reservations/' . intval($_GET['reservation_id']));
    $response_reservation = perform_rest_request('GET', $api_url, null, null);

	// Checking the consistence between the user requested and the owner of the reservation
	// It the match doesn't occur redirecting the user to the homepage
	if (!( $response_user["status"] == 200 && $response_reservation["status"] == 200 && $response_reservation["body"]["code"] === "0" 
			&& $response_user["body"]["code"] === "0" && $response_reservation["body"]["reservation"]["driver_id"] === $response_user["body"]["user"]["id"]))
	{
		header("Location: homepage.php");
		exit();
	}

	// Extracting the informations
	$name = $response_user["body"]["user"]["name"];
	$surname = $response_user["body"]["user"]["surname"];
	$email = $response_user["body"]["user"]["email"];
	$phone = $response_user["body"]["user"]["phone"];
	$score = $response_user["body"]["user"]["score"];
	$received_reviews = $response_user["body"]["received_reviews"];
	$parking_name = $response_reservation["body"]["reservation"]["parking_spot_name"];
	$latitude = $response_reservation["body"]["reservation"]["parking_spot_latitude"];
	$longitude = $response_reservation["body"]["reservation"]["parking_spot_longitude"];
	$cost_h = $response_reservation["body"]["reservation"]["cost"];
	$date = $response_reservation["body"]["reservation"]["slot_date"];
	$start = $response_reservation["body"]["reservation"]["start_time"];
	$end = $response_reservation["body"]["reservation"]["end_time"];
	
	// Extracting the address from coordinates
	$address = get_address_from_coordinates(floatval($latitude), floatval($longitude));

	// Calculating the total cost
	$cost = $cost_h * calculate_duration($start, $end);

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
		$received_html = "<p>$name $surname has not received any review!<p>";
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
						<?php echo strtoupper($name . " " . $surname); ?>
					</h2>
				</div>
			</div>

			<div class="user-info">
				<div class="info-item">
					<i class="fas fa-phone"></i>
					<span><strong>Email: </strong><?php echo $email; ?></span>
				</div>
				<div class="info-item">
					<i class="fas fa-phone"></i>
					<span><strong>Phone: </strong><?php echo $phone; ?></span>
				</div>
			</div>
		</div>

		<!-- Statistics Section -->
		<div class="dashboard-card statistics-card">
			<div class="section-title">User's Statistics</div>
			<div class="stats-grid">
				<div class="stat-box">
					<div class="stat-value" id="reputation">⭐ <?php echo $score; ?>/5</div>
					<div class="stat-label">Reputation Level</div>
				</div>

				<div class="stat-box">
					<div class="stat-value" id="totalReservations">TO BE FILLED</div>
					<div class="stat-label">Total Reservations</div>
				</div>
			</div>
		</div>

		<!-- Received Reviews -->
		<div class="dashboard-card review-card">
			<div class="section-title">Received Reviews</div>
			<div class="review-box">
                <?php echo $received_html; ?>	
			</div>
		</div>

		<div class="dashboard-card">
            <div class="section-title">Booking Request Details</div>
            <div>
                <h1 class="request-info"><?php echo $parking_name; ?></h1>
                <div class="request-column">
                    <div>
                        <p><strong>Location: </strong><?php echo $address; ?></p>
                    </div>
                    <div>
                        <p><strong>Date: </strong><?php echo $date; ?></p>
                    </div>
                    <div>
                        <p><strong>Slot: </strong><?php echo $start . " - " . $end; ?></p>
                    </div>
                    <div>
                        <p><strong>Total cost: </strong><?php echo $cost; ?> €</p>
                    </div>
					<button class="edit-btn" onclick="window.location.href='../php/manage_request.php'">
						<i class="fas fa-user-edit"></i> Manage it!
					</button>
				</div>                
            </div>
        </div>
	</main>
	
	<?php
		$footer = file_get_contents(FOOTER);
		echo $footer;
	?>
</body>
</html>