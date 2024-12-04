<?php
// Include database connection file
include 'db.php';

// Get the contract_id from the URL (assuming it’s passed as a GET parameter)
if (isset($_GET['id'])) {
    $contract_id = $_GET['id'];

    // SQL query to fetch the individual contract details
    $sql = "SELECT * FROM contract WHERE contract_id = ?";
    
    // Prepare and execute the query
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $contract_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if a contract was found
        if ($result->num_rows > 0) {
            // Fetch contract data
            $contract = $result->fetch_assoc();
        } else {
            echo "<p>No contract found with the specified ID.</p>";
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        echo "<p>Error preparing the SQL query.</p>";
    }
} else {
    echo "<p>No contract ID provided.</p>";
}

$conn->close();
?>

<!-- HTML code to display contract details -->
<?php if (isset($contract)): ?>
    <h2>Contract Details</h2>
    <table>
        <tr>
            <th>Farmer Name</th>
            <td><?php echo htmlspecialchars($contract['farmer_name']); ?></td>
        </tr>
        <tr>
            <th>Buyer Name</th>
            <td><?php echo htmlspecialchars($contract['buyer_name']); ?></td>
        </tr>
        <tr>
            <th>Crop Name</th>
            <td><?php echo htmlspecialchars($contract['crop_name']); ?></td>
        </tr>
        <tr>
            <th>Crop Quantity</th>
            <td><?php echo htmlspecialchars($contract['crop_quantity']); ?></td>
        </tr>
        <tr>
            <th>Agreement Date</th>
            <td><?php echo htmlspecialchars($contract['agreement_date']); ?></td>
        </tr>
        <tr>
            <th>Harvest Date</th>
            <td><?php echo htmlspecialchars($contract['harvest_date']); ?></td>
        </tr>
        <tr>
            <th>Quality Standards</th>
            <td><?php echo htmlspecialchars($contract['quality_standards']); ?></td>
        </tr>
        <tr>
            <th>Agreed Price</th>
            <td><?php echo htmlspecialchars($contract['agreed_price']); ?></td>
        </tr>
        <!-- Add more rows as needed for other columns -->
    </table>
<?php endif; ?>

<!-- Add CSS for styling -->
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        color: #333;
        margin: 0;
        padding: 0;
    }

    h2 {
        text-align: center;
        margin-top: 20px;
        color: #5a5a5a;
    }

    table {
        width: 80%;
        margin: 20px auto;
        border-collapse: collapse;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    table th, table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    table th {
        background-color: #5bc0de;
        color: white;
    }

    table tr:hover {
        background-color: #f1f1f1;
    }
</style>
