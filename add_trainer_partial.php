<?php
session_start();
include("dbconfig.php");

// Secure access: Only logged-in admin can use this
if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'admin') {
    echo "<div class='error'>Access denied. Admin login required.</div>";
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];

    if (empty($name) || empty($email) || empty($contact_number)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        $idQuery = "SELECT MAX(CAST(SUBSTRING(tid, 2) AS UNSIGNED)) AS max_id FROM trainers";
        $idResult = $conn->query($idQuery);
        $row = $idResult->fetch_assoc();
        $next_id = $row['max_id'] + 1;
        $tid = 'T' . str_pad($next_id, 3, '0', STR_PAD_LEFT);

        $insertQuery = "INSERT INTO trainers (tid, name, email, contact_number) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ssss", $tid, $name, $email, $contact_number);

        if ($stmt->execute()) {
            $success = "Trainer added successfully!";
        } else {
            $error = "Error adding trainer: " . $conn->error;
        }
    }
}

// Inject style if not already added
echo "<script>
  if (!document.querySelector('link[href=\"datatables_admin_style.css\"]')) {
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = 'datatables_admin_style.css';
    document.head.appendChild(link);
  }
</script>";
?>

<div class="container">
    <h1>Add Trainer</h1>

    <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
    <?php if ($success): ?><div class="success"><?php echo $success; ?></div><?php endif; ?>

    <form action="add_trainer_partial.php" method="POST">
        <div class="form-group">
            <label for="name">Trainer Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Trainer Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="contact_number">Contact Number:</label>
            <input type="text" id="contact_number" name="contact_number" required>
        </div>
        <button type="submit">Add Trainer</button>
    </form>
</div>
