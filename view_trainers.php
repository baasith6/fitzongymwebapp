<?php
session_start();
include("dbconfig.php");

// Check if admin is logged in
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit;
}

// Initialize variables
$filter_name = '';
$trainers = [];

// Handle filter form submission
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['filter_name'])) {
    $filter_name = $_GET['filter_name'];

    // Fetch trainers filtered by name
    $query = "SELECT * FROM trainers WHERE name LIKE ?";
    $stmt = $conn->prepare($query);
    $like_filter = "%" . $filter_name . "%";
    $stmt->bind_param("s", $like_filter);
    $stmt->execute();
    $result = $stmt->get_result();
    $trainers = $result->fetch_all(MYSQLI_ASSOC);
} else {
    // Fetch all trainers
    $query = "SELECT * FROM trainers";
    $result = $conn->query($query);
    $trainers = $result->fetch_all(MYSQLI_ASSOC);
}

// Handle delete action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_tid'])) {
    $tid = $_POST['delete_tid'];
    $deleteQuery = "DELETE FROM trainers WHERE tid = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $tid);

    if ($stmt->execute()) {
        header("Location: view_trainers.php");
        exit;
    } else {
        $error = "Failed to delete trainer. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Trainers</title>
    <link rel="stylesheet" href="view_trainers_style.css">
</head>
<body>
    <div class="container">
        <h1>View Trainers</h1>

        <!-- Filter Form -->
        <form action="view_trainers.php" method="GET" class="filter-form">
            <input type="text" name="filter_name" placeholder="Search by name" value="<?php echo htmlspecialchars($filter_name); ?>">
            <button type="submit">Search</button>
        </form>

        <!-- Trainers Table -->
        <?php if (!empty($trainers)): ?>
            <table>
                <thead>
                    <tr>
                        <th>TID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact Number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($trainers as $trainer): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($trainer['tid']); ?></td>
                            <td><?php echo htmlspecialchars($trainer['name']); ?></td>
                            <td><?php echo htmlspecialchars($trainer['email']); ?></td>
                            <td><?php echo htmlspecialchars($trainer['contact_number']); ?></td>
                            <td>
                                <!-- Delete Trainer -->
                                <form action="view_trainers.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="delete_tid" value="<?php echo $trainer['tid']; ?>">
                                    <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this trainer?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">No trainers found.</p>
        <?php endif; ?>

        <div class="back-link">
            <a href="admin_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
