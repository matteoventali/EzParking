<?php
    # Include constants
    define('HTML', __DIR__.'/../html');
    define('NAVBAR_USER', HTML.'/navbar_user.html');
    define('NAVBAR_ADMIN', HTML.'/navbar_admin.html');
    define('NAVBAR_GUEST', HTML.'/navbar_guest.html');
    define('FOOTER', HTML.'/footer.html');

    # Microservices parameters
    $protocol = 'http';
    $socket_account_ms = '10.5.0.11:5000';
    $socket_notification_ms ='10.5.0.11:5001';
    $socket_park_ms = '10.5.0.11:5002';
    $socket_payment_ms = '10.5.0.11:5003';
    $socket_dbms = '10.5.0.10:3306';
?>
