<?php
/**
 * MMCS SETUP CONFIGURATION CHECKER
 * ==================================
 * Run this script ONCE after deployment to verify all critical settings
 * are configured correctly for production.
 * 
 * Usage: php scripts/setup_check.php
 *        or place in browser (remove after checking)
 */

$checks = [];
$all_pass = true;

// Suppress CLI warnings (SERVER_PORT undefined in CLI mode)
if (php_sapi_name() === 'cli') {
    $_SERVER['SERVER_PORT'] = $_SERVER['SERVER_PORT'] ?? 80;
    $_SERVER['SCRIPT_NAME'] = $_SERVER['SCRIPT_NAME'] ?? '/';
    $_SERVER['HTTP_HOST'] = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $_SERVER['HTTPS'] = $_SERVER['HTTPS'] ?? 'off';
}

require __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';

// 1. DEV_MODE
$checks[] = [
    'item' => 'DEV_MODE',
    'status' => !defined('DEV_MODE') || DEV_MODE === false ? 'PASS' : 'FAIL',
    'detail' => defined('DEV_MODE') && DEV_MODE === false
        ? 'DEV_MODE is false (production)'
        : 'DEV_MODE is true — set to false in config.php for production',
];

// 2. Admin accounts
// No default credential is committed to this repository, so this check verifies
// that at least one admin exists and that none of them still uses a known-weak
// password. To re-enable the weak-password warning, put the plaintext password
// you want flagged into scripts/.flagged_admin_password (git-ignored) and re-run.
$admin_count = (int) $pdo->query("SELECT COUNT(*) FROM `admins`")->fetchColumn();
$flagged = @file_get_contents(__DIR__ . '/.flagged_admin_password');
$flagged = $flagged === false ? '' : trim($flagged);
$weak_users = [];
if ($flagged !== '') {
    foreach ($pdo->query("SELECT `username`, `password_hash` FROM `admins`") as $row) {
        if (password_verify($flagged, $row['password_hash'])) {
            $weak_users[] = $row['username'];
        }
    }
}
$checks[] = [
    'item' => 'Admin Accounts',
    'status' => ($admin_count === 0 || $weak_users) ? 'FAIL' : 'PASS',
    'detail' => $admin_count === 0
        ? 'No admin accounts exist — see the ADMIN ACCOUNT block in schema.sql to create the first one'
        : ($weak_users
            ? 'Weak password still active for: ' . implode(', ', $weak_users) . ' — change via Settings → Security Gate'
            : $admin_count . ' admin account(s), none using the flagged password'),
];

// 3. SMTP Configuration
$has_smtp = defined('MAIL_USER') && MAIL_USER !== 'your-email@gmail.com';
$checks[] = [
    'item' => 'SMTP Configuration',
    'status' => $has_smtp ? 'PASS' : 'WARN',
    'detail' => $has_smtp
        ? 'SMTP email appears configured'
        : 'SMTP still has placeholder values — update config.php with real email credentials',
];

// 4. Database connection
try {
    $pdo->query("SELECT 1");
    $checks[] = [
        'item' => 'Database Connection',
        'status' => 'PASS',
        'detail' => 'Connected to ' . DB_NAME . ' on ' . DB_HOST,
    ];
} catch (PDOException $e) {
    $checks[] = [
        'item' => 'Database Connection',
        'status' => 'FAIL',
        'detail' => 'Cannot connect: ' . $e->getMessage(),
    ];
    $all_pass = false;
}

// 5. Uploads directory writable
$uploads_writable = is_writable(__DIR__ . '/../uploads/');
$checks[] = [
    'item' => 'Uploads Directory Writable',
    'status' => $uploads_writable ? 'PASS' : 'FAIL',
    'detail' => $uploads_writable
        ? 'uploads/ is writable'
        : 'uploads/ is NOT writable — check permissions',
];

// 6. Uploads .htaccess
$htaccess_exists = file_exists(__DIR__ . '/../uploads/.htaccess');
$checks[] = [
    'item' => 'Uploads .htaccess',
    'status' => $htaccess_exists ? 'PASS' : 'WARN',
    'detail' => $htaccess_exists
        ? 'uploads/.htaccess present'
        : 'uploads/.htaccess missing — creates risk of PHP execution in uploads',
];

// 7. HTTPS
$is_https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
$checks[] = [
    'item' => 'HTTPS',
    'status' => $is_https ? 'PASS' : 'WARN',
    'detail' => $is_https
        ? 'Site is served over HTTPS'
        : 'Site is NOT served over HTTPS — install SSL certificate',
];

// 8. PHP version
$checks[] = [
    'item' => 'PHP Version',
    'status' => version_compare(PHP_VERSION, '8.0', '>=') ? 'PASS' : 'WARN',
    'detail' => 'PHP ' . PHP_VERSION . ' (' . (version_compare(PHP_VERSION, '8.0', '>=') ? '8.0+ supported' : 'Consider upgrading to 8.0+') . ')',
];

// 9. Required PHP extensions
$required_exts = ['pdo', 'pdo_mysql', 'mbstring', 'fileinfo', 'openssl'];
$missing_exts = [];
foreach ($required_exts as $ext) {
    if (!extension_loaded($ext)) {
        $missing_exts[] = $ext;
    }
}
$checks[] = [
    'item' => 'PHP Extensions',
    'status' => empty($missing_exts) ? 'PASS' : 'FAIL',
    'detail' => empty($missing_exts)
        ? 'All required extensions loaded'
        : 'Missing: ' . implode(', ', $missing_exts),
];

// Output
$width = 80;
echo str_repeat('=', $width) . "\n";
echo "  MMCS SETUP CONFIGURATION CHECKER\n";
echo str_repeat('=', $width) . "\n\n";

$pass_count = 0;
$warn_count = 0;
$fail_count = 0;

foreach ($checks as $c) {
    $status_str = str_pad($c['status'], 7);
    $icon = $c['status'] === 'PASS' ? '[✓]' : ($c['status'] === 'WARN' ? '[!]' : '[✗]');
    echo "  $icon $status_str {$c['item']}\n";
    echo "         {$c['detail']}\n\n";

    if ($c['status'] === 'PASS') $pass_count++;
    elseif ($c['status'] === 'WARN') $warn_count++;
    else $fail_count++;
}

echo str_repeat('-', $width) . "\n";
echo "  Results: $pass_count passed, $warn_count warnings, $fail_count failed\n";
echo str_repeat('-', $width) . "\n\n";

if ($fail_count > 0) {
    echo "  ❌ Fix FAIL items before going live.\n";
} elseif ($warn_count > 0) {
    echo "  ⚠️  Address WARN items before production delivery.\n";
} else {
    echo "  ✅ All checks passed. Ready for delivery.\n";
}
echo "\n";
