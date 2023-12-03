<?php
include_once("config.php");

session_start();
// only admin can access
if ($_SESSION['UserRole'] != 'admin') {
    header('Location: index.php');
    exit();
}

if (isset($_GET['UserID'])) {
    $UserID = $_GET['UserID'];
    $sql = "DELETE FROM users WHERE UserID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $UserID);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo "User deleted successfully.";
    } else {
        echo "Error deleting user.";
    }
}
?>
