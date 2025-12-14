<?php
    require_once "./config.php";
    require_once "./functions.php";

    // We must be logged in to access this page
    if ( !verify_session() )
        header("Location: " . $starting_page);
    else if ( $_SESSION['role'] != 'admin') // We must be admin to access this page
        header("Location: user_dashboard.php");
    else if ( !isset($_GET['status']) || !is_numeric($_GET['id']) ) // We need the current status and the user id
        header("Location: manage_user.php" );
    
    // Performing the enable/disable operation trough a REST request
    $payload_notification = [
        "user_id" => $_GET['id']
    ];

    if ( $_GET['status'] == 'true' )
    {
        $api_url = compose_url($protocol, $socket_account_ms, '/users/' . $_GET['id'] . '/disable');
        $api_url_notification = compose_url($protocol, $socket_notification_ms, '/notifications/account_disabled');
    }
    else
    {
        $api_url = compose_url($protocol, $socket_account_ms, '/users/' . $_GET['id'] . '/enable');
        $api_url_notification = compose_url($protocol, $socket_notification_ms, '/notifications/account_enabled');
    }
    
    $response = perform_rest_request('GET', $api_url, null, $_SESSION['session_token']);

    // Send a notification to the user
    if ( $response["status"] == 200 && $response["body"]["code"] === "0")
        $response_not = perform_rest_request('POST', $api_url_notification, $payload_notification, null);

    // Redirecting to the user details page
    header("Location: manage_user.php");
?>