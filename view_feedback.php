<?php
session_start();
include("dbconfig.php");

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];  // Get the logged-in user's email

// Query to fetch feedback from the logged-in user only
$feedbackQuery = "SELECT fid, feedback_text, status, feedback_time 
                  FROM feedback 
                  WHERE email = '$email' 
                  ORDER BY feedback_time DESC";

$feedbackResult = $conn->query($feedbackQuery);

// Check if feedback records exist
if ($feedbackResult->num_rows > 0) {
    $feedbacks = $feedbackResult->fetch_all(MYSQLI_ASSOC);
} else {
    $feedbacks = [];
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
        <h1>Your Feedback</h1>

        <?php if (empty($feedbacks)): ?>
            <p>You haven't submitted any feedback yet.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Feedback ID</th>
                        <th>Feedback Text</th>
                        <th>Status</th>
                        <th>Feedback Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($feedbacks as $feedback): ?>
                        <tr>
                            <td><?php echo $feedback['fid']; ?></td>
                            <td><?php echo nl2br(htmlspecialchars($feedback['feedback_text'])); ?></td>
                            <td><?php echo $feedback['status']; ?></td>
                            <td><?php echo $feedback['feedback_time']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div class="back-link">
            <a href="customer_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
