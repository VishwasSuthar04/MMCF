<?php
/**
 * MMCS ADMIN — TEAM MEMBERS CRUD
 * ================================
 * Manage expert/staff profiles shown on the public Our Experts page.
 * Actions: list, add, edit, delete (soft via is_active toggle).
 * 
 * Data table: `team_members` (id, name, role, photo, bio, expertise_tags, is_active)
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
    $role = trim($_POST['role'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $expertise_tags = trim($_POST['expertise_tags'] ?? '');
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Existing photo
    $existing_photo = $_POST['existing_photo'] ?? 'default-avatar.png';

    if (empty($name) || empty($role) || empty($bio)) {
        $error = 'Name, Designation Role, and Bio are required.';
    } else {
        // Handle photo upload
        $uploaded_photo = $existing_photo;
        if (isset($_FILES['photo_file']) && $_FILES['photo_file']['error'] === UPLOAD_ERR_OK) {
            $uploaded_path = upload_file($_FILES['photo_file'], 'team', 'image');
            if ($uploaded_path !== false) {
                $uploaded_photo = $uploaded_path;
            }
        }

        try {
            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO `team_members` (`name`, `role`, `photo`, `bio`, `expertise_tags`, `is_active`) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $role, $uploaded_photo, $bio, $expertise_tags, $is_active]);
                flash('alert', 'Team member added successfully!', 'success');
                redirect('team.php');
            } elseif ($action === 'edit' && $id > 0) {
                $stmt = $pdo->prepare("UPDATE `team_members` SET `name` = ?, `role` = ?, `photo` = ?, `bio` = ?, `expertise_tags` = ?, `is_active` = ? WHERE `id` = ?");
                $stmt->execute([$name, $role, $uploaded_photo, $bio, $expertise_tags, $is_active, $id]);
                flash('alert', 'Team member details updated successfully!', 'success');
                redirect('team.php');
            }
        } catch (PDOException $e) {
            $error = 'A database error occurred. Please try again.';
        }
    }
}

// Handle Delete Operation
if ($action === 'delete' && $id > 0) {
    try {
        // Get photo path to unlink
        $stmt = $pdo->prepare("SELECT `photo` FROM `team_members` WHERE `id` = ?");
        $stmt->execute([$id]);
        $photo_path = $stmt->fetchColumn();
        
        if ($photo_path && $photo_path !== 'default-avatar.png') {
            $full_path = dirname(__DIR__) . '/' . $photo_path;
            if (file_exists($full_path)) {
                unlink($full_path);
            }
        }

        $stmt = $pdo->prepare("DELETE FROM `team_members` WHERE `id` = ?");
        $stmt->execute([$id]);
        flash('alert', 'Team member profile deleted successfully!', 'success');
    } catch (PDOException $e) {
        flash('alert', 'Failed to delete team member. Please try again.', 'danger');
    }
    redirect('team.php');
}

// Load data for editing
$member = null;
if ($action === 'edit' && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM `team_members` WHERE `id` = ?");
    $stmt->execute([$id]);
    $member = $stmt->fetch();
    if (!$member) {
        flash('alert', 'Team member profile not found.', 'danger');
        redirect('team.php');
    }
}

// Load all team members for list view
$team_members = [];
if ($action === 'list') {
    try {
        $stmt = $pdo->query("SELECT * FROM `team_members` ORDER BY `id` DESC");
        $team_members = $stmt->fetchAll();
    } catch (PDOException $e) {
        $error = 'Failed to load team profiles. Please try again.';
    }
}
?>

<?php require_once __DIR__ . '/admin_header.php'; ?>

<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h2 class="admin-title font-outfit">Manage Experts Team</h2>
        <p class="text-muted">Register and update consultant, researcher, and staff profiles displayed in the team list.</p>
    </div>
    <div class="col-sm-6 text-sm-end">
        <?php if ($action === 'list'): ?>
            <a href="team.php?action=add" class="btn btn-premium-orange px-4 py-2 font-outfit fw-semibold">
                <i class="bi bi-person-plus-fill me-1"></i> Add Expert Profile
            </a>
        <?php else: ?>
            <a href="team.php" class="btn btn-outline-secondary px-4 py-2 font-outfit fw-medium">
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
        <?php if (empty($team_members)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-person-bounding-box fs-1 mb-2 d-block"></i>
                No team profiles found. Click "Add Expert Profile" to begin.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-premium align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Photo</th>
                            <th>Name / Designation</th>
                            <th>Expertise Areas</th>
                            <th>Short Bio Snippet</th>
                            <th style="width: 120px;">Status</th>
                            <th style="width: 150px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($team_members as $m): ?>
                            <tr>
                                <td>
                                    <?php 
                                    $photo_src = SITE_URL . (empty($m['photo']) || $m['photo'] === 'default-avatar.png' ? 'assets/images/default-avatar.png' : $m['photo']);
                                    ?>
                                    <img src="<?php echo escape($photo_src); ?>" class="rounded-circle shadow-sm" style="width: 50px; height: 50px; object-fit: cover; border: 2px solid var(--border-color);" alt="Expert Photo">
                                </td>
                                <td>
                                    <strong><?php echo escape($m['name']); ?></strong>
                                    <div class="text-muted small"><?php echo escape($m['role']); ?></div>
                                </td>
                                <td>
                                    <?php 
                                    $tags = explode(',', $m['expertise_tags']);
                                    foreach ($tags as $tag):
                                        if (empty(trim($tag))) continue;
                                    ?>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary-emphasis px-2 py-1 mb-1 me-1"><?php echo escape(trim($tag)); ?></span>
                                    <?php endforeach; ?>
                                </td>
                                <td><small class="text-muted"><?php echo escape(substr($m['bio'], 0, 80)) . (strlen($m['bio']) > 80 ? '...' : ''); ?></small></td>
                                <td>
                                    <?php if ($m['is_active']): ?>
                                        <span class="badge bg-success-subtle text-success px-2 py-1">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-subtle text-danger px-2 py-1">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: right;">
                                    <a href="team.php?action=edit&id=<?php echo $m['id']; ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="team.php?action=delete&id=<?php echo $m['id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this profile?');">
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
                    <?php echo ($action === 'add') ? 'Create Expert Profile' : 'Edit Expert Profile'; ?>
                </h5>
                
                <form action="" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="existing_photo" value="<?php echo escape($member['photo'] ?? 'default-avatar.png'); ?>">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Full Name</label>
                            <input type="text" class="form-control form-control-premium" name="name" value="<?php echo escape($member['name'] ?? ''); ?>" required placeholder="e.g. Dr. Muhammad Ali">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Designation / Role</label>
                            <input type="text" class="form-control form-control-premium" name="role" value="<?php echo escape($member['role'] ?? ''); ?>" required placeholder="e.g. Senior MEAL Specialist / Wash Lead">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-semibold text-secondary">Areas of Expertise (Comma-separated tags)</label>
                            <input type="text" class="form-control form-control-premium" name="expertise_tags" value="<?php echo escape($member['expertise_tags'] ?? ''); ?>" placeholder="e.g. MEAL, Proposal, WASH, Gender, Capacity Building">
                            <div class="form-text small text-muted">Separate terms with a comma to generate search tags on the experts directory.</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-semibold text-secondary">Expert Biography</label>
                            <textarea class="form-control form-control-premium" name="bio" rows="6" required placeholder="Write a summary of academic background, donor project experiences, and specific research profiles..."><?php echo escape($member['bio'] ?? ''); ?></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Profile Image</label>
                            <input type="file" class="form-control form-control-premium" name="photo_file" accept="image/*">
                            <div class="form-text small text-muted">Max size: 5MB. Format: JPG, PNG, WebP.</div>
                        </div>

                        <div class="col-md-6 d-flex align-items-center mt-4 pt-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" <?php echo (!isset($member) || $member['is_active']) ? 'checked' : ''; ?>>
                                <label class="form-check-label fw-medium text-secondary" for="is_active">Publish profile (Active on site)</label>
                            </div>
                        </div>

                        <?php if (!empty($member['photo']) && $member['photo'] !== 'default-avatar.png'): ?>
                            <div class="col-12">
                                <span class="small text-muted d-block mb-1">Current Profile Photo:</span>
                                <img src="<?php echo SITE_URL . escape($member['photo']); ?>" class="img-thumbnail rounded-circle" style="width: 80px; height: 80px; object-fit: cover;" alt="Current Headshot">
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mt-4 pt-3 border-top text-end">
                        <button type="submit" class="btn btn-premium-orange px-4">
                            <i class="bi bi-save me-1"></i> Save Profile
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
