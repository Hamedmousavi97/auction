<?php include_once("header.php")?>
<?php require("utilities.php")?>

<div class="container">

  <!-- Page Heading -->
  <h2 class="my-3">Recommendations for you</h2>

  <?php

    // requirements
    require_once("config.php");
    // This page is for showing a buyer recommended items based on their bid 
    // history. 
    
    // Check user's credentials (cookie/session).
    if (!isset($_SESSION['username'])) {

      // Redirect to login page, or show an error message
      echo "<p>Please log in to view recommendations.</p>";
      exit;
    }

    // Get the user's username
    $username = $_SESSION['username'];

    // Get the user's ID based on their username
    $sql = "SELECT UserID FROM users WHERE UserName = '$username'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    // set the user ID
    $userId = $row['UserID'];

    // Database connection setup
    $conn = mysqli_connect($db_server, $db_username, $db_password, $db_name);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Perform a query to pull up auctions they might be interested in.
    $query = "SELECT * FROM auctions WHERE auctionID IN (SELECT auctionID FROM bidreport WHERE bidUsername = '$username') ORDER BY auctionEndDate DESC";
    $result = mysqli_query($conn, $query);

    // Loop through results and print them out as list items.
    if ($result && mysqli_num_rows($result) > 0) {

      // Query into the auction table to find out more auctions based on the user category
      while ($row = mysqli_fetch_assoc($result)) {

        // Get the category of the auction
        $categories = $row['auctionCategory'];

        //Set the current date and time
        $currentDate = date("Y-m-d H:i:s");
      
        // Query into the auction table to find out more auctions based on the user category
        $query2 = "SELECT * FROM auctions WHERE auctionCategory = '$categories' AND auctionEndDate > '$currentDate' ORDER BY auctionEndDate DESC";
        $result2 = mysqli_query($conn, $query2);

        // Loop through results and print them out as list items.
        if ($result2 && mysqli_num_rows($result2) > 0 ){
          
          // Loop through the results
          while ($row2 = mysqli_fetch_assoc($result2)) {
  
            // Print out the list item
            echo '<li class="list-group-item">';
    
            // Print out the image
            if (!empty($row2['Image'])) {
    
              // Print out the image
              echo '<img src="data:image/jpg;charset=utf8;base64,'. $row2['Image'] .'" width="100" height="100"/>';
            } else {
    
              // Print out the default image
              echo '<img src="https://i1.sndcdn.com/avatars-000568343097-2ul7ra-t240x240.jpg" alt="Default Image" style="width: 100px; height: 100px;">';
            }
    
            // Print out the information
            printListingLi($row2['auctionID'], $row2['auctionTitle'], $row2['auctionDetails'], $row2['auctionCurrentPrice'], $row2['NumBid'], $row2['auctionEndDate'], $row2['auctionCategory'], $row2['UserName'], $row2['auctionStartDate']);
            echo '</li>';
            echo '<br>';    
          }
        } else {

          // Print out a message saying there are no recommendations
          echo "<p>No recommendations available based on your bid history.</p>";
        }
      }
    } else {

      // Print out a message saying there are no recommendations
      echo "<p>No recommendations available based on your bid history.</p>";
    }

    // Close the connection
    mysqli_close($conn);
  ?>
</div>

<?php include_once("footer.php")?>