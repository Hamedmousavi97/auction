<?php

  // this page is for extra features and funcctions. 
  include_once("config.php");

  // display_time_remaining:
  // Helper function to help figure out what time to display
  function display_time_remaining($interval) {

    // Check for date and time format
    if ($interval->days == 0 && $interval->h == 0) {

      // Less than one hour remaining: print mins + seconds:
      $time_remaining = $interval->format('%im %Ss');
    } else if ($interval->days == 0) {

      // Less than one day remaining: print hrs + mins:
      $time_remaining = $interval->format('%hh %im');
    } else {

      // At least one day remaining: print days + hrs:
      $time_remaining = $interval->format('%ad %hh');
    }

    // Return the time remaining
    return $time_remaining;
  }

  // print_listing_li:
  // This function prints an HTML <li> element containing an auction listing
  function printListingLi($item_id, $title, $desc, $price, $num_bids, $end_time, $category, $username, $date_created)
  {

    // Truncate long descriptions
    if (strlen($desc) > 250) {
      $desc_shortened = substr($desc, 0, 250) . '...';
    } else {

      // If description is short enough, don't truncate
      $desc_shortened = $desc;
    }

    // Check if user is logged in and watching this auction
    global $conn;
    if (!isset($_SESSION['username'])) {

      // If not logged in, don't show watch button
      $watching = '';
    } else {

      // If logged in, check if watching
      $current_user = $_SESSION['username'];

      // Query to check if user is watching this auction
      $query = "SELECT * FROM watchlist WHERE auctionID = '$item_id' AND username = '$current_user'";
      $result = mysqli_query($conn, $query);
      if ($result && mysqli_num_rows($result) > 0) {

        // If watching, show disabled button to indicate this
        $watching = '<button type="button" class="btn btn-success btn-sm" disabled>Watching</button>';
      } else {

        // If not watching, show button to add to watchlist
        $watching = '';
      }
    }

    // Fix language of bid vs. bids
    if ($num_bids == 1) {
      $bid = ' bid';
    }
    else {
      $bid = ' bids';
    }

    // Calculate time to auction end
    $now = new DateTime();
    $end_time = new DateTime($end_time);

    // Convert date_created to DateTime object
    $date_created = new DateTime($date_created);
    $date_created = $date_created->format('j M Y');

    // check that if the end time is in future
    if ($now < $end_time) {

      // if so show the time reremaining for the auction
      $time_to_end = $now->diff($end_time);
      $time_remaining = 'Auction end in ' . display_time_remaining($time_to_end) ;
    } else {

      // if not show the auction is ended.
      $time_remaining = 'Auction ended';
    }

    // Print HTML to show details of the auction. 
    echo('
      <strong> User "' . $username . '" Created an auction on: ' . $date_created . '</strong>
      <div class="text-right"> ' . $watching . '</div>
      <br>
      <li class="list-group-item d-flex justify-content-between">
      <div class="p-2 mr-5"><h5><a href="listing.php?item_id=' . $item_id . '">' . $title . '</a></h5>' . $desc_shortened . '<br> <strong>' . $category . '</strong></div>
      <div class="text-center text-nowrap"><span style="font-size: 1.5em">£' . number_format($price, 2) . '</span><br/>' . $num_bids . $bid . '<br/>' . $time_remaining . '</div>
      </li>'
    );
  }

  // function to finalise the auction
  function finaliseAuctions($item_id) {

    // get the database connection
    global $conn;

    // Get the current date and time
    $currentDateTime = date("Y-m-d H:i:s");

    // Query for auctions that have ended but not finalized
    $query = "SELECT auctions.*, bidreport.* FROM auctions JOIN bidreport ON auctions.BidID = bidreport.bidid WHERE auctionEndDate <= '$currentDateTime' AND auctions.auctionID = $item_id";
    $result = mysqli_query($conn, $query);

    // check for result
    if ($result && mysqli_num_rows($result) > 0) {

      // loop throught the results
      while ($row = mysqli_fetch_assoc($result)) {

        // Get the winning bid for the auction
        $auctionID = $row['auctionID'];
        $winner = $row['bidUsername'];
        $auctionTitle = $row['auctionTitle'];
        $auctionWinningBid = $row['bidamount'];
        $auctionReservePrice = $row['auctionReservePrice'];

        // check if the auction is the current auction shouwing.
        if ($item_id == $auctionID) {

          // check the prices
          if ($auctionReservePrice < $auctionWinningBid) {

            // Update the auction with the winning bid information and set it as finalized
            $updateQuery = "UPDATE auctions SET isFinished = 1 WHERE auctionID = $auctionID";

            //execute the query
            $updateResult = mysqli_query($conn, $updateQuery);
            if ($updateResult) {

              // Display final information.
              echo "</br>Auction $auctionTitle $auctionID has been finalised. <br>".$winner ." won the bid with the bid of £" . $auctionWinningBid . "<br>";
            } else {

              // otherwise show the error
              echo "Error updating auction $auctionTitle: " . mysqli_error($conn) . "<br>";
            }
          } else {

            //set the query to finalise auction without meeting the reserve price
            $updateQuery = "UPDATE auctions SET isFinished = 1 WHERE auctionID = $auctionID";
            $updateResult = mysqli_query($conn, $updateQuery);

            // if the query is successful show the message that the auction is finalised
            if ($updateResult) {
              echo "<br>This auction did not meet the reserved price. <br>";
            } else {

              // otherwise inform the user of the problem.
              echo "Error updating auction $auctionTitle: " . mysqli_error($conn) . "<br>";
            }
          }
        } else {

          // finalise the auction.
          $updateQuery = "UPDATE auctions SET isFinished = 1 WHERE auctionID = $auctionID";
          $updateResult = mysqli_query($conn, $updateQuery);

          //Check for the result and if successful show the message.
          if ($updateResult) {
            echo "<br>This auction did not meet the reserved price. <br>";
          } else {

            // otherwise show the error.
            echo "Error updating auction $auctionTitle: " . mysqli_error($conn) . "<br>";
          }
        }
      }
    } else {

      //show the error. 
      echo "<br>This auction did not meet the reserved price. <br>";
    }
  }

  // function to delet auction (seller only)
  function canDeleteAuction($auctionID) {

    //set the connection. 
    global $conn;

    // Query to get the auction details and check for auction information
    $query = "SELECT auctionCurrentPrice, auctionReservePrice FROM auctions WHERE auctionID = $auctionID";
    $result = mysqli_query($conn, $query);

    // get the result if the query is successful.
    if ($result && mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);

      // check if the user can delete the auction based on the situation
      if ($row['auctionCurrentPrice'] < $row['auctionReservePrice']) {

        // Current price is less than reserve price, can delete
        return true;
      }
    }
    return false; // Cannot delete if current price is equal or more than reserve price
  }

  // this is for admin to delet

  function getAllUsers() {

    // connect to the database
    global $conn;

    // set users arrey as empty
    $users = array();

    // query through the users table to get all users information
    $query = "SELECT * FROM users";
    $result = mysqli_query($conn, $query);

    //if successful store the users in to the array
    if ($result) {

      //loop through the users
      while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
      }
      mysqli_free_result($result);
    }
    return $users;
  }

  // admin to get all auction from the database. 
  function getAllAuctions() {

    // connect to the database
    global $conn;

    // set the auction array to empty
    $auctions = array();

    // query through the auction table to get all the details.
    $query = "SELECT * FROM auctions";
    $result = mysqli_query($conn, $query);

    // get all the results and set it to the array for auction.
    if ($result) {
      while ($row = mysqli_fetch_assoc($result)) {
        $auctions[] = $row;
      }
      mysqli_free_result($result);
    }

    //return the auction array.
    return $auctions;
  }

  // function to delete the auction.
  function deleteAuction($auctionID) {

    // connect to the database.
    global $conn;

    // check if they can delete the auction or not. 
    if (canDeleteAuction($auctionID)) {

      //if so delete the relevant auction from the database.
      $query = "DELETE FROM auctions WHERE auctionID = $auctionID";
      $result = mysqli_query($conn, $query);

      // if successfull show the message.
      if ($result) {
        echo "Auction deleted successfully.";
      } else {

        //otherwise throw an error
        echo "Error deleting auction: " . mysqli_error($conn);
      }
    } else {

      //if they don't have the privilage throw an error. 
      echo "Cannot delete auction as current price meets or exceeds reserve price.";
    }
  }

  // Admin - delete user
  function deleteUser($UserID) {

    //connect to the database.
    global $conn;

    // check admin privilages
    if ($_SESSION['account_type'] != 'admin') {
      echo "Access denied: Only admin can delete users.";
      return;
    } else {

      // query into the databse to delete the user with user id from users table.
      $sql = "DELETE FROM users WHERE UserID = ?";
      $stmt = mysqli_prepare($conn, $sql);
      mysqli_stmt_bind_param($stmt, "i", $UserID);
      mysqli_stmt_execute($stmt);
    
      if (mysqli_stmt_affected_rows($stmt) > 0) {

        // if successful show the message.
        echo "User deleted successfully.";
      } else {

        // otherwise throw an error.
        echo "Error deleting user.";
      }
    }
  }

  // Admin - delete auction
  function admin_deleteAuction($auctionID) {

    // connect to the databbase
    global $conn;

    // check admin privilages
    if ($_SESSION['account_type'] != 'admin') {
      echo "Access denied: Only admin can delete auctions.";
      return;
    } else {
      
      // if they have access delete the auction from the auction table.
      $sql = "DELETE FROM auctions WHERE auctionID = ?";
      $stmt = mysqli_prepare($conn, $sql);
      mysqli_stmt_bind_param($stmt, "i", $auctionID);
      mysqli_stmt_execute($stmt);
  
      // if successful show the message otherwise throw an error.
      if (mysqli_stmt_affected_rows($stmt) > 0) {
          echo "Auction deleted successfully.";
      } else {
          echo "Error deleting auction.";
      }
    }
  }

  // require relevant file for the email function.
  require 'vendor/autoload.php';
  use \SendGrid\Mail\Mail;

  //email function to send the message or notification to the users.
  function SendEmail($email, $subject, $message) {

    // set the new mail
    $email = new Mail();

    // Replace the email address and name with your verified sender
    $email->setFrom(
      'databasecheckingemail@gmail.com',
      'Winston Nagelmackers'
    );
    $email->setSubject($subject);

    // Replace the email address and name with your recipient
    $email->addTo(
      'databasecheckingemail@gmail.com',
      'Winston Nagelmackers'
    );

    //set the content. 
    $email->addContent(
      'text/html',
      '<strong>'. $message .'</strong>'
    );

    // call the function 
    $sendgrid = new \SendGrid('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');
    
    // in case of exeution.
    try {

      // get the response
      $response = $sendgrid->send($email);

      // get the header of the email.
      $headers = array_filter($response->headers());

      //set the header
      foreach ($headers as $header) {
          // echo '- ' . $header . "\n";
      }
    } catch (Exception $e) {

      //in case of error display the message. 
      echo 'Caught exception: '. $e->getMessage() ."\n";
    }
  }
?>
