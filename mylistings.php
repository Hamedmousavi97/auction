<?php
include_once("header.php");
include_once("config.php");
require("utilities.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// TODO: Check user's credentials (cookie/session).
if (!isset($_SESSION['username'])) {
    header('Location: browse.php');
    exit();
}

// TODO: Connect to the database
$db_server = "localhost";
$db_username = "root";
$db_password = "root";
$db_name = "Auction";

// Create a connection to the database
$conn = mysqli_connect($db_server, $db_username, $db_password, $db_name);
$conn->set_charset("utf8");

// Check connection
/* if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo "Connected successfully"; // Add this line for debugging purposes
} */

// Assuming you have a user ID stored in a variable
$username = $_SESSION['username'];

// Initialize $ordering and $keyword
$ordering = isset($_GET['order_by']) ? $_GET['order_by'] : 'pricelow';
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';


// If the keyword is not set, set it to an empty string
if ($keyword === null) {
    $keyword = '';
}

// Fetch categories for dropdown
$sqlCategories = "SELECT * FROM categories";
$resultCategories = mysqli_query($conn, $sqlCategories);

$category = isset($_GET['cat']) ? $_GET['cat'] : 'all';

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

$query = "SELECT * FROM auctions WHERE UserName = ? $categories AND (auctionTitle LIKE '%$keyword%' OR auctionDetails LIKE '%$keyword%') $orderByClause LIMIT $start_row, $results_per_page";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>

<div class="container">
    <h2 class="my-3">My listings</h2>

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
    echo '<h3>Search results for "' . $keyword . '"</h3>';
    ?>
        <ul class="list-group">
            <?php
            // Loop through results and print them out as list items.
            if ($result && mysqli_num_rows($result) > 0) {
                echo '<ul class="list-group">';
                while ($row = mysqli_fetch_array($result)) {
                    # printing out the list item
                    echo '<li class="list-group-item">';
                    printListingLi($row['auctionID'], $row['auctionTitle'], $row['auctionDetails'], $row['auctionCurrentPrice'], $row['NumBid'], $row['auctionEndDate'], $row['auctionCategory'], $row['UserName'], $row['auctionStartDate']);
                    echo '</li>';
                    echo '<br>';
                }
                echo '</ul>';
            } else {
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
                if ($key != "page") {
                    $querystring .= "$key=$value&amp;";
                }
            }
            $high_page_boost = max(3 - $curr_page, 0);
            $low_page_boost = max(2 - ($max_page - $curr_page), 0);
            $low_page = max(1, $curr_page - 2 - $low_page_boost);
            $high_page = min($max_page, $curr_page + 2 + $high_page_boost);
            if ($curr_page != 1) {
                echo('<li class="page-item">
                        <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page - 1) . '" aria-label="Previous">
                            <span aria-hidden="true"><i class="fa fa-arrow-left"></i></span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>');
            }
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
