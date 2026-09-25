<?php
/**
 * MMCS SITE SETTINGS MANAGER
 * ==========================
 * Allows the admin to update global site configuration stored in the `settings` DB table.
 * Also provides a password change form for the current admin account.
 * 
 * SETTINGS MANAGED (all stored as key/value pairs in the `settings` table):
 *   company_name, phone, email, address (head office), field_address,
 *   social_linkedin, social_twitter, social_facebook,
 *   stats_years, stats_projects, stats_clients, footer_text
 * 
 * TO ADD A NEW SETTING:
 *   1. Insert a row into `settings` (key + value)
 *   2. Add the key name to the $keys_to_update array below (line 14)
 *   3. Add a form <input> in the HTML section below
 *   4. Use get_setting('your_key') anywhere in public/includes pages
 */

require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once __DIR__ . '/admin_header.php';

$error = '';
$success = '';

// ---------------------------------------------------------------
//  HANDLE: Update site settings form submission
// ---------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_settings'])) {
    require_csrf_token();
    // ★ Add new setting keys here to make them editable
    $keys_to_update = [
        'company_name', 'phone', 'email', 'address', 'field_address',
        'social_linkedin', 'social_twitter', 'social_facebook',
        'stats_years', 'stats_projects', 'stats_clients', 'footer_text',
        'consultation_fee'
    ];

    try {
        $pdo->beginTransaction();
        // INSERT ... ON DUPLICATE KEY UPDATE ensures new keys are created if missing
        $stmt = $pdo->prepare("INSERT INTO `settings` (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)");
        
        foreach ($keys_to_update as $key) {
            $value = trim($_POST[$key] ?? '');
            $stmt->execute([$key, $value]);
        }
        
        $pdo->commit();
        $success = 'Site configuration updated successfully!';
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error = 'Failed to save settings. Please try again.';
    }
}

// ---------------------------------------------------------------
//  HANDLE: Admin password change form submission
// ---------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    require_csrf_token();
    $curr_pass = trim($_POST['curr_pass'] ?? '');
    $new_pass = trim($_POST['new_pass'] ?? '');
    $new_pass_confirm = trim($_POST['new_pass_confirm'] ?? '');

    if (empty($curr_pass) || empty($new_pass) || empty($new_pass_confirm)) {
        $error = 'All password fields are required.';
    } elseif ($new_pass !== $new_pass_confirm) {
        $error = 'New passwords do not match.';
    } elseif (strlen($new_pass) < 6) {
        $error = 'New password must be at least 6 characters long.';
    } elseif (!preg_match('/[A-Z]/', $new_pass) || !preg_match('/[a-z]/', $new_pass) || !preg_match('/[0-9]/', $new_pass)) {
        $error = 'New password must contain uppercase, lowercase, and a number.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT `password_hash`, `id` FROM `admins` WHERE `id` = ?");
            $stmt->execute([$_SESSION['admin_user_id']]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($curr_pass, $admin['password_hash'])) {
                $new_hash = password_hash($new_pass, PASSWORD_DEFAULT);
                $update = $pdo->prepare("UPDATE `admins` SET `password_hash` = ? WHERE `id` = ?");
                $update->execute([$new_hash, $admin['id']]);
                // Refresh session username in case it was changed
                $stmt = $pdo->prepare("SELECT `username` FROM `admins` WHERE `id` = ?");
                $stmt->execute([$_SESSION['admin_user_id']]);
                $_SESSION['admin_username'] = $stmt->fetchColumn();
                $success = 'Password changed successfully.';
            } else {
                $error = 'Current password is incorrect.';
            }
        } catch (PDOException $e) {
            $error = 'System error occurred. Please try again.';
        }
    }
}

// Load all current setting values from the DB to populate the form
$settings = [];
try {
    $stmt = $pdo->query("SELECT `key`, `value` FROM `settings`");
    while ($row = $stmt->fetch()) {
        $settings[$row['key']] = $row['value'];
    }
} catch (PDOException $e) {
    $error = 'Failed to fetch settings.';
}
?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="admin-title font-outfit">Site Settings</h2>
        <p class="text-muted">Manage company details, contact information, social links, stats, and administrator passwords.</p>
    </div>
</div>

<?php if (!empty($success)): ?>
    <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success rounded-3 py-2 px-3 mb-4 d-flex align-items-center" role="alert">
        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
        <span><?php echo escape($success); ?></span>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-3 py-2 px-3 mb-4 d-flex align-items-center" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
        <span><?php echo escape($error); ?></span>
    </div>
<?php endif; ?>

<div class="row">
    <!-- Left panel - Configuration form -->
    <div class="col-lg-8">
        <div class="admin-card">
            <form action="" method="POST">
                    <?php echo csrf_field(); ?>
                <h5 class="admin-title mb-4 font-outfit text-primary"><i class="bi bi-sliders me-2"></i>Global Website Configuration</h5>
                
                <div class="row g-3">
                    <!-- Company Name -->
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Company Name</label>
                        <input type="text" class="form-control form-control-premium" name="company_name" value="<?php echo escape($settings['company_name'] ?? ''); ?>" required>
                    </div>

                    <!-- Contact Phone -->
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Contact Phone</label>
                        <input type="text" class="form-control form-control-premium" name="phone" value="<?php echo escape($settings['phone'] ?? ''); ?>" required>
                    </div>

                    <!-- Contact Email -->
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Contact Email</label>
                        <input type="email" class="form-control form-control-premium" name="email" value="<?php echo escape($settings['email'] ?? ''); ?>" required>
                    </div>

                    <!-- Counter 1: Years -->
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold text-secondary">Stats: Years</label>
                        <input type="text" class="form-control form-control-premium" name="stats_years" value="<?php echo escape($settings['stats_years'] ?? ''); ?>">
                    </div>
                    <!-- Counter 2: Projects -->
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold text-secondary">Stats: Projects</label>
                        <input type="text" class="form-control form-control-premium" name="stats_projects" value="<?php echo escape($settings['stats_projects'] ?? ''); ?>">
                    </div>
                    <!-- Counter 3: Clients -->
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold text-secondary">Stats: Clients</label>
                        <input type="text" class="form-control form-control-premium" name="stats_clients" value="<?php echo escape($settings['stats_clients'] ?? ''); ?>">
                    </div>

                    <!-- Head Office Address -->
                    <div class="col-12">
                        <label class="form-label small fw-semibold text-secondary">Head Office Address</label>
                        <input type="text" class="form-control form-control-premium" name="address" value="<?php echo escape($settings['address'] ?? ''); ?>" required>
                    </div>

                    <!-- Field Office Address -->
                    <div class="col-12">
                        <label class="form-label small fw-semibold text-secondary">Field Office Address</label>
                        <input type="text" class="form-control form-control-premium" name="field_address" value="<?php echo escape($settings['field_address'] ?? ''); ?>">
                    </div>

                    <!-- Footer Copyright text -->
                    <div class="col-12">
                        <label class="form-label small fw-semibold text-secondary">Footer Copyright Text</label>
                        <input type="text" class="form-control form-control-premium" name="footer_text" value="<?php echo escape($settings['footer_text'] ?? ''); ?>" required>
                    </div>

                    <h6 class="font-outfit text-dark mt-4 mb-2 fw-semibold">Social Media Handles</h6>
                    <!-- LinkedIn -->
                    <div class="col-md-4">
                        <label class="form-label small text-muted"><i class="bi bi-linkedin text-primary me-1"></i>LinkedIn Profile</label>
                        <input type="url" class="form-control form-control-premium" name="social_linkedin" value="<?php echo escape($settings['social_linkedin'] ?? ''); ?>">
                    </div>

                    <!-- Twitter -->
                    <div class="col-md-4">
                        <label class="form-label small text-muted"><i class="bi bi-twitter-x text-dark me-1"></i>Twitter-X URL</label>
                        <input type="url" class="form-control form-control-premium" name="social_twitter" value="<?php echo escape($settings['social_twitter'] ?? ''); ?>">
                    </div>

                    <!-- Facebook -->
                    <div class="col-md-4">
                        <label class="form-label small text-muted"><i class="bi bi-facebook text-primary me-1"></i>Facebook URL</label>
                        <input type="url" class="form-control form-control-premium" name="social_facebook" value="<?php echo escape($settings['social_facebook'] ?? ''); ?>">
                    </div>

                    <!-- Consultation Fee -->
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-secondary">Consultation Fee (Quote Form)</label>
                        <input type="text" class="form-control form-control-premium" name="consultation_fee" value="<?php echo escape($settings['consultation_fee'] ?? ''); ?>" placeholder="e.g. $100 or Free">
                        <div class="form-text small text-muted">Displayed on the Get Quote form.</div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top text-end">
                    <button type="submit" name="update_settings" class="btn btn-premium-orange px-4">
                        <i class="bi bi-save me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right panel - Change Password -->
    <div class="col-lg-4">
        <div class="admin-card">
            <form action="" method="POST">
                <?php echo csrf_field(); ?>
                <h5 class="admin-title mb-4 font-outfit text-primary"><i class="bi bi-shield-lock-fill me-2"></i>Security Gate</h5>
                
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Current Password</label>
                    <input type="password" class="form-control form-control-premium" name="curr_pass" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">New Password</label>
                    <input type="password" class="form-control form-control-premium" name="new_pass" required>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold text-secondary">Confirm New Password</label>
                    <input type="password" class="form-control form-control-premium" name="new_pass_confirm" required>
                </div>

                <button type="submit" name="change_password" class="btn btn-dark w-100 py-2 font-outfit fw-semibold">
                    <i class="bi bi-key-fill me-1"></i> Change Password
                </button>
            </form>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/admin_footer.php';
?>
