<?php
    require_once "./config.php";
    require_once "./functions.php";

    $result = '';

    // We must be logged in to access this page
    if (!verify_session())
        $result = json_encode( array( "error" => "Unauthorized access" ) );
    else if ($_SESSION['role'] != 'user')
        $result = json_encode( array( "error" => "Unauthorized access" ) );

    if ( !isset($_POST['slot_id']) )
    {
        header('Content-Type: application/json');
        echo json_encode( array( "error" => "Missing parameters" ) );
        exit();    
    }

    $api_url = compose_url($protocol, $socket_park_ms, '/time_slots/info/' . $_POST["slot_id"]);
    $response = perform_rest_request('GET', $api_url, null, null);

    if ( $response["status"] == 200 && $response["body"]["code"] === "0" )
    {
        // Check if the owner of the slot is the same who has triggered the request
        if ( $_SESSION["user"]["id"] === $response["body"]["availability_slot"]["parking_spot_owner_id"] )
        {
            // Perform the delete request
            $api_url = compose_url($protocol, $socket_park_ms, '/time_slots/' . $_POST["slot_id"]);
            $response = perform_rest_request('DELETE', $api_url, null, null);
            $result = json_encode($response);
        }
        else
            $result = json_encode( array( "error" => "Unauthorized access" ) );
    }
    else
        $result = json_encode( array( "error" => "Unauthorized access" ) );

    echo $result;
?>