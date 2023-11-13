<?php 

/* TODO #1: Connect to MySQL database (perhaps by requiring a file that
            already does this). */
require_once("config.php");
include_once("header.php")?>

<div class="container my-5">

<?php

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    /* TODO #2: Extract form data into variables. Because the form was a 'post'
                form, its data can be accessed via $POST['auctionTitle'], 
                $POST['auctionDetails'], etc. Perform checking on the data to
                make sure it can be inserted into the database. If there is an
                issue, give some semi-helpful feedback to user. */
    $auctionTitle = mysqli_real_escape_string($conn, $_POST['auctionTitle']);
    $auctionDetails = mysqli_real_escape_string($conn, $_POST['auctionDetails']);
    $auctionCategory = mysqli_real_escape_string($conn, $_POST['auctionCategory']);
    $auctionStartPrice = mysqli_real_escape_string($conn, $_POST['auctionStartPrice']);
    $auctionReservePrice = mysqli_real_escape_string($conn, $_POST['auctionReservePrice']);
    $auctionEndDate = mysqli_real_escape_string($conn, $_POST['auctionEndDate']);

//Add a condition to check if all fields have been filled out. If not, display a message and redirect to the add auction page. 
if (empty($auctionTitle) || empty($auctionCategory) || empty($auctionStartPrice) || empty($auctionReservePrice) || empty($auctionEndDate)) {
    echo "<div class='alert alert-danger'>All fields are required. Please try again.</div>";
    header("refresh:2; url=create_auction.php");
    exit();
}

//Check if the reserve price is less than the start price. If it is, display a message and redirect to the add auction page. */
if ($auctionReservePrice < $auctionStartPrice) {
    echo "<div class='alert alert-danger'>The reserve price cannot be less than the start price. Please try again.</div>";
    header("refresh:2; url=create_auction.php");
    exit();
}
}
/* TODO #3: If everything looks good, make the appropriate call to insert
            data into the database. */

// prepare and bind
$stmt = $conn->prepare("INSERT INTO auctions (auctionTitle, auctionDetails, auctionCategory, auctionStartPrice, auctionReservePrice, auctionEndDate) VALUES ('$auctionTitle', '$auctionDetails', '$auctionCategory', '$auctionStartPrice', '$auctionReservePrice', '$auctionEndDate')");

// execute the prepared statement
if ($stmt->execute()) {
    echo "New record inserted successfully";
} else {
    echo "Error: " . $stmt->error;
}

// close statement and connection
$stmt->close();
$conn->close();           

// If all is successful, let user know.
echo('<div class="text-center">Auction successfully created! <a href="mylistings.php">View your new listing.</a></div>');


?>

</div>


<?php include_once("footer.php")?>