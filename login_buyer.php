<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute the query to check if buyer exists
    $stmt = $conn->prepare("SELECT id, password FROM buyers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if a buyer with that email exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($buyer_id, $hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['buyer_id'] = $buyer_id; // Set session
            header("Location: buyer_profile.php"); // Redirect to buyer's profile
            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No account found with that email!";
    }
    $stmt->close();
}
?>
