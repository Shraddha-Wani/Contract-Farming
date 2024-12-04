<?php
session_start();
require 'db.php';

$message = '';

// Check if buyer is logged in
if (!isset($_SESSION['buyer_id'])) {
    echo "Please log in to view your profile.";
    exit();
}

$buyer_id = $_SESSION['buyer_id'];

// Fetch profile from the database
$profile_query = $conn->prepare("SELECT * FROM buyer_profile WHERE buyer_id = ?");
$profile_query->bind_param("i", $buyer_id);
$profile_query->execute();
$profile_result = $profile_query->get_result();
$profile = $profile_result->fetch_assoc();

// Handle form submission to update/create profile
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $company_name = $_POST['company_name'];
    $email = $_POST['email'];
    $buying_needs = $_POST['buying_needs'];
    $preferred_crops = $_POST['preferred_crops'];
    $contract_terms = $_POST['contract_terms'];



// Save contact numbers as a single string or comma-separated values if it's an array
if (isset($_POST['contact_numbers'])) {
    if (is_array($_POST['contact_numbers'])) {
        $contact_numbers = implode(',', $_POST['contact_numbers']);
    } else {
        $contact_numbers = $_POST['contact_numbers']; // Use it as-is if it's a string
    }
} else {
    $contact_numbers = '';
}





    // Save contact numbers as comma-separated values
    // $contact_numbers = isset($_POST['contact_numbers']) ? implode(',', $_POST['contact_numbers']) : '';

    // Handle file upload for authentication document
    $auth_document_path = $profile['auth_document'] ?? null;
    if (isset($_FILES['auth_document']) && $_FILES['auth_document']['error'] == 0) {
        $file_name = $_FILES['auth_document']['name'];
        $file_tmp = $_FILES['auth_document']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['pdf', 'jpg', 'jpeg', 'png'];
        $upload_dir = 'uploads/';
        $new_file_name = uniqid() . '.' . $file_ext;
        $auth_document_path = $upload_dir . $new_file_name;

        if (in_array($file_ext, $allowed_ext)) {
            move_uploaded_file($file_tmp, $auth_document_path);
        } else {
            $message = "Invalid file type.";
        }
    }

    // Insert or update profile in the database
    if ($profile_result->num_rows == 0) {
        // Insert new profile
        $insert_query = $conn->prepare("INSERT INTO buyer_profile (buyer_id, name, location, company_name, email, buying_needs, preferred_crops, contract_terms, contact_numbers, auth_document) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insert_query->bind_param("isssssssss", $buyer_id, $name, $location, $company_name, $email, $buying_needs, $preferred_crops, $contract_terms, $contact_numbers, $auth_document_path);
        $insert_query->execute();
        $message = "Profile created successfully!";
    } else {
        // Update existing profile
        $update_query = $conn->prepare("UPDATE buyer_profile SET name = ?, location = ?, company_name = ?, email = ?, buying_needs = ?, preferred_crops = ?, contract_terms = ?, contact_numbers = ?, auth_document = ? WHERE buyer_id = ?");
        $update_query->bind_param("sssssssssi", $name, $location, $company_name, $email, $buying_needs, $preferred_crops, $contract_terms, $contact_numbers, $auth_document_path, $buyer_id);
        $update_query->execute();
        $message = "Profile updated successfully!";
    }

    // Refresh the page to show updated profile
    header("Location: home.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <title>Buyer Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
            background-image: url('images/background.jpg');
            background-position: center;

        }


        
        h1, h2 {
            text-align: center;
            color: #333;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input[type="text"], input[type="email"], textarea, input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        button {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #45a049;
        }
        .form-container {
            display: flex;
            flex-direction: column;
        }
        .form-container button {
            margin-top: 15px;
        }
        .message {
            text-align: center;
            padding: 10px;
            margin-bottom: 20px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<h1>Buyer Profile</h1>

<?php if ($message): ?>
    <div class="message"><?php echo $message; ?></div>
<?php endif; ?>

<?php if ($profile): ?>
    <h2>Your Profile Information</h2>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($profile['name']); ?></p>
    <p><strong>Location:</strong> <?php echo htmlspecialchars($profile['location']); ?></p>
    <p><strong>Company Name:</strong> <?php echo htmlspecialchars($profile['company_name']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($profile['email']); ?></p>
    <p><strong>Buying Needs:</strong> <?php echo htmlspecialchars($profile['buying_needs']); ?></p>
    <p><strong>Preferred Crops:</strong> <?php echo htmlspecialchars($profile['preferred_crops']); ?></p>
    <p><strong>Contract Terms:</strong> <?php echo htmlspecialchars($profile['contract_terms']); ?></p>
    <p><strong>Contact Numbers:</strong> <?php echo htmlspecialchars(str_replace(",", ", ", $profile['contact_numbers'])); ?></p>
    <p><strong>Authentication Document:</strong> <a href="<?php echo htmlspecialchars($profile['auth_document']); ?>" target="_blank">View Document</a></p>
<?php else: ?>
    <h2>Create Your Profile</h2>
<?php endif; ?>

<form method="POST" action="" enctype="multipart/form-data">
    <div class="form-container">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($profile['name'] ?? ''); ?>" required>

        <label for="location">Location:</label>
        <input type="text" name="location" id="location" value="<?php echo htmlspecialchars($profile['location'] ?? ''); ?>" required>

        <label for="company_name">Company Name:</label>
        <input type="text" name="company_name" id="company_name" value="<?php echo htmlspecialchars($profile['company_name'] ?? ''); ?>" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($profile['email'] ?? ''); ?>" required>

        <label for="buying_needs">Buying Needs:</label>
        <input type="text" name="buying_needs" id="buying_needs" value="<?php echo htmlspecialchars($profile['buying_needs'] ?? ''); ?>" required>

        <label for="preferred_crops">Preferred Crops:</label>
        <input type="text" name="preferred_crops" id="preferred_crops" value="<?php echo htmlspecialchars($profile['preferred_crops'] ?? ''); ?>" required>

        <label for="contract_terms">Contract Terms:</label>
        <textarea name="contract_terms" id="contract_terms" rows="4" required><?php echo htmlspecialchars($profile['contract_terms'] ?? ''); ?></textarea>

        <label for="contact_numbers">Contact Numbers:</label>
        <input type="text" name="contact_numbers" id="contact_numbers" value="<?php echo htmlspecialchars($profile['contact_numbers'] ?? ''); ?>" required>

        <label for="auth_document">Authentication Document:</label>
        <input type="file" name="auth_document" id="auth_document">

        <button type="submit">Save Profile</button>
    </div>
</form>

</body>
</html>
