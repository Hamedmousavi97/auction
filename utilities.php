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
function finaliseAuctions() {
    global $conn;

    // Get the current date and time
    $currentDateTime = date("Y-m-d H:i:s");

    // Query for auctions that have ended but not finalized
    $query = "SELECT auctions.*, bidreport.* FROM auctions INNER JOIN bidreport ON auctions.BidID = bidreport.bidid WHERE auctionEndDate <= '$currentDateTime' AND isFinished = 0";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Get the winning bid for the auction
            $auctionID = $row['auctions.auctionID'];
            $winner = $row['bidreport.UserName'];
            $auctionTitle = $row['auctions.auctionTitle'];
            $auctionWinningBid = $row['bidreport.bidamount'];
            $auctionReservePrice = $row['auctions.reservePrice'];

            if ($auctionReservePrice > $auctionWinningBid) {
                // Update the auction with the winning bid information and set it as finalized
                $updateQuery = "UPDATE auctions SET 
                                isFinalized = 1
                                WHERE auctionID = $auctionID";

                $updateResult = mysqli_query($conn, $updateQuery);

                if ($updateResult) {
                    

                    echo "Auction $auctionTitle has been finalised.".$winner ." won the bid with the bid of £: " . $auctionWinningBid . "<br>";
                } else {
                    echo "Error updating auction $auctionTitle: " . mysqli_error($conn) . "<br>";
                }
            } else {
                echo "No winning bid found for auction ID $auctionID". $winner ." <br>";
            }
        }
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




?>
