<!-- connection to database -->
<?php

//start the session
session_start();

//connect to database
$db_server = "localhost";
$db_username = "root";
$db_password = "root";
$db_name = "Auction"; 

//create connection to database
$conn = mysqli_connect($db_server, $db_username, $db_password, $db_name);
$conn->set_charset("utf8");

//check connection
if (mysqli_connect_errno()) {
    echo 'Failed to connect to the MySQL server: '. mysqli_connect_error();
}

?>