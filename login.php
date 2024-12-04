<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user is a Farmer
    $stmt = $conn->prepare("SELECT id, password FROM farmers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($farmer_id, $hashed_password);

    if ($stmt->num_rows > 0) {
        // Farmer credentials match
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['farmer_id'] = $farmer_id;
            header("Location: farmer_profile.php");  // Redirect to farmer profile
            exit();
        }
    }

    // Check if the user is a Buyer
    $stmt = $conn->prepare("SELECT id, password FROM buyers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($buyer_id, $hashed_password);

    if ($stmt->num_rows > 0) {
        // Buyer credentials match
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['buyer_id'] = $buyer_id;
            header("Location: buyer_profile.php");  // Redirect to buyer profile
            exit();
        }
    }

    // If no matches found, show invalid credentials
    echo "Invalid credentials!";
    $stmt->close();
}
?>

<!-- Login Form -->
<form method="POST">
    <label>Email:</label>
    <input type="email" name="email" required><br>
    <label>Password:</label>
    <input type="password" name="password" required><br>
    <button type="submit">Login</button>
</form>
