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

    // Checking if we have the data to perform the search
    if ( !isset($_GET['lat']) || !isset($_GET['lon']) )
    {
        header('Content-Type: application/json');
        echo json_encode( array( "error" => "Missing parameters" ) );
        exit();    
    }

    // Gets the user score reputation from the account_ms
    $api_url = compose_url($protocol, $socket_account_ms, '/pdata');
    $response = perform_rest_request('GET', $api_url, null, $_SESSION['session_token']);
    $reputation = $response['status'] == 200 ? $response['body']['user']['score'] : -1;

    if ( $reputation < 0 )
    {
        header('Content-Type: application/json');
        echo json_encode( array( "error" => "Unable to retrieve user reputation" ) );
        exit();    
    }
    
    // Prepare data for the API request
    $data = [
        'latitude' => $_GET['lat'],
        'longitude' => $_GET['lon'],
        'user_reputation' => $reputation
    ];
    
    $api_url = compose_url($protocol, $socket_park_ms, '/search');
    $response = perform_rest_request('POST', $api_url, $data, null);
    
    // Script responsible to check the status of the microservices
    header('Content-Type: application/json');
    echo json_encode($response);
?>