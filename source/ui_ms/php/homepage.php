<?php
    include_once 'config.php';

    // Small dispatcher script to get the right homepage for the user
    // based on the role in the session
    session_start();

    if ( isset($_SESSION['role']) )
    {
        if ( $_SESSION['role'] == 'admin' )
            header("Location: " . $homepage_admin);
        else if ( $_SESSION['role'] == 'user' )
            header("Location: " . $homepage_user);
        else
            header("Location: " . $starting_page);
    }
    else
        header("Location: " . $starting_page);
?>