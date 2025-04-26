<?php
session_start();
include("dbconfig.php");

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];

    // Update user name
    $email = $_SESSION['email'];
    $updateQuery = "UPDATE customers SET first_name = '$firstName', last_name = '$lastName' WHERE email = '$email'";

    if ($conn->query($updateQuery) === TRUE) {
        header("Location: dashboard.php");
        exit;
    }
}

$email = $_SESSION['email'];
$userQuery = "SELECT first_name, last_name FROM customers WHERE email = '$email'";
$result = $conn->query($userQuery);
$userRow = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Name</title>
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
    <div id="container">
        <div id="form-container">
            <div class="top-bar">
                <i class='bx bx-arrow-back back-arrow' onclick="window.location.href='customer_dashboard.php'"></i>
                <h1 id="form-title">Change Your Name</h1>
            </div>
            
            <form id="name-form" action="change_name.php" method="POST">
                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input id="first_name" type="text" name="first_name" value="<?php echo $userRow['first_name']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input id="last_name" type="text" name="last_name" value="<?php echo $userRow['last_name']; ?>" required>
                </div>
                <button id="submit-button" type="submit">Update Name</button>
            </form>
            
        </div>
    </div>
</body>
</html>
