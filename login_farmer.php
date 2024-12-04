<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the farmer exists in the database
    $stmt = $conn->prepare("SELECT id, password FROM farmers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($farmer_id, $hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Set session variables
            $_SESSION['farmer_id'] = $farmer_id;
            $_SESSION['email'] = $email;

            // Redirect to the farmer profile page
            header("Location: farmerlist.php");
            exit();
        } else {
            $error = "Incorrect password. Please try again.";
        }
    } else {
        $error = "No farmer found with this email.";
    }
    $stmt->close();
}
?>

