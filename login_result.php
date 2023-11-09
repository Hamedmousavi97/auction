<?php

// TODO: Extract $_POST variables, check they're OK, and attempt to login.
// Notify user of success/failure and redirect/give navigation options.

// For now, I will just set session variables and redirect.

//Getting the post variables
$email = $_POST['email'];
$password = $_POST['password'];

// Hash the password
$hashedPassword = hash('sha256', $password);

$query = "SELECT * FROM users WHERE UserEmail = '$email' AND UserPassword = '$hashedPassword'";
$data = mysqli_query($conn, $query);

if (mysqli_num_rows($data) == 1) {
    $row = mysqli_fetch_array($data);
    setcookie("account_type", $row['UserRole']);
    setcookie("username", $row['UserName']);
    header("Location: header.php");
  exit();
} else {
    echo 'invalid email or password';
}


session_start();
$_SESSION['logged_in'] = true;
$_SESSION['username'] = "test";
$_SESSION['account_type'] = "buyer";

//echo('<div class="text-center">You are now logged in! You will be redirected shortly.</div>');

// Redirect to index after 5 seconds
//header("refresh:5;url=index.php");

?>