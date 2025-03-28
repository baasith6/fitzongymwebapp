<?php
include("dbconfig.php");
session_start();

if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'admin') {
    echo "<p>Access Denied</p>";
    exit;
}

$success = '';
$error = '';

// Handle staff update
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['update_staff']) &&
    isset($_POST['staff_email'], $_POST['new_email'], $_POST['new_password'])
) {
    $staff_email = $_POST['staff_email'];
    $new_email = $_POST['new_email'];
    $new_password = sha1($_POST['new_password']);

    $updateQuery = "UPDATE management SET email = ?, password = ? WHERE email = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sss", $new_email, $new_password, $staff_email);

    if ($stmt->execute()) {
        $success = "Staff updated successfully.";
    } else {
        $error = "Failed to update staff.";
    }
}

$filter_email = isset($_GET['filter_email']) ? $_GET['filter_email'] : '';
$query = "SELECT first_name, last_name, email, contact_number FROM management";
if (!empty($filter_email)) {
    $query .= " WHERE email LIKE ?";
}

$stmt = $conn->prepare($query);
if (!empty($filter_email)) {
    $filter_email = "%$filter_email%";
    $stmt->bind_param("s", $filter_email);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container">
    <h2>Manage Staffs</h2>

    <?php if ($success): ?><div class="success"><?php echo $success; ?></div><?php endif; ?>
    <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>

    <form method="GET" class="filter-form">
        <input type="text" name="filter_email" placeholder="Search by email" value="<?php echo htmlspecialchars($filter_email); ?>">
        <button type="submit">Search</button>
    </form>

    <table class="datatable">
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
                            <form method="POST" class="inline-form">
                                <input type="hidden" name="staff_email" value="<?php echo htmlspecialchars($row['email']); ?>">
                                <input type="text" name="new_email" placeholder="New Email" required>
                                <input type="password" name="new_password" placeholder="New Password" required>
                                <button type="submit" name="update_staff">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">No staff found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
