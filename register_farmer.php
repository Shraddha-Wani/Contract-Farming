<?php
require 'db.php'; // Make sure this file contains the correct database connection

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Basic form validation
    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required!";
    } else {
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format!";
        } else {
            // Check if email already exists in the database
            $stmt = $conn->prepare("SELECT COUNT(*) FROM farmers WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count > 0) {
                $error = "Email already registered!";
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Prepare and execute the insert statement
                $stmt = $conn->prepare("INSERT INTO farmers (name, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $name, $email, $hashed_password);

                if ($stmt->execute()) {
                    // Redirect to the homepage upon successful registration
                    header("Location: index.html");
                    exit();
                } else {
                    $error = "Error: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }

    $conn->close(); // Close the database connection
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register as Farmer</title>
    <style>

        body {
            font-family: Arial, sans-serif;
            background-image: url('images/background.jpg');
            background-color: #f5f5f5;
            background-position: center;

            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
             
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }
        h1 {
            color: #28a745;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        button {
            background-color: #28a745;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #218838;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Register as Farmer</h1>
        <form method="POST" action="">
            <label for="name">Full Name:</label>
            <input type="text" name="name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Register</button>
        </form>

        <?php
        // Display error messages if any
        if (isset($error)) {
            echo "<p class='error'>$error</p>";
        }
        ?>
    </div>
</body>
</html>
