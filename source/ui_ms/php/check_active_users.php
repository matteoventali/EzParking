<?php
    require_once './config.php';
    require_once './functions.php';

    // Checking if the user is logged and is an admin
    if ( !verify_session() || $_SESSION['role'] != 'admin')
    {
        header('Content-Type: application/json');
        echo json_encode( array( "error" => "Unauthorized access" ) );
        exit();    
    }
    
    $api_url = compose_url($protocol, $socket_account_ms, '/users/active_count');
    $response = perform_rest_request('GET', $api_url, null, $_SESSION['session_token']);
    
    // Script responsible to check the status of the microservices
    header('Content-Type: application/json');
    echo json_encode($response["body"]["active_user_count"]);
?>