<?php
session_start();
include("dbconfig.php");

// ✅ Check for proper admin session
if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Handle password update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];

    if (!empty($new_password)) {
        $hashed_password = sha1($new_password); // Hash the password using SHA1
        $updateQuery = "UPDATE customers SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ss", $hashed_password, $email);

        if ($stmt->execute()) {
            $success = "Password updated successfully for $email.";
        } else {
            $error = "Failed to update password.";
        }
    } else {
        $error = "Password cannot be empty.";
    }
}

// Get customers (optionally filter by email)
$filter_email = isset($_GET['filter_email']) ? $_GET['filter_email'] : '';
$query = "SELECT email, first_name, last_name FROM customers";
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
<div class="container">
    <h1>View Customers</h1>

    <?php if (isset($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>

    <!-- Filter Form -->
    <form method="GET" class="filter-form">
        <label for="filter_email">Filter by Email:</label>
        <input type="text" name="filter_email" id="filter_email" placeholder="Enter email to search" value="<?php echo htmlspecialchars($filter_email); ?>">
        <button type="submit">Search</button>
    </form>

    <!-- Customers Table -->
    <table class="datatable">
        <thead>
            <tr>
                <th>Email</th>
                <th>First Name</th>
                <th>Last Name</th>
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
                        <td>
                            <form method="POST" class="update-form">
                                <input type="hidden" name="email" value="<?php echo htmlspecialchars($row['email']); ?>">
                                <input type="password" name="new_password" placeholder="New Password" required>
                                <button type="submit" name="update_password">Update Password</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No customers found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>