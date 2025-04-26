<?php
session_start();
include("dbconfig.php");

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];
$query = "SELECT first_name FROM management WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $staffRow = $result->fetch_assoc();
    $firstName = $staffRow['first_name'];
} else {
    echo "Staff member not found!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Dashboard</title>
  <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="admin_dashboard_style.css" />
  <style>
    #toast {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-color: #00B78E;
      color: white;
      padding: 15px 20px;
      border-radius: 6px;
      display: none;
      z-index: 9999;
      font-weight: 600;
    }
  </style>
</head>
<body>
  <div class="admin-wrapper">
    <aside class="sidebar">
      <div class="logo">FitZone</div>
      <ul class="nav">
        <li><a href="#" onclick="loadPage('manage_customers.php')"><i class='bx bx-user'></i> <span>Manage Customers</span></a></li>
        <li><a href="#" onclick="loadPage('view_classes.php')"><i class='bx bx-calendar-event'></i> <span>View Classes</span></a></li>
        <li><a href="#" onclick="loadPage('add_class.php')"><i class='bx bx-plus-circle'></i> <span>Add Class</span></a></li>
        <li><a href="#" onclick="loadPage('view_class_booking.php')"><i class='bx bx-list-check'></i> <span>Class Bookings</span></a></li>
        <li><a href="#" onclick="loadPage('view_trainers_partial.php')"><i class='bx bx-dumbbell'></i> <span>Trainers</span></a></li>
        <li><a href="#" onclick="loadPage('view_memberships_partial.php')"><i class='bx bx-id-card'></i> <span>Memberships</span></a></li>
        <li><a href="#" onclick="loadPage('view_payments_partial.php')"><i class='bx bx-credit-card'></i> <span>Payment Log</span></a></li>
        <li><a href="#" onclick="loadPage('add_payment.php')"><i class='bx bx-money'></i> <span>Add Payment</span></a></li>
        <li><a href="#" onclick="loadPage('view_feedbacks_partial.php')"><i class='bx bx-message-square-dots'></i> <span>View Feedback</span></a></li>
        <li><a href="#" onclick="loadPage('view_question.php')"><i class='bx bx-question-mark'></i> <span>View Questions</span></a></li>
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
        <p>Select an option from the sidebar to begin managing data.</p>
      </div>
    </main>
  </div>

  <div id="toast"></div>

  <script>
    function toggleSidebar() {
      const sidebar = document.querySelector('.sidebar');
      const dashboard = document.querySelector('.dashboard');
      const overlay = document.getElementById('sidebarOverlay');
      const toggleBtn = document.querySelector('.toggle-sidebar');
      dashboard.classList.toggle('expanded');

      const isMobile = window.innerWidth <= 768;
      if (isMobile) {
        sidebar.classList.toggle('show');
        overlay.style.display = sidebar.classList.contains('show') ? 'block' : 'none';
      } else {
        sidebar.classList.toggle('collapsed');
        toggleBtn.style.left = sidebar.classList.contains('collapsed') ? "20px" : "270px";
      }
    }

    document.getElementById('darkModeToggle').addEventListener('change', function() {
      document.body.classList.toggle('dark-mode', this.checked);
    });

    function loadPage(url) {
      const content = document.getElementById('main-content');
      content.innerHTML = '<p>Loading...</p>';

      fetch(url)
        .then(response => response.text())
        .then(html => {
          content.innerHTML = html;
        })
        .catch(err => {
          content.innerHTML = '<p>Error loading content.</p>';
          console.error(err);
        });
    }

    function showNotification(message) {
      const toast = document.getElementById('toast');
      toast.textContent = message;
      toast.style.display = 'block';
      setTimeout(() => {
        toast.style.display = 'none';
      }, 3000);
    }
  </script>
</body>
</html>