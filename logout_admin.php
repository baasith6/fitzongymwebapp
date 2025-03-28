<?php
// Start the session
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Redirect to the respective login page
header("Location: admin_login.php");  // You can change this to admin_login.php if needed
exit;
?>
