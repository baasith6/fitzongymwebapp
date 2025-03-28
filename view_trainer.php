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

// Fetch trainers
$trainersQuery = "SELECT * FROM trainers";
if (isset($_GET['tid'])) {
    $tid = $_GET['tid'];
    $trainersQuery .= " WHERE tid LIKE ?";
}
$stmt = $conn->prepare($trainersQuery);
if (isset($tid)) {
    $stmt->bind_param("s", $tid);
}
$stmt->execute();
$trainersResult = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Trainers</title>
    <link rel="stylesheet" href="view_feedback_style.css">
</head>
<body>
    <div class="container">
        <h1>View Trainers</h1>

        <!-- Success and Error Messages -->
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Search Form -->
        <form method="GET" action="view_trainers.php">
            <input type="text" name="tid" placeholder="Search by Trainer ID" value="<?php echo isset($tid) ? $tid : ''; ?>">
            <button type="submit">Search</button>
        </form>

        <!-- Trainers Table -->
        <table>
            <thead>
                <tr>
                    <th>Trainer ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($trainer = $trainersResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $trainer['tid']; ?></td>
                        <td><?php echo $trainer['name']; ?></td>
                        <td><?php echo $trainer['email']; ?></td>
                        <td><?php echo $trainer['contact_number']; ?></td>
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
