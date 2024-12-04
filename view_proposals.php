<?php
session_start();
require 'db.php';

// Check if the buyer is logged in
if (!isset($_SESSION['buyer_id'])) {
    header("Location: login_buyer.php");
    exit();
}

$buyer_id = $_SESSION['buyer_id'];

// Fetch all farmer profiles who sent proposals (modify this query based on your table structure)
$stmt = $conn->prepare("SELECT * FROM farmer_profile WHERE proposal_status = 'pending'");
$stmt->execute();
$result = $stmt->get_result();

// Check if any farmer profiles exist
if ($result->num_rows == 0) {
    echo "No proposals found!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Proposals</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fa;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #4CAF50;
            text-align: center;
        }

        h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .farmer-list {
            margin-bottom: 30px;
        }

        .farmer-list .farmer-item {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .button {
            display: inline-block;
            padding: 12px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            text-align: center;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #45a049;
        }

        .button:active {
            background-color: #3e8e41;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Farmer Proposals</h1>
        <div class="farmer-list">
            <?php while ($farmer_profile = $result->fetch_assoc()): ?>
                <div class="farmer-item">
                    <h2><?php echo $farmer_profile['name']; ?>'s Proposal</h2>
                    <p><strong>Location:</strong> <?php echo $farmer_profile['location']; ?></p>
                    <p><strong>Farm Size:</strong> <?php echo $farmer_profile['farm_size']; ?> acres</p>
                    <p><strong>Experience:</strong> <?php echo $farmer_profile['experience']; ?></p>
                    <p><strong>Crop Type:</strong> <?php echo $farmer_profile['crop_type']; ?></p>
                    <p><strong>Crop Quantity:</strong> <?php echo $farmer_profile['crop_quantity']; ?> tons</p>
                    <p><strong>Harvest Time:</strong> <?php echo $farmer_profile['harvest_time']; ?></p>
                    <p><strong>Contract Terms:</strong> <?php echo $farmer_profile['contract_terms']; ?></p>
                    <!-- Accept Proposal button -->
                    <a href="create_contract.php?farmer_id=<?php echo $farmer_profile['farmer_id']; ?>&buyer_id=<?php echo $buyer_id; ?>" class="button">Accept Proposal</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
