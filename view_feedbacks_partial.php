<?php
session_start();
include("dbconfig.php");

if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['resolve_id'])) {
    $fid = $_GET['resolve_id'];
    $updateQuery = "UPDATE feedback SET status = 'Resolved' WHERE fid = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("i", $fid);
    if ($stmt->execute()) {
        echo "<script>loadPage('view_feedbacks_partial.php');</script>";
        exit;
    } else {
        echo "<p>Error updating feedback status.</p>";
        exit;
    }
}

$sql = "SELECT * FROM feedback ORDER BY feedback_time DESC";
$result = $conn->query($sql);
?>

<div class="container">
    <h1>View Feedback</h1>

    <?php if ($result->num_rows > 0): ?>
        <table class="datatable">
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
                        <td><?php echo htmlspecialchars($row['fid']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['feedback_text']); ?></td>
                        <td><?php echo htmlspecialchars($row['feedback_time']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td>
                            <?php if ($row['status'] !== 'Resolved'): ?>
                                <a href="view_feedbacks_partial.php?resolve_id=<?php echo $row['fid']; ?>" class="resolve-button">Resolve</a>
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
</div>
