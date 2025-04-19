<?php
session_start();
include("dbconfig.php");

header('Content-Type: application/json');

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

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO management (first_name, last_name, email, contact_number, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $first_name, $last_name, $email, $contact_number, $hashed_password);

    if ($stmt->execute()) {
        $stmtUserType = $conn->prepare("INSERT INTO usertypes (email, user_type) VALUES (?, 'management')");
        $stmtUserType->bind_param("s", $email);

        if ($stmtUserType->execute()) {
            echo json_encode([
                'status' => 'success',
                'message' => "✅ Staff member <strong>{$first_name} {$last_name}</strong> added successfully."
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => '❌ Failed to add to usertypes: ' . $stmtUserType->error
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => '❌ Failed to add staff: ' . $stmt->error
        ]);
    }
    exit;
}
?>




<!-- Staff Form UI -->
<div class="container">
    <h1>Add New Staff</h1>
    <div id="messageBox"></div>

    <form id="addStaffForm" method="POST">
        <div class="form-group">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>
        </div>
        <div class="form-group">
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="contact_number">Contact Number:</label>
            <input type="text" id="contact_number" name="contact_number" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Add Staff</button>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("addStaffForm");
    const messageBox = document.getElementById("messageBox");

    form.addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(form);

        fetch("add_staff_partial.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                form.reset();

                // Display toast message from parent (admin_dashboard)
                if (window.parent && typeof window.parent.showNotification === "function") {
                    window.parent.showNotification(data.message);
                } else if (typeof showNotification === "function") {
                    showNotification(data.message);
                }

                // Also show it inside messageBox (optional)
                messageBox.innerHTML = `<div class='success'>${data.message}</div>`;
            } else {
                messageBox.innerHTML = `<div class='error'>${data.message}</div>`;
            }
        })
        .catch(error => {
            console.error("Error:", error);
            messageBox.innerHTML = "<div class='error'>❌ Unexpected error occurred.</div>";
        });
    });
});
</script>



