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
    $contactNumber = $_POST['contact_number'];

    // Update contact number
    $email = $_SESSION['email'];
    $updateQuery = "UPDATE customers SET contact_number = '$contactNumber' WHERE email = '$email'";

    if ($conn->query($updateQuery) === TRUE) {
        header("Location: dashboard.php");
        exit;
    }
}

// Retrieve current contact number
$email = $_SESSION['email'];
$userQuery = "SELECT contact_number FROM customers WHERE email = '$email'";
$result = $conn->query($userQuery);
$userRow = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Contact</title>
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
                <h1>Change Contact Number</h1>
            </div>
        

        <form action="change_contact.php" method="POST">
            <div class="form-group">
                <label for="contact_number" class="form-label" class="form-label">Contact Number:</label>
                <input type="text" class="contact_number" id="contact_number" name="contact_number" value="<?php echo $userRow['contact_number']; ?>" required>
            </div>
            <div class="form-group">
                <button id="update-contact-btn" type="submit">Update Contact</button>
            </div>
        </form>

        
    </div>
</body>
</html>
