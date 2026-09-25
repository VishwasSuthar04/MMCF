<?php
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf_token();
    $title = trim($_POST['title'] ?? '');
    $type = trim($_POST['type'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $requirements = trim($_POST['requirements'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $deadline = trim($_POST['deadline'] ?? '');
    $status = trim($_POST['status'] ?? 'open');

    if (empty($title) || empty($type) || empty($description)) {
        $error = 'Title, Type, and Description are required.';
    } else {
        try {
            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO `opportunities` (`title`, `type`, `description`, `requirements`, `location`, `deadline`, `status`) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$title, $type, $description, $requirements, $location, $deadline ?: null, $status]);
                flash('alert', 'Opportunity published successfully!', 'success');
                redirect('opportunities.php');
            } elseif ($action === 'edit' && $id > 0) {
                $stmt = $pdo->prepare("UPDATE `opportunities` SET `title` = ?, `type` = ?, `description` = ?, `requirements` = ?, `location` = ?, `deadline` = ?, `status` = ? WHERE `id` = ?");
                $stmt->execute([$title, $type, $description, $requirements, $location, $deadline ?: null, $status, $id]);
                flash('alert', 'Opportunity updated successfully!', 'success');
                redirect('opportunities.php');
            }
        } catch (PDOException $e) {
            $error = 'A database error occurred. Please try again.';
        }
    }
}

if ($action === 'delete' && $id > 0) {
    try {
        $stmt = $pdo->prepare("DELETE FROM `opportunities` WHERE `id` = ?");
        $stmt->execute([$id]);
        flash('alert', 'Opportunity deleted successfully!', 'success');
    } catch (PDOException $e) {
        flash('alert', 'Failed to delete opportunity. Please try again.', 'danger');
    }
    redirect('opportunities.php');
}

$opportunity = null;
if ($action === 'edit' && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM `opportunities` WHERE `id` = ?");
    $stmt->execute([$id]);
    $opportunity = $stmt->fetch();
    if (!$opportunity) {
        flash('alert', 'Opportunity not found.', 'danger');
        redirect('opportunities.php');
    }
}

$opportunities = [];
if ($action === 'list') {
    try {
        $stmt = $pdo->query("SELECT o.*, (SELECT COUNT(*) FROM `applications` WHERE `opportunity_id` = o.`id`) AS `app_count` FROM `opportunities` o ORDER BY `created_at` DESC");
        $opportunities = $stmt->fetchAll();
    } catch (PDOException $e) {
        $error = 'Failed to load opportunities. Please try again.';
    }
}
?>

<?php require_once __DIR__ . '/admin_header.php'; ?>

<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h2 class="admin-title font-outfit">Manage Opportunities</h2>
        <p class="text-muted">Publish and manage job openings, volunteer positions, internships, and expert calls.</p>
    </div>
    <div class="col-sm-6 text-sm-end">
        <?php if ($action === 'list'): ?>
            <a href="opportunities.php?action=add" class="btn btn-premium-orange px-4 py-2 font-outfit fw-semibold">
                <i class="bi bi-plus-circle-fill me-1"></i> Add Opportunity
            </a>
        <?php else: ?>
            <a href="opportunities.php" class="btn btn-outline-secondary px-4 py-2 font-outfit fw-medium">
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
        <?php if (empty($opportunities)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-megaphone fs-1 mb-2 d-block"></i>
                No opportunities found. Click "Add Opportunity" to publish one.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-premium align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Location</th>
                            <th>Deadline</th>
                            <th>Applications</th>
                            <th style="width: 100px;">Status</th>
                            <th style="width: 150px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($opportunities as $o): ?>
                            <tr>
                                <td>
                                    <strong><?php echo escape($o['title']); ?></strong>
                                    <div class="text-muted small"><?php echo escape(substr($o['description'], 0, 60)) . (strlen($o['description']) > 60 ? '...' : ''); ?></div>
                                </td>
                                <td>
                                    <?php
                                    $type_badges = ['volunteer' => 'bg-info text-white', 'intern' => 'bg-primary text-white', 'expert' => 'bg-warning text-dark'];
                                    $badge_class = $type_badges[$o['type']] ?? 'bg-secondary text-white';
                                    ?>
                                    <span class="badge <?php echo $badge_class; ?> px-3 py-1 text-capitalize"><?php echo escape($o['type']); ?></span>
                                </td>
                                <td><small class="text-muted"><?php echo escape($o['location'] ?: '—'); ?></small></td>
                                <td>
                                    <?php if ($o['deadline']): ?>
                                        <small class="<?php echo (strtotime($o['deadline']) < time()) ? 'text-danger fw-semibold' : 'text-muted'; ?>">
                                            <?php echo date('M j, Y', strtotime($o['deadline'])); ?>
                                        </small>
                                    <?php else: ?>
                                        <small class="text-muted">Rolling</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="opportunities.php?action=applications&id=<?php echo $o['id']; ?>" class="text-decoration-none">
                                        <span class="badge bg-dark bg-opacity-10 text-dark px-3 py-1"><?php echo $o['app_count']; ?> applicant(s)</span>
                                    </a>
                                </td>
                                <td>
                                    <?php if ($o['status'] === 'open'): ?>
                                        <span class="badge bg-success-subtle text-success px-2 py-1">Open</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-subtle text-danger px-2 py-1">Closed</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: right;">
                                    <a href="opportunities.php?action=applications&id=<?php echo $o['id']; ?>" class="btn btn-sm btn-outline-info me-1" title="View Applications">
                                        <i class="bi bi-people"></i>
                                    </a>
                                    <a href="opportunities.php?action=edit&id=<?php echo $o['id']; ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="opportunities.php?action=delete&id=<?php echo $o['id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this opportunity? All associated applications will also be deleted.');">
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
                    <?php echo ($action === 'add') ? 'Create Opportunity' : 'Edit Opportunity'; ?>
                </h5>
                
                <form action="" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold text-secondary">Title</label>
                            <input type="text" class="form-control form-control-premium" name="title" value="<?php echo escape($opportunity['title'] ?? ''); ?>" required placeholder="e.g. MEAL Intern / Community Volunteer">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-secondary">Type</label>
                            <select class="form-select form-control-premium" name="type" required>
                                <option value="">— Select —</option>
                                <option value="volunteer" <?php echo (isset($opportunity) && $opportunity['type'] === 'volunteer') ? 'selected' : ''; ?>>Volunteer</option>
                                <option value="intern" <?php echo (isset($opportunity) && $opportunity['type'] === 'intern') ? 'selected' : ''; ?>>Intern</option>
                                <option value="expert" <?php echo (isset($opportunity) && $opportunity['type'] === 'expert') ? 'selected' : ''; ?>>Expert</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-semibold text-secondary">Description</label>
                            <textarea class="form-control form-control-premium" name="description" rows="5" required placeholder="Describe the role, responsibilities, and expectations..."><?php echo escape($opportunity['description'] ?? ''); ?></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-semibold text-secondary">Requirements / Qualifications</label>
                            <textarea class="form-control form-control-premium" name="requirements" rows="4" placeholder="List required qualifications, skills, and experience..."><?php echo escape($opportunity['requirements'] ?? ''); ?></textarea>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-secondary">Location</label>
                            <input type="text" class="form-control form-control-premium" name="location" value="<?php echo escape($opportunity['location'] ?? ''); ?>" placeholder="e.g. Hyderabad, Sindh">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-secondary">Application Deadline</label>
                            <input type="date" class="form-control form-control-premium" name="deadline" value="<?php echo escape($opportunity['deadline'] ?? ''); ?>">
                            <div class="form-text small text-muted">Leave blank for rolling applications.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-secondary">Status</label>
                            <select class="form-select form-control-premium" name="status">
                                <option value="open" <?php echo (!isset($opportunity) || $opportunity['status'] === 'open') ? 'selected' : ''; ?>>Open</option>
                                <option value="closed" <?php echo (isset($opportunity) && $opportunity['status'] === 'closed') ? 'selected' : ''; ?>>Closed</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top text-end">
                        <button type="submit" class="btn btn-premium-orange px-4">
                            <i class="bi bi-save me-1"></i> Save Opportunity
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php elseif ($action === 'applications' && $id > 0): ?>
    <?php
    $stmt = $pdo->prepare("SELECT * FROM `opportunities` WHERE `id` = ?");
    $stmt->execute([$id]);
    $opp = $stmt->fetch();
    if (!$opp) {
        flash('alert', 'Opportunity not found.', 'danger');
        redirect('opportunities.php');
    }

    $stmt = $pdo->prepare("SELECT * FROM `applications` WHERE `opportunity_id` = ? ORDER BY `created_at` DESC");
    $stmt->execute([$id]);
    $apps = $stmt->fetchAll();
    ?>
    <div class="admin-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="admin-title font-outfit mb-1">Applications for: <?php echo escape($opp['title']); ?></h5>
                <span class="badge bg-<?php echo ($opp['type'] === 'volunteer') ? 'info' : (($opp['type'] === 'intern') ? 'primary' : 'warning'); ?> text-capitalize"><?php echo escape($opp['type']); ?></span>
            </div>
            <span class="text-muted small"><?php echo count($apps); ?> total application(s)</span>
        </div>

        <?php if (empty($apps)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 mb-2 d-block"></i>
                No applications received yet.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-premium align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Applicant Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Cover Letter</th>
                            <th>Resume</th>
                            <th style="width: 140px;">Applied On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($apps as $a): ?>
                            <tr>
                                <td><strong><?php echo escape($a['name']); ?></strong></td>
                                <td><a href="mailto:<?php echo escape($a['email']); ?>" class="text-decoration-none"><?php echo escape($a['email']); ?></a></td>
                                <td><?php echo escape($a['phone'] ?: '—'); ?></td>
                                <td>
                                    <?php if ($a['cover_letter']): ?>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#coverModal<?php echo $a['id']; ?>">
                                            <i class="bi bi-eye me-1"></i> View
                                        </button>
                                        <div class="modal fade" id="coverModal<?php echo $a['id']; ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h6 class="modal-title">Cover Letter — <?php echo escape($a['name']); ?></h6>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <pre class="text-wrap" style="white-space: pre-wrap; font-family: inherit;"><?php echo escape($a['cover_letter']); ?></pre>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($a['resume_path']): ?>
                                        <a href="<?php echo SITE_URL . escape($a['resume_path']); ?>" class="btn btn-sm btn-outline-success" target="_blank">
                                            <i class="bi bi-file-earmark-pdf me-1"></i> Download
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted small">—</span>
                                    <?php endif; ?>
                                </td>
                                <td><small class="text-muted"><?php echo date('M j, Y g:i A', strtotime($a['created_at'])); ?></small></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php
require_once __DIR__ . '/admin_footer.php';
?>
