<?php

session_start();

include("config.php");

// TODO: Extract $_POST variables, check they're OK, and attempt to login.
// Notify user of success/failure and redirect/give navigation options.

// For now, I will just set session variables and redirect.

//Getting the post variables
$email = $_POST['UserEmail'];
$password = $_POST['UserPassword'];

// Hash the password
$hashedPassword = hash('sha256', $password);

$query = "SELECT * FROM users WHERE UserEmail = '$email' AND UserPassword = '$hashedPassword'";
$data = mysqli_query($conn, $query);

if (mysqli_num_rows($data) == 1) {
    $row = mysqli_fetch_array($data);

    // Check account type and set session variables accordingly
    if ($row['account_type'] == 'buyer') {
      $_SESSION['logged_in'] = true;
      $_SESSION['username'] = $row['username'];
      $_SESSION['account_type'] = $row['account_type'];
      setcookie("account_type", $row['UserRole']);
      setcookie("username", $row['UserName']);
      header("Location: header.php");
      exit();
  } else {
      echo 'invalid account type';
}
} else {
      echo 'invalid email or password';
}

?>