<?php
session_start();
require 'db.php';

// Check if a buyer or farmer is logged in
if (isset($_SESSION['buyer_id'])) {
    $user_type = 'buyer';
    $user_id = $_SESSION['buyer_id'];
} elseif (isset($_SESSION['farmer_id'])) {
    $user_type = 'farmer';
    $user_id = $_SESSION['farmer_id'];
} else {
    echo "Please log in to view your profile.";
    exit();
}

// Fetch the profile data based on user type
if ($user_type === 'buyer') {
    $stmt = $conn->prepare("SELECT * FROM buyer_profile WHERE buyer_id = ?");
} else {
    $stmt = $conn->prepare("SELECT * FROM farmer_profile WHERE farmer_id = ?");
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Profile found
    $profile = $result->fetch_assoc();
} else {
    echo "Profile not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }
        

        h1 {
            text-align: center;
            color: #333;
        }

        .profile-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        .profile-container p {
            margin: 10px 0;
        }

        .profile-container .label {
            font-weight: bold;
        }

        .profile-container img {
            max-width: 100%;
            height: auto;
        }

        .btn {
            display: inline-block;
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 15px;
            border-radius: 4px;
            text-decoration: none;
            margin-top: 20px;
            text-align: center;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .logout-btn {
            background-color: #d9534f;
            margin-left: 10px;
        }
    </style>
</head>
<body>

<h1>My Profile</h1>

<div class="profile-container">
    <?php if ($user_type === 'buyer'): ?>
        <p><span class="label">Full Name:</span> <?php echo htmlspecialchars($profile['name'] ?? ''); ?></p>
        <p><span class="label">Location:</span> <?php echo htmlspecialchars($profile['location'] ?? ''); ?></p>
        <p><span class="label">Company Name:</span> <?php echo htmlspecialchars($profile['company_name'] ?? ''); ?></p>
        <p><span class="label">Email:</span> <?php echo htmlspecialchars($profile['email'] ?? ''); ?></p>
        <p><span class="label">Buying Needs:</span> <?php echo htmlspecialchars($profile['buying_needs'] ?? ''); ?></p>
        <p><span class="label">Preferred Crops:</span> <?php echo htmlspecialchars($profile['preferred_crops'] ?? ''); ?></p>
        <p><span class="label">Contract Terms:</span> <?php echo htmlspecialchars($profile['contract_terms'] ?? ''); ?></p>
        <p><span class="label">Contact Numbers:</span> <?php echo htmlspecialchars($profile['contact_numbers'] ?? ''); ?></p>
        <?php if (!empty($profile['auth_document'])): ?>
            <p><span class="label">Authentication Document:</span></p>
            <img src="<?php echo htmlspecialchars($profile['auth_document']); ?>" alt="Authentication Document">
        <?php else: ?>
            <p><span class="label">No Authentication Document Uploaded</span></p>
        <?php endif; ?>
    <?php elseif ($user_type === 'farmer'): ?>
        <p><span class="label">Full Name:</span> <?php echo htmlspecialchars($profile['name'] ?? ''); ?></p>
        <p><span class="label">Location:</span> <?php echo htmlspecialchars($profile['location'] ?? ''); ?></p>
        <p><span class="label">Farm Size:</span> <?php echo htmlspecialchars($profile['farm_size'] ?? ''); ?></p>
        <p><span class="label">Email:</span> <?php echo htmlspecialchars($profile['email'] ?? ''); ?></p>
        <p><span class="label">Crops Grown:</span> <?php echo htmlspecialchars($profile['crops'] ?? ''); ?></p>
        <p><span class="label">Farming Practices:</span> <?php echo htmlspecialchars($profile['farming_practices'] ?? ''); ?></p>
        <p><span class="label">Experience:</span> <?php echo htmlspecialchars($profile['experience'] ?? ''); ?></p>
        <p><span class="label">Contact Number:</span> <?php echo htmlspecialchars($profile['contact_number'] ?? ''); ?></p>
        <?php if (!empty($profile['auth_document'])): ?>
            <p><span class="label">Authentication Document:</span></p>
            <img src="<?php echo htmlspecialchars($profile['auth_document']); ?>" alt="Authentication Document">
        <?php else: ?>
            <p><span class="label">No Authentication Document Uploaded</span></p>
        <?php endif; ?>
    <?php endif; ?>

    <a href="index.html" class="btn">Edit Profile</a>
    <a href="logout.php" class="btn logout-btn">Logout</a>
</div>

</body>
</html>
