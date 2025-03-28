<?php
session_start();

// Check for proper admin session
if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];
include("dbconfig.php");

// Fetch admin name
$adminQuery = "SELECT name FROM admin WHERE email = ?";
$stmt = $conn->prepare($adminQuery);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $adminRow = $result->fetch_assoc();
    $adminName = $adminRow['name'];
} else {
    echo "Admin user not found!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>
  <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="admin_dashboard_style.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
</head>
<body>
  <div class="admin-wrapper">
    <aside class="sidebar">
      <div class="logo">FitZone</div>
      <ul class="nav">
        <li><a href="#" onclick="loadPage('admin_dashboard_cards.php')"><i class='bx bx-home'></i> <span>Dashboard</span></a></li>
        <li><a href="#" onclick="loadPage('view_customers_partial.php')"><i class='bx bx-user'></i> <span>Customers</span></a></li>
        <li><a href="#" onclick="loadPage('view_staff_partial.php')"><i class='bx bx-group'></i> <span>Staff</span></a></li>
        <li><a href="#"><i class='bx bx-dumbbell'></i> <span>Trainers</span></a></li>
        <li><a href="#"><i class='bx bx-id-card'></i> <span>Memberships</span></a></li>
        <li><a href="#"><i class='bx bx-credit-card'></i> <span>Payments</span></a></li>
        <li><a href="#"><i class='bx bx-message-dots'></i> <span>Feedback</span></a></li>
        <li><a href="logout_admin.php" class="logout"><i class='bx bx-log-out'></i> <span>Logout</span></a></li>
      </ul>
    </aside>

    <button class="toggle-sidebar" onclick="toggleSidebar()">
      <i class='bx bx-menu'></i>
    </button>

    <main class="dashboard">
      <header class="dashboard-header">
        <h1>Welcome, <?php echo htmlspecialchars($adminName); ?></h1>
        <div class="toggle-container">
            <label class="switch">
                <input type="checkbox" id="darkModeToggle">
                <span class="slider round"></span>
            </label>
        </div>
      </header>

      <div id="main-content">
        <?php include("admin_dashboard_cards.php"); ?>
      </div>
    </main>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script>
    function toggleSidebar() {
      const sidebar = document.querySelector('.sidebar');
      const toggleBtn = document.querySelector('.toggle-sidebar');
      const dashboard = document.querySelector('.dashboard');
      sidebar.classList.toggle('collapsed');
      if (sidebar.classList.contains('collapsed')) {
        toggleBtn.style.left = '90px';
        dashboard.style.marginLeft = '80px';
      } else {
        toggleBtn.style.left = '280px';
        dashboard.style.marginLeft = '270px';
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
          if (window.jQuery && $.fn.DataTable) {
            $('.datatable').DataTable();
          }
        })
        .catch(err => {
          content.innerHTML = '<p>Error loading content.</p>';
          console.error(err);
        });
    }
  </script>
</body>
</html>
