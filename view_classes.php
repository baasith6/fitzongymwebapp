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
$classesQuery = "SELECT * FROM classes"; // Default query to fetch all classes

// Filter by CID if parameter is passed
if (isset($_GET['cid'])) {
    $cid = $_GET['cid'];
    $classesQuery .= " WHERE cid LIKE ?";
    $stmt = $conn->prepare($classesQuery);
    $stmt->bind_param("s", $cid);
} else {
    $stmt = $conn->prepare($classesQuery);
}

$stmt->execute();
$classesResult = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Classes</title>
    <link rel="stylesheet" href="view_staffs_style.css">
</head>
<body>
    <div class="container">
        <h1>View Classes</h1>

        <!-- Success and Error Messages -->
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Search Form -->
        <form method="GET" action="view_classes.php">
            <input type="text" name="cid" placeholder="Search by Class ID" value="<?php echo isset($cid) ? $cid : ''; ?>">
            <button type="submit">Search</button>
        </form>

        <!-- Classes Table -->
        <table>
            <thead>
                <tr>
                    <th>Class ID</th>
                    <th>Class Name</th>
                    <th>Trainer</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($class = $classesResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $class['cid']; ?></td>
                        <td><?php echo $class['class_name']; ?></td>
                        <td><?php echo $class['trainer']; ?></td>
                        <td>
                            <button class="edit-btn" onclick="editClass('<?php echo $class['cid']; ?>', '<?php echo $class['class_name']; ?>', '<?php echo $class['trainer']; ?>')">Edit</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Edit Class Modal -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Edit Class</h2>
                <form method="POST" action="edit_class.php">
                    <input type="hidden" name="cid" id="cid">
                    <div class="form-group">
                        <label for="class_name">Class Name:</label>
                        <input type="text" name="class_name" id="class_name" required>
                    </div>
                    <div class="form-group">
                        <label for="trainer">Trainer:</label>
                        <input type="text" name="trainer" id="trainer" required>
                    </div>
                    <button type="submit" name="update_class">Update</button>

                    <div class="back-link">
                        <a href="management_dashboard.php">Back to Dashboard</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editClass(cid, class_name, trainer) {
            document.getElementById('cid').value = cid;
            document.getElementById('class_name').value = class_name;
            document.getElementById('trainer').value = trainer;
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
