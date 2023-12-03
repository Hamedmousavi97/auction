<?php


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

    // get the auction details from the database
    $sql = "SELECT * FROM users WHERE UserName = '$auctionCreator'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);

    // Check for errors
    if (!$result) {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    } else {

        // get the auction details from the database
        $currentRating = $row['UserRating'];
        $ratingCount = $row['UserRatingCount'];
    }

    // check if the user is the auction creator
    if ($auctionCreator == $username) {
        echo '<script>
        alert("You cannot rate yourself!");
        window.history.back();
        </script>';
    } else {

        // Check if the rating is between 1 and 5
        if ($rating >= 1 && $rating <= 5 ) {

            //Update user rating
            $currentRating = $currentRating*$ratingCount + $rating;
            $ratingCount = $ratingCount + 1;
            $currentRating = $currentRating/$ratingCount;
            
            // Update the current user rating in the table
            $stmt = $conn->prepare("UPDATE users SET UserRating = '$currentRating', UserRatingCount = '$ratingCount' WHERE UserName = '$auctionCreator'" );
            if ($stmt->execute()) {
                echo '<script>
                alert("Rated successfully!");
                window.history.back();
                </script>';

                // Redirect to same page as before
                header("Location: <a href=`listing.php?item_id=' . $item_id . '`>");
            } else {
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
?>