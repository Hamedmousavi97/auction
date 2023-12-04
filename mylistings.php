<?php

    // this page is set to show the seller all their created auctions. 
    // requirements and imports.
    include_once("header.php");
    include_once("config.php");
    require("utilities.php");

    // check for get values from the users.
    if (isset($_GET['deleteAuction']) && isset($_GET['auctionID'])) {

        // get the auction ID and username
        $auctionID = $_GET['auctionID'];
        $username = $_SESSION['username']; 
        deleteAuction($auctionID);
        header('Location: mylistings.php');
        exit();
    }

    // Check user's credentials (cookie/session).
    if (!isset($_SESSION['username'])) {
        header('Location: browse.php');
        exit();
    }

    // Create a connection to the database
    $conn->set_charset("utf8");

    // Assuming you have a user ID stored in a variable
    $username = $_SESSION['username'];

    // Initialize $ordering and $keyword
    $ordering = isset($_GET['order_by']) ? $_GET['order_by'] : 'pricelow';
    $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';


    // If the keyword is not set, set it to an empty string
    if ($keyword === null) {
        $keyword = '';
    }

    // Fetch categories for dropdown for categories
    $sqlCategories = "SELECT * FROM categories";
    $resultCategories = mysqli_query($conn, $sqlCategories);

    // get the set categories from client side. 
    $category = isset($_GET['cat']) ? $_GET['cat'] : 'all';

    // search for the desired categories.
    if ($category !== 'all') {
        $categories = is_numeric($category) ? "AND auctionCategoryID = $category" : "AND auctionCategory = '$category'";
    } else {
        $categories = '';
    }

    // Order by clause
    if ($ordering === 'pricelow') {
        $orderByClause = 'ORDER BY auctionStartPrice ASC';
    } elseif ($ordering === 'pricehigh') {
        $orderByClause = 'ORDER BY auctionStartPrice DESC';
    } elseif ($ordering === 'date') {
        $orderByClause = 'ORDER BY auctionEndDate ASC';
    } else {
        $orderByClause = 'ORDER BY auctionID DESC';
    }

    // Pagination variables
    $num_results = 96;
    $results_per_page = 5;
    $max_page = ceil($num_results / $results_per_page);
    $curr_page = isset($_GET['page']) ? $_GET['page'] : 1;

    // Calculate start row for pagination
    $start_row = ($curr_page - 1) * $results_per_page;

    // get the relevant auctions for the user and their search keywords. 
    $query = "SELECT * FROM auctions WHERE UserName = ? $categories AND (auctionTitle LIKE '%$keyword%' OR auctionDetails LIKE '%$keyword%') $orderByClause LIMIT $start_row, $results_per_page";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    // get the result.
    $result = mysqli_stmt_get_result($stmt);

?>

<div class="container">

    <!-- page title.--> 
    <h2 class="my-3">My listings</h2>

    <!-- search form-->
    <form method="get" action="mylistings.php">
        <div class="row">
            <div class="col-md-5 pr-0">
                <div class="form-group">
                    <label for="keyword" class="sr-only">Search keyword:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent pr-0 text-muted">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control border-left-0" id="keyword" name="keyword" placeholder="Search for anything">
                    </div>
                </div>
            </div>
            <div class="col-md-3 pr-0">
                <div class="form-group">
                    <label for="cat" class="sr-only">Search within:</label>
                    <select class="form-control" id="cat" name="cat">
                        <option selected value="all">All categories</option>

                        <?php

                            // Loop through the result set and generate options
                            if ($resultCategories && mysqli_num_rows($resultCategories) > 0) {
                                while ($row = mysqli_fetch_array($resultCategories)) {
                                    
                                    // Add "selected" attribute if the category matches the one in the URL
                                    $selected = ($row['categoryName'] == $category) ? 'selected' : '';
                                    echo "<option value='" . $row['categoryName'] . "' $selected>" . $row['categoryName'] . "</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3 pr-0">
                <div class="form-inline">
                    <label class="mx-2" for="order_by">Sort by:</label>
                    <select class="form-control" id="order_by" name="order_by">
                        <option value="pricelow" <?php echo ($ordering === 'pricelow' ? 'selected' : ''); ?>>Price (low to high)</option>
                        <option value="pricehigh" <?php echo ($ordering === 'pricehigh' ? 'selected' : ''); ?>>Price (high to low)</option>
                        <option value="date" <?php echo ($ordering === 'date' ? 'selected' : ''); ?>>Soonest expiry</option>
                    </select>
                </div>
            </div>
            <div class="col-md-1 px-0">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </div>
    </form>

    <div class="container mt-5">
    <?php
        /// set the keyword.
        if (!isset($_GET['keyword'])) {

            // Define behavior if a keyword has not been specified.
            $keyword = '';

            // showing all listings for the user.
            echo '<h3>All listings</h3>';
        } else {

            // if the keyword is defined.
            $keyword = $_GET['keyword'];
            if ($keyword == '' ){

                //show all listings if the user is done with the search. 
                echo '<h3>All listings</h3>';
            } else {

                // show the search keyword.
                echo '<h3>Search results for "' . $keyword . '"</h3>';
            }
        }
    ?>
    <ul class="list-group">
        <?php

            // Loop through results and print them out as list items.
            if ($result && mysqli_num_rows($result) > 0) {

                // show the results.
                echo '<ul class="list-group">';

                // loop through the results and print them out.
                while ($row = mysqli_fetch_array($result)) {

                    // printing out the list item
                    echo '<li class="list-group-item">';

                    // check if the image is empty.
                    if (!empty($row['Image'])) {

                        // show the image.
                        echo '<img src="data:image/jpg;charset=utf8;base64,'. $row['Image'] .'" width="100" height="100"/>';
                    } else {

                        // show the default image.
                        echo '<img src="https://i1.sndcdn.com/avatars-000568343097-2ul7ra-t240x240.jpg" alt="Default Image" style="width: 100px; height: 100px;">';
                    }

                    // print the list item.
                    printListingLi($row['auctionID'], $row['auctionTitle'], $row['auctionDetails'], $row['auctionCurrentPrice'], $row['NumBid'], $row['auctionEndDate'], $row['auctionCategory'], $row['UserName'], $row['auctionStartDate']);
                    
                    // delete auction
                    if ($username == $row['UserName'] && $row['auctionCurrentPrice'] < $row['auctionReservePrice']) {

                        // show the delete button.
                        echo '<a class="btn btn-danger btn-sm" href="mylistings.php?deleteAuction=true&auctionID=' . $row['auctionID'] . '" onclick="return confirm(\'Are you sure you want to delete this auction?\');">Delete Auction</a>';
                    }
                    echo '</li>';
                    echo '<br>';
                }
                echo '</ul>';
            } else {

                // show the user that they have no listings.
                echo "<p>You have no listings.</p>";
            }
            ?>
        </ul>
    </div>

    <div class="container mt-3">

        <!-- Display the pagination links here -->
        <nav aria-label="Search results pages" class="mt-5">
            <ul class="pagination justify-content-center">
                <?php

                // Copy any currently-set GET variables to the URL.
                $querystring = "";
                foreach ($_GET as $key => $value) {

                    // Ignore the "page" key if it exists.
                    if ($key != "page") {
                        $querystring .= "$key=$value&amp;";
                    }
                }

                // Calculate the number of pages to display
                $high_page_boost = max(3 - $curr_page, 0);
                $low_page_boost = max(2 - ($max_page - $curr_page), 0);
                $low_page = max(1, $curr_page - 2 - $low_page_boost);
                $high_page = min($max_page, $curr_page + 2 + $high_page_boost);

                // Display links to pages
                if ($curr_page != 1) {
                    echo('<li class="page-item">
                            <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page - 1) . '" aria-label="Previous">
                                <span aria-hidden="true"><i class="fa fa-arrow-left"></i></span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>');
                }

                // Loop through the pages
                for ($i = $low_page; $i <= $high_page; $i++) {
                    if ($i == $curr_page) {

                        // Highlight the link
                        echo('<li class="page-item active">');
                    } else {

                        // Non-highlighted link
                        echo('<li class="page-item">');
                    }

                    // Do this in any case
                    echo('<a class="page-link" href="mylistings.php?' . $querystring . 'page=' . $i . '">' . $i . '</a></li>');
                }

                // Display "Next" link if not on the last page
                if ($curr_page != $max_page) {
                    echo('<li class="page-item">
                            <a class="page-link" href="mylistings.php?' . $querystring . 'page=' . ($curr_page + 1) . '" aria-label="Next">
                                <span aria-hidden="true"><i class="fa fa-arrow-right"></i></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>');
                }
                ?>
        </ul>
    </div>
</div>

<?php include_once("footer.php") ?>
