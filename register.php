<?php
include("dbconfig.php");
session_start();

$error = "";
$success = false; // ADD this line

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $contactNumber = trim($_POST['contactNumber']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Server-side validation
    if (!preg_match("/^[a-zA-Z ]*$/", $firstName)) {
        $error = "First name can only contain letters and spaces.";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $lastName)) {
        $error = "Last name can only contain letters and spaces.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (!preg_match("/^[0-9]{10,15}$/", $contactNumber)) {
        $error = "Contact number must be between 10 to 15 digits.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } elseif (!preg_match("/[A-Za-z]/", $password) || !preg_match("/[0-9]/", $password)) {
        $error = "Password must contain both letters and numbers.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        // Check if email or contact already exists
        $checkQuery = "SELECT email FROM customers WHERE email = ? OR contact_number = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("ss", $email, $contactNumber);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Email or Contact Number already registered.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Start transaction
            $conn->begin_transaction();

            try {
                $insertCustomer = "INSERT INTO customers (first_name, last_name, email, contact_number, password)
                                   VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insertCustomer);
                $stmt->bind_param("sssss", $firstName, $lastName, $email, $contactNumber, $hashedPassword);
                $stmt->execute();

                $insertUserType = "INSERT INTO usertypes (email, user_type) VALUES (?, 'customer')";
                $stmt2 = $conn->prepare($insertUserType);
                $stmt2->bind_param("s", $email);
                $stmt2->execute();

                $conn->commit();
                $success = true; // Set success flag ✅
            } catch (Exception $e) {
                $conn->rollback();
                $error = "Registration failed. Please try again.";
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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Include SweetAlert2 -->
</head>
<body>

<?php if (!empty($error)) { ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Registration Failed!',
    text: '<?php echo $error; ?>'
});
</script>
<?php } ?>

<?php if ($success) { ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Registration Successful!',
    text: 'Redirecting to login page...',
    timer: 2000,
    showConfirmButton: false
}).then(() => {
    window.location.href = 'login.php';
});
</script>
<?php } ?>

<!-- Your Form Starts Below -->
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
