<?php
// Start the session
session_start();

// Include database connection file
include 'db.php';

// Ensure the session variables are set
$farmer_id = isset($_SESSION['farmer_id']) ? $_SESSION['farmer_id'] : null;
$buyer_id = isset($_SESSION['buyer_id']) ? $_SESSION['buyer_id'] : null;

// Get data from the form (from POST request)
$farmerName = $_POST['farmerName'];
$farmerAddress = $_POST['farmerAddress'];
$farmerContact = $_POST['farmerContact'];
$buyerName = $_POST['buyerName'];
$buyerAddress = $_POST['buyerAddress'];
$buyerContact = $_POST['buyerContact'];
$agreementDate = $_POST['agreementDate'];
$cropName = $_POST['cropName'];
$cropQuantity = $_POST['cropQuantity'];
$harvestDate = $_POST['harvestDate'];
$qualityStandards = $_POST['qualityStandards'];
$inputSupport = $_POST['inputSupport'];
$agreedPrice = $_POST['agreedPrice'];
$paymentTerms = $_POST['paymentTerms'];
$deliveryLocation = $_POST['deliveryLocation'];
$deliveryDeadline = $_POST['deliveryDeadline'];
$forceMajeure = $_POST['forceMajeure'];
$terminationConditions = $_POST['terminationConditions'];
$disputeResolution = $_POST['disputeResolution'];

// Handle file uploads (signature images)
$farmerSignature = $_FILES['farmerSignature']['name'];
$buyerSignature = $_FILES['buyerSignature']['name'];

// Ensure upload directory exists and has write permissions
$targetDir = "uploads/";  
move_uploaded_file($_FILES['farmerSignature']['tmp_name'], $targetDir . $farmerSignature);
move_uploaded_file($_FILES['buyerSignature']['tmp_name'], $targetDir . $buyerSignature);

// SQL to insert contract data with prepared statements
$stmt = $conn->prepare("INSERT INTO contract (
    contract_id, farmer_id, buyer_id, farmer_name, buyer_name, agreement_date, crop_name, crop_quantity, 
    harvest_date, quality_standards, input_support, agreed_price, payment_terms, delivery_location, 
    delivery_deadline, force_majeure, termination_conditions, dispute_resolution, 
    farmer_signature, buyer_signature, created_at, updated_at, farmer_address, buyer_address
) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?, ?)");

// Bind parameters to the prepared statement
$stmt->bind_param("iisssssssssssssssssss", 
    $farmer_id, $buyer_id, $farmerName, $buyerName, $agreementDate, $cropName, $cropQuantity, 
    $harvestDate, $qualityStandards, $inputSupport, $agreedPrice, $paymentTerms, $deliveryLocation, 
    $deliveryDeadline, $forceMajeure, $terminationConditions, $disputeResolution, 
    $farmerSignature, $buyerSignature, $farmerAddress, $buyerAddress
);


// Execute the prepared statement
if ($stmt->execute()) {
    // Redirect to view_contract.php with success message
    header("Location: view_contract.php?success=1");
    exit(); // Make sure to stop further execution after redirection
} else {
    echo "Error: " . $stmt->error;
}

// Close the prepared statement and the connection
$stmt->close();
$conn->close();
?>
