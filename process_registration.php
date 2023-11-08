<?php

// TODO: Extract $_POST variables, check they're OK, and attempt to create
// an account. Notify user of success/failure and redirect/give navigation
// options.


require_once("config.php");
include_once("browse.php");

  $username = $_POST['username'];
  $password = $_POST['password'];
  $email = $_POST['email'];
  $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// email registration - checking the email format 
function sanitizeEmail($email) {
  // remove the space in front or behind the email
  $email = trim($email);

  // remove illegal characters
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);

  // verify email format
  if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return $email;
  } else {
    return false; // verify fails, return false
  }
}

$sanitizedEmail = sanitizeEmail($email);

if ($sanitizedEmail) {
  // Verify success, do something
  $query = "INSERT INTO users (UserName, UserPassword, UserEmail) VALUES ('$username', '$hashedPassword', '$email')";
  if (!$conn->multi_query($query)) {
    echo 'Failed to connect to the MySQLserver: '. mysqli_connect_error();
  } else {
    header("Location: browse.php");
    mysqli_close($conn);

  }
} else {
  // Verify fails, do something
  echo "Invalid email. Please enter a valid email address.";
}

?>
