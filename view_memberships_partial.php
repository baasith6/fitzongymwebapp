<?php
session_start();
include("dbconfig.php");

// Check if staff is logged in
if (!isset($_SESSION['email'])) {
    echo "<p class='error'>Access denied. Please log in.</p>";
    exit;
}

// Initialize variables
$success = '';
$error = '';

// Fetch membership plans
$membershipsQuery = "SELECT * FROM membership_package";
if (isset($_GET['mid'])) {
    $mid = $_GET['mid'];
    $membershipsQuery .= " WHERE mid LIKE ?";
}
$stmt = $conn->prepare($membershipsQuery);
if (isset($mid)) {
    $stmt->bind_param("s", $mid);
}
$stmt->execute();
$membershipsResult = $stmt->get_result();
?>

<div class="container">
    <h1>View Membership Plans</h1>

    <!-- Success and Error Messages -->
    <?php if ($success): ?>
        <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Search Form -->
    <form method="GET" action="#" onsubmit="event.preventDefault(); loadPage('view_memberships_partial.php?mid=' + encodeURIComponent(this.mid.value));">
        <input type="text" name="mid" placeholder="Search by Membership ID" value="<?php echo isset($mid) ? $mid : ''; ?>">
        <button type="submit">Search</button>
    </form>

    <!-- Membership Plans Table -->
    <table class="datatable">
        <thead>
            <tr>
                <th>Membership ID</th>
                <th>Name</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($membership = $membershipsResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $membership['mid']; ?></td>
                    <td><?php echo $membership['name']; ?></td>
                    <td><?php echo $membership['price']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
