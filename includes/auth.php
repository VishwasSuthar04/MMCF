<?php
/**
 * MMCS ADMIN AUTH GATE
 * ====================
 * Drop this file at the top of any admin page to protect it.
 * If the user is NOT logged in, they are redirected to the login page.
 * 
 * USAGE:
 *   require_once __DIR__ . '/auth.php';
 *   // ... rest of admin-only code ...
 */

require_once __DIR__ . '/functions.php';

// Check idle session timeout
if (is_logged_in()) {
    $last_activity = $_SESSION['last_activity'] ?? 0;
    if ($last_activity > 0 && (time() - $last_activity) > SESSION_TIMEOUT) {
        $_SESSION = [];
        session_destroy();
        session_start();
        flash('alert', 'Your session has expired due to inactivity. Please log in again.', 'warning');
        $login_path = SITE_URL . 'admin/login.php';
        header("Location: " . $login_path);
        exit;
    }
    $_SESSION['last_activity'] = time();
}

if (!is_logged_in()) {
    flash('alert', 'Please log in to access the administrator panel.', 'warning');
    $login_path = SITE_URL . 'admin/login.php';
    header("Location: " . $login_path);
    exit;
}
