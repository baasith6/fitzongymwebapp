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
    <link rel="stylesheet" href="change_name_style.css">
</head>
<body>
    <div id="main-container">
        <div id="form-container">
            <h1 id="form-title">Change Your Name</h1>
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
            <a id="dashboard-link" href="dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
