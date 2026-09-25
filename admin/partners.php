<?php
/**
 * MMCS ADMIN — PARTNERS CRUD
 * ===========================
 * Manage client & partner logos shown on the About Us page.
 * Actions: list, add, edit, delete.
 * 
 * Data table: `partners` (id, name, logo, website, is_active, sort_order)
 */

require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$error = '';
$success = '';

// Handle CRUD Operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf_token();
    $name = trim($_POST['name'] ?? '');
    $website = trim($_POST['website'] ?? '');
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $sort_order = intval($_POST['sort_order'] ?? 0);

    // Existing logo
    $existing_logo = $_POST['existing_logo'] ?? '';

    if (empty($name)) {
        $error = 'Partner name is required.';
    } else {
        // Handle logo upload
        $uploaded_logo = $existing_logo;
        if (isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
            $uploaded_path = upload_file($_FILES['logo_file'], 'partners', 'image');
            if ($uploaded_path !== false) {
                // Delete old logo if replacing
                if (!empty($existing_logo)) {
                    $old_path = dirname(__DIR__) . '/' . $existing_logo;
                    if (file_exists($old_path)) {
                        unlink($old_path);
                    }
                }
                $uploaded_logo = $uploaded_path;
            }
        }

        try {
            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO `partners` (`name`, `logo`, `website`, `is_active`, `sort_order`) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$name, $uploaded_logo, $website, $is_active, $sort_order]);
                flash('alert', 'Partner added successfully!', 'success');
                redirect('partners.php');
            } elseif ($action === 'edit' && $id > 0) {
                $stmt = $pdo->prepare("UPDATE `partners` SET `name` = ?, `logo` = ?, `website` = ?, `is_active` = ?, `sort_order` = ? WHERE `id` = ?");
                $stmt->execute([$name, $uploaded_logo, $website, $is_active, $sort_order, $id]);
                flash('alert', 'Partner updated successfully!', 'success');
                redirect('partners.php');
            }
        } catch (PDOException $e) {
            $error = 'A database error occurred. Please try again.';
        }
    }
}

// Handle Delete Operation
if ($action === 'delete' && $id > 0) {
    try {
        // Get logo path to unlink
        $stmt = $pdo->prepare("SELECT `logo` FROM `partners` WHERE `id` = ?");
        $stmt->execute([$id]);
        $logo_path = $stmt->fetchColumn();

        if ($logo_path) {
            $full_path = dirname(__DIR__) . '/' . $logo_path;
            if (file_exists($full_path)) {
                unlink($full_path);
            }
        }

        $stmt = $pdo->prepare("DELETE FROM `partners` WHERE `id` = ?");
        $stmt->execute([$id]);
        flash('alert', 'Partner deleted successfully!', 'success');
    } catch (PDOException $e) {
        flash('alert', 'Failed to delete partner. Please try again.', 'danger');
    }
    redirect('partners.php');
}

// Load data for editing
$partner = null;
if ($action === 'edit' && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM `partners` WHERE `id` = ?");
    $stmt->execute([$id]);
    $partner = $stmt->fetch();
    if (!$partner) {
        flash('alert', 'Partner not found.', 'danger');
        redirect('partners.php');
    }
}

// Load all partners for list view
$partners = [];
if ($action === 'list') {
    try {
        $stmt = $pdo->query("SELECT * FROM `partners` ORDER BY `sort_order` ASC, `id` DESC");
        $partners = $stmt->fetchAll();
    } catch (PDOException $e) {
        $error = 'Failed to load partners. Please try again.';
    }
}
?>

<?php require_once __DIR__ . '/admin_header.php'; ?>

<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h2 class="admin-title font-outfit">Manage Partners & Clients</h2>
        <p class="text-muted">Add, edit, or remove partner logos displayed on the About Us page.</p>
    </div>
    <div class="col-sm-6 text-sm-end">
        <?php if ($action === 'list'): ?>
            <a href="partners.php?action=add" class="btn btn-premium-orange px-4 py-2 font-outfit fw-semibold">
                <i class="bi bi-plus-circle-fill me-1"></i> Add Partner
            </a>
        <?php else: ?>
            <a href="partners.php" class="btn btn-outline-secondary px-4 py-2 font-outfit fw-medium">
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
        <?php if (empty($partners)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-building fs-1 mb-2 d-block"></i>
                No partners found. Click "Add Partner" to begin.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-premium align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Logo</th>
                            <th>Partner Name</th>
                            <th>Website</th>
                            <th style="width: 100px;">Order</th>
                            <th style="width: 120px;">Status</th>
                            <th style="width: 150px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($partners as $p): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($p['logo'])): ?>
                                        <img src="<?php echo SITE_URL . escape($p['logo']); ?>" class="rounded shadow-sm" style="width: 50px; height: 50px; object-fit: contain; border: 2px solid var(--border-color); background: #fff; padding: 4px;" alt="Partner Logo">
                                    <?php else: ?>
                                        <div class="rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: var(--light-bg); border: 2px dashed var(--border-color);">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?php echo escape($p['name']); ?></strong></td>
                                <td>
                                    <?php if (!empty($p['website'])): ?>
                                        <a href="<?php echo escape($p['website']); ?>" target="_blank" class="text-decoration-none small"><?php echo escape($p['website']); ?></a>
                                    <?php else: ?>
                                        <span class="text-muted small">—</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $p['sort_order']; ?></td>
                                <td>
                                    <?php if ($p['is_active']): ?>
                                        <span class="badge bg-success-subtle text-success px-2 py-1">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-subtle text-danger px-2 py-1">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: right;">
                                    <a href="partners.php?action=edit&id=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="partners.php?action=delete&id=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this partner?');">
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
                    <?php echo ($action === 'add') ? 'Add New Partner' : 'Edit Partner'; ?>
                </h5>

                <form action="" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="existing_logo" value="<?php echo escape($partner['logo'] ?? ''); ?>">

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold text-secondary">Partner / Client Name</label>
                            <input type="text" class="form-control form-control-premium" name="name" value="<?php echo escape($partner['name'] ?? ''); ?>" required placeholder="e.g. SONAHRI HUMANITARIAN DEVELOPMENT SOCIETY (SHDS)">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-secondary">Sort Order</label>
                            <input type="number" class="form-control form-control-premium" name="sort_order" value="<?php echo escape($partner['sort_order'] ?? '0'); ?>" min="0">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-semibold text-secondary">Website URL (optional)</label>
                            <input type="url" class="form-control form-control-premium" name="website" value="<?php echo escape($partner['website'] ?? ''); ?>" placeholder="https://example.com">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Partner Logo</label>
                            <input type="file" class="form-control form-control-premium" name="logo_file" accept="image/*">
                            <div class="form-text small text-muted">Max size: 5MB. Format: JPG, PNG, WebP. Recommended: square image, min 200x200px.</div>
                        </div>

                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-check form-switch mt-4 pt-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" <?php echo (!isset($partner) || $partner['is_active']) ? 'checked' : ''; ?>>
                                <label class="form-check-label fw-medium text-secondary" for="is_active">Display on website</label>
                            </div>
                        </div>

                        <?php if (!empty($partner['logo'])): ?>
                            <div class="col-12">
                                <span class="small text-muted d-block mb-1">Current Logo:</span>
                                <img src="<?php echo SITE_URL . escape($partner['logo']); ?>" class="img-thumbnail rounded" style="width: 100px; height: 100px; object-fit: contain; background: #fff;" alt="Current Logo">
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mt-4 pt-3 border-top text-end">
                        <button type="submit" class="btn btn-premium-orange px-4">
                            <i class="bi bi-save me-1"></i> Save Partner
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
