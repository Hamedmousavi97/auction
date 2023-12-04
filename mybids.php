<!-- this page is for users to manage their bid and view their history -->

<!-- get the required files and details. -->
<?php include_once("header.php")?>
<?php include_once("config.php")?>
<?php require("utilities.php")?>


<div class="container">

<!-- display the title-->
<h2 class="my-3">My bids</h2>

<?php

  //  Check user's credentials (cookie/session).
  if (!isset($_SESSION['username'])) {

    // redirect user to browse page if they are not logged in
    header('Location: browse.php');
    exit();
  }

  // Create a connection to the database
  $conn->set_charset("utf8");

  // set the username variable
  $username = $_SESSION['username'];

  // Perform a query to pull up the auctions they've bidded on. merging 2 tables of uctions 
  // and bidreports as they contain the full report 
  $sql = "SELECT * FROM auctions
    JOIN bidreport ON auctions.BidID = bidreport.bidid
    WHERE bidreport.bidUsername = ?";

  // execute the query 
  $stmt = mysqli_prepare($conn, $sql);

  // check if the query executed
  if ($stmt === false) {

    // unsuccessful query 
    die("Prepare failed: " . mysqli_error($conn));
  }
  mysqli_stmt_bind_param($stmt, "s", $username);
  mysqli_stmt_execute($stmt);

  // get the results
  $result = mysqli_stmt_get_result($stmt);

  // Check for errors
  if (!$result) {
    die("Query failed: " . mysqli_error($conn));
  }

  // Loop through results and print them out as list items.
  if ($result && mysqli_num_rows($result) > 0) {

    //display the list of uctions.
    echo '<ul class="list-group">';

    // loop through the results
    while ($row = mysqli_fetch_array($result)) {

      //printing out the list item
      echo '<li class="list-group-item">';

      // check for images 
      if (!empty($row['Image'])) {

        // display the image from the database
        echo '<img src="data:image/jpg;charset=utf8;base64,'. $row['Image'] .'" width="100" height="100"/>';
      } else {

        // if there is not any image set it to the default value.
        echo '<img src="https://i1.sndcdn.com/avatars-000568343097-2ul7ra-t240x240.jpg" alt="Default Image" style="width: 100px; height: 100px;">';
      }

      // print auctions details and information. 
      printListingLi($row['auctionID'], $row['auctionTitle'], $row['auctionDetails'], $row['auctionCurrentPrice'], $row['NumBid'], $row['auctionEndDate'], $row['auctionCategory'], $row['UserName'], $row['auctionStartDate']);
      echo '</li>';
      echo '</li>';
      echo '<br>';
    }

    // close the list
    echo '</ul>';
} else {

  // if there is not any value display the message. 
  echo "<p>You have no bids.</p>";
}
?>

<?php include_once("footer.php")?>
