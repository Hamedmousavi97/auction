<?php

//start the session
session_start();

//connect to database
require_once("config.php");
require_once("browse.php");

//Getting the post variables
$email = $_POST['email'];
$password = $_POST['password'];

// Hash the password
$hashedPassword = hash('sha256', $password);

// Check if the email and password are valid
$query = "SELECT * FROM users WHERE UserEmail = '$email' AND UserPassword = '$hashedPassword'";

// Execute the query
$data = mysqli_query($conn, $query);

// Check if the query returns a result
if (mysqli_num_rows($data) == 1) {

    // Fetch the result as an associative array
    $row = mysqli_fetch_array($data);

    // Set session variables
    setcookie("account_type", $row['UserRole']);
    setcookie("username", $row['UserName']);
    setcookie("userID", $row['UserID']);

    // set session variables
    $_SESSION['logged_in'] = true;
    $_SESSION['username'] = $row['UserName'];
    $_SESSION['account_type'] = $row['UserRole'];
    
    // Redirect to browse.php
    header("Location: browse.php");
    
    // Close the connection
    exit();
 } else {

    // fail attempt to login throw error
    echo '<script>
            alert("invalid email or password please try again.");
            window.history.back();
        </script>';

    // Close the connection    
    exit();
}





//echo('<div class="text-center">You are now logged in! You will be redirected shortly.</div>');

// Redirect to index after 5 seconds
//header("refresh:5;url=index.php");

?>