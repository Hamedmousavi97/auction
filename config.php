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

// Read and execute the SQL script
// Using the multi_query() function allows us to execute multiple SQL statements at once
// $sqlScript = file_get_contents('Auction.sql');
// if (!$conn->multi_query($sqlScript)) {
//     echo "Error executing SQL script: " . $conn->error;
// }
?>