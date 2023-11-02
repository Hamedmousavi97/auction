<?php require_once("config.php")?>
<?php include_once("browse.php")?>

<?php

// TODO: Extract $_POST variables, check they're OK, and attempt to create
// an account. Notify user of success/failure and redirect/give navigation 
// options.


  $username = $_POST['username'];
  $password = $_POST['password'];
  $email = $_POST['email'];
  $query = "INSERT INTO users (userName, userPassword, userEmail) VALUES ('$username', '$password', '$email')";
  if (!mysqli_query($conn, $query)) {
    //die('Error: ' . mysqli_error($connection));
    echo 'Failed to connect to the MySQLserver: '. mysqli_connect_error();
  } else {
    header("Location: browse.php");
    mysqli_close($conn);

    exit();
  }

    // // Assuming you have already established a database connection and stored it in the $connection variable.

    // // Check if the HTTP request method is POST (assumed in your form).
    // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //     // Sanitize and validate user inputs (not shown in this code, but it's important).
    //     $username = $_POST['username'];
    //     $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password.
    //     $email = $_POST['email'];

    //     // Create a prepared statement.
    //     $query = "INSERT INTO users (userName, userPassword, userEmail) VALUES (?, ?, ?)";
    //     $stmt = mysqli_prepare($connection, $query);

    //     if ($stmt) {
    //         // Bind parameters and execute the query.
    //         mysqli_stmt_bind_param($stmt, "sss", $username, $password, $email);
    //         if (mysqli_stmt_execute($stmt)) {
    //             // Query executed successfully.
    //             header("Location: header.php");
    //             exit();
    //         } else {
    //             // Error occurred during query execution.
    //             die('Error: ' . mysqli_stmt_error($stmt));
    //         }
            
    //         // Close the prepared statement.
    //         mysqli_stmt_close($stmt);
    //     } else {
    //         // Error in preparing the statement.
    //         die('Error: ' . mysqli_error($connection));
    //     }
    // }

    // // Close the database connection when done.
    // mysqli_close($connection);


?>


