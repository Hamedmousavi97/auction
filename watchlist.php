<?php include_once("header.php")?>
<?php include_once("config.php")?>
<?php require("utilities.php")?>


<div class="container">

<h2 class="my-3">My Watchlist</h2>

<?php

  // This page is for showing a user their watchlist auctions they're watching.
  // It will be pretty similar to mybiding.php, but with a different query.
  
  //  Check user's credentials (cookie/session).
  if (!isset($_SESSION['username'])) {
    header('Location: browse.php');
    exit();
  }

  // Create a connection to the database
  $conn->set_charset("utf8");

  // get the username
  $username = $_SESSION['username'];

  //  Perform a query to pull up the auctions they've watched. on.
  $sql = "SELECT * FROM watchlist WHERE username = '$username'";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();

  // Check for errors
  if (!$result) {
    die("Query failed: " . mysqli_error($conn));
  }

  // Loop through results and print them out as list items.
  if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $auctionID = $row['auctionID'];

        // get the auction details from the database
        $sql2 = "SELECT * FROM auctions WHERE auctionID = '$auctionID'";
        $result2 = $conn->query($sql2);
        $row2 = $result2->fetch_assoc();

        // check for the error.
        if (!$result2) {
            die("Query failed: " . mysqli_error($conn));
        } else {

            //if successfull show the auction details.
            echo '<ul class="list-group">';
            echo '<li class="list-group-item">';

            //check for the image
            if (!empty($row2['Image'])) {

                // display the image
                echo '<img src="data:image/jpg;charset=utf8;base64,'. $row2['Image'] .'" width="100" height="100"/>';
            } else {

                //otherwise show the default one.
                echo '<img src="https://i1.sndcdn.com/avatars-000568343097-2ul7ra-t240x240.jpg" alt="Default Image" style="width: 100px; height: 100px;">';
            }

            //print auction details.
            printListingLi($row2['auctionID'], $row2['auctionTitle'], $row2['auctionDetails'], $row2['auctionCurrentPrice'], $row2['NumBid'], $row2['auctionEndDate'], $row2['auctionCategory'], $row2['UserName'], $row2['auctionStartDate']);
            echo '</li>';
            echo '</li>';
            echo '<br>';
        }
    }
    echo '</ul>';
} else {

    //show there is no auction to display. 
    echo "<p>You have no watchlist.</p>";
}
?>

<?php include_once("footer.php")?>
