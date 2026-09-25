<?php
/**
 * MMCS ADMIN — TESTIMONIALS CRUD
 * ================================
 * Manage client testimonials displayed on the homepage.
 * Actions: list, add, edit, delete, approve/unapprove.
 * 
 * Data table: `testimonials` (id, client_name, org, quote, photo, is_approved)
 */

require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$toggle_id = isset($_GET['toggle_id']) ? intval($_GET['toggle_id']) : 0;

$error = '';
$success = '';

// Handle Approval Toggle Action
if ($toggle_id > 0) {
    try {
        $stmt = $pdo->prepare("SELECT `is_approved` FROM `testimonials` WHERE `id` = ?");
        $stmt->execute([$toggle_id]);
        $current = $stmt->fetchColumn();
        
        $new_status = ($current == 1) ? 0 : 1;
        $update = $pdo->prepare("UPDATE `testimonials` SET `is_approved` = ? WHERE `id` = ?");
        $update->execute([$new_status, $toggle_id]);
        
        flash('alert', 'Testimonial approval status updated successfully.', 'success');
    } catch (PDOException $e) {
        flash('alert', 'Failed to update approval status.', 'danger');
    }
    redirect('testimonials.php');
}

// Handle CRUD Operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf_token();
    $client_name = trim($_POST['client_name'] ?? '');
    $org = trim($_POST['org'] ?? '');
    $quote = trim($_POST['quote'] ?? '');
    $is_approved = isset($_POST['is_approved']) ? 1 : 0;
    
    $existing_photo = $_POST['existing_photo'] ?? 'default-avatar.png';

    if (empty($client_name) || empty($org) || empty($quote)) {
        $error = 'Client Name, Organization, and Quote Text are required.';
    } else {
        // Handle photo upload
        $uploaded_photo = $existing_photo;
        if (isset($_FILES['photo_file']) && $_FILES['photo_file']['error'] === UPLOAD_ERR_OK) {
            $uploaded_path = upload_file($_FILES['photo_file'], 'team', 'image'); // save to staff/team uploads directory is fine
            if ($uploaded_path !== false) {
                $uploaded_photo = $uploaded_path;
            }
        }

        try {
            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO `testimonials` (`client_name`, `org`, `quote`, `photo`, `is_approved`) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$client_name, $org, $quote, $uploaded_photo, $is_approved]);
                flash('alert', 'Testimonial added successfully!', 'success');
                redirect('testimonials.php');
            } elseif ($action === 'edit' && $id > 0) {
                $stmt = $pdo->prepare("UPDATE `testimonials` SET `client_name` = ?, `org` = ?, `quote` = ?, `photo` = ?, `is_approved` = ? WHERE `id` = ?");
                $stmt->execute([$client_name, $org, $quote, $uploaded_photo, $is_approved, $id]);
                flash('alert', 'Testimonial updated successfully!', 'success');
                redirect('testimonials.php');
            }
        } catch (PDOException $e) {
            $error = 'A database error occurred. Please try again.';
        }
    }
}

// Handle Delete Operation
if ($action === 'delete' && $id > 0) {
    try {
        $stmt = $pdo->prepare("SELECT `photo` FROM `testimonials` WHERE `id` = ?");
        $stmt->execute([$id]);
        $photo_path = $stmt->fetchColumn();

        if ($photo_path) {
            $full_path = dirname(__DIR__) . '/' . $photo_path;
            if (file_exists($full_path)) {
                unlink($full_path);
            }
        }

        $stmt = $pdo->prepare("DELETE FROM `testimonials` WHERE `id` = ?");
        $stmt->execute([$id]);
        flash('alert', 'Testimonial deleted successfully!', 'success');
    } catch (PDOException $e) {
        flash('alert', 'Failed to delete testimonial. Please try again.', 'danger');
    }
    redirect('testimonials.php');
}

// Load data for editing
$testimonial = null;
if ($action === 'edit' && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM `testimonials` WHERE `id` = ?");
    $stmt->execute([$id]);
    $testimonial = $stmt->fetch();
    if (!$testimonial) {
        flash('alert', 'Testimonial not found.', 'danger');
        redirect('testimonials.php');
    }
}

// Load all testimonials for list view
$testimonials = [];
if ($action === 'list') {
    try {
        $stmt = $pdo->query("SELECT * FROM `testimonials` ORDER BY `id` DESC");
        $testimonials = $stmt->fetchAll();
    } catch (PDOException $e) {
        $error = 'Failed to load testimonials. Please try again.';
    }
}
?>

<?php require_once __DIR__ . '/admin_header.php'; ?>

<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h2 class="admin-title font-outfit">Client Testimonials</h2>
        <p class="text-muted">Approve or moderate client quotes that are displayed on the public home page carousel.</p>
    </div>
    <div class="col-sm-6 text-sm-end">
        <?php if ($action === 'list'): ?>
            <a href="testimonials.php?action=add" class="btn btn-premium-orange px-4 py-2 font-outfit fw-semibold">
                <i class="bi bi-chat-quote-fill me-1"></i> Add Testimonial
            </a>
        <?php else: ?>
            <a href="testimonials.php" class="btn btn-outline-secondary px-4 py-2 font-outfit fw-medium">
                <i class="bi bi-arrow-left me-1"></i> Back to List
            </a>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-3 py-2 px-3 mb-4">
        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
        <span><?php echo escape($error); ?></span>
    </div>
<?php endif; ?>

<?php if ($action === 'list'): ?>
    <div class="admin-card">
        <?php if (empty($testimonials)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-chat-left-quote fs-1 mb-2 d-block"></i>
                No testimonials found. Click "Add Testimonial" to begin.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-premium align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Client</th>
                            <th>Identity / Org</th>
                            <th>Quote Text</th>
                            <th style="width: 150px;">Approved</th>
                            <th style="width: 150px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($testimonials as $t): ?>
                            <tr>
                                <td>
                                    <?php 
                                    $photo_src = SITE_URL . (empty($t['photo']) || $t['photo'] === 'default-avatar.png' ? 'assets/images/default-avatar.png' : $t['photo']);
                                    ?>
                                    <img src="<?php echo escape($photo_src); ?>" class="rounded shadow-sm" style="width: 45px; height: 45px; object-fit: cover; border: 1px solid var(--border-color);" alt="Client Headshot">
                                </td>
                                <td>
                                    <strong><?php echo escape($t['client_name']); ?></strong>
                                    <div class="text-muted small"><?php echo escape($t['org']); ?></div>
                                </td>
                                <td><small class="text-secondary">"<?php echo escape($t['quote']); ?>"</small></td>
                                <td>
                                    <?php if ($t['is_approved']): ?>
                                        <a href="testimonials.php?toggle_id=<?php echo $t['id']; ?>" class="btn btn-sm btn-success px-3 rounded-pill">
                                            <i class="bi bi-check-circle-fill me-1"></i> Approved
                                        </a>
                                    <?php else: ?>
                                        <a href="testimonials.php?toggle_id=<?php echo $t['id']; ?>" class="btn btn-sm btn-outline-warning px-3 rounded-pill">
                                            <i class="bi bi-dash-circle me-1"></i> Pending
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: right;">
                                    <a href="testimonials.php?action=edit&id=<?php echo $t['id']; ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="testimonials.php?action=delete&id=<?php echo $t['id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this testimonial?');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

<?php elseif ($action === 'add' || $action === 'edit'): ?>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="admin-card">
                <h5 class="admin-title font-outfit text-primary mb-4">
                    <i class="bi <?php echo ($action === 'add') ? 'bi-plus-circle-fill' : 'bi-pencil-square'; ?> me-2"></i>
                    <?php echo ($action === 'add') ? 'Create Testimonial Card' : 'Edit Testimonial Details'; ?>
                </h5>
                
                <form action="" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="existing_photo" value="<?php echo escape($testimonial['photo'] ?? 'default-avatar.png'); ?>">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Client Name</label>
                            <input type="text" class="form-control form-control-premium" name="client_name" value="<?php echo escape($testimonial['client_name'] ?? ''); ?>" required placeholder="e.g. John Doe">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Organization / Company</label>
                            <input type="text" class="form-control form-control-premium" name="org" value="<?php echo escape($testimonial['org'] ?? ''); ?>" required placeholder="e.g. WASH Alliance NGO">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-semibold text-secondary">Quote Message</label>
                            <textarea class="form-control form-control-premium" name="quote" rows="4" required placeholder="Write what client states about MMCS services..."><?php echo escape($testimonial['quote'] ?? ''); ?></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Client Photo</label>
                            <input type="file" class="form-control form-control-premium" name="photo_file" accept="image/*">
                            <div class="form-text small text-muted">Max size: 5MB. Format: JPG, PNG, WebP.</div>
                        </div>

                        <div class="col-md-6 d-flex align-items-center mt-4 pt-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_approved" name="is_approved" <?php echo (!isset($testimonial) || $testimonial['is_approved']) ? 'checked' : ''; ?>>
                                <label class="form-check-label fw-medium text-secondary" for="is_approved">Approve instantly (Show on website homepage)</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top text-end">
                        <button type="submit" class="btn btn-premium-orange px-4">
                            <i class="bi bi-save me-1"></i> Save Testimonial
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php
require_once __DIR__ . '/admin_footer.php';
?>
