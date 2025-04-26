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
    <link rel="stylesheet" href="datatables_admin_style.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        .top-bar {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .back-arrow {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-right: 10px;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .back-arrow:hover {
            color: var(--primary-hover);
        }

        h1 {
            margin: 0;
            font-size: 1.8rem;
        }

        .profile-info label {
            font-weight: bold;
            margin-right: 10px;
        }

        .profile-info p {
            margin-bottom: 12px;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="top-bar">
            <i class='bx bx-arrow-back back-arrow' onclick="window.location.href='customer_dashboard.php'"></i>
            <h1>Your Feedback</h1>
        </div>
        

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

        
    </div>
</body>
</html>
