<?php
session_start();
include("dbconfig.php");

// Check if the admin is logged in
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit;
}

// Initialize search filter for payment log
$filter_email = isset($_GET['filter_email']) ? $_GET['filter_email'] : '';

// Fetch payment records from the database, with optional email filter
$sql = "SELECT * FROM payments";
if ($filter_email) {
    $sql .= " WHERE email LIKE '%$filter_email%'";
}
$sql .= " ORDER BY payment_date DESC, payment_time DESC";  // Order payments by date and time

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Payment History</title>
    <link rel="stylesheet" href="view_staffs_style.css">
</head>
<body>
    <div class="container">
        <h1>View Payment History</h1>

        <div class="filter">
            <form action="view_payments.php" method="GET">
                <label for="filter_email">Filter by Email:</label>
                <input type="text" name="filter_email" id="filter_email" value="<?php echo $filter_email; ?>">
                <button type="submit">Filter</button>
            </form>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Email</th>
                        <th>Payment Date</th>
                        <th>Payment Time</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['pid']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['payment_date']; ?></td>
                            <td><?php echo $row['payment_time']; ?></td>
                            <td><?php echo $row['amount']; ?></td>
                            <td><?php echo $row['payment_method']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No payments found.</p>
        <?php endif; ?>

        <div class="back-link">
            <a href="admin_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>



