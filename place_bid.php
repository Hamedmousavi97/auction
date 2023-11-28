<?php

// Include configuration and header
require_once("config.php");
include_once("header.php");
require_once("browse.php");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve user input
    $bidAmount = isset($_POST['bidamount']) ? (int)$_POST['bidamount'] : 0;
    $auctionId = isset($_POST['item_id']) ? (int)$_POST['item_id'] : null;
    $username = $_SESSION['username'];

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
    }

    // Check if the bid is valid
    if ($bidAmount > $current_price ) {

        // Update the number of bids
        $num_bids = $num_bids + 1;

        // Update the current highest bid
        $stmt = $conn->prepare("UPDATE auctions SET auctionCurrentPrice = '$bidAmount', NumBid = '$num_bids' WHERE auctionID = '$auctionId'" );
        if ($stmt->execute()) {
            
            // Insert data into bid report table
            $stmt2 = $conn->prepare("INSERT INTO bidreport (auctionID, UserName, bidamount) VALUES ('$auctionId', '$username', '$bidAmount')");

            // Execute the prepared statement
            if ($stmt2->execute()) {
                echo "New record inserted successfully";
            } else {
                echo "Error inserting your data into the database: " . $stmt2->error;
            }
        } else {
            echo "Error updating the current price: " . $stmt->error;
        }



    } else {
        // Inform the user that their bid is too low
        $error = "Your bid must be higher than the current highest bid.";
    }




    // Close the statement and connection
    $stmt->close();
    $conn->close();

}


?>