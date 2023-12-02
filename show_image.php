<?php
require_once("config.php");

if(isset($_GET['id'])) {
    $id = intval($_GET['id']); 

    $query = "SELECT Image FROM auctions WHERE auctionID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imageData = $row['Image'];

        
        header("Content-Type: image/jpeg"); 
        header('Content-Length: ' . strlen($imageData));
        echo $imageData;
    } else {
        
        echo "No image found.";
    }
}
?>
