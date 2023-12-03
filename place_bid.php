<?php

// Include configuration and header
require_once("config.php");
include_once("header.php");
require_once("browse.php");
require_once("utilities.php");


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve user input
    $bidAmount = isset($_POST['bidamount']) ? (int)$_POST['bidamount'] : 0;
    $auctionId = isset($_POST['item_id']) ? (int)$_POST['item_id'] : null;
    $username = $_SESSION['username'];
    $auctionTitle = isset($_POST['auctionTitle']) ? $_POST['auctionTitle'] : '';


    // get the auction details from the database
    $sql = "SELECT * FROM auctions WHERE auctionID = '$auctionId'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);

    // Check for errors
    if (!$result) {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    } else {

        // get the auction details from the database
        $startingPrice = $row['auctionStartPrice'];
        $num_bids = $row['NumBid'];
        $current_price = $row['auctionStartPrice'];
        $auctionReservePrice = $row['auctionReservePrice'];
        $auctionEndDate = $row['auctionEndDate'];
        $auctionCreator = $row['UserName'];

    }

    // check if the user is the auction creator
    if ($auctionCreator == $username) {
        echo '<script>
        alert("You cannot bid on your own auction!");
        window.history.back();
        </script>';
    } else {
        // Check if the bid is valid
        if ($bidAmount > $current_price ) {

            // Update the number of bids
            $num_bids = $num_bids + 1;

            // Update the current highest bid
            $stmt = $conn->prepare("UPDATE auctions SET auctionCurrentPrice = '$bidAmount', NumBid = '$num_bids' WHERE auctionID = '$auctionId'" );
            if ($stmt->execute()) {

                // Insert data into bid report table
                $stmt2 = $conn->prepare("INSERT INTO bidreport (auctionID, bidUsername, bidamount) VALUES ('$auctionId', '$username', '$bidAmount')");

                // Execute the prepared statement
                if ($stmt2->execute()) {
                  $sql = "SELECT * FROM auctions WHERE auctionCreator = '$auctionCreator'";
                  $message =  "A bid of £$bidAmount been placed on you're $auctionTitle auction. Please log in to view the details.";
                  SendEmail($email, $subject, $message); // the message for auction creator, new bid placed
                  $sql = "SELECT * FROM bidreport WHERE bidUsername = '$username'";
                  $message =  "A bid of £$bidAmount by $username has been placed on the $auctionTitle successfully. Please log in to view the details.";
                  SendEmail($email, $subject, $message); // the message for bid placer that the bid was successful

                    //Update bid ID into auction table
                    $bid_id = mysqli_insert_id($conn);

                    //Insert bid ID into auctions table
                    $stmt3 = $conn->prepare("UPDATE auctions SET BidID = '$bid_id' WHERE auctionID = '$auctionId'" );

                    // Execute the prepared statement
                    if ($stmt3->execute()) {
                        echo "New bid id inserted successfully";
                        // show success message
                        echo '<script>
                                alert("Bid placed successfully!");
                                window.history.back();
                            </script>';
                        // Redirect to browse.php
                        header("Location: <a href=`listing.php?item_id=' . $item_id . '`>");
                      } else {
                        echo "Error inserting your data into the database: " . $stmt3->error;
                    }
                } else {
                    echo "Error inserting your data into the database: " . $stmt2->error;
                }
            } else {
                echo "Error updating the current price: " . $stmt->error;
            }

        } else {
            // Inform the user that their bid is too low
            echo '<script>
            alert("Please make sure to enter a valid amount. Your bid should be more than the current price.");
            window.history.back();
            </script>';
        }
    }



    // Close the statement and connection
    $stmt->close();
    $conn->close();

}


?>
