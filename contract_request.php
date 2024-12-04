<?php
session_start();
require 'db.php';

// Check if the buyer is logged in
if (!isset($_SESSION['buyer_id'])) {
    echo "Proposal sent successfully.";
    exit();
}

// Fetch buyer ID from session
$buyer_id = $_SESSION['buyer_id'];

// Get the farmer ID from the URL
$farmer_id = $_GET['farmer_id'];

// Fetch the farmer's profile from the database
$stmt = $conn->prepare("SELECT * FROM farmer_profile WHERE farmer_id = ?");
$stmt->bind_param("i", $farmer_id);
$stmt->execute();
$result = $stmt->get_result();
$farmer_profile = $result->fetch_assoc();
$stmt->close();

if (!$farmer_profile) {
    echo "Farmer not found!";
    exit();
}

// Check if a proposal already exists
$check_proposal = $conn->prepare("SELECT * FROM proposals WHERE buyer_id = ? AND farmer_id = ?");
$check_proposal->bind_param("ii", $buyer_id, $farmer_id);
$check_proposal->execute();
$existing_proposal = $check_proposal->get_result()->fetch_assoc();
$check_proposal->close();

if (!$existing_proposal) {
    // Insert proposal into the database if it doesn't already exist
    $insert_proposal = $conn->prepare("INSERT INTO proposals (buyer_id, farmer_id, proposal_date, status) VALUES (?, ?, NOW(), 'Pending')");
    $insert_proposal->bind_param("ii", $buyer_id, $farmer_id);
    $insert_proposal->execute();
    $insert_proposal->close();

    // Redirect to the home page after successful proposal submission
    header("Location: home.html");  // Replace 'home.php' with the URL of your home page
    exit();
} else {
    echo "Proposal send Successfully";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contract Proposal</title>
    <style>
        /* Add your CSS here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }

        h1 {
            color: #333;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Contract Proposal</h1>
        <div class="farmer-info">
            <h2>Farmer Information</h2>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($farmer_profile['location']); ?></p>
            <p><strong>Farm Size:</strong> <?php echo htmlspecialchars($farmer_profile['farm_size']); ?> acres</p>
            <p><strong>Experience:</strong> <?php echo htmlspecialchars($farmer_profile['experience']); ?></p>
            <p><strong>Crop Type:</strong> <?php echo htmlspecialchars($farmer_profile['crop_type']); ?></p>
            <p><strong>Crop Quantity:</strong> <?php echo htmlspecialchars($farmer_profile['crop_quantity']); ?> tons</p>
            <p><strong>Harvest Time:</strong> <?php echo htmlspecialchars($farmer_profile['harvest_time']); ?></p>
            <p><strong>Contract Terms:</strong> <?php echo htmlspecialchars($farmer_profile['contract_terms']); ?></p>
        </div>

        <!-- Accept Proposal button -->
        <a href="create_contract.php?farmer_id=<?php echo $farmer_id; ?>&buyer_id=<?php echo $buyer_id; ?>" class="button">Accept Proposal</a>
    </div>
</body>
</html>
