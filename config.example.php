<?php
/**
 * MMCS ROOT CONFIGURATION — TEMPLATE
 * ==================================
 * Copy this file to config.php and fill in your own values:
 *
 *     cp config.example.php config.php
 *
 * config.php is git-ignored, so your credentials are never committed.
 * This template holds no real secrets — every value below is a placeholder
 * or a safe default.
 *
 * TO UPDATE:
 * - Database credentials  -> section 1
 * - SMTP / email settings -> section 3
 * - File upload limits    -> section 4
 * - Toggle error display  -> section 6 (DEV_MODE)
 *
 * DESIGN & DEVELOPMENT: Vishwas Suthar
 */

// ---------------------------------------------------
//  SECURITY: Block direct browser access to this file
// ---------------------------------------------------
if (count(get_included_files()) === 1) {
    http_response_code(403);
    exit('Direct access not permitted');
}

// ---------------------------------------------------
//  SESSION: Start securely (httponly, Secure on HTTPS)
// ---------------------------------------------------
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        ini_set('session.cookie_secure', 1);
    }
    session_start();
}

// ---------------------------------------------------
//  1. DATABASE — change these to match your environment
// ---------------------------------------------------
define('DB_HOST', 'localhost');
define('DB_NAME', 'mmcs_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// ---------------------------------------------------
//  2. BASE URL — auto-detected, override only if wrong
// ---------------------------------------------------
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? 80) == 443) ? "https://" : "http://";
$domain = $_SERVER['HTTP_HOST'] ?? 'localhost';
$script_name = $_SERVER['SCRIPT_NAME'] ?? '';
$dir = str_replace('\\', '/', dirname($script_name));
$base_path = ($dir === '/') ? '/' : $dir . '/';
$base_path = preg_replace('/\/(public|admin|includes)\/$/', '/', $base_path);
$base_url = $protocol . $domain . $base_path;
define('SITE_URL', rtrim($base_url, '/') . '/');

// ---------------------------------------------------
//  3. SMTP MAIL — used for contact/inquiry form emails
// ---------------------------------------------------
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USER', 'your-email@gmail.com');       // Update with real SMTP user
define('MAIL_PASS', 'your-gmail-app-password');    // Update with real app password
define('MAIL_FROM', 'mmconsultancysolutions@gmail.com');
define('MAIL_FROM_NAME', 'MMCS Web Desk');

// ---------------------------------------------------
//  4. FILE UPLOADS — size limit & allowed formats
// ---------------------------------------------------
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5 MB max
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp', 'image/jfif']);
define('ALLOWED_DOC_TYPES', ['application/pdf']);

// ---------------------------------------------------
//  5. SESSION TIMEOUT — idle time in seconds (30 min)
// ---------------------------------------------------
define('SESSION_TIMEOUT', 1800);

// ---------------------------------------------------
//  6. DEV MODE — set false on production server
// ---------------------------------------------------
define('DEV_MODE', false);

if (DEV_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// ---------------------------------------------------
//  7. DEPLOYMENT CHECK — warn if credentials are defaults
// ---------------------------------------------------
if (DB_USER === 'root' && DB_PASS === '' && php_sapi_name() === 'cli') {
    fwrite(STDERR, "MMCF WARNING: Database credentials are still defaults (root/empty). Update config.php before production.\n");
} elseif (DB_USER === 'root' && DB_PASS === '' && isset($_SERVER['HTTP_HOST'])) {
    error_log("MMCF WARNING: Database credentials are still defaults (root/empty). Update config.php before production.");
}
