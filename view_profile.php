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
            <h1>Your Profile</h1>
        </div>

        <div class="profile-info">
            <p><label>Name: </label><span><?php echo $userRow['first_name'] . ' ' . $userRow['last_name']; ?></span></p>
            <p><label>Email: </label><span><?php echo $userRow['email']; ?></span></p>
            <p><label>Number: </label><span><?php echo $userRow['contact_number']; ?></span></p>
            <p><label>Date of Birth: </label><span><?php echo $userRow['dob']; ?></span></p>
            <p><label>Membership: </label><span><?php echo $userRow['membership_name'] ?: 'Not Assigned'; ?></span></p>
        </div>

        
    </div>
</body>
</html>
