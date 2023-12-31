<?php

    // This page is used to handle the registration process
    // Start the session
    require_once("config.php");
    include_once("browse.php");


    // Validate email address
    function validateEmail($input) {

        // Remove all illegal characters from email
        $input = trim($input);

        // Remove all illegal characters from email
        $input = stripslashes($input);

        // Remove all illegal characters from email
        $input = htmlspecialchars($input);

        // Regular expression pattern for matching email addresses
        $pattern = "/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/";

        // Check if the email matches the pattern
        if (!preg_match($pattern, $input)) {
            return false;
        }

        return true; // Return true if the email is valid
    }

    // Sanitize email address
    function sanitizeEmail($email) {

        // Remove all illegal characters from email
        $email = trim($email);

        // Remove all illegal characters from email
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // Validate email
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : false;
    }

    // Check if the form is submitted
    // Set the posted data from the form as local variables
    $registeredUser = $_POST;
    $accountType = $registeredUser['accountType'];
    $username = $registeredUser['username'];
    $password = $registeredUser['password'];
    $email = $registeredUser['email'];
    $confirmPassword = $registeredUser['confirmPassword'];
    $address1 = $registeredUser['Address1'];
    $address2 = $registeredUser['Address2'];
    $city = $registeredUser['City'];
    $postcode = $registeredUser['Postcode'];


    // check if email is empty
    if (empty($email)) {

        // Show error message
        echo '<script>
                alert("Email cannot be null or empty. Please enter a valid email.");
                window.history.back();
                </script>';
        exit();
    }

    // check if username is empty
    if (empty($username)) {

        // Show error message
        echo '<script>
                alert("Username cannot be null or empty. Please enter a valid email.");
                window.history.back();
                </script>';
        exit();
    }



    // Check if the password and confirm password fields match
    if ($password != $confirmPassword){

        // Show error message
        echo '<script>
                    alert("Passwords do not match. Please try again.");
                    window.history.back();
                </script>';
        exit();
    }

    // Check if the password is valid, check the lenght of password string
    if (strlen($password) < 8) {

        // Show error message
        echo '<script>
                    alert("Password must be at least 8 characters long.");
                    window.history.back();
                </script>';
        exit();
    }

    // Check if the password contains at least one uppercase letter
    if (!preg_Match('/[0-9]/', $password)) {

        // Show error message
        echo '<script>
                    alert("Password must contain at least one number.");
                    window.history.back();
                </script>';
        exit();
    }

    // Check if the password contains at least one uppercase letter
    if (!preg_Match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password)) {

        // Show error message
        echo '<script>
                    alert("Password must contain at least one special character.");
                    window.history.back();
                </script>';
        exit();
    }

    // Hash the password
    $hashedPassword = hash('sha256', $password);

    // Sanitize email address
    $sanitizedEmail = sanitizeEmail($email);

    // Check if the email is valid
    if ($sanitizedEmail && validateEmail($sanitizedEmail)) {

        // Query in to the databese to check if the username or email already exists
        $stmtUsername = $conn->prepare("SELECT UserName FROM users WHERE UserName = '$username'");
        $stmtUsername->bind_param("s", $username);
        $stmtUsername->execute();
        $stmtUsername->store_result();

        $stmtEmail = $conn->prepare("SELECT UserEmail FROM users WHERE UserEmail = '$email'");
        $stmtEmail->bind_param("s", $sanitizedEmail);
        $stmtEmail->execute();
        $stmtEmail->store_result();

        // Check for existing username
        if ($stmtUsername->num_rows > 0) {

            // Show error message
            echo '<script>
                alert("Username is already taken. Please choose another username.");
                window.history.back();
            </script>';

        // Check for existing email
        } elseif ($stmtEmail->num_rows > 0) {

            // Show error message
            echo '<script>
                alert("Email is already taken. Please choose another email.");
                window.history.back();
            </script>';
        } else {
            // Proceed with user registration
            // Prepare the SQL statement to insert the user info in the database
            $query = "INSERT INTO users (UserName, UserPassword, UserEmail, UserRole, Address1, Address2, City, Postcode) VALUES ('$username', '$hashedPassword', '$email', '$accountType', '$address1', '$address2', '$city', '$postcode')";
            $stmt = $conn->prepare($query);


            // Execute the statement
            if ($stmt->execute()) {

                // Registration successful, redirect to the login page and set the cookie
                setcookie("account_type", $accountType);
                setcookie("username", $username);

                // Show success message
                echo '<script>
                        alert("Account created successfully! Please log in to continue.");
                    </script>';

                // Redirect to the login page
                header("Location: browse.php");
                exit(); // Ensure no further execution after redirection
            } else {

            // Registration failed, show error message
            echo '<script>
                    alert("Failed to create an account. Please try again.");
                    window.history.back();
                </script>';
            }

        }

        // Close the statement
        $stmt->close();
        $stmtUsername->close();
        $stmtEmail->close();
        mysqli_close($conn);
    } else {

        // Invalid email format during registration, show error message
        echo '<script>
                alert("Invalid email format. Please enter a valid email address.");
                window.history.back();
            </script>';
    }
?>
