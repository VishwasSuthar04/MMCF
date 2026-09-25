<?php
/**
 * MMCS ADMIN — ORGANOGRAM CRUD
 * =============================
 * Manage organizational hierarchy (org chart) entries.
 * Actions: list, add, edit, delete.
 * 
 * Data table: `organogram` (id, name, designation, photo, parent_id, display_order)
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
    $designation = trim($_POST['designation'] ?? '');
    $parent_id = ($_POST['parent_id'] === '') ? null : intval($_POST['parent_id']);
    $display_order = intval($_POST['display_order'] ?? 0);

    $existing_photo = $_POST['existing_photo'] ?? '';

    if (empty($name) || empty($designation)) {
        $error = 'Name and Designation are required.';
    } elseif ($parent_id === $id && $action === 'edit') {
        $error = 'A person cannot report to themselves.';
    } else {
        // Handle photo upload
        $uploaded_photo = $existing_photo;
        if (isset($_FILES['photo_file']) && $_FILES['photo_file']['error'] === UPLOAD_ERR_OK) {
            $uploaded_path = upload_file($_FILES['photo_file'], 'organogram', 'image');
            if ($uploaded_path !== false) {
                // Remove old photo if replacing
                if (!empty($existing_photo)) {
                    $old_path = dirname(__DIR__) . '/' . $existing_photo;
                    if (file_exists($old_path)) {
                        unlink($old_path);
                    }
                }
                $uploaded_photo = $uploaded_path;
            } else {
                $error = 'Photo upload failed. Check format (JPG, PNG, WebP) and size (max 5MB).';
            }
        }

        if (empty($error)) {
            try {
                // Validate circular hierarchy on save
                if ($parent_id !== null) {
                    // Walk up from parent_id to check if $id is an ancestor
                    $check_id = $parent_id;
                    $is_circular = false;
                    while ($check_id !== null) {
                        if ($check_id === $id) {
                            $is_circular = true;
                            break;
                        }
                        $stmt = $pdo->prepare("SELECT `parent_id` FROM `organogram` WHERE `id` = ?");
                        $stmt->execute([$check_id]);
                        $check_id = $stmt->fetchColumn();
                        $check_id = $check_id !== null ? intval($check_id) : null;
                    }
                    if ($is_circular) {
                        $error = 'Cannot set this reporting line — it would create a circular hierarchy.';
                    }
                }

                if (empty($error)) {
                    if ($action === 'add') {
                        $stmt = $pdo->prepare("INSERT INTO `organogram` (`name`, `designation`, `photo`, `parent_id`, `display_order`) VALUES (?, ?, ?, ?, ?)");
                        $stmt->execute([$name, $designation, $uploaded_photo ?: null, $parent_id, $display_order]);
                        flash('alert', 'Organogram entry added successfully!', 'success');
                        redirect('organogram.php');
                    } elseif ($action === 'edit' && $id > 0) {
                        $stmt = $pdo->prepare("UPDATE `organogram` SET `name` = ?, `designation` = ?, `photo` = ?, `parent_id` = ?, `display_order` = ? WHERE `id` = ?");
                        $stmt->execute([$name, $designation, $uploaded_photo ?: null, $parent_id, $display_order, $id]);
                        flash('alert', 'Organogram entry updated successfully!', 'success');
                        redirect('organogram.php');
                    }
                }
            } catch (PDOException $e) {
                $error = 'A database error occurred. Please try again.';
            }
        }
    }
}

// Handle Delete Operation
if ($action === 'delete' && $id > 0) {
    try {
        // Check if this person has children
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM `organogram` WHERE `parent_id` = ?");
        $stmt->execute([$id]);
        $child_count = $stmt->fetchColumn();

        if ($child_count > 0) {
            flash('alert', 'Cannot delete this entry — ' . $child_count . ' other member(s) report to them. Please reassign or delete subordinates first.', 'danger');
            redirect('organogram.php');
        }

        // Get photo path to unlink
        $stmt = $pdo->prepare("SELECT `photo` FROM `organogram` WHERE `id` = ?");
        $stmt->execute([$id]);
        $photo_path = $stmt->fetchColumn();

        if (!empty($photo_path)) {
            $full_path = dirname(__DIR__) . '/' . $photo_path;
            if (file_exists($full_path)) {
                unlink($full_path);
            }
        }

        $stmt = $pdo->prepare("DELETE FROM `organogram` WHERE `id` = ?");
        $stmt->execute([$id]);
        flash('alert', 'Organogram entry deleted successfully!', 'success');
    } catch (PDOException $e) {
        flash('alert', 'Failed to delete entry. Please try again.', 'danger');
    }
    redirect('organogram.php');
}

// Load data for editing
$member = null;
if ($action === 'edit' && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM `organogram` WHERE `id` = ?");
    $stmt->execute([$id]);
    $member = $stmt->fetch();
    if (!$member) {
        flash('alert', 'Organogram entry not found.', 'danger');
        redirect('organogram.php');
    }
}

// Load all entries for list view and parent dropdown
$organogram_entries = [];
if ($action === 'list') {
    try {
        $stmt = $pdo->query("SELECT o.*, p.`name` AS parent_name FROM `organogram` o LEFT JOIN `organogram` p ON o.`parent_id` = p.`id` ORDER BY o.`display_order` ASC, o.`id` ASC");
        $organogram_entries = $stmt->fetchAll();
    } catch (PDOException $e) {
        $error = 'Failed to load organogram entries. Please try again.';
    }
}

// All entries for parent dropdown (exclude self when editing)
$parent_options = [];
try {
    $sql = "SELECT `id`, `name`, `designation` FROM `organogram` ORDER BY `display_order` ASC, `id` ASC";
    $parent_options = $pdo->query($sql)->fetchAll();
} catch (PDOException $e) {
    // silent
}
?>

<?php require_once __DIR__ . '/admin_header.php'; ?>

<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h2 class="admin-title font-outfit">Manage Organogram</h2>
        <p class="text-muted">Build and maintain the organizational hierarchy chart shown on the About page.</p>
    </div>
    <div class="col-sm-6 text-sm-end">
        <?php if ($action === 'list'): ?>
            <a href="organogram.php?action=add" class="btn btn-premium-orange px-4 py-2 font-outfit fw-semibold">
                <i class="bi bi-person-plus-fill me-1"></i> Add Member
            </a>
        <?php else: ?>
            <a href="organogram.php" class="btn btn-outline-secondary px-4 py-2 font-outfit fw-medium">
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
        <?php if (empty($organogram_entries)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-diagram-3 fs-1 mb-2 d-block"></i>
                No organogram entries found. Click "Add Member" to start building the org chart.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-premium align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Photo</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Reports To</th>
                            <th style="width: 80px;">Order</th>
                            <th style="width: 150px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($organogram_entries as $e): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($e['photo'])): ?>
                                        <img src="<?php echo SITE_URL . escape($e['photo']); ?>" class="rounded-circle shadow-sm" style="width: 50px; height: 50px; object-fit: cover; border: 2px solid var(--border-color);" alt="Photo">
                                    <?php else: ?>
                                        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px; background: #e9ecef; border: 2px solid var(--border-color);">
                                            <i class="bi bi-person-fill text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?php echo escape($e['name']); ?></strong></td>
                                <td><?php echo escape($e['designation']); ?></td>
                                <td>
                                    <?php if ($e['parent_id'] === null): ?>
                                        <span class="badge bg-primary-subtle text-primary px-2 py-1">— Top Level —</span>
                                    <?php else: ?>
                                        <span class="text-muted"><?php echo escape($e['parent_name']); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo intval($e['display_order']); ?></td>
                                <td style="text-align: right;">
                                    <a href="organogram.php?action=edit&id=<?php echo $e['id']; ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="organogram.php?action=delete&id=<?php echo $e['id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this entry?');">
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
                    <?php echo ($action === 'add') ? 'Add Organogram Member' : 'Edit Organogram Member'; ?>
                </h5>

                <form action="" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="existing_photo" value="<?php echo escape($member['photo'] ?? ''); ?>">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Full Name</label>
                            <input type="text" class="form-control form-control-premium" name="name" value="<?php echo escape($member['name'] ?? ''); ?>" required placeholder="e.g. Dr. Muhammad Ali">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Designation / Position</label>
                            <input type="text" class="form-control form-control-premium" name="designation" value="<?php echo escape($member['designation'] ?? ''); ?>" required placeholder="e.g. Executive Director">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Reports To (Parent)</label>
                            <select class="form-select form-control-premium" name="parent_id">
                                <option value="">None — Top Level</option>
                                <?php foreach ($parent_options as $opt): ?>
                                    <?php if ($action === 'edit' && intval($opt['id']) === $id) continue; ?>
                                    <option value="<?php echo $opt['id']; ?>" <?php echo (($member['parent_id'] ?? null) != null && intval($member['parent_id']) === intval($opt['id'])) ? 'selected' : ''; ?>>
                                        <?php echo escape($opt['name'] . ' — ' . $opt['designation']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Display Order</label>
                            <input type="number" class="form-control form-control-premium" name="display_order" value="<?php echo intval($member['display_order'] ?? 0); ?>" min="0">
                            <div class="form-text small text-muted">Lower numbers appear first among siblings.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Photo (Optional)</label>
                            <input type="file" class="form-control form-control-premium" name="photo_file" accept="image/*">
                            <div class="form-text small text-muted">Max size: 5MB. Format: JPG, PNG, WebP.</div>
                        </div>

                        <?php if (!empty($member['photo'])): ?>
                            <div class="col-md-6 d-flex align-items-end">
                                <div>
                                    <span class="small text-muted d-block mb-1">Current Photo:</span>
                                    <img src="<?php echo SITE_URL . escape($member['photo']); ?>" class="img-thumbnail rounded-circle" style="width: 80px; height: 80px; object-fit: cover;" alt="Current Photo">
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mt-4 pt-3 border-top text-end">
                        <button type="submit" class="btn btn-premium-orange px-4">
                            <i class="bi bi-save me-1"></i> Save Entry
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
