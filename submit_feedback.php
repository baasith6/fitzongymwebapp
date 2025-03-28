<?php
session_start();
include("dbconfig.php");

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// Initialize variables
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_SESSION['email'];  // Get logged-in user's email
    $feedback_text = $_POST['feedback_text'];  // Get feedback from form

    // Check if feedback is not empty
    if (empty($feedback_text)) {
        $error = "Please provide your feedback.";
    } else {
        // Generate feedback ID (F001, F002, F003, etc.)
        $idQuery = "SELECT MAX(CAST(SUBSTRING(fid, 2) AS UNSIGNED)) AS max_id FROM feedback";
        $idResult = $conn->query($idQuery);
        if ($idResult === FALSE) {
            $error = "Error fetching max feedback ID: " . $conn->error;
        } else {
            $row = $idResult->fetch_assoc();
            $next_id = $row['max_id'] + 1;
            $fid = 'F' . str_pad($next_id, 3, '0', STR_PAD_LEFT);  // Generate fid (F001, F002, etc.)

            // Insert feedback into the database
            $insertQuery = "INSERT INTO feedback (fid, email, feedback_text, status) 
                            VALUES ('$fid', '$email', '$feedback_text', 'pending')";

            if ($conn->query($insertQuery) === TRUE) {
                $success = "Feedback submitted successfully!";
            } else {
                $error = "Error submitting feedback: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Feedback</title>
    <link rel="stylesheet" href="submit_feedback_style.css">
</head>
<body>
    <div class="container">
        <h1>Submit Feedback</h1>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form action="submit_feedback.php" method="POST">
            <div class="form-group">
                <label for="feedback_text">Your Feedback:</label>
                <textarea name="feedback_text" id="feedback_text" rows="5" required></textarea>
            </div>
            <button type="submit">Submit Feedback</button>
        </form>

        <div class="back-link">
            <a href="customer_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
