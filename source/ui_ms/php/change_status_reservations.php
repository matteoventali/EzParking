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
    $response = change_status_reservation($_POST["reservation_id"], $_POST["new_status"]);
    
    // Sending the response
    header('Content-Type: application/json');
    echo json_encode($response);
?>