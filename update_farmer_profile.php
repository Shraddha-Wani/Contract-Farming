<?php
session_start();
require 'db.php';

// Check if the farmer is logged in
if (!isset($_SESSION['farmer_id'])) {
    header("Location: login_farmer.php");
    exit();
}

$farmer_id = $_SESSION['farmer_id'];

// Get form data
$location = $_POST['location'];
$farm_size = $_POST['farm_size'];
$experience = $_POST['experience'];
$crop_type = $_POST['crop_type'];
$crop_quantity = $_POST['crop_quantity'];
$harvest_time = $_POST['harvest_time'];
$contract_terms = $_POST['contract_terms'];
$crop_image = '';

// Handle file upload if a file was submitted
if (isset($_FILES['crop_image']) && $_FILES['crop_image']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $crop_image = $upload_dir . basename($_FILES['crop_image']['name']);
    move_uploaded_file($_FILES['crop_image']['tmp_name'], $crop_image);
} else {
    // If no new file uploaded, retain the old file if it exists
    $stmt = $conn->prepare("SELECT crop_image FROM farmer_profile WHERE farmer_id = ?");
    $stmt->bind_param("i", $farmer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $existing = $result->fetch_assoc();
    $crop_image = $existing['crop_image'] ?? '';
}

// Check if the profile already exists
$stmt = $conn->prepare("SELECT farmer_id FROM farmer_profile WHERE farmer_id = ?");
$stmt->bind_param("i", $farmer_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Update the existing profile
    $stmt->close();
    $stmt = $conn->prepare("UPDATE farmer_profile SET location=?, farm_size=?, experience=?, crop_type=?, crop_quantity=?, harvest_time=?, contract_terms=?, crop_image=? WHERE farmer_id=?");
    $stmt->bind_param("sissssssi", $location, $farm_size, $experience, $crop_type, $crop_quantity, $harvest_time, $contract_terms, $crop_image, $farmer_id);
} else {
    // Insert a new profile
    $stmt->close();
    $stmt = $conn->prepare("INSERT INTO farmer_profile (farmer_id, location, farm_size, experience, crop_type, crop_quantity, harvest_time, contract_terms, crop_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isissssss", $farmer_id, $location, $farm_size, $experience, $crop_type, $crop_quantity, $harvest_time, $contract_terms, $crop_image);
}

if ($stmt->execute()) {
    echo "Profile updated successfully!";
    header("Location: farmer_profile.php");
    exit();
} else {
    echo "Error updating profile: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
<?php
session_start();
require 'db.php';

// Check if the farmer is logged in
if (!isset($_SESSION['farmer_id'])) {
    header("Location: login_farmer.php");
    exit();
}

$farmer_id = $_SESSION['farmer_id'];

// Get form data
$location = $_POST['location'];
$farm_size = $_POST['farm_size'];
$experience = $_POST['experience'];
$crop_type = $_POST['crop_type'];
$crop_quantity = $_POST['crop_quantity'];
$harvest_time = $_POST['harvest_time'];
$contract_terms = $_POST['contract_terms'];
$crop_image = '';

// Handle file upload if a file was submitted
if (isset($_FILES['crop_image']) && $_FILES['crop_image']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $crop_image = $upload_dir . basename($_FILES['crop_image']['name']);
    move_uploaded_file($_FILES['crop_image']['tmp_name'], $crop_image);
} else {
    // If no new file uploaded, retain the old file if it exists
    $stmt = $conn->prepare("SELECT crop_image FROM farmer_profile WHERE farmer_id = ?");
    $stmt->bind_param("i", $farmer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $existing = $result->fetch_assoc();
    $crop_image = $existing['crop_image'] ?? '';
}

// Check if the profile already exists
$stmt = $conn->prepare("SELECT farmer_id FROM farmer_profile WHERE farmer_id = ?");
$stmt->bind_param("i", $farmer_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Update the existing profile
    $stmt->close();
    $stmt = $conn->prepare("UPDATE farmer_profile SET location=?, farm_size=?, experience=?, crop_type=?, crop_quantity=?, harvest_time=?, contract_terms=?, crop_image=? WHERE farmer_id=?");
    $stmt->bind_param("sissssssi", $location, $farm_size, $experience, $crop_type, $crop_quantity, $harvest_time, $contract_terms, $crop_image, $farmer_id);
} else {
    // Insert a new profile
    $stmt->close();
    $stmt = $conn->prepare("INSERT INTO farmer_profile (farmer_id, location, farm_size, experience, crop_type, crop_quantity, harvest_time, contract_terms, crop_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isissssss", $farmer_id, $location, $farm_size, $experience, $crop_type, $crop_quantity, $harvest_time, $contract_terms, $crop_image);
}

if ($stmt->execute()) {
    echo "Profile updated successfully!";
    header("Location: farmer_profile.php");
    exit();
} else {
    echo "Error updating profile: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
