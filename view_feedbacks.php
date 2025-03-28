<?php
session_start();
include("dbconfig.php");

// Check if the admin is logged in
if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit;
}

// Fetch feedback records from the database
$sql = "SELECT * FROM feedback ORDER BY feedback_time DESC";  // Order feedback by time
$result = $conn->query($sql);

// Handle status update
if (isset($_GET['resolve_id'])) {
    $fid = $_GET['resolve_id'];
    $updateQuery = "UPDATE feedback SET status = 'Resolved' WHERE fid = '$fid'";
    if ($conn->query($updateQuery) === TRUE) {
        header("Location: view_feedbacks.php");  // Refresh the page to show updated status
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback</title>
    <link rel="stylesheet" href="view_staffs_style.css">
</head>
<body>
    <div class="container">
        <h1>View Feedback</h1>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Feedback ID</th>
                        <th>Email</th>
                        <th>Feedback Text</th>
                        <th>Feedback Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['fid']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['feedback_text']; ?></td>
                            <td><?php echo $row['feedback_time']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td>
                                <?php if ($row['status'] !== 'Resolved'): ?>
                                    <a href="view_feedback.php?resolve_id=<?php echo $row['fid']; ?>" class="resolve-button">Mark as Resolved</a>
                                <?php else: ?>
                                    <span>Resolved</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No feedback found.</p>
        <?php endif; ?>

        <div class="back-link">
            <a href="admin_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
