<?php

include_once("config.php");

// display_time_remaining:
// Helper function to help figure out what time to display
function display_time_remaining($interval) {

    if ($interval->days == 0 && $interval->h == 0) {
      // Less than one hour remaining: print mins + seconds:
      $time_remaining = $interval->format('%im %Ss');
    }
    else if ($interval->days == 0) {
      // Less than one day remaining: print hrs + mins:
      $time_remaining = $interval->format('%hh %im');
    }
    else {
      // At least one day remaining: print days + hrs:
      $time_remaining = $interval->format('%ad %hh');
    }

  return $time_remaining;

}

// print_listing_li:
// This function prints an HTML <li> element containing an auction listing
function printListingLi($item_id, $title, $desc, $price, $num_bids, $end_time, $category, $username, $date_created)
{
  // Truncate long descriptions
  if (strlen($desc) > 250) {
    $desc_shortened = substr($desc, 0, 250) . '...';
  }
  else {
    $desc_shortened = $desc;
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

  if ($now < $end_time) {
      $time_to_end = $now->diff($end_time);
      $time_remaining = 'Auction end in ' . display_time_remaining($time_to_end) ;
  } else {
      $time_remaining = 'Auction ended';
  }


  // Print HTML
  echo('
    <strong> User "' . $username . '" Created an auction on: ' . $date_created . '</strong>
    <br>
    <li class="list-group-item d-flex justify-content-between">
    <div class="p-2 mr-5"><h5><a href="listing.php?item_id=' . $item_id . '">' . $title . '</a></h5>' . $desc_shortened . '<br> <strong>' . $category . '</strong></div>
    <div class="text-center text-nowrap"><span style="font-size: 1.5em">£' . number_format($price, 2) . '</span><br/>' . $num_bids . $bid . '<br/>' . $time_remaining . '</div>

    </li>'
  );
}

// function to finalise the auction
function finaliseAuctions($item_id) {
    global $conn;
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);



    // Get the current date and time
    $currentDateTime = date("Y-m-d H:i:s");

    // Query for auctions that have ended but not finalized
    $query = "SELECT auctions.*, bidreport.* FROM auctions JOIN bidreport ON auctions.BidID = bidreport.bidid WHERE auctionEndDate <= '$currentDateTime' AND auctions.auctionID = $item_id";
    $result = mysqli_query($conn, $query);


    if ($result && mysqli_num_rows($result) > 0) {
        
        while ($row = mysqli_fetch_assoc($result)) {
            // Get the winning bid for the auction
            $auctionID = $row['auctionID'];
            $winner = $row['bidUsername'];
            $auctionTitle = $row['auctionTitle'];
            $auctionWinningBid = $row['bidamount'];
            $auctionReservePrice = $row['auctionReservePrice'];

            
            if ($item_id == $auctionID) {
            
              if ($auctionReservePrice < $auctionWinningBid) {
                  // Update the auction with the winning bid information and set it as finalized
                  $updateQuery = "UPDATE auctions SET 
                                  isFinished = 1
                                  WHERE auctionID = $auctionID";

                  $updateResult = mysqli_query($conn, $updateQuery);

                  if ($updateResult) {
                      

                      echo "</br>Auction $auctionTitle $auctionID has been finalised. <br>".$winner ." won the bid with the bid of £" . $auctionWinningBid . "<br>";
                  } else {
                      echo "Error updating auction $auctionTitle: " . mysqli_error($conn) . "<br>";
                  }
              } else {
                  echo "No winning bid found for this auction ID $auctionID <br>";

              }
            }
            else {
              $updateQuery = "UPDATE auctions SET 
              isFinished = 1
              WHERE auctionID = $auctionID";

              $updateResult = mysqli_query($conn, $updateQuery);

              echo "<br>This auction did not meet the reserved price. <br>";

            }
        }
    } else {
        echo "<br>This auction did not meet the reserved price. <br>";
    }
}

// function getWinningBid($auctionID) {
//     global $conn;

//     // Query to get the winning bid for the given auction
//     $query = "SELECT * FROM bids WHERE auctionID = $auctionID ORDER BY bidAmount DESC LIMIT 1";
//     $result = mysqli_query($conn, $query);

//     if ($result && mysqli_num_rows($result) > 0) {
//         return mysqli_fetch_assoc($result);
//     }

//     return null;
// }



function getCategories($conn) {
  $categories = array();

  $query = "SELECT * FROM categories";
  $result = mysqli_query($conn, $query);

  if ($result && mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
          $categories[] = $row;
      }
      mysqli_free_result($result);
  }

  return $categories;
}



// function to delet auction (seller only)
function canDeleteAuction($auctionID) {
  global $conn;

  // Query to get the auction details
  $query = "SELECT auctionCurrentPrice, auctionReservePrice FROM auctions WHERE auctionID = $auctionID";
  $result = mysqli_query($conn, $query);

  if ($result && mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      if ($row['auctionCurrentPrice'] < $row['auctionReservePrice']) {
          // Current price is less than reserve price, can delete
          return true;
      }
  }
  return false; // Cannot delete if current price is equal or more than reserve price
}

function deleteAuction($auctionID) {
  global $conn;

  if (canDeleteAuction($auctionID)) {
      $query = "DELETE FROM auctions WHERE auctionID = $auctionID";
      $result = mysqli_query($conn, $query);

      if ($result) {
          echo "Auction deleted successfully.";
      } else {
          echo "Error deleting auction: " . mysqli_error($conn);
      }
  } else {
      echo "Cannot delete auction as current price meets or exceeds reserve price.";
  }
}




?>
