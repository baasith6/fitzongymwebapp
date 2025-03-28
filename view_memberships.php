<?php
session_start();
include("dbconfig.php");

// Check if admin is logged in
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit;
}

// Handle delete membership
if (isset($_GET['delete'])) {
    $mid = $_GET['delete'];
    $deleteQuery = "DELETE FROM membership_package WHERE mid = '$mid'";

    if ($conn->query($deleteQuery) === TRUE) {
        $successMessage = "Membership deleted successfully.";
    } else {
        $errorMessage = "Error deleting membership: " . $conn->error;
    }
}

// Handle search filter by Membership ID
$searchMid = '';
if (isset($_POST['search'])) {
    $searchMid = $_POST['search_mid'];
}

// Fetch memberships from database
$sql = "SELECT * FROM membership_package WHERE mid LIKE '%$searchMid%' ORDER BY mid ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Memberships</title>
    <link rel="stylesheet" href="view_staffs_style.css">
</head>
<body>
    <div class="container">
        <h1>Manage Memberships</h1>
        
        <!-- Success or error message -->
        <?php if (isset($successMessage)): ?>
            <div class="success"><?php echo $successMessage; ?></div>
        <?php elseif (isset($errorMessage)): ?>
            <div class="error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <!-- Search Form -->
        <form action="view_memberships.php" method="POST">
            <label for="search_mid">Filter by Membership ID (MID):</label>
            <input type="text" name="search_mid" id="search_mid" value="<?php echo $searchMid; ?>" placeholder="Enter MID (e.g. M001)">
            <button type="submit" name="search">Search</button>
        </form>

        <!-- Memberships Table -->
        <table>
            <thead>
                <tr>
                    <th>Membership ID</th>
                    <th>Membership Name</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['mid']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['price']; ?></td>
                            <td>
                                <a href="edit_membership.php?mid=<?php echo $row['mid']; ?>">Edit</a> |
                                <a href="view_memberships.php?delete=<?php echo $row['mid']; ?>" onclick="return confirm('Are you sure you want to delete this membership?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No memberships found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        
        
        <div class="back-link">
            <a href="admin_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
