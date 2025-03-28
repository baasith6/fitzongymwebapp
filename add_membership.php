<?php
session_start();
include("dbconfig.php");

// Check if the admin is logged in
if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit;
}

// Initialize error and success variables
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];  // Get the membership name
    $price = $_POST['price'];  // Get the membership price

    // Validate the inputs
    if (empty($name) || empty($price)) {
        $error = "Please provide all fields.";
    } else {
        // Generate membership ID (MID) like M001, M002, etc.
        $idQuery = "SELECT MAX(CAST(SUBSTRING(mid, 2) AS UNSIGNED)) AS max_id FROM membership_package";
        $idResult = $conn->query($idQuery);
        $row = $idResult->fetch_assoc();
        $next_id = $row['max_id'] + 1;
        $mid = 'M' . str_pad($next_id, 3, '0', STR_PAD_LEFT);  // Generate membership ID (e.g., M001, M002, etc.)

        // Insert new membership into the database
        $insertQuery = "INSERT INTO membership_package (mid, name, price) 
                        VALUES ('$mid', '$name', '$price')";
        
        if ($conn->query($insertQuery) === TRUE) {
            $success = "New membership added successfully!";
        } else {
            $error = "Error adding membership. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Membership</title>
    <link rel="stylesheet" href="add_trainer_style.css">
</head>
<body>
    <div class="container">
        <h1>Add New Membership</h1>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form action="add_membership.php" method="POST">
            <div class="form-group">
                <label for="name">Membership Name:</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div class="form-group">
                <label for="price">Membership Price:</label>
                <input type="text" name="price" id="price" required>
            </div>
            <button type="submit">Add Membership</button>
        </form>

        <div class="back-link">
            <a href="view_memberships.php">View Memberships</a>
        </div>

        <div class="back-link">
            <a href="admin_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
