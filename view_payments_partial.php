<?php
session_start();
include("dbconfig.php");

// Check if the admin is logged in
if (!isset($_SESSION['email']) || !in_array($_SESSION['user_type'], ['admin', 'management'])) {
    http_response_code(403);
    echo "Unauthorized access.";
    exit;
}


// Initialize search filter for payment log
$filter_email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filter_email'])) {
    $filter_email = $_POST['filter_email'];
}

// Fetch payment records from the database, with optional email filter
$sql = "SELECT * FROM payments";
if (!empty($filter_email)) {
    $sql .= " WHERE email LIKE ?";
}
$sql .= " ORDER BY payment_date DESC, payment_time DESC";

$stmt = $conn->prepare($sql);
if (!empty($filter_email)) {
    $search_email = '%' . $filter_email . '%';
    $stmt->bind_param("s", $search_email);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container">
    <h1>View Payment History</h1>

    <div class="filter">
        <form method="POST">
            <label for="filter_email">Filter by Email:</label>
            <input type="text" name="filter_email" id="filter_email" value="<?php echo htmlspecialchars($filter_email); ?>">
            <button type="submit">Filter</button>
        </form>
    </div>

    <?php if ($result->num_rows > 0): ?>
        <table class="datatable">
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
                        <td><?php echo htmlspecialchars($row['pid']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['payment_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['payment_time']); ?></td>
                        <td><?php echo htmlspecialchars($row['amount']); ?></td>
                        <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No payments found.</p>
    <?php endif; ?>
</div>