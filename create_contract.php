<?php
// Include your database connection or start your session here if necessary
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contract Farming Agreement Generator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('images/background.jpg'); /* Add the correct path to your background image */
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.9); /* White background with opacity */
            padding: 20px;
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        h2 {
            color: #555;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            display: block;
        }

        input[type="text"], input[type="number"], input[type="date"], textarea, input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
        }

        button {
            display: block;
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .form-group input[type="file"] {
            padding: 5px;
        }

        .form-group input[type="text"]:focus, .form-group input[type="number"]:focus, .form-group input[type="date"]:focus, .form-group textarea:focus {
            border-color: #4CAF50;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Contract Farming Agreement Generator</h1>
        <form id="contract-form" action="save_contract.php" method="POST" enctype="multipart/form-data">
            <h2>Parties Information</h2>
            <div class="form-group">
                <label for="farmerName">Farmer's Name:</label>
                <input type="text" id="farmerName" name="farmerName" required>
            </div>
            <div class="form-group">
                <label for="farmerAddress">Farmer's Address:</label>
                <textarea id="farmerAddress" name="farmerAddress" rows="2" required></textarea>
            </div>
            <div class="form-group">
                <label for="farmerContact">Farmer's Contact Information:</label>
                <input type="text" id="farmerContact" name="farmerContact" required>
            </div>
            <div class="form-group">
                <label for="buyerName">Buyer's Name/Company Name:</label>
                <input type="text" id="buyerName" name="buyerName" required>
            </div>
            <div class="form-group">
                <label for="buyerAddress">Buyer's Address:</label>
                <textarea id="buyerAddress" name="buyerAddress" rows="2" required></textarea>
            </div>
            <div class="form-group">
                <label for="buyerContact">Buyer's Contact Information:</label>
                <input type="text" id="buyerContact" name="buyerContact" required>
            </div>
            <div class="form-group">
                <label for="agreementDate">Date of Agreement:</label>
                <input type="date" id="agreementDate" name="agreementDate" required>
            </div>

            <h2>Contract Terms</h2>
            <div class="form-group">
                <label for="cropName">Name of Crop:</label>
                <input type="text" id="cropName" name="cropName" required>
            </div>
            <div class="form-group">
                <label for="cropQuantity">Quantity/Weight:</label>
                <input type="text" id="cropQuantity" name="cropQuantity" required>
            </div>
            <div class="form-group">
                <label for="harvestDate">Harvest Period or Date:</label>
                <input type="date" id="harvestDate" name="harvestDate" required>
            </div>
            <div class="form-group">
                <label for="qualityStandards">Quality Standards:</label>
                <textarea id="qualityStandards" name="qualityStandards" rows="3" required placeholder="Specify grade, size, moisture content, etc."></textarea>
            </div>
            <div class="form-group">
                <label for="inputSupport">Input Support (Seeds, Fertilizers, etc.):</label>
                <textarea id="inputSupport" name="inputSupport" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="agreedPrice">Agreed Price (₹ per unit):</label>
                <input type="number" id="agreedPrice" name="agreedPrice" required>
            </div>
            <div class="form-group">
                <label for="paymentTerms">Payment Terms:</label>
                <textarea id="paymentTerms" name="paymentTerms" rows="2" required placeholder="e.g., on delivery, within X days after delivery"></textarea>
            </div>
            <div class="form-group">
                <label for="deliveryLocation">Delivery Location:</label>
                <textarea id="deliveryLocation" name="deliveryLocation" rows="2" required></textarea>
            </div>
            <div class="form-group">
                <label for="deliveryDeadline">Delivery Deadline:</label>
                <input type="date" id="deliveryDeadline" name="deliveryDeadline" required>
            </div>
            <div class="form-group">
                <label for="forceMajeure">Force Majeure Conditions:</label>
                <textarea id="forceMajeure" name="forceMajeure" rows="2" required placeholder="e.g., natural disasters, disease outbreaks"></textarea>
            </div>
            <div class="form-group">
                <label for="terminationConditions">Termination Conditions:</label>
                <textarea id="terminationConditions" name="terminationConditions" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="disputeResolution">Dispute Resolution:</label>
                <textarea id="disputeResolution" name="disputeResolution" rows="2" required></textarea>
            </div>

            <h2>Signatures</h2>
            <div class="form-group">
                <label for="farmerSignature">Farmer's Signature (Upload Image):</label>
                <input type="file" id="farmerSignature" name="farmerSignature" accept="image/*" required>
            </div>
            <div class="form-group">
                <label for="buyerSignature">Buyer's Signature (Upload Image):</label>
                <input type="file" id="buyerSignature" name="buyerSignature" accept="image/*" required>
            </div>

            <button type="submit">Generate Contract</button>
        </form>
    </div>
</body>
</html>
