<?php

    // This file is for displaying the image of an auction item.
    // requirements
    require_once("config.php");

    // check if the auction id is set.
    if(isset($_GET['id'])) {

        // get the auction id.
        $id = intval($_GET['id']); 

        // get the image data from the database.
        $query = "SELECT Image FROM auctions WHERE auctionID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // get the result.
        $result = $stmt->get_result();

        // if there is an image, display it.
        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $imageData = $row['Image'];

            // set the header and display the image.
            header("Content-Type: image/jpeg"); 
            header('Content-Length: ' . strlen($imageData));
            echo $imageData;
        } else {
            
            // if there is no image, display a message.
            echo "No image found.";
        }
    }
?>
