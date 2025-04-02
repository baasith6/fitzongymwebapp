<?php
include("dbconfig.php");
session_start();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $conn->real_escape_string($_POST['firstName']);
    $lastName = $conn->real_escape_string($_POST['lastName']);
    $email = $conn->real_escape_string($_POST['email']);
    $contactNumber = $conn->real_escape_string($_POST['contactNumber']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Password match check
    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match.');</script>";
    } else {
        // Check if email exists
        $checkQuery = "SELECT email FROM customers WHERE email = '$email'";
        $result = $conn->query($checkQuery);

        if ($result->num_rows > 0) {
            echo "<script>alert('Email already exists.');</script>";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $insertCustomer = "INSERT INTO customers (first_name, last_name, email, contact_number, password)
                               VALUES ('$firstName', '$lastName', '$email', '$contactNumber', '$hashedPassword')";

            if ($conn->query($insertCustomer)) {
                $conn->query("INSERT INTO usertypes (email, user_type) VALUES ('$email', 'customer')");
                echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('Error during registration.');</script>";
            }
        }
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register</title>
  <link rel="stylesheet" href="registerstyle.css" />
  <script defer src="register.js"></script>
</head>
<body>

<div class="auth-wrapper">
  <div class="auth-box">
    <div class="auth-left">
      <h2>Already a Member?</h2>
      <a href="login.php" class="btn">Login</a>
      <a href="index.php" class="btn back-btn">← Back to Home</a>
    </div>

    <div class="auth-right">
      <h2>Create Your Account</h2>
      <form method="POST" action="">

        <div class="input-group">
          <label>First Name</label>
          <input type="text" name="firstName" required />
        </div>

        <div class="input-group">
          <label>Last Name</label>
          <input type="text" name="lastName" required />
        </div>

        <div class="input-group">
          <label>Email</label>
          <input type="email" name="email" required />
        </div>

        <div class="input-group">
          <label>Contact Number</label>
          <input type="tel" name="contactNumber" required />
        </div>

        <div class="input-group">
          <label>Password</label>
          <input type="password" id="password" name="password" required />
        </div>

        <div class="input-group">
          <label>Confirm Password</label>
          <input type="password" id="confirmPassword" name="confirmPassword" required />
          <span id="passwordError" style="color:red;"></span>
        </div>

        <button type="submit" class="btn primary-btn">Register</button>

        <p class="signup-link">Already have an account? <a href="login.php">Log in</a></p>
      </form>
    </div>
  </div>
</div>



</body>
</html>


