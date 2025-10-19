<?php

require './config.php';

function generate_navbar($role){

    switch($role){
        case 'admin':
            $navbar = file_get_contents(NAVBAR_ADMIN);
            break;
        case 'user':
            $navbar = file_get_contents(NAVBAR_USER);
            break;
        default:
            $navbar = file_get_contents(NAVBAR_GUEST);
    }

return $navbar;
}
?>
