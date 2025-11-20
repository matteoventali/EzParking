<?php
    require_once './config.php';
    require_once './functions.php';

    // Checking if the user is logged
    if ( !verify_session() )
    {
        header('Content-Type: application/json');
        echo json_encode( array( "error" => "Unauthorized access" ) );
        exit();    
    }

    // Checking if we have the data to perform the fetch operation
    if ( !isset($_GET['id']) )
    {
        header('Content-Type: application/json');
        echo json_encode( array( "error" => "Missing parameters" ) );
        exit();    
    }

    // Gets the user info from the account_ms
    $api_url = compose_url($protocol, $socket_account_ms, '/users/' . intval($_GET['id']));
    $response = perform_rest_request('GET', $api_url, null, $_SESSION['session_token']);
    
    // Sending the response to the client
    header('Content-Type: application/json');
    echo json_encode($response);
?>