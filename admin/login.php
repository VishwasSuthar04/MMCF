<?php
/**
 * MMCS ADMIN LOGIN PAGE
 * =====================
 * Authenticates the user against the `admins` table using password_verify().
 * On success, session variables are set and user is redirected to dashboard.
 * 
 * ADMIN ACCOUNTS: no default credential is shipped with this repository.
 * Create the first admin after importing the schema:
 *   php scripts/set_admin.php
 * That script sets a single admin account and stores only a bcrypt hash.
 * 
 * DESIGN & DEVELOPMENT: Vishwas Suthar
 */

require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';

// If already logged in, skip the login screen
if (is_logged_in()) {
    redirect(SITE_URL . 'admin/dashboard.php');
}

$error = '';

// Brute-force rate limiting: track failed attempts by IP
$ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
$rate_limit_file = __DIR__ . '/logs/rate_limit_' . md5($ip) . '.tmp';
$max_attempts = 5;
$lockout_minutes = 15;

if (file_exists($rate_limit_file)) {
    $data = json_decode(file_get_contents($rate_limit_file), true);
    $attempts = $data['attempts'] ?? 0;
    $first_fail = $data['first_fail'] ?? 0;
    
    if ($attempts >= $max_attempts && (time() - $first_fail) < ($lockout_minutes * 60)) {
        $wait = ($lockout_minutes * 60) - (time() - $first_fail);
        $error = 'Too many failed attempts. Please try again in ' . ceil($wait / 60) . ' minute(s).';
    } elseif ((time() - $first_fail) >= ($lockout_minutes * 60)) {
        // Reset after lockout period
        $attempts = 0;
        $first_fail = 0;
    }
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error)) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = 'Please fill in both fields.';
    } elseif (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid form token. Please refresh and try again.';
    } else {
        try {
            // Look up the admin by username
            $stmt = $pdo->prepare("SELECT * FROM `admins` WHERE `username` = ?");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['password_hash'])) {
                // Clear rate-limit file on success
                if (file_exists($rate_limit_file)) {
                    unlink($rate_limit_file);
                }

                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);
                
                // Credentials are correct — start the session
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_user_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['last_activity'] = time();
                
                // Update last login timestamp
                $update = $pdo->prepare("UPDATE `admins` SET `last_login` = NOW() WHERE `id` = ?");
                $update->execute([$admin['id']]);

                // Regenerate CSRF token after login
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                // Securely redirect to dashboard
                redirect(SITE_URL . 'admin/dashboard.php');
            } else {
                // Track failed attempt
                $attempts = ($attempts ?? 0) + 1;
                $first_fail = $first_fail ?? time();
                $log_dir = __DIR__ . '/logs/';
                if (!is_dir($log_dir)) {
                    mkdir($log_dir, 0755, true);
                }
                file_put_contents($rate_limit_file, json_encode(['attempts' => $attempts, 'first_fail' => $first_fail]));

                // Log failed attempt details
                $log_msg = sprintf("[%s] Failed login attempt for username: '%s' from IP: %s (attempt %d)\n", date('Y-m-d H:i:s'), $username, $ip, $attempts);
                file_put_contents($log_dir . 'failed_logins.log', $log_msg, FILE_APPEND);
                
                // Keep error generic for security
                $error = 'Invalid username or password.';
            }
        } catch (PDOException $e) {
            $error = 'A system error occurred. Please try again later.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | <?php echo escape(get_setting('company_name')); ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?php echo SITE_URL; ?>favicon.xml">
    <link rel="alternate icon" href="<?php echo SITE_URL; ?>favicon.ico">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --navy-dark: #0f172a;
            --navy-light: #1e293b;
            --orange-primary: #ea580c;
            --orange-hover: #c2410c;
            --slate-gray: #64748b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--navy-dark) 0%, var(--navy-light) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            width: 100%;
            max-width: 440px;
            padding: 40px 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
        }

        .brand-logo {
            font-family: 'Outfit', sans-serif;
            color: #ffffff;
            font-weight: 700;
            font-size: 24px;
            text-align: center;
            margin-bottom: 30px;
            letter-spacing: -0.5px;
        }

        .brand-logo i {
            color: var(--orange-primary);
            margin-right: 8px;
        }

        .form-label {
            color: #cbd5e1;
            font-size: 14px;
            font-weight: 500;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: #ffffff;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--orange-primary);
            box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.25);
            color: #ffffff;
        }

        .btn-submit {
            background: var(--orange-primary);
            border: none;
            border-radius: 10px;
            color: #ffffff;
            font-weight: 600;
            padding: 12px;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 15px;
        }

        .btn-submit:hover {
            background: var(--orange-hover);
            transform: translateY(-1px);
        }

        .back-to-site {
            text-align: center;
            margin-top: 25px;
        }

        .back-to-site a {
            color: var(--slate-gray);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .back-to-site a:hover {
            color: #ffffff;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="brand-logo">
            <i class="bi bi-briefcase-fill"></i>
            <span>MMCS Admin Gate</span>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-3 py-2 px-3 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <small><?php echo escape($error); ?></small>
            </div>
        <?php endif; ?>

        <?php render_flash('alert'); ?>

        <form action="" method="POST" autocomplete="off">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required placeholder="Enter username">
            </div>
            
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required placeholder="Enter password">
            </div>
            
            <button type="submit" class="btn-submit">
                <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
            </button>
        </form>

        <div class="back-to-site">
            <a href="<?php echo SITE_URL; ?>public/index.php">
                <i class="bi bi-arrow-left me-1"></i> Return to Public Website
            </a>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
