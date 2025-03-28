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

// Fetch feedback from the database
$feedbackQuery = "SELECT * FROM feedback";
if (isset($_GET['email'])) {
    $email = $_GET['email'];
    $feedbackQuery .= " WHERE email LIKE ?";
}
$stmt = $conn->prepare($feedbackQuery);
if (isset($email)) {
    $stmt->bind_param("s", $email);
}
$stmt->execute();
$feedbackResult = $stmt->get_result();

// Handle feedback status update (mark as resolved)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resolve_feedback'])) {
    $feedback_id = $_POST['feedback_id'];

    // Update the feedback status to 'Resolved'
    $updateQuery = "UPDATE feedback SET status = 'Resolved' WHERE fid = ?";
    $stmtUpdate = $conn->prepare($updateQuery);
    $stmtUpdate->bind_param("i", $feedback_id);

    if ($stmtUpdate->execute()) {
        $success = "Feedback marked as resolved successfully!";
    } else {
        $error = "Failed to update feedback status.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback</title>
    <link rel="stylesheet" href="view_feedback_style.css">
</head>
<body>
    <div class="container">
        <h1>View Customer Feedback</h1>

        <!-- Success and Error Messages -->
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Search Form -->
        <form method="GET" action="view_feedback_staff.php">
            <input type="text" name="email" placeholder="Search by email" value="<?php echo isset($email) ? $email : ''; ?>">
            <button type="submit">Search</button>
        </form>

        <!-- Feedback Table -->
        <table>
            <thead>
                <tr>
                    <th>Feedback Text</th>
                    <th>Email</th>
                    <th>Feedback Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($feedback = $feedbackResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $feedback['feedback_text']; ?></td>
                        <td><?php echo $feedback['email']; ?></td>
                        <td><?php echo $feedback['feedback_date']; ?></td>
                        <td><?php echo $feedback['status']; ?></td>
                        <td>
                            <?php if ($feedback['status'] !== 'Resolved'): ?>
                                <form method="POST" action="view_feedback_staff.php">
                                    <input type="hidden" name="feedback_id" value="<?php echo $feedback['fid']; ?>">
                                    <button type="submit" name="resolve_feedback">Mark as Resolved</button>
                                </form>
                            <?php else: ?>
                                <span>Resolved</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="back-link">
            <a href="management_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
