<?php
    // process the log out and terminate the sessions 
    // start the session
    session_start();

    // terminate sessions
    unset($_SESSION['logged_in']);
    unset($_SESSION['account_type']);

    // distroy the cookies
    setcookie(session_name(), "", time() - 360);

    // destroy the session
    session_destroy();

    // Redirect to index
    header("Location: index.php");

?>