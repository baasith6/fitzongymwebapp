<?php
session_start();
include("dbconfig.php");
header('Content-Type: application/json');

if (!isset($_SESSION['email']) || ($_SESSION['user_type'] !== 'management' && $_SESSION['user_type'] !== 'admin')) {
    echo json_encode(['status' => 'error', 'message' => 'Access denied.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $className = trim($_POST['class_name']);
    $trainer = trim($_POST['trainer']);
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);

    if (empty($className) || empty($trainer) || empty($date) || empty($time)) {
        echo json_encode(['status' => 'error', 'message' => '❌ All fields are required.']);
        exit;
    }

    // Generate Class ID if you have logic (Example: C001, C002 etc.)

    $cidQuery = "SELECT MAX(CAST(SUBSTRING(cid, 2) AS UNSIGNED)) AS max_id FROM classes";
    $cidResult = $conn->query($cidQuery);
    $row = $cidResult->fetch_assoc();
    $nextId = $row['max_id'] + 1;
    $cid = 'C' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

    $insertQuery = $conn->prepare("INSERT INTO classes (cid, class_name, trainer, date, time) VALUES (?, ?, ?, ?, ?)");
    $insertQuery->bind_param("sssss", $cid, $className, $trainer, $date, $time);

    if ($insertQuery->execute()) {
        echo json_encode(['status' => 'success', 'message' => "✅ Class <strong>" . htmlspecialchars($className) . "</strong> added successfully!"]);
    } else {
        echo json_encode(['status' => 'error', 'message' => '❌ Failed to add class. Please try again.']);
    }
    exit;
}
?>
