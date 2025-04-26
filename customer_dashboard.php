<?php
session_start();
include("dbconfig.php");

// Check if user is logged in
if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'customer') {
    header("Location: login.php");
    exit;
}

// ✅ Define email from session
$email = $_SESSION['email'];

// Get user details
$query = "SELECT first_name, last_name, dob, membership, payment_option FROM customers WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$firstName = $user['first_name'];
$lastName = $user['last_name'];
$dob = $user['dob'];
$membership = $user['membership'];
$paymentOption = $user['payment_option'];

// Fetch membership packages
$packageQuery = "SELECT mid, name FROM membership_package";
$packages = $conn->query($packageQuery);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dob = $_POST['dob'];
    $membership = $_POST['membership'];
    $paymentOption = $_POST['paymentOption'];

    $updateQuery = "UPDATE customers SET dob=?, membership=?, payment_option=? WHERE email=?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssss", $dob, $membership, $paymentOption, $email);
    
    if ($stmt->execute()) {
        echo "<div class='success'>✅ Profile updated successfully.</div>";
    } else {
        echo "<div class='error'>❌ Failed to update profile.</div>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Dashboard</title>
  <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="admin_dashboard_style.css">
  <link rel="stylesheet" href="datatables_admin_style.css">
</head>
<body>
  <div class="admin-wrapper">
    <aside class="sidebar">
      <div class="logo">FitZone</div>
      <ul class="nav">
        <li><a href="#" onclick="loadPage('view_profile.php')"><i class='bx bx-user'></i> <span>View Profile</span></a></li>
        <li><a href="#" onclick="loadPage('change_name.php')"><i class='bx bx-edit'></i> <span>Change Name</span></a></li>
        <li><a href="#" onclick="loadPage('change_contact.php')"><i class='bx bx-phone'></i> <span>Change Contact</span></a></li>
        <li><a href="#" onclick="loadPage('change_password.php')"><i class='bx bx-lock'></i> <span>Change Password</span></a></li>
        <li><a href="#" onclick="loadPage('book_class.php')"><i class='bx bx-calendar'></i> <span>Book Class</span></a></li>
        <li><a href="#" onclick="loadPage('View_my_booking.php')"><i class='bx bx-show'></i> <span>My Bookings</span></a></li>
        <li><a href="#" onclick="loadPage('view_meal_plan.php')"><i class='bx bx-food-menu'></i> <span>Meal Plan</span></a></li>
        <li><a href="#" onclick="loadPage('bmi_calculator.php')"><i class='bx bx-body'></i> <span>BMI Calculator</span></a></li>
        <li><a href="#" onclick="loadPage('payment_history.php')"><i class='bx bx-credit-card'></i> <span>Payment History</span></a></li>
        <li><a href="#" onclick="loadPage('submit_feedback.php')"><i class='bx bx-message'></i> <span>Submit Feedback</span></a></li>
        <li><a href="#" onclick="loadPage('view_feedback.php')"><i class='bx bx-list-ul'></i> <span>View Feedback</span></a></li>
        <li><a href="logout.php" class="logout"><i class='bx bx-log-out'></i> <span>Logout</span></a></li>
      </ul>
    </aside>

    <div class="overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <button class="toggle-sidebar" onclick="toggleSidebar()">
      <i class='bx bx-menu'></i>
    </button> 

    <main class="dashboard">
      <header class="dashboard-header">
        <h1>Welcome, <?php echo htmlspecialchars($firstName); ?>!</h1>
        <div class="toggle-container">
          <label class="switch">
            <input type="checkbox" id="darkModeToggle">
            <span class="slider round"></span>
          </label>
        </div>
      </header>

      <div id="main-content">
        <h2>Complete Your Membership Details</h2>
        <form method="POST">
          <div class="form-group">
            <label for="dob">Date of Birth</label>
            <input type="date" id="dob" name="dob" value="<?= htmlspecialchars($dob) ?>" required>
          </div>

          <div class="form-group">
            <label for="membership">Membership Package</label>
            <select name="membership" id="membership" required>
              <option value="" disabled>Select a package</option>
              <?php while($pkg = $packages->fetch_assoc()): ?>
                <option value="<?= $pkg['mid'] ?>" <?= ($membership === $pkg['mid'] ? 'selected' : '') ?>>
                  <?= htmlspecialchars($pkg['name']) ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="paymentOption">Payment Option</label>
            <select name="paymentOption" id="paymentOption" required>
              <option value="credit" <?= $paymentOption === 'credit' ? 'selected' : '' ?>>Credit Card</option>
              <option value="paypal" <?= $paymentOption === 'paypal' ? 'selected' : '' ?>>PayPal</option>
              <option value="bank" <?= $paymentOption === 'bank' ? 'selected' : '' ?>>Bank Transfer</option>
            </select>
          </div>

          <button type="submit" class="btn">Save Details</button>
        </form>
      </div>
    </main>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script>
    function toggleSidebar() {
      const sidebar = document.querySelector('.sidebar');
      const dashboard = document.querySelector('.dashboard');
      const overlay = document.getElementById('sidebarOverlay');
      dashboard.classList.toggle('expanded');
      // Detect screen size
      const isMobile = window.innerWidth <= 768;

      if (isMobile) {
        sidebar.classList.toggle('show');
        overlay.style.display = sidebar.classList.contains('show') ? 'block' : 'none';
      } else {
        sidebar.classList.toggle('collapsed');

        if (sidebar.classList.contains('collapsed')) {
          toggleBtn.style.left = "20px";
        } else {
          toggleBtn.style.left = "270px";
        }
      }
    
    }

    function loadPage(url) {
      const content = document.getElementById('main-content');
      content.innerHTML = '<p>Loading...</p>';

      fetch(url)
        .then(response => response.text())
        .then(html => {
          content.innerHTML = html;
          if (window.jQuery && $.fn.DataTable) {
            $('.datatable').DataTable();
          }
        })
        .catch(err => {
          content.innerHTML = '<p>Error loading content.</p>';
          console.error(err);
        });
    }

    document.getElementById('darkModeToggle').addEventListener('change', function () {
      document.body.classList.toggle('dark-mode', this.checked);
    });
  </script>
</body>
</html>

