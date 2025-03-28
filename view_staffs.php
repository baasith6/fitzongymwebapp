<?php
session_start();
include("dbconfig.php");

// Check if admin is logged in
if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Initialize variables
$success = '';
$error = '';

// Handle staff update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_staff'])) {
    $staff_email = $_POST['staff_email'];
    $new_email = $_POST['new_email'];
    $new_password = $_POST['new_password'];

    // Encrypt password
    $encrypted_password = sha1($new_password);

    // Update query
    $updateQuery = "UPDATE management SET email = ?, password = ? WHERE email = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sss", $new_email, $encrypted_password, $staff_email);

    if ($stmt->execute()) {
        $success = "Staff details updated successfully.";
    } else {
        $error = "Failed to update staff details. Please try again.";
    }
}

// Get staff details (optionally filter by email)
$filter_email = isset($_GET['filter_email']) ? $_GET['filter_email'] : '';
$query = "SELECT first_name, last_name, email, contact_number FROM management";
if (!empty($filter_email)) {
    $query .= " WHERE email LIKE ?";
}

$stmt = $conn->prepare($query);
if (!empty($filter_email)) {
    $filter_email = "%" . $filter_email . "%";
    $stmt->bind_param("s", $filter_email);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Staffs</title>
    <link rel="stylesheet" href="view_staffs_style.css">
</head>
<body>
    <div class="container">
        <h1>View and Manage Staffs</h1>

        <!-- Success and Error Messages -->
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Filter Form -->
        <form method="GET" class="filter-form">
            <label for="filter_email">Filter by Email:</label>
            <input type="text" name="filter_email" id="filter_email" placeholder="Enter email to search" value="<?php echo htmlspecialchars($filter_email); ?>">
            <button type="submit">Search</button>
        </form>

        <!-- Staffs Table -->
        <table>
            <thead>
                <tr>
                    <th>Email</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Contact Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                            <td>
                                <!-- Edit Button -->
                                <form method="POST" style="display:inline-block;">
                                    <input type="hidden" name="staff_email" value="<?php echo htmlspecialchars($row['email']); ?>">
                                    <input type="text" name="new_email" placeholder="New Email" required>
                                    <input type="password" name="new_password" placeholder="New Password" required>
                                    <button type="submit" name="update_staff">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No staff members found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="back-link">
            <a href="admin_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
