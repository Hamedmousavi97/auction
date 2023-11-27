<?php include_once("header.php")?>
<?php require("utilities.php")?>


<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<div class="container">
<h2 class="my-3">Browse listings</h2>
<div id="searchSpecs">
<!-- When this form is submitted, this PHP page is what processes it.
     Search/sort specs are passed to this page through parameters in the URL
     (GET method of passing data to a page). -->
     <form method="get" action="browse.php">
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
                            <input type="text" class="form-control border-left-0" id="keyword" placeholder="Search for anything">
                        </div>
                    </div>
                </div>
                <div class="col-md-3 pr-0">
                    <div class="form-group">
                        <?php
                        require_once("config.php");

                        $db_server = "localhost";
                        $db_username = "root";
                        $db_password = "root";
                        $db_name = "Auction";

                        //create connection to database
                        $conn = mysqli_connect($db_server, $db_username, $db_password, $db_name);

                        // Check connection
                        if (!$conn) {
                            die("Connection failed: " . mysqli_connect_error());
                        }

                        $conn->set_charset("utf8");

                        $sql = "SELECT * FROM categories";
                        $result = mysqli_query($conn, $sql);

                        $category = isset($_GET['cat']) ? $_GET['cat'] : 'all';


                        echo '<label for="cat" class="sr-only">Search within:</label>';
                        echo '<select class="form-control" id="cat" name="cat">';
                        echo '<option selected value="all">All categories</option>';

                        // Loop through the result set and generate options
                        if ($result && mysqli_num_rows($result) > 0) {
                          while ($row = mysqli_fetch_array($result)) {
                              // Add "selected" attribute if the category matches the one in the URL
                              $selected = ($row['categoryName'] == $category) ? 'selected' : '';
                              echo "<option value='" . $row['categoryName'] . "' $selected>" . $row['categoryName'] . "</option>";
                            }
                        }
                        $ordering = isset($_GET['order_by']) ? $_GET['order_by'] : 'pricelow';

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
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </form>

    <div class="container mt-5">
        <ul class="list-group">
        <?php
  // Retrieve these from the URL
  if (!isset($_GET['keyword'])) {
    // TODO: Define behavior if a keyword has not been specified.
    $keyword = '';
  } else {
    $keyword = $_GET['keyword'];
  }

  if (!isset($_GET['cat'])) {
    $category = 'Sports';
  } else {
      $category = $_GET['cat'];
  }

  if (!isset($_GET['order_by'])) {
    // TODO: Define behavior if an order_by value has not been specified.
    $ordering = '';
  } else {
    $ordering = $_GET['order_by'];
  }
  if (!isset($_GET['page'])) {
    $curr_page = 1;
  }
  else {
    $curr_page = $_GET['page'];
  }
  /* TODO: Use above values to construct a query. Use this query to
                    retrieve data from the database. (If there is no form data entered,
                    decide on appropriate default value/default query to make. */
                    $num_results = 96;
                    $results_per_page = 10;
                    $max_page = ceil($num_results / $results_per_page);

                    if ($ordering === 'pricelow') {
                        $orderByClause = 'ORDER BY auctionStartPrice ASC';
                    } elseif ($ordering === 'pricehigh') {
                        $orderByClause = 'ORDER BY auctionStartPrice DESC';
                    } elseif ($ordering === 'date') {
                        $orderByClause = 'ORDER BY auctionEndDate ASC';
                    } else {
                        $orderByClause = 'ORDER BY auctionID DESC';
                    }

                    $start_row = ($curr_page - 1) * $results_per_page;

                    if ($category === 'all') {
                        $categories = '';
                    } else {
                        $categories = is_numeric($category)
                            ? "AND auctionCategoryID = $category"
                            : "AND auctionCategory = '$category'";
                    }

                    $query = "SELECT * FROM auctions WHERE 1 $categories $orderByClause LIMIT $start_row, $results_per_page";
                    $newAuctionsResult = mysqli_query($conn, $query);



                    if ($newAuctionsResult && mysqli_num_rows($newAuctionsResult) > 0) {
                        while ($row = mysqli_fetch_assoc($newAuctionsResult)) {
                            echo '<li class="list-group-item">';
                            printListingLi($row['auctionID'], $row['auctionTitle'], $row['auctionDetails'], $row['auctionCurrentPrice'], $row['NumBid'], $row['auctionEndDate'], $row['auctionCategory'], $row['UserName'], $row['auctionStartDate']);
                            echo '</li>';
                            echo '<br>';
                        }
                    }
                    ?>
                </ul>
            </div>
</ul>
<!-- Pagination for results listings -->
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
    echo('
    <li class="page-item">
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page - 1) . '" aria-label="Previous">
        <span aria-hidden="true"><i class="fa fa-arrow-left"></i></span>
        <span class="sr-only">Previous</span>
      </a>
    </li>');
  }
  for ($i = $low_page; $i <= $high_page; $i++) {
    if ($i == $curr_page) {
      // Highlight the link
      echo('
    <li class="page-item active">');
    }
    else {
      // Non-highlighted link
      echo('
    <li class="page-item">');
    }
    // Do this in any case
    echo('
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . $i . '">' . $i . '</a>
    </li>');
  }
  if ($curr_page != $max_page) {
    echo('
    <li class="page-item">
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page + 1) . '" aria-label="Next">
        <span aria-hidden="true"><i class="fa fa-arrow-right"></i></span>
        <span class="sr-only">Next</span>
      </a>
    </li>');
  }
?>
  </ul>
</nav>
</div>
<?php include_once("footer.php")?>
