<?php

    // Delete auction access for admins only
    include_once("config.php");

    session_start();

    // only admin can access
    if ($_SESSION['UserRole'] != 'admin') {
        header('Location: index.php');
        exit();
    }

    // delete auction
    // check if auctionID is set
    if (isset($_GET['auctionID'])) {
        $auctionID = $_GET['auctionID'];

        // delete auction sql query
        $sql = "DELETE FROM auctions WHERE auctionID = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $auctionID);
        mysqli_stmt_execute($stmt);

        // check if auction was deleted
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "Auction deleted successfully.";
        } else {
            echo "Error deleting auction.";
        }
    }
?>
