<?php

// display_time_remaining:
// Helper function to help figure out what time to display
function display_time_remaining($interval) {

    if ($interval->days == 0 && $interval->h == 0) {
      // Less than one hour remaining: print mins + seconds:
      $time_remaining = $interval->format('%im %Ss');
    }
    else if ($interval->days == 0) {
      // Less than one day remaining: print hrs + mins:
      $time_remaining = $interval->format('%hh %im');
    }
    else {
      // At least one day remaining: print days + hrs:
      $time_remaining = $interval->format('%ad %hh');
    }

  return $time_remaining;

}

// print_listing_li:
// This function prints an HTML <li> element containing an auction listing
function printListingLi($item_id, $title, $desc, $price, $num_bids, $end_time, $category, $username, $date_created)
{
  // Truncate long descriptions
  if (strlen($desc) > 250) {
    $desc_shortened = substr($desc, 0, 250) . '...';
  }
  else {
    $desc_shortened = $desc;
  }

  // Fix language of bid vs. bids
  if ($num_bids == 1) {
    $bid = ' bid';
  }
  else {
    $bid = ' bids';
  }

  // Calculate time to auction end
  $now = new DateTime();
  $end_time = new DateTime($end_time);
  // Convert date_created to DateTime object
  $date_created = new DateTime($date_created);
  $date_created = $date_created->format('j M Y');

  if ($now < $end_time) {
      $time_to_end = $now->diff($end_time);
      $time_remaining = 'Auction end in ' . display_time_remaining($time_to_end) ;
  } else {
      $time_remaining = 'Auction ended';
  }


  // Print HTML
  echo('
    <strong> User "' . $username . '" Created an auction on: ' . $date_created . '</strong>
    <br>
    <li class="list-group-item d-flex justify-content-between">
    <div class="p-2 mr-5"><h5><a href="listing.php?item_id=' . $item_id . '">' . $title . '</a></h5>' . $desc_shortened . '<br> <strong>' . $category . '</strong></div>
    <div class="text-center text-nowrap"><span style="font-size: 1.5em">£' . number_format($price, 2) . '</span><br/>' . $num_bids . $bid . '<br/>' . $time_remaining . '</div>

    </li>'
  );
}

function getCategories($conn) {
  $categories = array();

  $query = "SELECT * FROM categories";
  $result = mysqli_query($conn, $query);

  if ($result && mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
          $categories[] = $row;
      }
      mysqli_free_result($result);
  }

  return $categories;
}

?>
