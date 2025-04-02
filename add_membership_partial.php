<?php
session_start();
include("dbconfig.php");

// ✅ Protect this route: Only logged-in admins can access
if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}


$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];

    if (empty($name) || empty($price)) {
        $error = "Please provide all fields.";
    } else {
        $idQuery = "SELECT MAX(CAST(SUBSTRING(mid, 2) AS UNSIGNED)) AS max_id FROM membership_package";
        $idResult = $conn->query($idQuery);
        $row = $idResult->fetch_assoc();
        $next_id = $row['max_id'] + 1;
        $mid = 'M' . str_pad($next_id, 3, '0', STR_PAD_LEFT);

        $insertQuery = "INSERT INTO membership_package (mid, name, price) VALUES ('$mid', '$name', '$price')";

        if ($conn->query($insertQuery) === TRUE) {
            $success = "New membership added successfully!";
        } else {
            $error = "Error adding membership. Please try again.";
        }
    }
}
?>

<div class="container">
    <h1>Add New Membership</h1>

    <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
    <?php if ($success): ?><div class="success"><?php echo $success; ?></div><?php endif; ?>

    <form action="add_membership_partial.php" method="POST">
        <div class="form-group">
            <label for="name">Membership Name:</label>
            <input type="text" name="name" id="name" required>
        </div>
        <div class="form-group">
            <label for="price">Membership Price:</label>
            <input type="text" name="price" id="price" required>
        </div>
        <button type="submit">Add Membership</button>
    </form>
</div>
