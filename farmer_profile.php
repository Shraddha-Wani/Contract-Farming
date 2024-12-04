<?php
session_start();
require 'db.php';

// Initialize message variable for displaying save status
$message = '';

// Check if the farmer is logged in
if (!isset($_SESSION['farmer_id'])) {
    echo "Please log in to view or create your profile.";
    exit();
}

$farmer_id = $_SESSION['farmer_id'];
$profile_query = $conn->prepare("SELECT * FROM farmer_profile WHERE farmer_id = ?");
$profile_query->bind_param("i", $farmer_id);
$profile_query->execute();
$profile_result = $profile_query->get_result();

// Fetch profile if it exists
$profile = $profile_result->fetch_assoc(); // This will fetch the profile data as an associative array

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handling form submission
    $name = $_POST['name'];
    $location = $_POST['location'];
    $crop_type = $_POST['crop_type'];
    $crop_quantity = $_POST['crop_quantity'];
    $experience = $_POST['experience'];
    $harvest_time = $_POST['harvest_time'];
    $contract_terms = $_POST['contract_terms'];

    // Handle authentication document upload
    if (isset($_FILES['auth_document']) && $_FILES['auth_document']['error'] == 0) {
        $file_name = $_FILES['auth_document']['name'];
        $file_tmp = $_FILES['auth_document']['tmp_name'];
        $file_size = $_FILES['auth_document']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Define allowed file types and maximum file size (in bytes)
        $allowed_ext = ['pdf', 'jpg', 'jpeg', 'png'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        // Check file size and extension
        if ($file_size > $max_size) {
            $message = "File size exceeds the maximum limit of 5MB.";
        } elseif (!in_array($file_ext, $allowed_ext)) {
            $message = "Invalid file type. Only PDF, JPG, JPEG, and PNG files are allowed.";
        } else {
            // Generate a unique file name and move the uploaded file to the 'uploads' directory
            $new_file_name = uniqid() . '.' . $file_ext;
            $upload_dir = 'uploads/'; // Make sure this folder exists and has proper write permissions
            if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
                $auth_document_path = $upload_dir . $new_file_name;
            } else {
                $message = "Error uploading file.";
            }
        }
    } else {
        // If no document is uploaded, set auth_document to NULL or empty
        $auth_document_path = null;
    }

    // Handle multiple contact numbers
    $contact_numbers = isset($_POST['contact_numbers']) ? implode(',', $_POST['contact_numbers']) : ''; // Avoid warning if empty

    // Insert or update profile
    if ($profile_result->num_rows == 0) {
        // Insert new profile
        $insert_query = $conn->prepare("INSERT INTO farmer_profile (farmer_id, name, location, crop_type, crop_quantity, experience, harvest_time, contract_terms, auth_document, contact_numbers) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insert_query->bind_param("isssiissss", $farmer_id, $name, $location, $crop_type, $crop_quantity, $experience, $harvest_time, $contract_terms, $auth_document_path, $contact_numbers);
        $insert_query->execute();
        $message = "Profile successfully created!";
    } else {
        // Update existing profile
        $update_query = $conn->prepare("UPDATE farmer_profile SET name = ?, location = ?, crop_type = ?, crop_quantity = ?, experience = ?, harvest_time = ?, contract_terms = ?, auth_document = ?, contact_numbers = ? WHERE farmer_id = ?");
        $update_query->bind_param("sssiissssi", $name, $location, $crop_type, $crop_quantity, $experience, $harvest_time, $contract_terms, $auth_document_path, $contact_numbers, $farmer_id);
        $update_query->execute();
        $message = "Profile successfully updated!";
    }

    // Redirect to the home page after saving or updating the profile
    header("Location: home.html");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Farmer Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
            background-position: center;
            background-image: url('images/background.jpg');
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

        input[type="text"], input[type="number"], textarea, input[type="file"] {
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

<h1>Farmer Profile</h1>

<?php if ($message): ?>
    <div class="message"><?php echo $message; ?></div>
<?php endif; ?>

<?php if ($profile): ?>
    <h2>Your Profile Information</h2>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($profile['name']); ?></p>
    <p><strong>Location:</strong> <?php echo htmlspecialchars($profile['location']); ?></p>
    <p><strong>Crop Type:</strong> <?php echo htmlspecialchars($profile['crop_type']); ?></p>
    <p><strong>Crop Quantity:</strong> <?php echo htmlspecialchars($profile['crop_quantity']); ?> tons</p>
    <p><strong>Experience:</strong> <?php echo htmlspecialchars($profile['experience']); ?> years</p>
    <p><strong>Harvest Time:</strong> <?php echo htmlspecialchars($profile['harvest_time']); ?></p>
    <p><strong>Contract Terms:</strong> <?php echo htmlspecialchars($profile['contract_terms']); ?></p>
    <p><strong>Authentication Document:</strong> <a href="<?php echo htmlspecialchars($profile['auth_document']); ?>" target="_blank">View Document</a></p>
    <p><strong>Contact Number:</strong> <?php echo htmlspecialchars(str_replace(",", ", ", $profile['contact_numbers'])); ?></p>
<?php else: ?>
    <h2>Create Your Profile</h2>
<?php endif; ?>

<form method="POST" action="" enctype="multipart/form-data">
    <div class="form-container">
        <label for="name">Name:</label><br>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($profile['name'] ?? ''); ?>" required><br>

        <label for="location">Location:</label><br>
        <input type="text" name="location" id="location" value="<?php echo htmlspecialchars($profile['location'] ?? ''); ?>" required><br>

        <label for="crop_type">Crop Type:</label><br>
        <input type="text" name="crop_type" id="crop_type" value="<?php echo htmlspecialchars($profile['crop_type'] ?? ''); ?>" required><br>

        <label for="crop_quantity">Crop Quantity (tons):</label><br>
        <input type="number" name="crop_quantity" id="crop_quantity" value="<?php echo htmlspecialchars($profile['crop_quantity'] ?? ''); ?>" required><br>

        <label for="experience">Experience (years):</label><br>
        <input type="number" name="experience" id="experience" value="<?php echo htmlspecialchars($profile['experience'] ?? ''); ?>" required><br>

        <label for="harvest_time">Harvest Time:</label><br>
        <input type="text" name="harvest_time" id="harvest_time" value="<?php echo htmlspecialchars($profile['harvest_time'] ?? ''); ?>" required><br>

        <label for="contract_terms">Contract Terms:</label><br>
        <textarea name="contract_terms" id="contract_terms" required><?php echo htmlspecialchars($profile['contract_terms'] ?? ''); ?></textarea><br>

        <label for="auth_document">Authentication Document (PDF, JPG, JPEG, PNG):</label><br>
        <input type="file" name="auth_document" id="auth_document"><br>

        <label for="contact_numbers">Contact Number:</label><br>
        <input type="text" name="contact_numbers[]" id="contact_numbers" value="<?php echo htmlspecialchars($profile['contact_numbers'] ?? ''); ?>"><br>

        <button type="submit">Save Profile</button>
    </div>
</form>

</body>
</html>
