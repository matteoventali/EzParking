<?php
    require_once './config.php';
    require_once './functions.php';
    
    // Script responsible to check the status of the microservices
    header('Content-Type: application/json');
    $status = check_status_microservices();
    echo json_encode($status);
?>