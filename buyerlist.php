<?php
session_start();
require 'db.php';

// Check if a farmer is logged in
if (!isset($_SESSION['farmer_id'])) {
    // Redirect to login page if not logged in
    header("Location: ");
    exit();
}

// Fetch all buyer profiles from the database
$fetch_buyers = $conn->prepare("SELECT * FROM buyer_profile");
$fetch_buyers->execute();
$buyers = $fetch_buyers->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registered Buyers</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; padding: 20px; }
        h1 { text-align: center; color: #333; }
        .buyer-card { background-color: #fff; padding: 20px; margin: 15px 0; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        .buyer-card h2 { margin-top: 0; }
        .contact-btn { background-color: #4CAF50; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>

<h1>Registered Buyers</h1>

<?php while ($buyer = $buyers->fetch_assoc()): ?>
    <div class="buyer-card">
        <h2><?php echo htmlspecialchars($buyer['name']); ?></h2>
        
        <p><strong>Location:</strong> <?php echo htmlspecialchars($buyer['location']); ?></p>
        <p><strong>Buying Needs:</strong> <?php echo htmlspecialchars($buyer['buying_needs']); ?></p>
        <p><strong>Preferred Crops:</strong> <?php echo htmlspecialchars($buyer['preferred_crops']); ?></p>
        <p><strong>Contract Terms:</strong> <?php echo htmlspecialchars($buyer['contract_terms']); ?></p>
       <p><strong>Contact Numbers:</strong> <?php echo htmlspecialchars(str_replace(",", ", ", $buyer['contact_numbers'])); ?></p>
     
        <a href="create_contract.php?buyer_id=<?php echo $buyer['buyer_id']; ?>" class="contact-btn">Create contract</a>
    </div>
<?php endwhile; ?>

</body>
</html>
