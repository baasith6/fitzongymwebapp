<?php
session_start();
include("dbconfig.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Step 1: Identify user type
    $userTypeQuery = "SELECT user_type FROM usertypes WHERE email = ?";
    $stmt = $conn->prepare($userTypeQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $userTypeResult = $stmt->get_result();

    if ($userTypeResult->num_rows === 1) {
        $userTypeRow = $userTypeResult->fetch_assoc();
        $userType = $userTypeRow['user_type'];

        // Step 2: Determine table based on user type
        if ($userType === 'admin') {
            $query = "SELECT name, email, password FROM admin WHERE email = ?";
        } elseif ($userType === 'management') {
            $query = "SELECT first_name AS name, email, password FROM management WHERE email = ?";
        } elseif ($userType === 'customer') {
            $query = "SELECT first_name AS name, email, password FROM customers WHERE email = ?";
        } else {
            $error = "Invalid user type.";
        }

        // Step 3: Authenticate user
        if (!isset($error)) {
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();

                // Admin uses SHA1, others use password_hash()
                $isValidPassword = false;
                if ($userType === 'admin') {
                    $isValidPassword = sha1($password) === $user['password'];
                } else {
                    $isValidPassword = password_verify($password, $user['password']);
                }

                if ($isValidPassword) {
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['user_type'] = $userType;

                    // Redirect to respective dashboard
                    if ($userType === 'admin') {
                        header("Location: admin_dashboard.php");
                    } elseif ($userType === 'management') {
                        header("Location: management_dashboard.php");
                    } else {
                        header("Location: customer_dashboard.php");
                    }
                    exit;
                } else {
                    $error = "Invalid email or password.";
                }
            } else {
                $error = "No account found for this email.";
            }
        }
    } else {
        $error = "No account found for this email.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>
  <link rel="stylesheet" href="loginstyle.css" />
  <script defer src="login.js"></script>
</head>
<body>
  <div class="auth-wrapper">
    <div class="auth-box">
      
      <!-- Left Side -->
      <div class="auth-left">
        <h2>Hello, Welcome!</h2>
        <p>Don't have an account?</p>
        <a href="register.php" class="btn secondary-btn">Register</a>
        <!-- Back Button -->
        <a href="index.php" class="btn back-btn" style="margin-top: 20px;">← Back to Home</a>
      </div>

      <!-- Right Side -->
      <div class="auth-right">
        <h2>Login</h2>
        <?php if (!empty($error)): ?>
          <div style="color:red; font-weight:bold; margin-bottom:10px;">
            <?php echo htmlspecialchars($error); ?>
          </div>
        <?php endif; ?>
        <form action="login.php" method="POST" id="loginForm">
          <div class="input-group">
            <label for="email">Email</label>
            <input type="text" id="email" name="email" required />
          </div>
          <div class="input-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required />
          </div>
          <div class="input-group checkbox-group">
            <input type="checkbox" id="showPassword" />
            <label for="showPassword">Show Password</label>
          </div>
          <button type="submit" class="btn primary-btn">Login</button>
          <p class="signup-link">Don't have an account? <a href="register.php">Register</a></p>
        </form>
      </div>

    </div>
  </div>
</body>
</html>