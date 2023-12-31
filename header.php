<?php

  // Start session
  // Header page where all pages will include for specific users. 
  session_start();
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
  <!-- Bootstrap and FontAwesome CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <!-- Custom CSS file -->
  <link rel="stylesheet" href="css/custom.css">

    <!-- Add new style for biddin-history table -->
    <style>
    .bidding-history h3 {
    font-size: 1.5em;
    margin-top: 20px;
    }

    .bidding-history table {
      width: 100%;
      border-collapse: collapse;
      border: 1px solid black;
    }

    .bidding-history th, .bidding-history td {
      border: 1px solid black;
      padding: 8px;
    }

    .bidding-history th {
      background-color: #f2f2f2;
      text-align: left;
    }
  </style>

  <!-- Website title -->
  <title>Group 2 Auction Website </title>
</head>


<body>

  <!-- Navbars -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light mx-2">
    <a class="navbar-brand" href="#">Group 2 Auction Website <!--CHANGEME!--></a>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
    
        <?php
          // Displays either login or logout on the right, depending on user's login status
          if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {

            // display logout and users name if logged in
            echo '<a class="nav-link" href="logout.php">Welcome '.$_SESSION['username'].'! Logout</a>';
          } else {
            // display login if not logged in
            echo '<button type="button" class="btn nav-link" data-toggle="modal" data-target="#loginModal">Login</button>';
        }
        ?>
      </li>
    </ul>
  </nav>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <ul class="navbar-nav align-middle">
      <li class="nav-item mx-1">

        <!-- show browse link -->
        <a class="nav-link" href="browse.php">Browse</a>
      </li>
      <?php

        // if admin, display admin dashboard link
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true && $_SESSION['account_type'] == 'admin') {
          echo '<a class="nav-link" href="admin_dashboard.php">Admin Dashboard</a>';
        }

        // if logged in as buyer, display my bids, my listings, and create auction links and watchlist
        if (isset($_SESSION['account_type']) && $_SESSION['account_type'] == 'buyer') {
          echo('
            <li class="nav-item mx-1">
              <a class="nav-link" href="mybids.php">My Bids</a>
            </li>
            <li class="nav-item mx-1">
              <a class="nav-link" href="recommendations.php">Recommended</a>
            </li>
            <li class="nav-item mx-1">
              <a class="nav-link" href="watchlist.php">Watchlist</a>
            </li>'
          );
        }

        // if logged in as seller, display my bids, my listings, and create auction links and watchlist
        if (isset($_SESSION['account_type']) && $_SESSION['account_type'] == 'seller') {
        echo('
          <li class="nav-item mx-1">
            <a class="nav-link" href="mybids.php">My Bids</a>
          </li>
          <li class="nav-item mx-1">
              <a class="nav-link" href="mylistings.php">My Listings</a>
            </li>
          <li class="nav-item ml-3">
              <a class="nav-link btn border-light" href="create_auction.php">+ Create auction</a>
            </li>
            <li class="nav-item mx-1">
            <a class="nav-link" href="recommendations.php">Recommended</a>
          </li>
          <li class="nav-item mx-1">
              <a class="nav-link" href="watchlist.php">Watchlist</a>
            </li>'
          );
        }
      ?>
    </ul>
  </nav>

  <!-- Login modal -->
  <div class="modal fade" id="loginModal">
    <div class="modal-dialog">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Login</h4>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
          <form method="POST" action="login_result.php">
            <div class="form-group">
              <label for="email">Email</label>
              <input type="text" class="form-control" id="email" placeholder="Email" name="email">
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" class="form-control" id="password" placeholder="Password" name="password">
            </div>
            <button type="submit" class="btn btn-primary form-control">Sign in</button>
          </form>
          <div class="text-center">or <a href="register.php">create an account</a></div>
        </div>

      </div>
    </div>
  </div> <!-- End modal -->
