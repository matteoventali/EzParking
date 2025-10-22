<?php
    require_once './config.php';
    require_once './functions.php';
    
    // Verifying the session status and performing the logout only if
    // there is an active session alive
    if ( verify_session() )
    {
        // Script to perform a logout
        $api_url = compose_url($protocol, $socket_account_ms, '/auth/logout');
        $response = perform_rest_request('GET', $api_url, null, $_SESSION["session_token"]);
        session_destroy();
    }
        
    // Redirect the user to the first page of the site
    header("Location: " . $starting_page);
?>
