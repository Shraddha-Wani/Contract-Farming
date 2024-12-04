<?php
// Include database connection file
include 'db.php';

// Check if a success message is passed via the URL
if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo "<p class='success-message'>Contract created successfully!</p>";
}

// SQL to fetch all contracts
$sql = "SELECT contract_id, farmer_name, buyer_name, crop_name, crop_quantity, agreement_date FROM contract";
$result = $conn->query($sql);

// Check if contracts are available
if ($result->num_rows > 0) {
    echo "<h2 class='page-title'>Contract List</h2>";
    echo "<table class='contract-table'>
            <tr>
                <th>Farmer Name</th>
                <th>Buyer Name</th>
                <th>Crop Name</th>
                <th>Quantity</th>
                <th>Agreement Date</th>
                <th>Actions</th>
            </tr>";
    
    // Loop through all the results and display them
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['farmer_name']) . "</td>
                <td>" . htmlspecialchars($row['buyer_name']) . "</td>
                <td>" . htmlspecialchars($row['crop_name']) . "</td>
                <td>" . htmlspecialchars($row['crop_quantity']) . "</td>
                <td>" . htmlspecialchars($row['agreement_date']) . "</td>
                <td><a class='view-details-btn' href='view_individual_contract.php?id=" . $row['contract_id'] . "'>View Details</a></td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p class='no-contracts'>No contracts found.</p>";
}

$conn->close();
?>

<!-- Add CSS for styling -->
<style>
    /* Global Styles */
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        color: #333;
        margin: 0;
        padding: 0;
    }

    /* Header Style */
    .page-title {
        text-align: center;
        margin-top: 20px;
        color: #5a5a5a;
    }

    /* Success Message */
    .success-message {
        text-align: center;
        background-color: #4CAF50;
        color: white;
        padding: 10px;
        margin-top: 20px;
        font-size: 18px;
        border-radius: 5px;
    }

    /* Table Styles */
    .contract-table {
        width: 90%;
        margin: 30px auto;
        border-collapse: collapse;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .contract-table th, .contract-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .contract-table th {
        background-color: #5bc0de;
        color: white;
    }

    .contract-table tr:hover {
        background-color: #f1f1f1;
    }

    /* View Details Button */
    .view-details-btn {
        background-color: #4CAF50;
        color: white;
        padding: 8px 16px;
        text-decoration: none;
        border-radius: 5px;
    }

    .view-details-btn:hover {
        background-color: #45a049;
    }

    /* No Contracts Message */
    .no-contracts {
        text-align: center;
        font-size: 18px;
        color: #d9534f;
        margin-top: 20px;
    }
</style>
