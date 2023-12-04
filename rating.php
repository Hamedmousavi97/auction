<?php

    // This page is used to handle the rating process
    // Include configuration and header
    require_once("config.php");
    include_once("header.php");
    require_once("browse.php");

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Retrieve user input
        $rating = isset($_POST['ratingAmount']) ? (int)$_POST['ratingAmount'] : 0;
        $item_id = isset($_POST['item_id']) ? (int)$_POST['item_id'] : null;
        $auctionCreator = isset($_POST['auctionCreator']) ? $_POST['auctionCreator'] : null;
        $username = $_SESSION['username'];

        // get the user details from the database
        $sql = "SELECT * FROM users WHERE UserName = '$auctionCreator'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result);

        // Check for errors
        if (!$result) {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        } else {

            // get the user rating details from the database
            $currentRating = $row['UserRating'];
            $ratingCount = $row['UserRatingCount'];
        }

        
        //Check if the user has already rated the auction creator in the rating table
        $sql2 = "SELECT * FROM ratings WHERE auctionID = '$item_id' AND ratingUsername = '$username'";
        $result2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_array($result2);

        // Check for errors
        if (!$row2) {
            echo "Error: " . $sql2 . "<br>" . mysqli_error($conn);
        } else {

            // get the user rating details from the database
            $ratingExists = $row2['ratingUsername'];
        }

        // Check that only the auction winner can rate the auction creator from the auctios tableand the bidreport table. 
        $sql3 = "SELECT * FROM auctions JOIN bidreport ON auctions.BidID = bidreport.bidid WHERE auctions.auctionID = '$item_id' ";
        $result3 = mysqli_query($conn, $sql3);
        $row3 = mysqli_fetch_array($result3);

        // Check for errors
        if (!$row3) {
            echo "Error: " . $sql3 . "<br>" . mysqli_error($conn);
        } else {

            // get the auction winner
            $auctionWinner = $row3['bidUsername'];
        }
        
        // Check if the user has already rated the auction creator
        if ($ratingExists == $username) {
            echo '<script>
                    alert("You have already rated this user!");
                    window.history.back();
                </script>';
        } else {

            // Check if the user is the auction winner
            if ($auctionWinner != $username) {

                // Inform the user that only the auction winner can rate the auction creator
                echo '<script>
                        alert("Only the auction winner can rate the auction creator!");
                        window.history.back();
                        console.log("auctionWinner: " . $auctionWinner);
                    </script>';
            } else {

                // check if the user is the auction creator
                if ($auctionCreator == $username) {
                    echo '<script>
                    alert("You cannot rate yourself!");
                    window.history.back();
                    </script>';
                } else {

                    // Check if the rating is between 1 and 5 user can only pick an option but this is for 
                    // extra security
                    if ($rating >= 1 && $rating <= 5 ) {

                        //Update user rating
                        $currentRating = $currentRating*$ratingCount + $rating;
                        $ratingCount = $ratingCount + 1;
                        $currentRating = $currentRating/$ratingCount;
                        
                        // Update the current user rating in the table
                        $stmt = $conn->prepare("UPDATE users SET UserRating = '$currentRating', UserRatingCount = '$ratingCount' WHERE UserName = '$auctionCreator'" );
                        if ($stmt->execute()) {

                            // Insert data into ratings table
                            $stmt2 = $conn->prepare("INSERT INTO ratings (auctionID, ratingUsername, ratingAmount, ratedUsername) VALUES ('$item_id', '$username', '$rating', '$auctionCreator')");
                            if ($stmt2->execute()) {

                                // Show success message
                                echo '<script>
                                        alert("Rated successfully!");
                                        window.history.back();
                                    </script>';

                                // Redirect to same page as before
                                header("Location: <a href=`listing.php?item_id=' . $item_id . '`>");
                            } else {

                                // Show error message
                                echo "Error inserting your data into the database: " . $stmt->error;
                            }
                        } else {

                            // Show error message
                            echo "Error inserting your data into the database: " . $stmt->error;
                        }
                    } else {

                        // Inform the user that their bid is too low
                        echo '<script>
                                alert("Please make sure to enter a valid number. Your rating should be a number between 1 and 5.");
                                window.history.back();
                            </script>';
                    }

                    // Close the statement and connection
                    $stmt->close();
                    $conn->close();
                }
            }    
        }
    }
?>