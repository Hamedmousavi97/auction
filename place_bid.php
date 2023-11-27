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



    }




    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Process the bid (you would usually save this information to a database)
    // $currentBid = getCurrentBid(); // Function to get the current highest bid (from a database)
    // if ($bidAmount > $currentBid) {
    //     // Update the current highest bid
    //     updateCurrentBid($bidAmount); // Function to update the current highest bid (to a database)

    //     // Inform the user that the bid was successful
    //     $success = "Bid placed successfully!";
    // } else {
    //     // Inform the user that their bid is too low
    //     $error = "Your bid must be higher than the current highest bid.";
    // }
}

// Function to get the current highest bid (you would fetch this from a database)
// function getCurrentBid() {
//     $query = "SELECT MAX(bidamount) AS current_bid FROM bidreport";
//     $stmt = $pdo->query($query);
//     $result = $stmt->fetch(PDO::FETCH_ASSOC);
//     return $result['current_bid'] ?? 0;
// }

// Function to update the current highest bid (you would update this in a database)
// function updateCurrentBid($newBid) {
//     $query = "UPDATE bids SET bid_amount = :newBid WHERE id = 1"; // Assuming the bid is stored in a table named "bids" with an identifier 'id'
//     $stmt = $pdo->prepare($query);
//     $stmt->bindParam(':newBid', $newBid, PDO::PARAM_INT);
//     $stmt->execute();
//     echo "New highest bid: $newBid";
// }

// try {
//     // Handle database connection errors or query execution errors
//     // catch (PDOException $e) {
//     //     die("Error: " . $e->getMessage());
//     // }
// } catch (PDOException $e) {
//     // Handle database connection errors or query execution errors
//     die("Error: " . $e->getMessage());
// }

//     catch (PDOException $e) {
//         // Handle database connection errors or query execution errors
//         die("Error: " . $e->getMessage());
//     }
//         // Handle database connection errors or query execution errors
//         die("Error: " . $e->getMessage());



?>