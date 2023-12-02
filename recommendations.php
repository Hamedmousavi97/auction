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

  if (!isset($_SESSION['username'])) {
    header('Location: browse.php'); // Redirect to browse page if not logged in
    exit();
  }

  $username = $_SESSION['username'];

  // Database connection setup
  require_once("config.php");
  $conn = mysqli_connect($db_server, $db_username, $db_password, $db_name);
  if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
  }


  // Assuming you have a database connection established

  // Function to get recommended auctions based on user's bids
  function getRecommendedAuctions($conn, $username)
  {
    $recommendedAuctions = [];

      // Fetch user's bids
      $userBidsQuery = "SELECT * FROM auctions
      JOIN bidreport ON auctions.BidID = bidreport.bidid
      WHERE bidreport.Username = ?";

      // Using prepared statement to prevent SQL injection
        $stmt = $conn->prepare($userBidsQuery);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $userBidsResult = $stmt->get_result();
     
      if ($userBidsResult->num_rows > 0) {
      
          // Loop through user's bids to find matching categories
          while ($row = $userBidsResult->fetch_assoc()) {
              $auctionId = $row['auctionID'];
              $category = $row['auctionCategory'];

              // Get auctions with the same category, excluding those the user has already bid on
              $recommendationsQuery = "SELECT * FROM auctions WHERE auctionCategory = ? AND auctionID <> ?";
              $stmt = $conn->prepare($recommendationsQuery);
              $stmt->bind_param("si", $category, $auctionId);
              $stmt->execute();
              $recommendationsResult = $stmt->get_result();

              if ($recommendationsResult->num_rows > 0) {
                  // Add recommended auctions to the array
                  while ($auction = $recommendationsResult->fetch_assoc()) {
                      $recommendedAuctions[] = $auction;
                  }
              }
          }
          return $recommendedAuctions;
      }
    }
      $recommendedAuctions = getRecommendedAuctions($conn, $username);

      // Display recommended auctions
      if (!empty($recommendedAuctions)) {
        echo '<ul class="list-group">';
        foreach ($recommendedAuctions as $auction) {
            echo '<li class="list-group-item">';
            if (isset($auction['auctionID'])) {
                // Assuming the keys in $auction array match the parameters of printListingLi function
                printListingLi($auction['auctionID'], $auction['auctionTitle'], $auction['auctionDetails'], $auction['auctionCurrentPrice'], $auction['NumBid'], $auction['auctionEndDate'], $auction['auctionCategory'], $auction['UserName'], $auction['auctionStartDate']);
            }
            echo '</li>';
        }
        echo '</ul>';
      } else {
        echo "No recommendations found.";
      }


    

      // Close the database connection
      $conn->close();
  ?>
</div>

  <?php include_once("footer.php")?>