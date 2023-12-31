<!-- This page displays the details of an auction. It is accessed from the search results page. 
Browse page or if user like to view more details.
-->

<?php include_once("header.php")?>
<?php require("utilities.php")?>
<?php 
  
  //Requierements 
  require_once("config.php");

  // Get info from the URL:
  $item_id = $_GET['item_id'];

  // Use item_id to make a query to the database. to get all information about the auction.
  $sql = "SELECT * FROM auctions WHERE auctionID = '$item_id'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($result);

  // Get the auction details
  if (!$result) {

    // If the query failed, print an error message
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  } else {

    // If the query succeeded, store the auction details in variables
    $title = $row['auctionTitle'];
    $description = $row['auctionDetails'];
    $startingPrice = $row['auctionStartPrice'];
    $num_bids = $row['NumBid'];
    $end_time = new DateTime($row['auctionEndDate']);
    $current_price = $row['auctionCurrentPrice'];
    $auctionEndDate = $row['auctionEndDate'];
    $auctionCreator = $row['UserName'];
    $auctionReservePrice = $row['auctionReservePrice'];
  }

  //Get the auction creator's rating
  $sql2 = "SELECT * FROM users WHERE UserName = '$auctionCreator'";
  $result2 = mysqli_query($conn, $sql2);
  $row2 = mysqli_fetch_array($result2);

  // Get the auction creator's rating
  if (!$result2) {

    // If the query failed, print an error message
    echo "Error: " . $sql2 . "<br>" . mysqli_error($conn);
  } else {

    // If the query succeeded, store the auction details in variables
    $auctionCreatorRating = $row2['UserRating'];
    $auctionCreatorRatingCount = $row2['UserRatingCount'];
  }

  // Calculate time to auction end:
  $now = new DateTime();
  if ($now < $end_time) {

    // get the time remaining
    $time_to_end = $now->diff($end_time);
    $time_remaining = display_time_remaining($time_to_end) ;
  }

  // check the user session for watchlist
  $has_session = $_SESSION['logged_in'];
  $watching = false;

  // check if the user is watching the auction
  if ($has_session) {
    $username = $_SESSION['username'];
    $check_query = "SELECT COUNT(*) FROM watchlist WHERE username = ? AND auctionID = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("si", $username, $item_id);
    $check_stmt->execute();
    $check_stmt->bind_result($count);
    $check_stmt->fetch();
    if ($count > 0) {

      // if the user is watching the auction, set $watching to true
      $watching = true;
    }

    // close the statement
    $check_stmt->close();
  }

  // Get the bid history
  $bid_query = "SELECT bidUsername, biddatetime, bidamount FROM bidreport WHERE auctionID = ? ORDER BY biddatetime DESC";
  $bid_stmt = mysqli_prepare($conn, $bid_query);
  mysqli_stmt_bind_param($bid_stmt, "i", $item_id);
  mysqli_stmt_execute($bid_stmt);

  // Get the result of the query
  $bid_result = mysqli_stmt_get_result($bid_stmt);
?>


<div class="container">

  <div class="row"> <!-- Row #1 with auction title + watch button -->
    <div class="col-sm-8"> <!-- Left col -->

      <!-- auction title -->
      <h1 class="my-3"><?php echo($title); ?></h1>

      <!-- auction creator -->
      <p style="font-size: 20px;">Seller: <?php echo($auctionCreator); ?></p>

      <!-- auction creator rating -->
      <p style="font-size: 20px;">Seller Rating: <?php echo(number_format($auctionCreatorRating,1)); ?>/5 (<?php echo($auctionCreatorRatingCount); ?>)</p>
      
      <!-- auction description -->
      <div class="itemDescription">
        <p style="font-size: 20px;"><?php echo($description); ?><h2>
      </div>

      <!-- auction image -->
      <img src="data:image/jpg;charset=utf8;base64,<?php echo $row['Image']; ?>" width="500" height="500" />

      <!-- bidding history -->
      <div class="bidding-history">
        <?php
          if (mysqli_num_rows($bid_result) > 0) {

            // Print the bid history in a table
            echo "<h3>Bidding History</h3>";
            echo "<table>";
            echo "<tr><th>Username</th><th>Bid Amount(£)</th><th>Bid Time</th></tr>"; // table header

            // Print each row of the table
            while ($bid_row = mysqli_fetch_assoc($bid_result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($bid_row['bidUsername']) . "</td>";
                echo "<td>£" . htmlspecialchars($bid_row['bidamount']) . "</td>";
                echo "<td>" . htmlspecialchars($bid_row['biddatetime']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
          } else {

            // If there are no bids, print a message
            echo "<p>No bids have been placed for this auction.</p>";
          }
        ?>
      </div>

    </div>

    <div class="col-sm-4 align-self-center"> <!-- Right col -->
      <!-- Print the watch button if the auction is still active -->
      <?php if ($now < $end_time): ?>
        <div id="watch_nowatch"

          <?php if ($has_session && $watching && $auctionCreator !== $username) echo('style="display: none"');?> >
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addToWatchlist()">+ Add to watchlist</button>
        </div>
        <div id="watch_watching" 
          <?php if (!$has_session || !$watching) echo('style="display: none"');?> >
            <button type="button" class="btn btn-success btn-sm" disabled>Watching</button>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeFromWatchlist()">Remove watch</button>
        </div>


        <!-- the bidding information here -->
        <p>Auction ends in <?php echo(date_format($end_time, 'j M H:i') . ' time remaining: ' . $time_remaining) ?></p>
        <p class="lead">Current Price: £<?php echo(number_format($current_price, 2)) ?></p>
        <p class="lead">Reserve Price: £<?php echo(number_format($auctionReservePrice, 2)) ?></p>
        <p class="lead">Number of bids: <?php echo($num_bids) ?></p>

        <!-- Bidding form -->
        <form method="POST" action="place_bid.php">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">£</span>
            </div>
            <input type="number" class="form-control" id="bid" name="bidamount">
          </div>

          <!-- Hidden inputs for the auction ID and the current price -->
          <input type="hidden" name="item_id" value="<?php echo($item_id);?>">
          <br>

          <!-- allow users to delete auction before anyone bid on it -->
          <?php if ($username == $auctionCreator && $current_price < $row['auctionReservePrice']): ?>
            <br>
              <a class="btn btn-danger btn-sm" href="mylistings.php?deleteAuction=true&auctionID=<?php echo $item_id; ?>" onclick="return confirm('Are you sure you want to delete this auction?');">Delete Auction</a>
            <br>
          <?php endif; ?>
          <br>

          <!-- you can not bid on your own auction -->
          <?php if ($has_session == true and $username == $auctionCreator): ?>
            <button type="button" class="btn btn-primary form-control" disabled>You can't bid on your own auction</button>
          
          <?php elseif ($has_session == true): ?>
            <button type="submit" class="btn btn-primary form-control">Place bid</button>
          <?php else: ?>

            <!-- redirct to login modal on the header page -->
            <button type="button" class="btn btn-primary form-control" data-toggle="modal" data-target="#loginModal">Please log in</button>
          <?php endif; ?>
        </form>
      <?php endif /* Print nothing otherwise */ ?>
    </div>
  </div>
</div> <!-- End of container-->


<div class="row"> <!-- Row #2 with auction description + bidding info -->
  <div class="col-sm-8"> <!-- Left col with item info -->
  </div>
    <div class="col-sm-4 align-self-center"> <!-- Right col with bidding info -->

      <!-- show that auction has been ended. -->
      <?php if ($now > $end_time): ?>
        This auction ended on
          <?php

            // Print the end time
            echo(date_format($end_time, 'j M H:i'));

            // finalise the auction
            finaliseAuctions($item_id);

            // send email to the winner
            $message =  "The $title has ended. Please log in to view the details.";
            SendEmail($email, $subject, $message);
          ?>
          <!-- rating form -->
          <br>
          <form method="POST" action="rating.php">
            <p>
              <label for="rating">Rate this seller:

                <!-- rating drop down -->
                <select name="ratingAmount" id="rating" name="ratingAmount">
                  <option value="1">1 - Very bad</option>
                  <option value="2">2 - Bad</option>
                  <option value="3">3 - Average</option>
                  <option value="4">4 - Good</option>
                  <option value="5">5 - Very good</option>
                </select>
              </label>
            </p>
            <div class="input-group">
              <div class="input-group-prepend">
              </div>
              <div class="input-group-prepend">
                <br>
              </div>
              
              <!-- Hidden inputs for the auction ID and the creator -->
              <input type="hidden" name="auctionCreator" value="<?php echo($auctionCreator);?>">
              <input type="hidden" name="item_id" value="<?php echo($item_id);?>">

              <!-- you can not rate yourself -->
              <?php if ($has_session == true and $username == $auctionCreator): ?>
                <button type="button" class="btn btn-primary form-control" disabled>You can't rate yourself!</button>

              <!-- check the user is logged in -->
              <?php elseif ($has_session == true): ?>
                <button type="submit" class="btn btn-primary form-control">Rate this seller</button>
              <?php else: ?>
                <!-- redirct to login modal on the header page -->
                <button type="button" class="btn btn-primary form-control" data-toggle="modal" data-target="#loginModal">Please log in</button>
              <?php endif; ?>
          </form>
              <br>
            </div>
          </div> <!-- End of right col with bidding info -->
        </div> <!-- End of row #2 -->
      <?php endif; ?>
    </div>


  </div> <!-- End of right col with bidding info -->

</div> <!-- End of row #2 -->
</p>
</div>



<?php include_once("footer.php")?>


<script>
// JavaScript functions: addToWatchlist and removeFromWatchlist.

function addToWatchlist(button) {

  // This performs an asynchronous call to a PHP function using POST method.
  // Sends item ID as an argument to that function.
  $.ajax('watchlist_funcs.php', {
    type: "POST",
    data: {functionname: 'add_to_watchlist', arguments: [<?php echo($item_id);?>]},

    // if the call is successful, hide the watch button and show the remove watch button
    success:
      function (obj, textstatus) {
        // Callback function for when call is successful and returns obj
        var objT = obj.trim();
        console.log(obj);
        console.log("Success");
        statusCheck = `<!-- connection to database -->
"success"`; // this is the string that is returned from the php file

        if (objT == statusCheck) {
          $("#watch_nowatch").hide();
          $("#watch_watching").show();
        }
        else {
          var mydiv = document.getElementById("watch_nowatch");
          mydiv.appendChild(document.createElement("br"));
          mydiv.appendChild(document.createTextNode("Add to watch failed. Try again later."));
        }
      },

    // if the call is unsuccessful, print an error message
    error:
      function (obj, textstatus) {
        console.log("Error");
      }
  }); // End of AJAX call

} // End of addToWatchlist func

function removeFromWatchlist(button) {
  // This performs an asynchronous call to a PHP function using POST method.
  // Sends item ID as an argument to that function.
  $.ajax('watchlist_funcs.php', {
    type: "POST",
    data: {functionname: 'remove_from_watchlist', arguments: [<?php echo($item_id);?>]},

    success:
      function (obj, textstatus) {
        // Callback function for when call is successful and returns obj
        console.log("Success");
        var objT = obj.trim();
        statusCheck = `<!-- connection to database -->
"success"`; // this is the string that is returned from the php file


        if (objT == statusCheck) {
          $("#watch_watching").hide();
          $("#watch_nowatch").show();
        }
        else {
          var mydiv = document.getElementById("watch_watching");
          mydiv.appendChild(document.createElement("br"));
          mydiv.appendChild(document.createTextNode("Watch removal failed. Try again later."));
        }
      },

    error:
      function (obj, textstatus) {
        console.log("Error");
      }
  }); // End of AJAX call

} // End of addToWatchlist func
</script>
