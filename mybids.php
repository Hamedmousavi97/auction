<?php include_once("header.php")?>
<?php include_once("config.php")?>
<?php require("utilities.php")?>


<div class="container">

<h2 class="my-3">My bids</h2>

<?php
  // This page is for showing a user the auctions they've bid on.
  // It will be pretty similar to browse.php, except there is no search bar.
  // This can be started after browse.php is working with a database.
  // Feel free to extract out useful functions from browse.php and put them in
  // the shared "utilities.php" where they can be shared by multiple files.


  //  Check user's credentials (cookie/session).

  if (!isset($_SESSION['username'])) {

    header('Location: browse.php');
    exit();
  }


  // Create a connection to the database
  $conn->set_charset("utf8");

  $username = $_SESSION['username'];

  //  Perform a query to pull up the auctions they've bidded on.

  $sql = "SELECT * FROM auctions
  JOIN bidreport ON auctions.BidID = bidreport.bidid
  WHERE bidreport.Username = ?";

  $stmt = mysqli_prepare($conn, $sql);
  if ($stmt === false) {
      die("Prepare failed: " . mysqli_error($conn));
  }
  mysqli_stmt_bind_param($stmt, "s", $username);
  mysqli_stmt_execute($stmt);

  $result = mysqli_stmt_get_result($stmt);

  // Check for errors
  if (!$result) {
    die("Query failed: " . mysqli_error($conn));
  }

  // Loop through results and print them out as list items.


  if ($result && mysqli_num_rows($result) > 0) {
    echo '<ul class="list-group">';
    while ($row = mysqli_fetch_array($result)) {

      # printing out the list item
      echo '<li class="list-group-item">';
      printListingLi($row['auctionID'], $row['auctionTitle'], $row['auctionDetails'], $row['auctionCurrentPrice'], $row['NumBid'], $row['auctionEndDate'], $row['auctionCategory'], $row['UserName'], $row['auctionStartDate']);
      echo '</li>';
      echo '</li>';
      echo '<br>';
      }

    echo '</ul>';
} else {
    echo "<p>You have no bids.</p>";
}
?>

<?php include_once("footer.php")?>
