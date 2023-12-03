<?php

// make sure session has started
session_start(); 

if (!isset($_POST['functionname']) || !isset($_POST['arguments'])) {
  return;
}

// assume you have a user logged in and their ID is stored in a session variable
$username = $_SESSION['username'];
// Extract arguments from the POST variables:
$auctionID = $_POST['arguments'][0];
// database config
require_once("config.php"); 
// $conn = mysqli_connect($db_server, $db_username, $db_password, $db_name);

// if (!$conn) {
//   die("Connection failed: " . mysqli_connect_error());
// }

if ($_POST['functionname'] == "add_to_watchlist") {
  // Update database and return success/failure.
  $query = "INSERT INTO watchlist (username, auctionID) VALUES ('$username', '$auctionID')";
  $stmt = $conn->prepare($query);

  if ($stmt->execute()) {
    $res = "success";
  } else {
    $res = "error";
  }
}

else if ($_POST['functionname'] == "remove_from_watchlist") {
  // Update database and return success/failure.
  $query = "DELETE FROM watchlist WHERE username = ? AND auctionID = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ii", $username, $auctionID);

  if ($stmt->execute()) {
    $res = "success";
  } else {
    $res = "error". $stmt->error;
  }
}


// Note: Echoing from this PHP function will return the value as a string.
// If multiple echo's in this file exist, they will concatenate together,
// so be careful. You can also return JSON objects (in string form) using
// echo json_encode($res).
//echo $res;

$stmt->close();
$conn->close();

echo json_encode($res);
?>

