<?php

// TODO: Extract $_POST variables, check they're OK, and attempt to create
// an account. Notify user of success/failure and redirect/give navigation 
// options.


require_once("config.php");
include_once("browse.php");

  $username = $_POST['username'];
  $password = $_POST['password'];
  $email = $_POST['email'];
  $query = "INSERT INTO users (UserName, UserPassword, UserEmail) VALUES ('$username', '$password', '$email')";
  if (!$conn->multi_query($query)) {
    echo 'Failed to connect to the MySQLserver: '. mysqli_connect_error();
  } else {
    header("Location: browse.php");
    mysqli_close($conn);

    //exit();
  }


?>


