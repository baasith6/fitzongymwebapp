<?php
session_start();
include("dbconfig.php");

// Check if staff is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// Initialize variables
$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_name = $_POST['class_name'];
    $trainer = $_POST['trainer'];

    // Check if all fields are filled
    if (empty($class_name) || empty($trainer)) {
        $error = "All fields are required.";
    } else {
        // Insert new class into the database
        $insertQuery = "INSERT INTO classes (class_name, trainer) VALUES (?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ss", $class_name, $trainer);

        if ($stmt->execute()) {
            $success = "Class added successfully!";
        } else {
            $error = "Failed to add class. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Class</title>
    <link rel="stylesheet" href="add_trainer_style.css">
</head>
<body>
    <div class="container">
        <h1>Add New Class</h1>

        <!-- Success and Error Messages -->
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Add Class Form -->
        <form action="add_class.php" method="POST">
            <div class="form-group">
                <label for="class_name">Class Name:</label>
                <input type="text" id="class_name" name="class_name" required>
            </div>
            <div class="form-group">
                <label for="trainer">Trainer:</label>
                <input type="text" id="trainer" name="trainer" required>
            </div>
            <button type="submit">Add Class</button>
        </form>

        <div class="back-link">
            <a href="management_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
