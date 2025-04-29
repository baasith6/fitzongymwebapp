<?php
session_start();
include("dbconfig.php");
header('Content-Type: application/json');

// Admin Access Only
if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'admin') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Access denied. Admin login required.'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $contact_number = trim($_POST['contact_number']);

    if (empty($name) || empty($email) || empty($contact_number)) {
        echo json_encode([
            'status' => 'error',
            'message' => '❌ All fields are required.'
        ]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'status' => 'error',
            'message' => '❌ Invalid email format.'
        ]);
        exit;
    }

    // Check if email already exists
    $checkQuery = $conn->prepare("SELECT tid FROM trainers WHERE email = ?");
    $checkQuery->bind_param("s", $email);
    $checkQuery->execute();
    $checkResult = $checkQuery->get_result();

    if ($checkResult->num_rows > 0) {
        echo json_encode([
            'status' => 'error',
            'message' => '❌ Trainer with this email already exists.'
        ]);
        exit;
    }

    // Generate new Trainer ID
    $idQuery = "SELECT MAX(CAST(SUBSTRING(tid, 2) AS UNSIGNED)) AS max_id FROM trainers";
    $idResult = $conn->query($idQuery);
    $row = $idResult->fetch_assoc();
    $next_id = $row['max_id'] + 1;
    $tid = 'T' . str_pad($next_id, 3, '0', STR_PAD_LEFT);

    // Insert trainer
    $insertQuery = $conn->prepare("INSERT INTO trainers (tid, name, email, contact_number) VALUES (?, ?, ?, ?)");
    $insertQuery->bind_param("ssss", $tid, $name, $email, $contact_number);

    if ($insertQuery->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => "✅ Trainer <strong>" . htmlspecialchars($name) . "</strong> added successfully!"
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => '❌ Failed to add trainer: ' . $conn->error
        ]);
    }
    exit;
}
?>
