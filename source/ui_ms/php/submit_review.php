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
    if ( !isset($_POST['reservation_id']) || !isset($_POST["rating"]) || !isset($_POST["review"]))
    {
        header('Content-Type: application/json');
        echo json_encode( array( "error" => "Missing parameters" ) );
        exit();    
    }

    // Request to the microservice to get details of the current reservation
    // in order to dispatch the target and the source of the review
    $api_url = compose_url($protocol, $socket_park_ms, '/reservations/' . $_POST["reservation_id"]);
    $response = perform_rest_request('GET', $api_url, null, null);

    if ( $response["status"] == 200 && $response["body"]["code"] === "0")
    {
        $target_id = null;
        
        if ( $response["body"]["reservation"]["resident_id"] === $_SESSION["user"]["id"] )
        {
            // Here we are the resident so the target is the driver
            $target_id = $response["body"]["reservation"]["driver_id"];
        }
        else if ( $response["body"]["reservation"]["driver_id"] === $_SESSION["user"]["id"] )
        {
            // Here we are the driver so the target is the resident
            $target_id = $response["body"]["reservation"]["resident_id"];
        }
        else
            $response = "Flow unexpected";


        // If the target id is determined we submit the review
        if ( $target_id )
        {
            // Preparing the data
            $payload = [
                'reservation_id' => $_POST["reservation_id"],
                'target_id' => $target_id,
                'star' => intval($_POST["rating"]),
                'review_description' => $_POST["review"]
            ];
            
            // Perform the submit
            $api_url = compose_url($protocol, $socket_account_ms, '/reviews');
            $response = perform_rest_request('POST', $api_url, $payload, $_SESSION["session_token"]);
        }
    }
    else
        $response = 'Reservation not found';
    
    // Sending the response
    header('Content-Type: application/json');
    echo json_encode($response);
?>