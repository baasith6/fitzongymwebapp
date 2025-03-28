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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_payment'])) {
    $email = $_POST['email'];
    $payment_date = $_POST['payment_date'];
    $payment_time = $_POST['payment_time'];
    $amount = $_POST['amount'];
    $payment_method = $_POST['payment_method'];

    // Check if all fields are filled
    if (empty($email) || empty($payment_date) || empty($payment_time) || empty($amount) || empty($payment_method)) {
        $error = "All fields are required.";
    } else {
        // Generate a unique payment ID
        $payment_id = 'P' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

        // Insert the payment into the database
        $insertQuery = "INSERT INTO payments (pid, email, payment_date, payment_time, amount, payment_method) 
                        VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ssssds", $payment_id, $email, $payment_date, $payment_time, $amount, $payment_method);

        if ($stmt->execute()) {
            $success = "Payment added successfully!";
        } else {
            $error = "Failed to add payment. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Payment</title>
    <link rel="stylesheet" href="add_trainer_style.css">
</head>
<body>
    <div class="container">
        <h1>Add Payment</h1>

        <!-- Success and Error Messages -->
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Add Payment Form -->
        <form method="POST" action="add_payment.php">
            <div class="form-group">
                <label for="email">Customer Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="payment_date">Payment Date:</label>
                <input type="date" id="payment_date" name="payment_date" required>
            </div>
            <div class="form-group">
                <label for="payment_time">Payment Time:</label>
                <input type="time" id="payment_time" name="payment_time" required>
            </div>
            <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="number" id="amount" name="amount" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="payment_method">Payment Method:</label>
                <select id="payment_method" name="payment_method" required>
                    <option value="Cash">Cash</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="PayPal">PayPal</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                </select>
            </div>
            <button type="submit" name="add_payment">Add Payment</button>
        </form>

        <div class="back-link">
            <a href="management_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
