<?php
session_start(); // start the session

if(!isset($_SESSION['user']['last_active'])) {
    $_SESSION['user']['last_active'] = time(); // initialize the last active time
}

if (time() - $_SESSION['user']['last_active'] > 300) {
    // if the user has been inactive for more than 5 minutes, set the visitor count to 0
    $_SESSION['user']['visitors'] = 0;
} else {
    // otherwise, increment the visitor count
    if(!isset($_SESSION['user']['visitors'])) {
        $_SESSION['user']['visitors'] = 0; // initialize the visitor count to 0
    }

    $_SESSION['user']['visitors']++; // increment the visitor count
}

$_SESSION['user']['last_active'] = time(); // update the last active time