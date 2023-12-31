<?php

  // include the required files
  require_once("config.php");
  include_once("header.php");

?>

<!-- Create auction page. Where users can create a new auction. -->
<!-- Validate the form using JavaScript -->
<script>
    function validateForm() {

      // Get the value of the auction title field
      var auctionTitle = document.getElementById("auctionTitle").value.trim();

      // Regular expression pattern for allowing only letters, spaces, and common symbols
      var titleRegex = /^[a-zA-Z\s!"#$%&'()*+,-./:;<=>?@[\\\]^_`{|}~]+$/;

      // Check if the auction title is empty
      if (auctionTitle === "") {
        alert("Please enter a valid auction title");
        return false;
      }

      // Check if the auction title contains only letters and spaces
      if (!titleRegex.test(auctionTitle)) {
        alert("Please enter a title with only letters and spaces.");
        return false;
      }

      return true;
    }
</script>


<?php
  //(Uncomment this block to redirect people without selling privileges away from this page)
  // If user is not logged in or not a seller, they should not be able to
  // use this page.
  if (!isset($_SESSION['account_type']) || $_SESSION['account_type'] != 'seller') {
    header('Location: browse.php');
  }


?>

<!-- Create auction form -->
<div class="container">
<form method="post" action="create_auction_result.php" enctype="multipart/form-data" onsubmit="return validateForm();">
<!-- Create auction form -->
<div style="max-width: 800px; margin: 10px auto">

  <!-- Title -->
  <h2 class="my-3">Create new auction</h2>
  <div class="card">
    <div class="card-body">
      <form method="post" action="create_auction_result.php" enctype="multipart/form-data">
      
      <!-- Hidden input to store the username of the seller -->
      <input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
        <div class="form-group row">

          <!-- Auction title -->
          <label for="auctionTitle" class="col-sm-2 col-form-label text-right">Title of auction</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="auctionTitle" name="auctionTitle" placeholder="e.g. Black mountain bike">
            <small id="titleHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> A short description of the item you're selling, which will display in listings.</small>
          </div>
        </div>

        <!-- Auction details -->
        <div class="form-group row">
          <label for="auctionDetails" class="col-sm-2 col-form-label text-right">Details</label>
          <div class="col-sm-10">
          <textarea class="form-control" id="auctionDetails" rows="4" name="auctionDetails"></textarea>
            <small id="detailsHelp" class="form-text text-muted">Full details of the listing to help bidders decide if it's what they're looking for.</small>
          </div>
        </div>

        <!-- Auction image -->
        <div class="form-group row">
          <label for="Image" class="col-sm-2 col-form-label text-right">Upload Image:</label>
          <div class="col-sm-10">
            <input type="file" name="Image" id="Image" accept="Image/*">
            <small id="titleHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> A photo of the product to show the conditions of the product.</small>
          </div>

          <!-- Auction category -->
          <div class="form-group row">
            <label for="auctionCategory" class="col-sm-2 col-form-label text-right">Category</label>
            <div class="col-sm-10">

              <!-- Dropdown menu for categories -->
              <select class="form-control" id="auctionCategory" name="auctionCategory" onchange="showOtherCategory(this)">
              <?php

                // Get all categories from the database
                require_once("config.php");
                $sql = "SELECT * FROM categories";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_num_rows($result);

                // Check if there are rows in the result set
                if ($result && mysqli_num_rows($result) > 0) {
                  while ($row = mysqli_fetch_array($result)) {

                    // Display each category as an option in the dropdown menu
                    echo "<option value='" . $row['categoryName'] . "'>" . $row['categoryDescription'] . "</option>";
                  }
                } else {

                  // If there are no categories in the database, display a message
                  echo "<option value=''>No categories found</option>";
                }
              ?>
              </select>
              <small id="categoryHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Select a category for this item.</small>
            </div>
          </div>

          <!-- Other category -->
          <div class="form-group row" id="otherCategoryInput" style="display: none;">
            <div class="col-sm-10 offset-sm-2">
              <input type="text" class="form-control" id="otherCategory" name="otherCategory" placeholder="Enter a new category">
              <small id="titleHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Please write the new category of the product you are trying to sell.</small>
            </div>
          </div>

          <!-- show other categories -->
          <script>
            function showOtherCategory(select) {
              var otherCategoryInput = document.getElementById('otherCategoryInput');

              // If "Other" is selected, show the input field; otherwise, hide it
              otherCategoryInput.style.display = select.value === 'Other' ? 'block' : 'none';
            }
          </script>
          <div class="form-group row">

            <!-- Auction start price -->
            <label for="auctionStartPrice" class="col-sm-2 col-form-label text-right">Starting price</label>
            <div class="col-sm-10">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">£</span>
                </div>
                <input type="number" class="form-control" id="auctionStartPrice" name="auctionStartPrice">
              </div>
              <small id="startBidHelp" class="form-text text-muted"><span class="text-danger">* Required.</span>
                Initial bid amount.</small>
            </div>
          </div>

          <!-- Auction reserve price -->
          <div class="form-group row">
            <label for="auctionReservePrice" class="col-sm-2 col-form-label text-right">Reserve price</label>
            <div class="col-sm-10">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">£</span>
                </div>
                <input type="number" class="form-control" id="auctionReservePrice" name="auctionReservePrice">
              </div>
              <small id="startBidHelp" class="form-text text-muted"><span class="text-danger">* Required.</span>
                Please enter a reserve price. The reserve price is the minimum the seller would be willing to accept for an auction</small>
            </div>
          </div>

          <!-- Auction end date -->
          <div class="form-group row">
            <label for="auctionEndDate" class="col-sm-2 col-form-label text-right">End date</label>
            <div class="col-sm-10">
            <?php

              // Get the current date and time
              $currentDateTime = (new DateTime())->format('Y-m-d\TH:i');
            ?>
            <input type="datetime-local" class="form-control" id="auctionEndDate" name="auctionEndDate" min="<?= $currentDateTime ?>" max="9999-12-31T23:59">
              <small id="endDateHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Day
                for the auction to end.</small>
            </div>
          </div>

          <!-- Submit button -->
          <button type="submit" class="btn btn-primary form-control">Create Auction</button>
        </form>
      </div>
    </div>
  </div>

</div>



<?php include_once("footer.php"); ?>
