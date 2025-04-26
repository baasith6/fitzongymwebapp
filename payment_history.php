<?php
session_start();
include("dbconfig.php");

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];  // Logged in user's email

// Query to fetch payment history for the logged-in user, including payment time
$paymentQuery = "SELECT p.pid, p.payment_date, p.payment_time, p.amount, p.payment_method 
                 FROM payments p
                 WHERE p.email = '$email' 
                 ORDER BY p.payment_date DESC";

$paymentResult = $conn->query($paymentQuery);

// Check if there are any payments
if ($paymentResult->num_rows > 0) {
    $payments = $paymentResult->fetch_all(MYSQLI_ASSOC);
} else {
    $payments = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History</title>
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
            <h1>Your Payment History</h1>
        </div>
        

        <?php if (empty($payments)): ?>
            <p>You have no payment history yet.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td><?php echo $payment['pid']; ?></td>
                            <td><?php echo $payment['payment_date']; ?></td>
                            <td><?php echo $payment['payment_time']; ?></td>
                            <td><?php echo $payment['amount']; ?></td>
                            <td><?php echo $payment['payment_method']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        
    </div>
</body>
</html>
