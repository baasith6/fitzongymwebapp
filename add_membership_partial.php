<?php
session_start();
include("dbconfig.php");
header('Content-Type: application/json');

// ✅ Protect this route: Only logged-in admins can access
if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'admin') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Access denied. Admin login required.'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);

    if (empty($name) || empty($price)) {
        echo json_encode([
            'status' => 'error',
            'message' => '❌ Please provide all fields.'
        ]);
        exit;
    }

    // Create unique membership ID
    $idQuery = "SELECT MAX(CAST(SUBSTRING(mid, 2) AS UNSIGNED)) AS max_id FROM membership_package";
    $idResult = $conn->query($idQuery);
    $row = $idResult->fetch_assoc();
    $next_id = $row['max_id'] + 1;
    $mid = 'M' . str_pad($next_id, 3, '0', STR_PAD_LEFT);

    $insertQuery = $conn->prepare("INSERT INTO membership_package (mid, name, price) VALUES (?, ?, ?)");
    $insertQuery->bind_param("ssi", $mid, $name, $price);

    if ($insertQuery->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => "✅ Membership <strong>" . htmlspecialchars($name) . "</strong> added successfully!"
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => '❌ Failed to add membership. Please try again.'
        ]);
    }
    exit;
}
?>
