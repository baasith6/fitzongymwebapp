<?php
session_start();
include("dbconfig.php");

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];  // Logged in user's email

// Fetch user's bookings from the class_bookings table along with class and trainer details
$bookingQuery = "SELECT b.bid, c.class_name, t.name AS trainer_name, b.date, b.time 
                 FROM class_bookings b
                 JOIN classes c ON b.cid = c.cid
                 JOIN trainers t ON c.trainer = t.tid
                 WHERE b.email = '$email'";

$bookingResult = $conn->query($bookingQuery);

// Check if the user has any bookings
if ($bookingResult->num_rows > 0) {
    $bookings = $bookingResult->fetch_all(MYSQLI_ASSOC);
} else {
    $bookings = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
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
            <h1>Your Bookings</h1>
        </div>
        

        <?php if (empty($bookings)): ?>
            <p>You have not booked any classes yet.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Class Name</th>
                        <th>Trainer Name</th>
                        <th>Date</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?php echo $booking['bid']; ?></td>
                            <td><?php echo $booking['class_name']; ?></td>
                            <td><?php echo $booking['trainer_name']; ?></td>
                            <td><?php echo $booking['date']; ?></td>
                            <td><?php echo $booking['time']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        
    </div>
</body>
</html>
