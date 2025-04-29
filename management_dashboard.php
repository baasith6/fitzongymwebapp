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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        <li><a href="#" onclick="loadPage('manage_customers_partial.php')"><i class='bx bx-user'></i> <span>Manage Customers</span></a></li>
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

          setTimeout(() => {
            // Auto-attach form submissions if specific page loaded
            if (url === 'add_class.php' && document.getElementById('addClassForm')) {
              attachAddClassFormSubmit();
            }
            if (url === 'add_payment.php' && document.getElementById('addPaymentForm')) {
              attachAddPaymentFormSubmit();
            }
            if (url === 'manage_customers_partial.php') {   // ✅ NEW LINE
              attachEditCustomerEvents();                  // ✅ NEW LINE
            }
            // Add more if needed
            if (window.jQuery && $.fn.DataTable) {
              $('.datatable').DataTable(); // If page contains datatables
            }
          }, 100); // slight timeout to ensure DOM is ready
        })
        .catch(err => {
          content.innerHTML = '<p>Error loading content.</p>';
          console.error(err);
        });
    }
    function attachEditCustomerEvents() {
      document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
          document.getElementById('first_name').value = this.dataset.firstname;
          document.getElementById('last_name').value = this.dataset.lastname;
          document.getElementById('new_email').value = this.dataset.email;
          document.getElementById('old_email').value = this.dataset.email;
          document.getElementById('contact_number').value = this.dataset.contact;
          document.getElementById('editModal').style.display = "block";
        });
      });

      // Close Modal
      document.querySelector(".close").onclick = function() {
        document.getElementById('editModal').style.display = "none";
      };

      window.onclick = function(event) {
        if (event.target == document.getElementById('editModal')) {
          document.getElementById('editModal').style.display = "none";
        }
      };

      // Handle Submit of Edit Form
      document.getElementById('editCustomerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('update_customer_partial.php', {
          method: 'POST',
          body: formData,
          credentials: 'include'
        })
        .then(res => res.json())
        .then(data => {
          if (data.status === 'success') {
            Swal.fire({
              icon: 'success',
              title: 'Updated!',
              text: data.message,
              timer: 2000,
              showConfirmButton: false
            }).then(() => {
              window.location.reload();
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: data.message
            });
          }
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.fire({
            icon: 'error',
            title: 'Unexpected Error!',
            text: 'Please try again later.'
          });
        });
      });
    }

    function attachAddClassFormSubmit() {
      const form = document.getElementById('addClassForm');
      if (form) {
        form.addEventListener('submit', function(e) {
          e.preventDefault();

          const formData = new FormData(form);

          fetch('add_class_partial.php', {
            method: 'POST',
            body: formData,
            credentials: 'include'
          })
          .then(res => res.json())
          .then(data => {
            if (data.status === "success") {
              Swal.fire({
                icon: 'success',
                title: 'Class Added!',
                html: data.message,
                timer: 2000,
                showConfirmButton: false
              }).then(() => {
                form.reset();
                window.location.reload();
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error!',
                html: data.message
              });
            }
          })
          .catch(error => {
            console.error('Error:', error);
            Swal.fire({
              icon: 'error',
              title: 'Unexpected Error!',
              text: '❌ Please try again later.'
            });
          });
        });
      }
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