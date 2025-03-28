<?php
session_start();
include("dbconfig.php");

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// Get staff details from the database
$email = $_SESSION['email'];
$query = "SELECT first_name FROM management WHERE email = '$email'"; // Assuming the staff data is in the 'staff' table
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $staffRow = $result->fetch_assoc();
    $firstName = $staffRow['first_name'];
} else {
    echo "Staff member not found!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="customer_dashboard_style.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="logout-btn">
            <a href="logout.php">Log Out</a>
        </div>

        <!-- Welcome Message -->
        <h1>Welcome, <?php echo $firstName; ?>!</h1>  <!-- Displaying staff's first name -->

        <div class="options">
            <div class="options-section">
                <h2>1. Manage Customer Profile</h2>
                <ul>
                    <li><a href="manage_customers.php">Manage Customer Profile</a></li>
                </ul>
            </div>

            <div class="options-section">
                <h2>2. Manage Classes</h2>
                <ul>
                    <li><a href="view_classes.php">View Classes</a></li>
                    <li><a href="add_class.php">Add New Class</a></li>
                    <li><a href="view_class_booking.php">View Class Bookings</a></li>
                </ul>
            </div>

            <div class="options-section">
                <h2>3. View Trainers</h2>
                <ul>
                    <li><a href="view_trainer.php">View Trainers</a></li>
                </ul>
            </div>

            <div class="options-section">
                <h2>4. View Membership Plans</h2>
                <ul>
                    <li><a href="view_membership.php">View Memberships</a></li>
                </ul>
            </div>

            <div class="options-section">
                <h2>5. Manage Payments</h2>
                <ul>
                    <li><a href="view_payment.php">View Payment Log</a></li>
                    <li><a href="add_payment.php">Add Payment</a></li>
                </ul>
            </div>

            <div class="options-section">
                <h2>6. Manage Feedback</h2>
                <ul>
                    <li><a href="view_feedback_staff.php">View Feedback</a></li>
                    <li><a href="view_question.php">View Question</a></li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
