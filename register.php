<?php
// Database connection
include("dbconfig.php");

// Retrieve Membership Packages
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['fetchMemberships'])) {
    $sql = "SELECT mid, name, price FROM membership_package";
    $result = $conn->query($sql);
    $memberships = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $memberships[] = $row;
        }
    }
    echo json_encode($memberships);
    exit;
}

// Handle Registration Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $conn->real_escape_string($_POST['firstName']);
    $lastName = $conn->real_escape_string($_POST['lastName']);
    $email = $conn->real_escape_string($_POST['email']);
    $contactNumber = $conn->real_escape_string($_POST['contactNumber']);
    $dob = $conn->real_escape_string($_POST['dob']);
    $membership = $conn->real_escape_string($_POST['membership']);
    $paymentOption = $conn->real_escape_string($_POST['paymentOption']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encrypt password

    // Check if email already exists
    $checkEmailSql = "SELECT email FROM customers WHERE email = '$email'";
    $emailResult = $conn->query($checkEmailSql);

    if ($emailResult->num_rows > 0) {
        // Email exists
        echo "Error: Email already exists.";
    } else {
        // Insert into `customers`
        $sql = "INSERT INTO customers (first_name, last_name, email, contact_number, dob, membership, payment_option, password) 
                VALUES ('$firstName', '$lastName', '$email', '$contactNumber', '$dob', '$membership', '$paymentOption', '$password')";
        
        if ($conn->query($sql) === TRUE) {
            // Insert into `user_type`
            $userTypeSql = "INSERT INTO usertypes (email, user_type) VALUES ('$email', 'customer')";
            if ($conn->query($userTypeSql) === TRUE) {
                echo "Registration successful!";
                header("Location: login.php");
            } else {
                echo "Error in user_type insertion: " . $conn->error;
            }
        } else {
            echo "Error in customer insertion: " . $conn->error;
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

      <!-- Left Panel -->
      <div class="auth-left">
        <h2>Already a Member?</h2>
        <p>Log in to your account</p>
        <a href="login.php" class="btn">Login</a>
        <!-- Back Button -->
        <a href="index.php" class="btn back-btn" style="margin-top: 20px;">← Back to Home</a>
      </div>

      <!-- Right Panel -->
      <div class="auth-right">
        <h2>Register</h2>
        <form id="registerForm" method="POST">

          <div class="input-group">
            <label for="firstName">First Name</label>
            <input type="text" id="firstName" name="firstName" required />
          </div>

          <div class="input-group">
            <label for="lastName">Last Name</label>
            <input type="text" id="lastName" name="lastName" required />
          </div>

          <div class="input-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required />
          </div>

          <div class="input-group">
            <label for="contactNumber">Contact Number</label>
            <input type="tel" id="contactNumber" name="contactNumber" required />
          </div>

          <div class="input-group">
            <label for="dob">Date of Birth</label>
            <input type="date" id="dob" name="dob" required />
            <span id="age"></span>
          </div>

          <div class="input-group">
            <label for="membership">Membership Package</label>
            <select id="membership" name="membership" required>
              <option value="" disabled selected>Select a package</option>
            </select>
            <span id="membershipPrice">Price: </span>
          </div>

          <div class="input-group">
            <label for="paymentOption">Payment Option</label>
            <select id="paymentOption" name="paymentOption" required>
              <option value="credit">Credit Card</option>
              <option value="paypal">PayPal</option>
              <option value="bank">Bank Transfer</option>
            </select>
          </div>

          <div class="input-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required />
            <small>Password must be strong and secure.</small>
            <span id="passwordError" style="color: red;"></span>
          </div>

          <div class="input-group">
            <label for="confirmPassword">Confirm Password</label>
            <input type="password" id="confirmPassword" name="confirmPassword" required />
            <span id="passwordMatchError" style="color: red;"></span>
          </div>

          <div class="input-group checkbox-group">
            <input type="checkbox" id="showPassword" />
            <label for="showPassword">Show Password</label>
          </div>

          <button type="submit" class="btn primary-btn">Register</button>

          <p class="signup-link">Already have an account? <a href="login.php">Log in</a></p>
        </form>
      </div>

    </div>
  </div>
</body>
</html>

