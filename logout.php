<?php
session_start();

// Prevent caching immediately
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

// Unset all session variables
session_unset();

// If session uses cookies, remove the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    
    if (PHP_VERSION_ID >= 70300) {
        setcookie(
            session_name(),
            '',
            [
                'expires' => time() - 42000,
                'path' => $params['path'],
                'domain' => $params['domain'],
                'secure' => $params['secure'],
                'httponly' => $params['httponly'],
                'samesite' => 'Lax'
            ]
        );
    } else {
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
}

// Finally, destroy the session
session_destroy();

// Redirect to login
header("Location: login.php");
flush();
exit;
?>
