<?php
session_start();
include("dbconfig.php");

// Check if admin is logged in
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit;
}

// Initialize variables
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];

    // Validate inputs
    if (empty($name) || empty($email) || empty($contact_number)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Generate a unique TID
        $idQuery = "SELECT MAX(CAST(SUBSTRING(tid, 2) AS UNSIGNED)) AS max_id FROM trainers";
        $idResult = $conn->query($idQuery);
        $row = $idResult->fetch_assoc();
        $next_id = $row['max_id'] + 1;
        $tid = 'T' . str_pad($next_id, 3, '0', STR_PAD_LEFT);

        // Insert trainer into the database
        $insertQuery = "INSERT INTO trainers (tid, name, email, contact_number) 
                        VALUES ('$tid', '$name', '$email', '$contact_number')";

        if ($conn->query($insertQuery) === TRUE) {
            $success = "Trainer added successfully!";
        } else {
            $error = "Error adding trainer: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Trainer</title>
    <link rel="stylesheet" href="add_trainer_style.css">
</head>
<body>
    <div class="container">
        <h1>Add Trainer</h1>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form action="add_trainer.php" method="POST">
            <div class="form-group">
                <label for="name">Trainer Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Trainer Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="contact_number">Contact Number:</label>
                <input type="text" id="contact_number" name="contact_number" required>
            </div>
            <button type="submit">Add Trainer</button>
        </form>

        <div class="back-link">
            <a href="admin_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
