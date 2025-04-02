<?php
session_start();
include("dbconfig.php");

// Check if admin is logged in
if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'admin') {
    echo "<div class='error'>Access denied. Admin login required.</div>";
    exit;
}

$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $password = $_POST['password'];

    if (empty($first_name) || empty($last_name) || empty($email) || empty($contact_number) || empty($password)) {
        $error = "All fields are required.";
    } else {
        $encrypted_password = password_hash($password, PASSWORD_BCRYPT);

        $insertQuery = "INSERT INTO management (first_name, last_name, email, contact_number, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $contact_number, $encrypted_password);

        if ($stmt->execute()) {
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

<div class="container">
    <h1>Add New Staff</h1>

    <?php if ($success): ?><div class="success"><?php echo $success; ?></div><?php endif; ?>
    <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>

    <form action="add_staff_partial.php" method="POST">
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
</div>
