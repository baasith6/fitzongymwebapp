<?php
session_start();
include("dbconfig.php");

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];
$userQuery = "
    SELECT c.first_name, c.last_name, c.email, c.contact_number, c.dob, m.name AS membership_name
    FROM customers c
    LEFT JOIN membership_package m ON c.membership = m.mid
    WHERE c.email = '$email'";
    
$result = $conn->query($userQuery);

if ($result->num_rows > 0) {
    $userRow = $result->fetch_assoc();
} else {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <link rel="stylesheet" href="view_profile_style.css">
</head>
<body>
    <div class="container">
        <h1>Your Profile</h1>

        <div class="profile-info">
            <p><label>Name: </label><span><?php echo $userRow['first_name'] . ' ' . $userRow['last_name']; ?></span></p>
            <p><label>Email: </label><span><?php echo $userRow['email']; ?></span></p>
            <p><label>Number: </label><span><?php echo $userRow['contact_number']; ?></span></p>
            <p><label>Date of Birth: </label><span><?php echo $userRow['dob']; ?></span></p>
            <p><label>Membership: </label><span><?php echo $userRow['membership_name'] ?: 'Not Assigned'; ?></span></p>
        </div>

        <div class="back-link">
            <a href="customer_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
