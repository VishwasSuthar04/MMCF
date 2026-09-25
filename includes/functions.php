<?php
/**
 * MMCS GLOBAL UTILITY FUNCTIONS
 * =============================
 * Shared helpers used across public and admin pages.
 * Included automatically by header.php, footer.php, and admin_header.php.
 * 
 * DESIGN & DEVELOPMENT: Vishwas Suthar
 */

require_once __DIR__ . '/db.php';

// ---------------------------------------------------------------
//  escape()
//  Safely encode a string for HTML output (prevents XSS).
//  USE THIS for every echo of user-entered or DB-sourced text.
// ---------------------------------------------------------------
function escape($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

// ---------------------------------------------------------------
//  redirect()
//  Send an HTTP redirect header and stop script execution.
// ---------------------------------------------------------------
function redirect($url) {
    header("Location: " . $url);
    exit;
}

// ---------------------------------------------------------------
//  flash()
//  Store or retrieve a one-time flash message in the session.
//  Usage:
//    flash('key', 'message', 'success');   // store
//    $msg = flash('key');                  // retrieve & clear
// ---------------------------------------------------------------
function flash($key = '', $message = '', $type = 'success') {
    if (session_status() === PHP_SESSION_NONE) {
        return;
    }
    
    if (!empty($key)) {
        if (!empty($message)) {
            $_SESSION['flash'][$key] = [
                'message' => $message,
                'type' => $type
            ];
        } else {
            $msg = $_SESSION['flash'][$key] ?? null;
            unset($_SESSION['flash'][$key]);
            return $msg;
        }
    }
}

// ---------------------------------------------------------------
//  render_flash()
//  Output a Bootstrap alert for the given flash key.
// ---------------------------------------------------------------
function render_flash($key = 'alert') {
    $flash = flash($key);
    if ($flash) {
        echo '<div class="alert alert-' . escape($flash['type']) . ' alert-dismissible fade show" role="alert">';
        echo escape($flash['message']);
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
    }
}

// ---------------------------------------------------------------
//  get_setting()
//  Retrieve a single value from the `settings` database table.
//  Results are cached in a static variable for the request.
//  To add a new setting:
//    1. Insert a row into the `settings` table (key / value)
//    2. Call get_setting('your_key') anywhere in the app
// ---------------------------------------------------------------
function get_setting($key) {
    global $pdo;
    static $settings = [];
    
    if (empty($settings)) {
        try {
            $stmt = $pdo->query("SELECT `key`, `value` FROM `settings`");
            while ($row = $stmt->fetch()) {
                $settings[$row['key']] = $row['value'];
            }
        } catch (PDOException $e) {
            return '';
        }
    }
    
    return $settings[$key] ?? '';
}

// ---------------------------------------------------------------
//  is_logged_in()
//  Check whether the current session has an authenticated admin.
// ---------------------------------------------------------------
function is_logged_in() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

// ---------------------------------------------------------------
//  generate_csrf_token()
//  Get or create a CSRF token for the current session.
// ---------------------------------------------------------------
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// ---------------------------------------------------------------
//  csrf_field()
//  Output a hidden <input> with the CSRF token.
// ---------------------------------------------------------------
function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . generate_csrf_token() . '">';
}

// ---------------------------------------------------------------
//  validate_csrf_token()
//  Check the submitted token against the session token.
// ---------------------------------------------------------------
function validate_csrf_token($token) {
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

// ---------------------------------------------------------------
//  require_csrf_token()
//  Validate CSRF token from POST and abort with flash on failure.
// ---------------------------------------------------------------
function require_csrf_token() {
    $token = $_POST['csrf_token'] ?? '';
    if (!validate_csrf_token($token)) {
        flash('alert', 'Invalid or expired form token. Please try again.', 'danger');
        redirect($_SERVER['PHP_SELF'] ?? 'index.php');
    }
}

// ---------------------------------------------------------------
//  validate_password_strength()
//  Single source of truth for admin password policy. Used by both
//  admin/settings.php (Security Gate) and scripts/set_admin.php so a
//  password accepted by one path cannot be rejected by the other.
//
//  Returns an error string on failure, or NULL when the password passes.
//
//  Policy:
//    - at least 12 characters
//    - at least 3 of the 4 character classes (lower, upper, digit, symbol)
//    - must not contain the username or obvious site words
// ---------------------------------------------------------------
function validate_password_strength($password, $username = '') {
    $errors = [];

    if (strlen($password) < 12) {
        $errors[] = 'Password must be at least 12 characters long.';
    }

    $classes = 0;
    foreach (['/[a-z]/', '/[A-Z]/', '/[0-9]/', '/[^a-zA-Z0-9]/'] as $re) {
        if (preg_match($re, $password)) {
            $classes++;
        }
    }
    if ($classes < 3) {
        $errors[] = 'Password must use at least 3 of: lowercase, uppercase, number, symbol.';
    }

    $haystack = strtolower($password);
    if ($username !== '' && $username !== null && str_contains($haystack, strtolower(explode('@', $username)[0]))) {
        $errors[] = 'Password must not contain your username.';
    }
    foreach (['mmcs', 'admin', 'password', 'tharparkar', 'consultancy', 'welcome'] as $word) {
        if (str_contains($haystack, $word)) {
            $errors[] = 'Password must not contain the word "' . $word . '".';
            break;
        }
    }

    return $errors ? implode(' ', $errors) : null;
}

// ---------------------------------------------------------------
//  upload_file()
//  Securely upload an image or PDF to the /uploads/ folder.
//
//  Parameters:
//    $file        — $_FILES['field_name'] array
//    $subfolder   — target sub-directory (e.g. 'team', 'blog')
//    $typeAllowed — 'image' | 'pdf'
//
//  Returns:
//    Relative path string on success, FALSE on failure.
// ---------------------------------------------------------------
function upload_file($file, $subfolder, $typeAllowed = 'image') {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    // Validate file size against MAX_FILE_SIZE (from config.php)
    if ($file['size'] > MAX_FILE_SIZE) {
        flash('alert', 'File size exceeds the 5MB limit.', 'danger');
        return false;
    }

    // Validate MIME type
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    
    if ($typeAllowed === 'image') {
        if (!in_array($mimeType, ALLOWED_IMAGE_TYPES)) {
            flash('alert', 'Invalid image format. Allowed: JPEG, PNG, WebP.', 'danger');
            return false;
        }
    } elseif ($typeAllowed === 'pdf') {
        if (!in_array($mimeType, ALLOWED_DOC_TYPES)) {
            flash('alert', 'Invalid document format. Only PDF allowed.', 'danger');
            return false;
        }
    } else {
        return false;
    }

    // Validate file extension
    $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'jfif', 'pdf'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExts)) {
        flash('alert', 'File extension not allowed.', 'danger');
        return false;
    }

    // Ensure target directory exists
    $targetDir = dirname(__DIR__) . '/uploads/' . rtrim($subfolder, '/') . '/';
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // Generate a unique, sanitised filename
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $cleanName = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($file['name'], PATHINFO_FILENAME));
    $newFileName = $cleanName . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $targetFilePath = $targetDir . $newFileName;

    if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
        return 'uploads/' . $subfolder . '/' . $newFileName;
    } else {
        flash('alert', 'Failed to move uploaded file.', 'danger');
        return false;
    }
}
