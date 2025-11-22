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

    // Checking if we have the data
    if ( !isset($_POST['reservation_id']) || !isset($_POST["new_status"]) )
    {
        header('Content-Type: application/json');
        echo json_encode( array( "error" => "Missing parameters" ) );
        exit();    
    }

    // Perform the status change
    $payload = [
        "user_id" => $_SESSION["user"]["id"],
        "new_status" => $_POST["new_status"]
    ];
    $api_url = compose_url($protocol, $socket_park_ms, '/reservations/' . $_POST["reservation_id"]. "/status");
    $response = perform_rest_request('PUT', $api_url, $payload, null);

    // Sending the response
    header('Content-Type: application/json');
    echo json_encode($response);
?>