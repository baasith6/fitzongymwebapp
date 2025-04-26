<?php
// $adminPassword = "#icbt1101#";
// $hashedPassword = sha1($adminPassword);

$password = "#Seerahh6#";// get plain text password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

echo "Admin Hashed Password: " . $hashedPassword;
?>
