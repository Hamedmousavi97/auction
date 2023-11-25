<?php include_once("header.php")?>
<?php include_once("config.php")?>
<?php require("utilities.php")?>

<?php session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);?>

<div class="container">

<h2 class="my-3">My listings</h2>

<?php
  // This page is for showing a user the auction listings they've made.
  // It will be pretty similar to browse.php, except there is no search bar.
  // This can be started after browse.php is working with a database.
  // Feel free to extract out useful functions from browse.php and put them in
  // the shared "utilities.php" where they can be shared by multiple files.


  // TODO: Check user's credentials (cookie/session).

  if (!isset($_SESSION['username'])) {

    header('Location: browse.php');
    exit();
  }

  // TODO: Connect to the database

  $db_server = "localhost";
  $db_username = "root";
  $db_password = "root";
  $db_name = "Auction";

  // Create a connection to the database
  $conn = mysqli_connect($db_server, $db_username, $db_password, $db_name);
  $conn->set_charset("utf8");

  // Check connection

  /* if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  } else {
    echo "Connected successfully"; // Add this line for debugging purposes
  } */


  // Assuming you have a user ID stored in a variable

  $username = $_SESSION['username'];

  // TODO: Perform a query to pull up their auctions.

  $sql = "SELECT * FROM auctions
  JOIN users ON auctions.UserName = users.UserName
  WHERE users.UserName = ?";

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
?>


<?php
  // Loop through results and print them out as list items.

  if ($result && mysqli_num_rows($result) > 0) {
    echo '<ul class="list-group">';
    while ($row = mysqli_fetch_array($result)) {
        $item_id = $row['auctionID'];
        $title = $row['auctionTitle'];
        $description = $row['auctionDetails'];
        $current_price = $row['auctionStartPrice'];
        $num_bids = $row['NumBid'];
        $end_date = $row['auctionEndDate'];
        $user_id = $row['UserName'];


        print_listing_li($item_id, $title, $description, $current_price, $num_bids, $end_date);
    }

    echo '</ul>';
} else {
    echo "<p>You have no listings.</p>";
}
?>

</div>

<?php include_once("footer.php") ?>
