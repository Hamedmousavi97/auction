<!-- Browse page. In this page users are allowed to browse into the live auctions and 
search based on their desired criteria. -->
<?php include_once("header.php")?>
<?php require("utilities.php")?>

<!-- Search form -->
<div class="container">
<h2 class="my-3">Browse listings</h2>
<div id="searchSpecs">

    <!-- When this form is submitted, this PHP page is what processes it.
    Search/sort specs are passed to this page through parameters in the URL
    (GET method of passing data to a page). 
    Searching form.-->
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
                            <input type="text" class="form-control border-left-0" id="keyword" placeholder="Search for anything" name="keyword">
                        </div>
                    </div>
                </div>
                <div class="col-md-3 pr-0">
                    <div class="form-group">
                        <?php
                        require_once("config.php");

                        // Check connection
                        if (!$conn) {
                            die("Connection failed: " . mysqli_connect_error());
                        }

                        // Set encoding to match PHP script encoding.
                        $conn->set_charset("utf8");

                        // Get all categories from the database
                        $sql = "SELECT * FROM categories";
                        $result = mysqli_query($conn, $sql);

                        // Get the category from the URL, if it exists
                        $category = isset($_GET['cat']) ? $_GET['cat'] : 'all';


                        // Generate the category dropdown
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
                        }else {

                          // If no categories are found, display a message
                          echo "<option value=''>No categories found</option>";
                        }

                        // Set the ordering from the URL, if it exists
                        $ordering = isset($_GET['order_by']) ? $_GET['order_by'] : 'pricelow';
                        ?>
                        </select>
                    </div>
                </div>

                <!-- Sort by dropdown -->
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

                  <!-- Search button -->      
                  <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </form>
    <!-- End of search form -->

    <!-- Display the search/sort results -->
    <div class="container mt-5">
        <ul class="list-group">
        <?php

          // Retrieve these from the URL
          if (!isset($_GET['keyword'])) {

            // Define behavior if a keyword has not been specified.
            $keyword = '';
            echo '<h3>All listings</h3>';
          } else {

            // Define behavior if a keyword has been specified.
            $keyword = $_GET['keyword'];

            // Display the keyword in the heading of the table.
            if ($keyword == '' ){

              // Define behavior if a keyword has not been specified to show all listings.
              echo '<h3>All listings</h3>';
            } else {

              // Define behavior if a keyword has been specified to show the keyword in the heading.
              echo '<h3>Search results for "' . $keyword . '"</h3>';
            }
          }

          // Retrieve these from the URL
          if (!isset($_GET['cat'])) {

            // Define behavior if a category has not been specified.
            $category = 'all';
          } else {

            // Define behavior if a category has been specified.
            $category = $_GET['cat'];
          }

          // Retrieve these from the URL
          if (!isset($_GET['order_by'])) {

            // Define behavior if an order_by value has not been specified.
            $ordering = '';
          } else {

            // Define behavior if an order_by value has been specified.
            $ordering = $_GET['order_by'];
          }

          // Retrieve these from the URL
          if (!isset($_GET['page'])) {

            // Define behavior if a page has not been specified.
            $curr_page = 1;
          }
          else {

            // Define behavior if a page has been specified.
            $curr_page = $_GET['page'];
          }

          // Show 5 results per page and fix the maximum number of pages 
          // Query to get the total number of results from the auction table. Show only the results that are not finished and that match the keyword.
          $sql = "SELECT count(*) FROM auctions WHERE isFinished = 0 AND (auctionTitle LIKE '%$keyword%' OR auctionDetails LIKE '%$keyword%')";
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_row($result);

          // Calculate the number of pages needed to display the results.
          $num_results = $row[0];

          // Set the number of results per page.
          $results_per_page = 5;
          $max_page = ceil($num_results / $results_per_page);

          // Fix the maximum number of pages to 5.
          if ($max_page > $results_per_page) {
            $max_page = $results_per_page;
          }

          // Calculate the start and end row numbers for the results to be displayed.
          $start_row = ($curr_page - 1) * $results_per_page;
          $end_row = ($curr_page + $results_per_page) - 1;
          if ($end_row > $num_results) {
            $end_row = $num_results;
          }

          // Sort the results based on the order_by value.
          if ($ordering === 'pricelow') {
            $orderByClause = 'ORDER BY auctionStartPrice ASC';
          } elseif ($ordering === 'pricehigh') {
            $orderByClause = 'ORDER BY auctionStartPrice DESC';
          } elseif ($ordering === 'date') {
            $orderByClause = 'ORDER BY auctionEndDate ASC';
          } else {
            $orderByClause = 'ORDER BY auctionID DESC';
          }

          // Set the category to be searched for.
          if ($category === 'all') {
            $categories = '';
          } else {

            // If the category is numeric, search by category ID; otherwise, search by category name.
            $categories = is_numeric($category)
            ? "AND auctionCategoryID = $category"
            : "AND auctionCategory = '$category'";
          }

          // Query to get the results from the auction table. Show only the results that are not finished and that match the keyword.
          $query = "SELECT * FROM auctions WHERE isFinished = 0 AND 1 $categories AND (auctionTitle LIKE '%$keyword%' OR auctionDetails LIKE '%$keyword%') AND auctionEndDate > NOW() $orderByClause LIMIT $start_row, $results_per_page";
            $newAuctionsResult = mysqli_query($conn, $query);

            // Check if there are rows in the result set
            if ($newAuctionsResult && mysqli_num_rows($newAuctionsResult) > 0) {

              // Loop through the rows in the result set
              while ($row = mysqli_fetch_assoc($newAuctionsResult)) {

                // Display the results in a list
                echo '<li class="list-group-item">';

                // Display the image if there is one
                if (!empty($row['Image'])) {

                  // Display the image
                  echo '<img src="data:image/jpg;charset=utf8;base64,'. $row['Image'] .'" width="100" height="100"/>';
                } else {

                  // Display the default image
                  echo '<img src="https://i1.sndcdn.com/avatars-000568343097-2ul7ra-t240x240.jpg" alt="Default Image" style="width: 100px; height: 100px;">';
                }

                // Displaying the auction details
                printListingLi($row['auctionID'], $row['auctionTitle'], $row['auctionDetails'], $row['auctionCurrentPrice'], $row['NumBid'], $row['auctionEndDate'], $row['auctionCategory'], $row['UserName'], $row['auctionStartDate']);
                echo '</li>';
                echo '<br>';
              }
            } else {

              // If no results are found, display a message
              echo "<p>No results found. Please try another keyword</p>";
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

        // Loop through all GET variables
        foreach ($_GET as $key => $value) {
          if ($key != "page") {

            // Add the GET variable to the querystring
            $querystring .= "$key=$value&amp;";
          }
        }

        // Calculate the page numbers to display
        $high_page_boost = max(3 - $curr_page, 0);

        // If the current page is less than 3, show the first 5 pages
        $low_page_boost = max(2 - ($max_page - $curr_page), 0);

        // If the current page is greater than the max page - 2, show the last 5 pages
        $low_page = max(1, $curr_page - 2 - $low_page_boost);

        // If the current page is greater than the max page - 2, show the last 5 pages
        $high_page = min($max_page, $curr_page + 2 + $high_page_boost);

        // Display the page links
        if ($curr_page != 1) {
          echo('
            <li class="page-item">
              <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page - 1) . '" aria-label="Previous">
                <span aria-hidden="true"><i class="fafa-arrow-left"></i></span>
                <span class="sr-only">Previous</span>
              </a>
            </li>'
          );
        }

        // Loop through the page numbers to display links to them
        for ($i = $low_page; $i <= $high_page; $i++) {
          
          // Highlight the link
          if ($i == $curr_page) {

            echo('
              <li class="page-item active">'
            );
          } else {
          
            // Non-highlighted link
            echo('
              <li class="page-item">'
            );
          }

          // Do this in any case
          echo('
            <a class="page-link" href="browse.php?' . $querystring . 'page=' . $i . '">' . $i . '</a>
            </li>'
          );
        } 

        // Display the next page link
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
