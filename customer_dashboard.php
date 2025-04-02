<?php
session_start();
include("dbconfig.php");

// Check if user is logged in
if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'customer') {
    header("Location: login.php");
    exit;
}


// Get user details
$email = $_SESSION['email'];
$userQuery = "SELECT first_name, last_name FROM customers WHERE email = '$email'";
$result = $conn->query($userQuery);
if ($result->num_rows > 0) {
    $userRow = $result->fetch_assoc();
    $firstName = $userRow['first_name'];
    $lastName = $userRow['last_name'];
} else {
    echo "User not found!";
    exit;
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
        <p>Select an option from the menu.</p>
      </div>
    </main>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script>
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

