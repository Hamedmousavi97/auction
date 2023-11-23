<?php include_once("header.php")?>
<?php require("utilities.php")?>

<div class="container">

<h2 class="my-3">My listings</h2>

<?php
  // This page is for showing a user the auction listings they've made.
  // It will be pretty similar to browse.php, except there is no search bar.
  // This can be started after browse.php is working with a database.
  // Feel free to extract out useful functions from browse.php and put them in
  // the shared "utilities.php" where they can be shared by multiple files.


  // TODO: Check user's credentials (cookie/session).

  if (!isset($_SESSION['user_id'])) {
    header('Location: header.php');
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

  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  } else {
    echo "Connected successfully"; // Add this line for debugging purposes
  }

  // Assuming you have a user ID stored in a variable

  $user_id = $_SESSION['user_id'];

  // TODO: Perform a query to pull up their auctions.

  $sql = "SELECT * FROM auctions WHERE sellerID = $user_id";
  $result = mysqli_query($conn, $sql);

  // Check for errors
  if (!$result) {
    die("Query failed: " . mysqli_error($conn));
  }
?>

<h2 class="my-3">My listings</h2>

<?php
  // Loop through results and print them out as list items.

  if ($result && mysqli_num_rows($result) > 0) {
    echo '<ul class="list-group">';
    while ($row = mysqli_fetch_array($result)) {
    $item_id = $row['itemID'];
    $title = $row['auctionTitle'];
    $description = $row['auctionDetails'];
    $current_price = $row['auctionStartPrice'];
    $num_bids = $row['currentBid'];
    $end_date = $row['auctionEndDate'];

    echo "Item ID: $item_id, Title: $title, Description: $description, Current Price: $current_price, Num Bids: $num_bids, End Date: $end_date";

    print_listing_li($item_id, $title, $description, $current_price, $num_bids, $end_date);
}

    echo '</ul>';
  } else {
    echo "<p>You have no listings.</p>";
  }
?>

</div>

<?php include_once("footer.php")?>
