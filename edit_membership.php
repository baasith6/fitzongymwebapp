<?php
session_start();
include("dbconfig.php");

// Check if the admin is logged in
if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit;
}

// Initialize error and success variables
$error = '';
$success = '';

// Get the membership ID from the URL
$mid = $_GET['mid'];

// Fetch current membership data
$query = "SELECT * FROM membership_package WHERE mid = '$mid'";
$result = $conn->query($query);
$membership = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];

    // Validate the inputs
    if (empty($name) || empty($price)) {
        $error = "Please provide all fields.";
    } else {
        // Update the membership in the database
        $updateQuery = "UPDATE membership_package SET name = '$name', price = '$price' WHERE mid = '$mid'";
        if ($conn->query($updateQuery) === TRUE) {
            $success = "Membership updated successfully!";
        } else {
            $error = "Error updating membership. Please try again.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Membership</title>
    <link rel="stylesheet" href="add_trainer_style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Membership</h1>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form action="edit_membership.php?mid=<?php echo $mid; ?>" method="POST">
            <div class="form-group">
                <label for="name">Membership Name:</label>
                <input type="text" name="name" id="name" value="<?php echo $membership['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Membership Price:</label>
                <input type="text" name="price" id="price" value="<?php echo $membership['price']; ?>" required>
            </div>
            <button type="submit">Update Membership</button>
        </form>

        <div class="back-link">
            <a href="view_memberships.php">Back to Memberships</a>
        </div>
    </div>
</body>
</html>
