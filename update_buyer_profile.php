<?php
session_start();
require 'db.php';

if (!isset($_SESSION['buyer_id'])) {
    header("Location: login_buyer.php");
    exit();
}

$buyer_id = $_SESSION['buyer_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_name = $_POST['company_name'];
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];
    $preferred_crop = $_POST['preferred_crop'];
    $purchase_quantity = $_POST['purchase_quantity'];
    $contract_terms = $_POST['contract_terms'];

    // Insert or update buyer profile data
    $stmt = $conn->prepare("REPLACE INTO buyer_profile (buyer_id, company_name, contact_number, address, preferred_crop, purchase_quantity, contract_terms) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssis", $buyer_id, $company_name, $contact_number, $address, $preferred_crop, $purchase_quantity, $contract_terms);

    if ($stmt->execute()) {
        header("Location: buyer_profile.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>
