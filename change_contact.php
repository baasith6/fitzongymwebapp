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
    <link rel="stylesheet" href="change_contact_style.css">
</head>
<body>
    <div class="container">
        <h1>Change Contact Number</h1>

        <form action="change_contact.php" method="POST">
            <div class="form-group">
                <label for="contact_number" class="form-label" class="form-label">Contact Number:</label>
                <input type="text" class="contact_number" id="contact_number" name="contact_number" value="<?php echo $userRow['contact_number']; ?>" required>
            </div>
            <div class="form-group">
                <button id="update-contact-btn" type="submit">Update Contact</button>
            </div>
        </form>

        <div class="back-link">
            <a href="customer_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
