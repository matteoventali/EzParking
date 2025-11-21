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

		<!-- TO BE REMOVED!!! -->
		<!-- Reputation Section -->
		<div class="dashboard-card reputation-card">
			<div class="section-title">Reputation</div>
			<div class="reputation-score">⭐ <?php echo $_SESSION['user']['score']; ?>/5</div>
			<p style="margin-top: 0.5rem; color:#666; font-size:0.95rem;">
				Keep contributing to improve your score!
			</p>
		</div>

		<div class="dashboard-card review-card">
			<div class="section-title">Reviews</div>
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


		<!-- Reservations Section -->
		<div class="dashboard-card reservations-card">
			<div class="section-title">Your Parking Reservations</div>

			<div class="reservation-list-container">
				<ul class="reservation-list">
					<li onclick="openReviewPopup(1, 'Parking Lot A')">
						<strong>Parking Lot A</strong><br>
						<small>Date: 2025-10-18 | Time: 09:00 | Cost: €5 | Status: Confirmed</small>
					</li>
					<li onclick="openReviewPopup(1, 'Parking Garage B')">
						<strong>Parking Garage B</strong><br>
						<small>Date: 2025-10-22 | Time: 11:00 | Cost: €7 | Status: Pending</small>
					</li>
					<li onclick="window.location.href='reservation_details.php?id=3'">
						<strong>Open Lot C</strong><br>
						<small>Date: 2025-10-29 | Time: 15:30 | Cost: €4 | Status: Cancelled</small>
					</li>
					<li onclick="window.location.href='reservation_details.php?id=4'">
						<strong>Central Parking</strong><br>
						<small>Date: 2025-11-03 | Time: 08:30 | Cost: €6 | Status: Confirmed</small>
					</li>
					<li onclick="window.location.href='reservation_details.php?id=5'">
						<strong>Underground Lot D</strong><br>
						<small>Date: 2025-11-12 | Time: 17:00 | Cost: €5 | Status: Pending</small>
					</li>
				</ul>
			</div>
		</div>

		<!-- Review Popup -->
		<div id="reviewModal" class="modal-overlay">
			<div class="modal-backdrop" onclick="closeReviewPopup()"></div>

			<div class="modal-content">
				<button class="modal-close-btn" onclick="closeReviewPopup()">×</button>

				<h3 class="modal-title" id="reviewTitle">Leave a Review</h3>

				<p class="modal-message">Tell us about your parking experience.</p>

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

	</main>
	<?php
	$footer = file_get_contents(FOOTER);
	echo $footer;
	?>
</body>
<script>
	let selectedRating = 0;
	let currentReservationId = null;

	function openReviewPopup(id, name) {
		currentReservationId = id;
		document.getElementById("reviewTitle").innerText = "Review for " + name;
		document.getElementById("reviewModal").classList.add("active");
		document.querySelector(".page-content")?.classList.add("blurred");
	}

	function closeReviewPopup() {
		document.getElementById("reviewModal").classList.remove("active");
		document.querySelector(".page-content")?.classList.remove("blurred");
		clearReviewPopup();
	}

	function clearReviewPopup() {
		selectedRating = 0;
		highlightStars();
		document.getElementById("reviewText").value = "";
	}

	function setRating(value) {
		selectedRating = value;
		highlightStars();
	}

	function highlightStars() {
		const stars = document.querySelectorAll(".rating-stars span");
		stars.forEach((star, index) => {
			star.style.color = (index < selectedRating) ? "#fbbf24" : "#d1d5db";
		});
	}

	function submitReview() {
		const review = document.getElementById("reviewText").value;

		if (selectedRating === 0) {
			alert("Please select a rating.");
			return;
		}

		if (review.trim() === "") {
			alert("Please write a review.");
			return;
		}
		// Here you would typically send the review data to the server via AJAX
		// For this example, we'll just log it to the console and close the popup
		// Example AJAX call (uncomment and modify as needed):
		/*
		fetch('submit_review.php', {							
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify({
				reservation_id: currentReservationId,
				rating: selectedRating,
				review_text: review
			})
		})*/
		closeReviewPopup();
		alert("Review submitted successfully!");
	}
</script>


</html>