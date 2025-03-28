<?php
session_start();
include("dbconfig.php");

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// Handle form submission for password change
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if the new passwords match
    if ($newPassword !== $confirmPassword) {
        $error = "New passwords do not match.";
    } else {
        $email = $_SESSION['email'];

        // Fetch the current password from the database
        $userQuery = "SELECT password FROM customers WHERE email = '$email'";
        $result = $conn->query($userQuery);
        $userRow = $result->fetch_assoc();

        // Verify the current password
        if (password_verify($currentPassword, $userRow['password'])) {
            // Update the password
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateQuery = "UPDATE customers SET password = '$newPasswordHash' WHERE email = '$email'";

            if ($conn->query($updateQuery) === TRUE) {
                $success = "Password updated successfully!";
            } else {
                $error = "Error updating password. Please try again.";
            }
        } else {
            $error = "Current password is incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="change_password_style.css">
</head>
<body>
    <div class="container">
        <h1>Change Your Password</h1>

        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form action="change_password.php" method="POST">
            <div class="form-group">
                <label for="current_password">Current Password:</label>
                <input type="password" name="current_password" id="current_password" required>
            </div>

            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" name="new_password" id="new_password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>

            <button type="submit">Change Password</button>
        </form>

        <div class="back-link">
            <a href="customer_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
