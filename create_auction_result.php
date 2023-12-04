<?php

    // This page is used to process the form data from create_auction.php
    // Include configuration and header
    require_once("config.php");
    require_once("utilities.php");
    include_once("header.php");

    // Check if the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        /* #2: Extract form data into variables. Because the form was a 'post'
        form, its data can be accessed via $POST['auctionTitle'],
        $POST['auctionDetails'], etc. Perform checking on the data to
        make sure it can be inserted into the database. If there is an
        issue, give some semi-helpful feedback to the user. 
        */
        
        // Sanitize and validate form data
        $auctionTitle = mysqli_real_escape_string($conn, $_POST['auctionTitle']);
        $auctionDetails = mysqli_real_escape_string($conn, $_POST['auctionDetails']);
        $auctionCategory = mysqli_real_escape_string($conn, $_POST['auctionCategory']);
        $auctionStartPrice = mysqli_real_escape_string($conn, $_POST['auctionStartPrice']);
        $auctionReservePrice = mysqli_real_escape_string($conn, $_POST['auctionReservePrice']);
        $auctionEndDate = mysqli_real_escape_string($conn, $_POST['auctionEndDate']);
        $username = $_SESSION['username'];
        $auctionCurrentPrice = $auctionStartPrice;

        // Add a condition to check if all fields have been filled out. If not, display a message and redirect to the add auction page.
        if (empty($auctionTitle) || empty($auctionDetails) || empty($auctionCategory) || empty($auctionStartPrice) || empty($auctionReservePrice) || empty($auctionEndDate)) {
            echo '<script>
                    alert("Please fill up all the fields.!");
                    window.history.back();
                </script>';
            header("url=create_auction.php");
            exit();
        }

        // Add a condition to check if the start price is less than 0. If it is, display a message and redirect to the add auction page.
        if ($auctionStartPrice < 0) {
            echo '<script>
                    alert("You can not sell your product less than 0!");
                    window.history.back();
                </script>';
            header("url=create_auction.php");
            exit();
        }

        // Add a condition to check if the reserve price is less than 0. If it is, display a message and redirect to the add auction page.
        if ($auctionReservePrice < 0) {
            echo '<script>
                    alert("Reserve Price should be more than 0!");
                    window.history.back();
                </script>';
            header("url=create_auction.php");
            exit();
        }

        // Add a condition to check if the end date is less than the current date. If it is, display a message and redirect to the add auction page.
        if ($auctionEndDate < date("Y-m-d")) {
            echo '<script>
                    alert("The End date should be in future not past!");
                    window.history.back();
                </script>';
            header("url=create_auction.php");
            exit();
        }

        // Add a condition to check if the start price is less than the reserve price. If it is, display a message and redirect to the add auction page.
        if ($auctionStartPrice > $auctionReservePrice) {
            echo '<script>
                    alert("You can not sell your product less than your start price!");
                    window.history.back();
                </script>';
            header("url=create_auction.php");
            exit();
        }

        // Set the image variable to an empty string
        $Image = '';

        // Check if the image has been uploaded
        if (isset($_FILES['Image']) && $_FILES['Image']['error'] == 0) {

            // Check if the file is an image
            $file = $_FILES['Image'];
            $allowedTypes = ['image/jpeg'];

            // set the maximum size of the file
            $maxSize = 5 * 1024 * 1024; // 5 MB

            // Check if the file is of an allowed type
            if (!in_array($file['type'], $allowedTypes)) {
                echo '<div class="alert alert-danger">Invalid file type. Only JPG, PNG, and GIF files are allowed.</div>';
                exit();
            }

            // Check if the file is too large
            if ($file['size'] > $maxSize) {
                echo '<div class="alert alert-danger">File is too large. Maximum size is 5MB.</div>';
                exit();
            }

            // Check if the file has been uploaded successfully
            $imageData = file_get_contents($file['tmp_name']);

            // Convert the image data into base64
            $base64Image = base64_encode($imageData);
        } else {
            // If the image has not been uploaded, display a message and redirect to the add auction page.
            echo '<div class="alert alert-danger">Please upload an image.</div>';
            exit();
        }
    }

    /* #3: If everything looks good, make the appropriate call to insert
                data into the database. */

    // Set the SQL statement to insert the data into the database for auctions table.
    $stmt = $conn->prepare("INSERT INTO auctions (auctionTitle, auctionDetails, auctionCategory, auctionStartPrice, auctionReservePrice, auctionEndDate, auctionCurrentPrice, UserName, Image) VALUES ('$auctionTitle', '$auctionDetails', '$auctionCategory', '$auctionStartPrice', '$auctionReservePrice', '$auctionEndDate', '$auctionCurrentPrice', '$username', '$base64Image')");

    // Execute the prepared statement
    if ($stmt->execute()) {

        // if the auction is created successfully, send an email to the user
        echo "<br>";
        echo "<br>";
        $message = "Creation of $auctionTitle successful, please log in to view the details.";
        SendEmail($email, $subject, $message);

    } else {

        // If execution fails, display error
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // If all is successful, let user know.
    echo('<div class="text-center" style="font-size: 50px; font-weight: bold; margin-top: 20px;">Auction successfully created! View it <a href="mylistings.php">here</a>.</div>');
?>

<?php include_once("footer.php") ?>
