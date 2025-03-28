<?php
session_start();
include("dbconfig.php");

// Check if admin is logged in
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit;
}

// Initialize variables
$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $password = $_POST['password'];

    // Check if all fields are filled
    if (empty($first_name) || empty($last_name) || empty($email) || empty($contact_number) || empty($password)) {
        $error = "All fields are required.";
    } else {
        // Encrypt password
        $encrypted_password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        // Insert into the 'management' table
        $insertQuery = "INSERT INTO management (first_name, last_name, email, contact_number, password) 
                        VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $contact_number, $encrypted_password);

        if ($stmt->execute()) {
            // Insert email into 'usertypes' table with user_type = 'management'
            $insertUserTypeQuery = "INSERT INTO usertypes (email, user_type) VALUES (?, 'management')";
            $stmtUserType = $conn->prepare($insertUserTypeQuery);
            $stmtUserType->bind_param("s", $email);
            
            if ($stmtUserType->execute()) {
                $success = "Staff member added successfully.";
            } else {
                $error = "Failed to add user type to usertypes table.";
            }
        } else {
            $error = "Failed to add staff member. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Staff</title>
    <link rel="stylesheet" href="add_staff_style.css">
</head>
<body>
    <div class="container">
        <h1>Add New Staff</h1>

        <!-- Success and Error Messages -->
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Add Staff Form -->
        <form action="add_staff.php" method="POST">
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="contact_number">Contact Number:</label>
                <input type="text" id="contact_number" name="contact_number" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Add Staff</button>
        </form>

        <div class="back-link">
            <a href="admin_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
