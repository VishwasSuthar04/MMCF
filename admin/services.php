<?php
/**
 * MMCS ADMIN — SERVICES CRUD
 * ===========================
 * Manage the 8 service cards displayed on the public site.
 * Actions: list, add, edit, delete (soft via is_active toggle).
 * 
 * Data table: `services` (id, title, description, icon_path, is_active, sort_order)
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
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $icon_path = trim($_POST['icon_path'] ?? 'bi-gear');
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $sort_order = intval($_POST['sort_order'] ?? 0);

    if (empty($title) || empty($description)) {
        $error = 'Service Title and Description are required.';
    } else {
        try {
            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO `services` (`title`, `description`, `icon_path`, `is_active`, `sort_order`) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$title, $description, $icon_path, $is_active, $sort_order]);
                flash('alert', 'Service added successfully!', 'success');
                redirect('services.php');
            } elseif ($action === 'edit' && $id > 0) {
                $stmt = $pdo->prepare("UPDATE `services` SET `title` = ?, `description` = ?, `icon_path` = ?, `is_active` = ?, `sort_order` = ? WHERE `id` = ?");
                $stmt->execute([$title, $description, $icon_path, $is_active, $sort_order, $id]);
                flash('alert', 'Service updated successfully!', 'success');
                redirect('services.php');
            }
        } catch (PDOException $e) {
            $error = 'A database error occurred. Please try again.';
        }
    }
}

// Handle Delete Operation
if ($action === 'delete' && $id > 0) {
    try {
        $stmt = $pdo->prepare("DELETE FROM `services` WHERE `id` = ?");
        $stmt->execute([$id]);
        flash('alert', 'Service deleted successfully!', 'success');
    } catch (PDOException $e) {
        flash('alert', 'Failed to delete service. Please try again.', 'danger');
    }
    redirect('services.php');
}

// Load data for editing
$service = null;
if ($action === 'edit' && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM `services` WHERE `id` = ?");
    $stmt->execute([$id]);
    $service = $stmt->fetch();
    if (!$service) {
        flash('alert', 'Service not found.', 'danger');
        redirect('services.php');
    }
}

// Load all services for list view
$services = [];
if ($action === 'list') {
    try {
        $stmt = $pdo->query("SELECT * FROM `services` ORDER BY `sort_order` ASC, `title` ASC");
        $services = $stmt->fetchAll();
    } catch (PDOException $e) {
        $error = 'Failed to load services. Please try again.';
    }
}
?>

<?php require_once __DIR__ . '/admin_header.php'; ?>

<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h2 class="admin-title font-outfit">Manage Services</h2>
        <p class="text-muted">Display up to 8 core dynamic consultation service offerings on the website.</p>
    </div>
    <div class="col-sm-6 text-sm-end">
        <?php if ($action === 'list'): ?>
            <a href="services.php?action=add" class="btn btn-premium-orange px-4 py-2 font-outfit fw-semibold">
                <i class="bi bi-plus-circle me-1"></i> Add New Service
            </a>
        <?php else: ?>
            <a href="services.php" class="btn btn-outline-secondary px-4 py-2 font-outfit fw-medium">
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
        <?php if (empty($services)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-folder-x fs-1 mb-2 d-block"></i>
                No services defined yet. Click "Add New Service" to start.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-premium align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Order</th>
                            <th style="width: 80px;">Icon</th>
                            <th>Service Title</th>
                            <th>Description Snippet</th>
                            <th style="width: 120px;">Status</th>
                            <th style="width: 150px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($services as $s): ?>
                            <tr>
                                <td><span class="badge bg-secondary rounded-pill px-2"><?php echo escape($s['sort_order']); ?></span></td>
                                <td>
                                    <div class="bg-light border text-orange text-center rounded py-1 px-2">
                                        <i class="bi <?php echo escape($s['icon_path'] ?: 'bi-gear'); ?> fs-5"></i>
                                    </div>
                                </td>
                                <td><strong><?php echo escape($s['title']); ?></strong></td>
                                <td><small class="text-muted"><?php echo escape(substr($s['description'], 0, 100)) . (strlen($s['description']) > 100 ? '...' : ''); ?></small></td>
                                <td>
                                    <?php if ($s['is_active']): ?>
                                        <span class="badge bg-success-subtle text-success px-2 py-1">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-subtle text-danger px-2 py-1">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: right;">
                                    <a href="services.php?action=edit&id=<?php echo $s['id']; ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="services.php?action=delete&id=<?php echo $s['id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this service?');">
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
                    <?php echo ($action === 'add') ? 'Add New Service Offering' : 'Edit Service Offering'; ?>
                </h5>
                
                <form action="" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold text-secondary">Service Title</label>
                            <input type="text" class="form-control form-control-premium" name="title" value="<?php echo escape($service['title'] ?? ''); ?>" required placeholder="e.g. Monitoring & Evaluation (M&E)">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-secondary">Bootstrap Icon Class</label>
                            <input type="text" class="form-control form-control-premium" name="icon_path" value="<?php echo escape($service['icon_path'] ?? 'bi-gear'); ?>" required placeholder="e.g. bi-graph-up-arrow">
                            <div class="form-text small text-muted"><a href="https://icons.getbootstrap.com/" target="_blank">Search Bootstrap Icons <i class="bi bi-box-arrow-up-right"></i></a></div>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-semibold text-secondary">Detailed Description</label>
                            <textarea class="form-control form-control-premium" name="description" rows="6" required placeholder="Write details about the services, sub-services, and client delivery model..."><?php echo escape($service['description'] ?? ''); ?></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Display Sort Order</label>
                            <input type="number" class="form-control form-control-premium" name="sort_order" value="<?php echo escape($service['sort_order'] ?? 0); ?>" min="0">
                        </div>

                        <div class="col-md-6 d-flex align-items-center mt-4 pt-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" <?php echo (!isset($service) || $service['is_active']) ? 'checked' : ''; ?>>
                                <label class="form-check-label fw-medium text-secondary" for="is_active">Publish Service (Active on site)</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top text-end">
                        <button type="submit" class="btn btn-premium-orange px-4">
                            <i class="bi bi-save me-1"></i> Save Service
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
