<?php
include_once("header.php");
include_once("utilities.php");

// check if delete user or auction
if (isset($_GET['deleteUser']) && isset($_GET['UserID'])) {
    deleteUser($_GET['UserID']);
}
if (isset($_GET['admin_deleteAuction']) && isset($_GET['AuctionID'])) {
    admin_deleteAuction($_GET['AuctionID']);
}

// get all users and auctions
$users = getAllUsers();
$auctions = getAllAuctions();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <!-- Bootstrap  -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        .container {
            padding: 20px;
        }

        .table-container {
            margin: auto;
            width: 80%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #eaeaea;
        }

        a.delete-link {
            color: #ff0000;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        
        <div class="table-container">
            <h2>Users</h2>
            <table>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['UserID']; ?></td>
                        <td><?php echo $user['UserName']; ?></td>
                        <td>
                            <a href="admin_dashboard.php?deleteUser=true&UserID=<?php echo $user['UserID']; ?>"
                            onclick="return confirm('Are you sure you want to delete this user?');">Delete</a></td>
                        
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div class="table-container">
            <h2>Auctions</h2>
            <table>
                <tr>
                    <th>Auction ID</th>
                    <th>Title</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($auctions as $auction): ?>
                    <tr>
                        <td><?php echo $auction['auctionID']; ?></td>
                        
                        <td>
                    <!-- hyperlink-->
                            <a href="listing.php?auctionID=<?php echo $auction['auctionID']; ?>">
                                <?php echo $auction['auctionTitle']; ?>
                            </a>
                        </td>
                        <td>
                            <a href="admin_dashboard.php?admin_deleteAuction=true&AuctionID=<?php echo $auction['auctionID']; ?>"
                            onclick="return confirm('Are you sure you want to delete this auction?');">Delete</a>
                        </td>
                        
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>
</html>
