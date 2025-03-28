<?php
session_start();
include("dbconfig.php");

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// Get user details
$email = $_SESSION['email'];
$userQuery = "SELECT first_name, last_name FROM customers WHERE email = '$email'";
$result = $conn->query($userQuery);
if ($result->num_rows > 0) {
    $userRow = $result->fetch_assoc();
    $firstName = $userRow['first_name'];
    $lastName = $userRow['last_name'];
} else {
    echo "User not found!";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="customer_dashboard_style.css">
</head>
<body>
    
    <div class="dashboard-container">
        
        <form action="logout.php" method="POST" class="logout-btn">
            <button type="submit">Log Out</button>
        </form>

        <h1>Welcome to FitZone, <?php echo $firstName; ?>!</h1>
        <div class="options">
            <h2>Manage Profile</h2>
            <ul>
                <li><a href="view_profile.php">View Profile</a></li>
                <li><a href="change_name.php">Change Name</a></li>
                <li><a href="change_contact.php">Change Contact Number</a></li>
                <li><a href="change_password.php">Change Password</a></li>
            </ul>

            <h2>Booking Classes</h2>
            <ul>
                <li><a href="book_class.php">Book a Class</a></li>
                <li><a href="View_my_booking.php">View My Bookings</a></li>
            </ul>

            <h2>Meal Plan</h2>
            <ul>
                <li><a href="view_meal_plan.php">View Meal Plan</a></li>
            </ul>

            <h2>BMI Calculator</h2>
            <ul>
                <li><a href="bmi_calculator.php">Calculate BMI</a></li>
            </ul>

            <h2>Payment History</h2>
            <ul>
                <li><a href="payment_history.php">View Payment History</a></li>
            </ul>

            <h2>Feedback</h2>
            <ul>
                <li><a href="submit_feedback.php">Submit Feedback</a></li>
                <li><a href="view_feedback.php">View Feedback</a></li>
            </ul>
        </div>
    </div>
    
    
</body>
</html>
