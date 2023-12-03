<?php
  include_once("header.php");
  require_once("utilities.php");
 ?>

<div class="container">
  <h2 class="my-3">Recommendations for you</h2>

  <?php
  // This page is for showing a buyer recommended items based on their bid 
  // history. It will be pretty similar to browse.php, except there is no 
  // search bar. This can be started after browse.php is working with a database.
  // Feel free to extract out useful functions from browse.php and put them in
  // the shared "utilities.php" where they can be shared by multiple files.
  
  
  // TODO: Check user's credentials (cookie/session).
  if (!isset($_SESSION['userId'])) {
    // Redirect to login page, or show an error message
    echo "<p>Please log in to view recommendations.</p>";
    exit;
  }

  $userId = $_SESSION['userId'];

  // Database connection setup
  require_once("config.php");
  $conn = mysqli_connect($db_server, $db_username, $db_password, $db_name);
  if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
  }

  // Perform a query to pull up auctions they might be interested in.
  $query = "SELECT * FROM auctions WHERE auctionID IN (SELECT auctionID FROM bids WHERE userID = '$userId') ORDER BY auctionEndDate DESC";
  $result = mysqli_query($conn, $query);

  // Loop through results and print them out as list items.
  if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      printListingLi($row['auctionID'], $row['auctionTitle'], $row['auctionDetails'], $row['auctionStartPrice'], $row['numBids'], $row['auctionEndDate'], $row['UserName'], $row['auctionCategory'], $row['auctionReservePrice']);
    }
  } else {
    echo "<p>No recommendations available based on your bid history.</p>";
  }


    

      // Close the database connection
      $conn->close();
  ?>
</div>

  <?php include_once("footer.php")?>