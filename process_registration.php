<?php


require_once("config.php");
include_once("browse.php");

function validateEmail($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);

    // Regular expression pattern for matching email addresses
    $pattern = "/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/";

    if (!preg_match($pattern, $input)) {
        return false;
    }

    return true; // Return true if the email is valid
}

function sanitizeEmail($email) {
    $email = trim($email);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : false;
}

$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
$sanitizedEmail = sanitizeEmail($email);
if ($sanitizedEmail && validateEmail($sanitizedEmail)) {
    $query = "INSERT INTO users (UserName, UserPassword, UserEmail) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);

    // Bind parameters
    $stmt->bind_param("sss", $username, $hashedPassword, $sanitizedEmail);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: browse.php");
        exit(); // Ensure no further execution after redirection
    } else {
        // Registration failed, show error message
        echo '<script>
                alert("Failed to create account. Please try again.");
                window.history.back();
             </script>';
    }

    // Close the statement
    $stmt->close();
    mysqli_close($conn);
} else {
    // Invalid email format during registration, show error message
    echo '<script>
            alert("Invalid email format. Please enter a valid email address.");
            window.history.back();
         </script>';
}
?>
