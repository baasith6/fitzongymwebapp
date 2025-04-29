<?php
session_start();
include("dbconfig.php");
header('Content-Type: application/json');

if (!isset($_SESSION['email']) || ($_SESSION['user_type'] !== 'management' && $_SESSION['user_type'] !== 'admin')) {
    echo json_encode(['status' => 'error', 'message' => 'Access Denied']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_email = $_POST['old_email'];
    $new_email = $_POST['new_email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $contact_number = $_POST['contact_number'];

    $update = $conn->prepare("UPDATE customers SET email=?, first_name=?, last_name=?, contact_number=? WHERE email=?");
    $update->bind_param("sssss", $new_email, $first_name, $last_name, $contact_number, $old_email);

    if ($update->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Customer profile updated successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update profile']);
    }
}
?>
