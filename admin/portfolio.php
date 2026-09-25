<?php
/**
 * MMCS ADMIN — PORTFOLIO / PROJECTS CRUD
 * ========================================
 * Manage project showcase entries on the public portfolio page.
 * Actions: list, add, edit, delete.
 * 
 * Data table: `projects` (id, title, client, sector, location, year, description, images)
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
    $client = trim($_POST['client'] ?? '');
    $sector = trim($_POST['sector'] ?? 'WASH');
    $location = trim($_POST['location'] ?? '');
    $year = intval($_POST['year'] ?? date('Y'));
    $description = trim($_POST['description'] ?? '');
    
    // Loaded images variable
    $existing_images = $_POST['existing_images'] ?? '';

    if (empty($title) || empty($client) || empty($location) || empty($description)) {
        $error = 'Please fill out all required fields.';
    } else {
        // Handle file upload
        $uploaded_image = $existing_images;
        if (isset($_FILES['project_photo']) && $_FILES['project_photo']['error'] === UPLOAD_ERR_OK) {
            $uploaded_path = upload_file($_FILES['project_photo'], 'projects', 'image');
            if ($uploaded_path !== false) {
                $uploaded_image = $uploaded_path;
            }
        }

        try {
            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO `projects` (`title`, `client`, `sector`, `location`, `year`, `description`, `images`) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$title, $client, $sector, $location, $year, $description, $uploaded_image]);
                flash('alert', 'Project added successfully!', 'success');
                redirect('portfolio.php');
            } elseif ($action === 'edit' && $id > 0) {
                $stmt = $pdo->prepare("UPDATE `projects` SET `title` = ?, `client` = ?, `sector` = ?, `location` = ?, `year` = ?, `description` = ?, `images` = ? WHERE `id` = ?");
                $stmt->execute([$title, $client, $sector, $location, $year, $description, $uploaded_image, $id]);
                flash('alert', 'Project updated successfully!', 'success');
                redirect('portfolio.php');
            }
        } catch (PDOException $e) {
            $error = 'A database error occurred. Please try again.';
        }
    }
}

// Handle Delete Operation
if ($action === 'delete' && $id > 0) {
    try {
        // Get image path to unlink if desired
        $stmt = $pdo->prepare("SELECT `images` FROM `projects` WHERE `id` = ?");
        $stmt->execute([$id]);
        $image_path = $stmt->fetchColumn();
        
        if ($image_path && $image_path !== 'project1.jpg' && $image_path !== 'project2.jpg' && $image_path !== 'project3.jpg') {
            $full_path = dirname(__DIR__) . '/' . $image_path;
            if (file_exists($full_path)) {
                unlink($full_path);
            }
        }

        $stmt = $pdo->prepare("DELETE FROM `projects` WHERE `id` = ?");
        $stmt->execute([$id]);
        flash('alert', 'Project deleted successfully!', 'success');
    } catch (PDOException $e) {
        flash('alert', 'Failed to delete project. Please try again.', 'danger');
    }
    redirect('portfolio.php');
}

// Load data for editing
$project = null;
if ($action === 'edit' && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM `projects` WHERE `id` = ?");
    $stmt->execute([$id]);
    $project = $stmt->fetch();
    if (!$project) {
        flash('alert', 'Project not found.', 'danger');
        redirect('portfolio.php');
    }
}

// Load all projects for list view
$projects = [];
if ($action === 'list') {
    try {
        $stmt = $pdo->query("SELECT * FROM `projects` ORDER BY `year` DESC, `id` DESC");
        $projects = $stmt->fetchAll();
    } catch (PDOException $e) {
        $error = 'Failed to load projects. Please try again.';
    }
}
?>

<?php require_once __DIR__ . '/admin_header.php'; ?>

<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h2 class="admin-title font-outfit">Manage Portfolio Projects</h2>
        <p class="text-muted">Administer the portfolio database of past development and consultation projects.</p>
    </div>
    <div class="col-sm-6 text-sm-end">
        <?php if ($action === 'list'): ?>
            <a href="portfolio.php?action=add" class="btn btn-premium-orange px-4 py-2 font-outfit fw-semibold">
                <i class="bi bi-plus-circle me-1"></i> Add New Project
            </a>
        <?php else: ?>
            <a href="portfolio.php" class="btn btn-outline-secondary px-4 py-2 font-outfit fw-medium">
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
        <?php if (empty($projects)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-image-alt fs-1 mb-2 d-block"></i>
                No projects added to the portfolio yet. Click "Add New Project" to start.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-premium align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 100px;">Photo</th>
                            <th>Project Title</th>
                            <th>Client / Donor</th>
                            <th>Sector</th>
                            <th>Location / Year</th>
                            <th style="width: 150px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $p): ?>
                            <tr>
                                <td>
                                    <?php 
                                    $img_src = SITE_URL . (empty($p['images']) ? 'assets/images/placeholder-project.jpg' : $p['images']);
                                    ?>
                                    <img src="<?php echo escape($img_src); ?>" class="rounded shadow-sm" style="width: 70px; height: 45px; object-fit: cover; border: 1px solid var(--border-color);" alt="Project Image">
                                </td>
                                <td><strong><?php echo escape($p['title']); ?></strong></td>
                                <td><span class="text-secondary fw-medium"><?php echo escape($p['client']); ?></span></td>
                                <td><span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1"><?php echo escape($p['sector']); ?></span></td>
                                <td><small class="text-muted"><?php echo escape($p['location']); ?> (<?php echo escape($p['year']); ?>)</small></td>
                                <td style="text-align: right;">
                                    <a href="portfolio.php?action=edit&id=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="portfolio.php?action=delete&id=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this project?');">
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
        <div class="col-lg-10">
            <div class="admin-card">
                <h5 class="admin-title font-outfit text-primary mb-4">
                    <i class="bi <?php echo ($action === 'add') ? 'bi-plus-circle-fill' : 'bi-pencil-square'; ?> me-2"></i>
                    <?php echo ($action === 'add') ? 'Add New Project' : 'Edit Project Details'; ?>
                </h5>
                
                <form action="" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="existing_images" value="<?php echo escape($project['images'] ?? ''); ?>">
                    
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold text-secondary">Project Title</label>
                            <input type="text" class="form-control form-control-premium" name="title" value="<?php echo escape($project['title'] ?? ''); ?>" required placeholder="e.g. WASH Infrastructure Feasibility Assessment">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-secondary">Sector Tag</label>
                            <select class="form-select form-control-premium" name="sector">
                                <option value="WASH" <?php echo (isset($project) && $project['sector'] === 'WASH') ? 'selected' : ''; ?>>WASH (Water, Sanitation, Hygiene)</option>
                                <option value="Education" <?php echo (isset($project) && $project['sector'] === 'Education') ? 'selected' : ''; ?>>Education Quality</option>
                                <option value="Climate" <?php echo (isset($project) && $project['sector'] === 'Climate') ? 'selected' : ''; ?>>Climate Change & Environment</option>
                                <option value="Gender" <?php echo (isset($project) && $project['sector'] === 'Gender') ? 'selected' : ''; ?>>Gender Development</option>
                                <option value="Research" <?php echo (isset($project) && $project['sector'] === 'Research') ? 'selected' : ''; ?>>Research & Studies</option>
                                <option value="Livelihoods" <?php echo (isset($project) && $project['sector'] === 'Livelihoods') ? 'selected' : ''; ?>>Livelihoods & Governance</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Client / Funder Name</label>
                            <input type="text" class="form-control form-control-premium" name="client" value="<?php echo escape($project['client'] ?? ''); ?>" required placeholder="e.g. UNICEF / WASH NGO Coalition">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-secondary">Geographical Location</label>
                            <input type="text" class="form-control form-control-premium" name="location" value="<?php echo escape($project['location'] ?? ''); ?>" required placeholder="e.g. Islamabad / Sindh, Pakistan">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small fw-semibold text-secondary">Implementation Year</label>
                            <input type="number" class="form-control form-control-premium" name="year" value="<?php echo escape($project['year'] ?? date('Y')); ?>" min="2000" max="2100" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-semibold text-secondary">Project Details & Summary</label>
                            <textarea class="form-control form-control-premium" name="description" rows="8" required placeholder="Outline key milestones, objectives, outcomes, and research baseline details..."><?php echo escape($project['description'] ?? ''); ?></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-semibold text-secondary">Project Feature Photo</label>
                            <input type="file" class="form-control form-control-premium" name="project_photo" accept="image/*">
                            <div class="form-text small text-muted">Upload a banner image for the project card. Maximum size 5MB. Format: JPG, PNG, WebP.</div>
                            
                            <?php if (!empty($project['images'])): ?>
                                <div class="mt-3">
                                    <span class="small text-muted d-block mb-1">Current Image:</span>
                                    <img src="<?php echo SITE_URL . escape($project['images']); ?>" class="img-thumbnail rounded" style="max-height: 120px;" alt="Current Banner">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top text-end">
                        <button type="submit" class="btn btn-premium-orange px-4">
                            <i class="bi bi-save me-1"></i> Save Project
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
