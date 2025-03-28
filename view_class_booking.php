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

// Fetch class bookings
$classBookingsQuery = "SELECT * FROM class_bookings";
if (isset($_GET['email'])) {
    $email = $_GET['email'];
    $classBookingsQuery .= " WHERE email LIKE ?";
}
$stmt = $conn->prepare($classBookingsQuery);
if (isset($email)) {
    $stmt->bind_param("s", $email);
}
$stmt->execute();
$classBookingsResult = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Class Bookings</title>
    <link rel="stylesheet" href="view_trainers_style.css">
</head>
<body>
    <div class="container">
        <h1>View Class Bookings</h1>

        <!-- Success and Error Messages -->
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Search Form -->
        <form method="GET" action="view_class_booking.php">
            <input type="text" name="email" placeholder="Search by email" value="<?php echo isset($email) ? $email : ''; ?>">
            <button type="submit">Search</button>
        </form>

        <!-- Class Bookings Table -->
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Email</th>
                    <th>Class ID</th>
                    <th>Class Name</th>
                    <th>Date</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($booking = $classBookingsResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $booking['bid']; ?></td>
                        <td><?php echo $booking['email']; ?></td>
                        <td><?php echo $booking['cid']; ?></td>
                        <td>
                            <?php
                            // Fetch class name based on class ID (cid)
                            $classQuery = "SELECT class_name FROM classes WHERE cid = ?";
                            $stmtClass = $conn->prepare($classQuery);
                            $stmtClass->bind_param("s", $booking['cid']);
                            $stmtClass->execute();
                            $classResult = $stmtClass->get_result();
                            $class = $classResult->fetch_assoc();
                            echo $class['class_name'];
                            ?>
                        </td>
                        <td><?php echo $booking['date']; ?></td>
                        <td><?php echo $booking['time']; ?></td>
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
