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
    if ( $_GET['status'] == 'true' )
        $api_url = compose_url($protocol, $socket_account_ms, '/users/' . $_GET['id'] . '/disable');
    else
        $api_url = compose_url($protocol, $socket_account_ms, '/users/' . $_GET['id'] . '/enable');

    $response = perform_rest_request('GET', $api_url, null, $_SESSION['session_token']);

    // Redirecting to the user details page
    header("Location: user_details.php?id=" . $_GET['id']);
?>