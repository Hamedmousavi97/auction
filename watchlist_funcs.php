<?php

  //watchlist functionlality page
  // make sure session has started
  session_start(); 

  // get the relevant function from the post 
  if (!isset($_POST['functionname']) || !isset($_POST['arguments'])) {
    return;
  }

  // assume you have a user logged in and their username is stored in a session variable
  $username = $_SESSION['username'];

  // Extract arguments from the POST variables:
  $auctionID = $_POST['arguments'][0];

  // database config
  require_once("config.php"); 

  // add to watchlist function.
  if ($_POST['functionname'] == "add_to_watchlist") {

    // Update database and return success/failure. Insert into the watchlist table.
    $query = "INSERT INTO watchlist (username, auctionID) VALUES ('$username', '$auctionID')";
    $stmt = $conn->prepare($query);

    // check for the success statuse of the query. 
    if ($stmt->execute()) {
      $res = "success";
    } else {
      $res = "error";
    }
  } else if ($_POST['functionname'] == "remove_from_watchlist") {

    // remove the auction from the watchlist function. 

    // Update database and return success/failure.
    $query = "DELETE FROM watchlist WHERE username = ? AND auctionID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $username, $auctionID);

    //if the query is successful show the message otherwise throw the error. 
    if ($stmt->execute()) {
      $res = "success";
    } else {
      $res = "error". $stmt->error;
    }
  }

  // close the connection
  $stmt->close();
  $conn->close();
?>

