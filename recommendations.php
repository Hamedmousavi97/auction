<?php include_once("header.php")?>
<?php require("utilities.php")?>

<div class="container">
  <h2 class="my-3">Recommendations for you</h2>

  <?php
  // This page is for showing a buyer recommended items based on their bid 
  // history. It will be pretty similar to browse.php, except there is no 
  // search bar. This can be started after browse.php is working with a database.
  // Feel free to extract out useful functions from browse.php and put them in
  // the shared "utilities.php" where they can be shared by multiple files.
  
  
  // Check user's credentials (cookie/session).
  if (!isset($_SESSION['username'])) {
    // Redirect to login page, or show an error message
    echo "<p>Please log in to view recommendations.</p>";
    exit;
  }

  $username = $_SESSION['username'];

  // Get the user's ID
  $sql = "SELECT UserID FROM users WHERE UserName = '$username'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  $userId = $row['UserID'];

  // Database connection setup
  require_once("config.php");
  $conn = mysqli_connect($db_server, $db_username, $db_password, $db_name);
  if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
  }

  // Perform a query to pull up auctions they might be interested in.
  $query = "SELECT * FROM auctions WHERE auctionID IN (SELECT auctionID FROM bidreport WHERE bidUsername = '$username') ORDER BY auctionEndDate DESC";
  $result = mysqli_query($conn, $query);

  // Loop through results and print them out as list items.
  if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      ini_set('display_errors', 1);
      ini_set('display_startup_errors', 1);
      error_reporting(E_ALL);
      echo '<li class="list-group-item">';
      if (!empty($row['Image'])) {
        echo '<img src="data:image/jpg;charset=utf8;base64,'. $row['Image'] .'" width="100" height="100"/>';
      } else {
          echo '<img src="https://i1.sndcdn.com/avatars-000568343097-2ul7ra-t240x240.jpg" alt="Default Image" style="width: 100px; height: 100px;">';
      }
      printListingLi($row['auctionID'], $row['auctionTitle'], $row['auctionDetails'], $row['auctionCurrentPrice'], $row['NumBid'], $row['auctionEndDate'], $row['auctionCategory'], $row['UserName'], $row['auctionStartDate']);
      echo '</li>';
      echo '<br>';    }
  } else {
    echo "<p>No recommendations available based on your bid history.</p>";
  }

  mysqli_close($conn);
?>
</div>

<?php include_once("footer.php")?>