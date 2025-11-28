<?php
    require_once "./config.php";
    require_once "./functions.php";

    // We must be logged in to access this page
    if ( !verify_session() )
        header("Location: " . $starting_page);
    else if ( $_SESSION['role'] != 'admin') // We must be admin to access this page
        header("Location: user_dashboard.php");
    else if ( !isset($_GET['id']) || !is_numeric($_GET['id']) ) // We must have access to the user id to perform the loading of data
        header("Location: manage_user.php" );

    // Loading the user data trough a REST request
    $api_url = compose_url($protocol, $socket_account_ms, '/users/' . $_GET['id']);
    $response = perform_rest_request('GET', $api_url, null, $_SESSION['session_token']);
    $user = $response['status'] == 200 ? $response['body']['user'] : null;

    if ( $user == null )
    {
        // Return to the manage user page
        header("Location: manage_user.php");
        exit();
    }
        
    // Loading the reviews received and written by the user
    $api_url = compose_url($protocol, $socket_account_ms, '/reviews/' . $_GET["id"]);
    $response_review = perform_rest_request('GET', $api_url, null, $_SESSION["session_token"]);

    // If the user is an admin, we don't need to load the reviews
    if ( $user["role"] != "admin" )
    {
        // Populating the received reviews
        $received_html = ''; $written_html = '';
        $name = $user["name"]; $surname = $user["surname"];

        if ( $response_review["status"] == 200 && $response_review["body"]["code"] === "0" )
        {
            $received_reviews = $response_review["body"]["received_reviews"];
            $written_reviews = $response_review["body"]["written_reviews"];
            
            // Reading the template
            $card_template = file_get_contents('../html/received_review.html');
            
            if ( count($response_review["body"]["received_reviews"]) > 0 )
            {
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
                $received_html = "<p style=text-align: center;'> $name $surname has not received any review!<p>";

            if ( count($response_review["body"]["written_reviews"]) > 0 )
            {
                foreach ( $written_reviews as $res )
                {
                    $card = str_replace("%NAME%", $res["other_side_name"] . " " . $res["other_side_surname"], $card_template);
                    $card = str_replace("%ID%", $res["id"], $card);
                    $card = str_replace("%STAR%", $res["star"], $card);
                    $card = str_replace("%TEXT%", $res["review_description"], $card);
                    $card = str_replace("%DATE%", $res["review_date"], $card);
                    
                    $written_html .= $card . "\n";
                }
            }
            else
                $written_html = "<p style=text-align: center;'> $name $surname has not written any review!<p>";
        }
        else
        {
            $received_html = "<p style=text-align: center;'> $name $surname has not received any review!<p>";
            $written_html = "<p style=text-align: center;'> $name $surname has not written any review!<p>";
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <link rel="stylesheet" href="../css/homepage.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/style.css">
    
    <link rel="stylesheet" href="../css/navbar.css">
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

        <main class="dashboard-grid">

        <div class="dashboard-card user-data-card">
            <div class="user-header">
            <div class="avatar-wrapper">
                <img src="../images/account.svg" alt="User Avatar" class="user-avatar">
                
            </div>
            <div>
                <div class="user-name-wrapper">
                    <h2 class="user-name"><?php echo strtoupper($user["name"] . " " . $user["surname"]); ?></h2>
                </div>
                
            </div>
        </div>

        <div class="user-info">
            <div class="info-item">
                <i class="fas fa-phone"></i>
                <span><strong>Name: </strong><?php echo $user["name"];?></span>
            </div>
            <div class="info-item">
                <i class="fas fa-phone"></i>
                <span><strong>Surname: </strong><?php echo $user["surname"];?></span>
            </div>
            <div class="info-item">
                <i class="fas fa-phone"></i>
                <span><strong>Email: </strong><?php echo $user["email"];?></span>
            </div>
            <div class="info-item">
                <i class="fas fa-phone"></i>
                <span><strong>Phone: </strong><?php echo $user["phone"];?></span>
            </div>
            <div class="info-item">
                <i class="fas"></i>
                <span><strong>Role: </strong><?php echo strtoupper($user['role']); ?></span>
            </div>
        </div>

        <?php
            $label = $user['status'] ? 'Disable' : 'Enable';
            $color = $user['status'] ? 'red' : 'green';

            // We show the enable/disable button only if we're seeing a user profile.
            // An admin cannot be disabled/enabled by another admin
            $button = '<button id="button_enable" class="edit-btn" style="background: %s"
                            onclick="location.href=\'enable_disable_user.php?id=%d&status=%s\'">
                            <i class="fas fa-user-edit"></i> ' . $label . ' Profile
                        </button>';
            $button = sprintf($button, $color, $user['id'], $user['status'] ? 'true' : 'false');
            
            if ( $user['role'] == 'user' )
                echo $button;
        ?>
    </div>

    <?php
        $review_section =
            '<!-- Reviews -->
            <div class="dashboard-card review-card">
                <div class="section-title">Received Reviews</div>
                    <div class="review-box">
                        %s
                    </div>          				      
                </div>
            </div>
            <div class="dashboard-card review-card">
                <div class="section-title">Submitted Reviews</div>
                    <div class="review-box">
                        %s
                    </div>          				      
                </div>
            </div>';

        $stats_section =
            '<div class="dashboard-card statistics-card">
            <div class="section-title">User\'s Statistics</div>
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="stat-value" id="reputation">⭐ %s/5</div>
                    <div class="stat-label">Reputation Level</div>
                </div>

                <div class="stat-box">
                    <div class="stat-value" id="ownedSpots">%s</div>
                    <div class="stat-label">Parking Spots Owned</div>
                </div>

                <div class="stat-box">
                    <div class="stat-value" id="totalReservations">%s</div>
                    <div class="stat-label">Total Reservations</div>
                </div>

                <div class="stat-box">
                    <div class="stat-value" id="activeReservations">%s</div>
                    <div class="stat-label">Active Reservations</div>
                </div>

                <div class="stat-box">
                    <div class="stat-value" id="occupiedSpots">%s</div>
                    <div class="stat-label">Owned Spots Currently Booked</div>
                </div>

                <button class="stat-box stat-button">
                    <div class="stat-value" id="occupiedSpots">%s€</div>
                    <div class="stat-label">Total Earnings</div>
                </button>
            </div>
            </div>';

        // Show the reputation and review section only for normal users
        if ( $user['role'] == 'user' )
        {
            echo $stats_section;
            echo sprintf($review_section, $received_html, $written_html);
        }
    ?>
		
    </main>
    <?php
        $footer = file_get_contents(FOOTER);
        echo $footer;
    ?>
</body>
</html>
