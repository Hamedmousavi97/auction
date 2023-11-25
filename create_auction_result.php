<?php

// Include configuration and header
require_once("config.php");
include_once("header.php");

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /* TODO #2: Extract form data into variables. Because the form was a 'post'
              form, its data can be accessed via $POST['auctionTitle'],
              $POST['auctionDetails'], etc. Perform checking on the data to
              make sure it can be inserted into the database. If there is an
              issue, give some semi-helpful feedback to the user. */
    // Sanitize and validate form data
    $auctionTitle = mysqli_real_escape_string($conn, $_POST['auctionTitle']);
    $auctionDetails = mysqli_real_escape_string($conn, $_POST['auctionDetails']);
    $auctionCategory = mysqli_real_escape_string($conn, $_POST['auctionCategory']);
    $auctionStartPrice = mysqli_real_escape_string($conn, $_POST['auctionStartPrice']);
    $auctionReservePrice = mysqli_real_escape_string($conn, $_POST['auctionReservePrice']);
    $auctionEndDate = mysqli_real_escape_string($conn, $_POST['auctionEndDate']);
    $username = $_SESSION['username'];

    // Add a condition to check if all fields have been filled out. If not, display a message and redirect to the add auction page.
    // if (empty($auctionTitle) || empty($auctionDetails) || empty($auctionCategory) || empty($auctionStartPrice) || empty($auctionReservePrice) || empty($auctionEndDate)) {
    //     echo "<div class='alert alert-danger'>All fields are required. Please try again.</div>";
    //     header("refresh:2; url=create_auction.php");
    //     exit();
    // }

    // Check if reserve price is less than start price
if ($auctionReservePrice !== null && $auctionReservePrice !== 0 && $auctionReservePrice < $auctionStartPrice) {
    echo "<div class='alert alert-danger'>The reserve price cannot be less than the start price. Please try again.</div>";
    header("refresh:2; url=create_auction.php");
    exit();
}


    // Prepare and bind parameters for the SQL query
    $stmt = $conn->prepare("INSERT INTO auctions (auctionTitle, auctionDetails, auctionCategory, auctionStartPrice, auctionReservePrice, auctionEndDate, UserName) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $auctionTitle, $auctionDetails, $auctionCategory, $auctionStartPrice, $auctionReservePrice, $auctionEndDate, $username);

    // Execute the prepared statement
    if ($stmt->execute()) {
        echo "New record inserted successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Display success message and redirect
    echo('<div class="text-center">Auction successfully created! <a href="mylistings.php">View your new listing.</a></div>');
    header("refresh:2; url=browse.php");
    exit;
}

?>

<div class="container my-5">
    <!-- Your HTML content goes here -->
</div>

<?php include_once("footer.php") ?>
