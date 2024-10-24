<?php
session_start(); // Start the session
require 'config.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the SQL statement to select the user
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    if ($stmt) {
        $stmt->bind_param("s", $username); // Bind the username parameter
        $stmt->execute();
        $stmt->store_result(); // Store result to check if the user exists

        // Check if user exists
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($storedHashedPassword); // Bind the password result
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $storedHashedPassword)) {
                $_SESSION['username'] = $username; // Set session variable
                header("Location: home.php"); // Redirect to home
                exit(); // Ensure script stops after header()
            } else {
                $error = "Invalid username or password."; // Incorrect password
            }
        } else {
            $error = "Invalid username or password."; // Username not found
        }
        $stmt->close(); // Close the statement
    } else {
        die("Error preparing SQL statement: " . $conn->error); // Handle SQL errors
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Library Management</title>
    <link rel="stylesheet" href="styles/style2.css">
</head>
<body>
    <h2>Login</h2>

    <?php if (isset($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p> <!-- Prevent XSS -->
    <?php endif; ?>

    <!-- Login Form -->
    <form method="POST" action="login.php">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit" style="margin-left: 120px">Login</button>
        <a href="registration.php" style="text-decoration: none;">
        <button type="button" style="all: unset; padding: 10px 10px; background-color: #4CAF50; color: white; border-radius: 4px; cursor: pointer;">Register</button>
    </a>
    </form>
</body>
</html>
