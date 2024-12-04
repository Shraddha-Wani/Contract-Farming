<?php
// Database connection details
$servername = "localhost";
$username = "root";       // Default username for XAMPP
$password = "";           // Default password for XAMPP (empty by default)
$dbname = "agrohub";      // Updated database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "<br>";
}
?>
