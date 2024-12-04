<!-- 
<?php
session_start();
require 'db.php';

// Check if a buyer is logged in
if (!isset($_SESSION['buyer_id'])) {
    echo "Please log in as a buyer to view the list of farmers.";
    exit();
}

// Fetch all farmer profiles from the database
$fetch_farmers = $conn->prepare("SELECT * FROM farmer_profile");
$fetch_farmers->execute();
$farmers = $fetch_farmers->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registered Farmers</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; padding: 20px; }
        h1 { text-align: center; color: #333; }
        .farmer-card { background-color: #fff; padding: 20px; margin: 15px 0; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        .farmer-card h2 { margin-top: 0; }
        .contact-btn { background-color: #4CAF50; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>

<h1>Registered Farmers</h1>

<?php while ($farmer = $farmers->fetch_assoc()): ?>
    <div class="farmer-card">
        <h2><?php echo htmlspecialchars($farmer['name']); ?></h2>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($farmer['location']); ?></p>
        <p><strong>Crop Type:</strong> <?php echo htmlspecialchars($farmer['crop_type']); ?></p>
        <p><strong>Crop Quantity:</strong> <?php echo htmlspecialchars($farmer['crop_quantity']); ?> tons</p>
        <p><strong>Experience:</strong> <?php echo htmlspecialchars($farmer['experience']); ?> years</p>
        <p><strong>Harvest Time:</strong> <?php echo htmlspecialchars($farmer['harvest_time']); ?></p>
        <p><strong>Contract Terms:</strong> <?php echo htmlspecialchars($farmer['contract_terms']); ?></p>
        <p><strong>Contact Numbers:</strong> <?php echo htmlspecialchars(str_replace(",", ", ", $farmer['contact_numbers'])); ?></p>
        <a href="create_contract.php?farmer_id=<?php echo $farmer['farmer_id']; ?>" class="contact-btn">Create Contract</a>
    </div>
<?php endwhile; ?>

</body>
</html> -->

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


require 'db.php';

// Check if a buyer is logged in
if (!isset($_SESSION['buyer_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Fetch all farmer profiles from the database
$fetch_farmers = $conn->prepare("SELECT * FROM farmer_profile");
$fetch_farmers->execute();
$farmers = $fetch_farmers->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registered Farmers</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; padding: 20px; }
        h1 { text-align: center; color: #333; }
        .farmer-card { background-color: #fff; padding: 20px; margin: 15px 0; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        .farmer-card h2 { margin-top: 0; }
        .contact-btn { background-color: #4CAF50; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>

<h1>Registered Farmers</h1>

<?php while ($farmer = $farmers->fetch_assoc()): ?>
    <div class="farmer-card">
        <h2><?php echo htmlspecialchars($farmer['name']); ?></h2>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($farmer['location']); ?></p>
        <p><strong>Crop Type:</strong> <?php echo htmlspecialchars($farmer['crop_type']); ?></p>
        <p><strong>Crop Quantity:</strong> <?php echo htmlspecialchars($farmer['crop_quantity']); ?> tons</p>
        <p><strong>Experience:</strong> <?php echo htmlspecialchars($farmer['experience']); ?> years</p>
        <p><strong>Harvest Time:</strong> <?php echo htmlspecialchars($farmer['harvest_time']); ?></p>
        <p><strong>Contract Terms:</strong> <?php echo htmlspecialchars($farmer['contract_terms']); ?></p>
        <p><strong>Contact Numbers:</strong> <?php echo htmlspecialchars(str_replace(",", ", ", $farmer['contact_numbers'])); ?></p>
        <a href="create_contract.php?farmer_id=<?php echo $farmer['farmer_id']; ?>" class="contact-btn">Create Contract</a>
    </div>
<?php endwhile; ?>

</body>
</html>
