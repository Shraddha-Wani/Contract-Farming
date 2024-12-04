<?php
session_start();
require 'db.php';

// Check if the buyer is logged in
if (!isset($_SESSION['buyer_id'])) {
    header("Location: login_buyer.php");
    exit();
}

$buyer_id = $_SESSION['buyer_id'];

// Check if the proposal ID is passed
if (isset($_GET['proposal_id'])) {
    $proposal_id = $_GET['proposal_id'];

    // Update the proposal status to "Accepted"
    $stmt = $conn->prepare("UPDATE proposals SET status = 'Accepted', buyer_id = ? WHERE proposal_id = ?");
    $stmt->bind_param("ii", $buyer_id, $proposal_id);

    if ($stmt->execute()) {
        echo "Proposal accepted successfully!";
        header("Location: buyer_profile.php"); // Redirect back to buyer profile
    } else {
        echo "Error accepting the proposal.";
    }
    $stmt->close();
} else {
    echo "Invalid proposal ID!";
}
?>
