<?php
session_start();
include("dbconfig.php");

// Check if staff is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// Initialize variables
$success = '';
$error = '';

// Fetch customer profiles
$customersQuery = "SELECT * FROM customers";
if (isset($_GET['email'])) {
    $email = $_GET['email'];
    $customersQuery .= " WHERE email LIKE ?";
}
$stmt = $conn->prepare($customersQuery);
if (isset($email)) {
    $stmt->bind_param("s", $email);
}
$stmt->execute();
$customersResult = $stmt->get_result();

// Handle customer email update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_customer'])) {
    $customer_id = $_POST['customer_id'];
    $new_email = $_POST['new_email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $contact_number = $_POST['contact_number'];

    // Update customer profile in customers table
    $updateQuery = "UPDATE customers SET email = ?, first_name = ?, last_name = ?, contact_number = ? WHERE customer_id = ?";
    $stmtUpdate = $conn->prepare($updateQuery);
    $stmtUpdate->bind_param("ssssi", $new_email, $first_name, $last_name, $contact_number, $customer_id);

    if ($stmtUpdate->execute()) {
        // If email changes, update related tables
        $updateRelatedTables = "UPDATE class_bookings SET email = ? WHERE email = ?;
                                UPDATE feedback SET email = ? WHERE email = ?;
                                UPDATE payments SET email = ? WHERE email = ?";
        $stmtRelated = $conn->prepare($updateRelatedTables);
        $stmtRelated->bind_param("ssssss", $new_email, $email, $new_email, $email, $new_email, $email);

        if ($stmtRelated->execute()) {
            $success = "Customer profile updated successfully!";
        } else {
            $error = "Failed to update related tables.";
        }
    } else {
        $error = "Failed to update customer profile.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Customers</title>
    <link rel="stylesheet" href="view_staffs_style.css">
</head>
<body>
    <div class="container">
        <h1>Manage Customer Profiles</h1>

        <!-- Success and Error Messages -->
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Search Form -->
        <form method="GET" action="manage_customers.php">
            <input type="text" name="email" placeholder="Search by email" value="<?php echo isset($email) ? $email : ''; ?>">
            <button type="submit">Search</button>
        </form>

        <!-- Customer Table -->
        <table>
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($customer = $customersResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $customer['first_name']; ?></td>
                        <td><?php echo $customer['last_name']; ?></td>
                        <td><?php echo $customer['email']; ?></td>
                        <td><?php echo $customer['contact_number']; ?></td>
                        <td>
                            <button class="edit-btn" onclick="editCustomer('<?php echo $customer['first_name']; ?>', '<?php echo $customer['last_name']; ?>', '<?php echo $customer['email']; ?>', '<?php echo $customer['contact_number']; ?>')">Edit</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Edit Customer Modal -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Edit Customer</h2>
                <form method="POST" action="manage_customers.php">
                    <input type="hidden" name="customer_id" id="customer_id">
                    <div class="form-group">
                        <label for="first_name">First Name:</label>
                        <input type="text" name="first_name" id="first_name" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name:</label>
                        <input type="text" name="last_name" id="last_name" required>
                    </div>
                    <div class="form-group">
                        <label for="new_email">Email:</label>
                        <input type="email" name="new_email" id="new_email" required>
                    </div>
                    <div class="form-group">
                        <label for="contact_number">Contact Number:</label>
                        <input type="text" name="contact_number" id="contact_number" required>
                    </div>
                    <button type="submit" name="update_customer">Update</button>

                    <div class="back-link">
                        <a href="management_dashboard.php">Back to Dashboard</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editCustomer(first_name, last_name,email, contact_number) {
            document.getElementById('first_name').value = first_name;
            document.getElementById('last_name').value = last_name;
            document.getElementById('new_email').value = email;
            document.getElementById('contact_number').value = contact_number;
            document.getElementById('editModal').style.display = "block";
        }

        // Close the modal when the close button is clicked
        document.querySelector(".close").onclick = function() {
            document.getElementById('editModal').style.display = "none";
        }

        // Close the modal if clicked outside of it
        window.onclick = function(event) {
            if (event.target == document.getElementById('editModal')) {
                document.getElementById('editModal').style.display = "none";
            }
        }
    </script>

        
</body>
</html>
