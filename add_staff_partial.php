<?php
session_start();
include("dbconfig.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

// Admin Access Control
if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'admin') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Access denied. Admin login required.'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $contact_number = trim($_POST['contact_number']);
    $password = $_POST['password'];

    if (empty($first_name) || empty($last_name) || empty($email) || empty($contact_number) || empty($password)) {
        echo json_encode([
            'status' => 'error',
            'message' => '❌ All fields are required.'
        ]);
        exit;
    }

    $check = $conn->prepare("SELECT email FROM management WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo json_encode([
            'status' => 'error',
            'message' => '❌ This email is already registered.'
        ]);
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("INSERT INTO management (first_name, last_name, email, contact_number, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $contact_number, $hashed_password);
        $stmt->execute();

        $stmtUserType = $conn->prepare("INSERT INTO usertypes (email, user_type) VALUES (?, 'management')");
        $stmtUserType->bind_param("s", $email);
        $stmtUserType->execute();

        $conn->commit(); 

        echo json_encode([
            'status' => 'success',
            'message' => "✅ Staff member <strong>" . htmlspecialchars($first_name) . " " . htmlspecialchars($last_name) . "</strong> added successfully."
        ]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode([
            'status' => 'error',
            'message' => '❌ Failed to add staff: ' . $e->getMessage()
        ]);
    }
    exit;
}
?>
