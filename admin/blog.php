<?php
/**
 * MMCS ADMIN — BLOG POSTS CRUD
 * ==============================
 * Manage blog articles and resources on the public Blog page.
 * Actions: list, add, edit, delete, toggle status (draft / published).
 * 
 * Data table: `blog_posts` (id, title, content, category, file_path, published_at, status)
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
    $content = trim($_POST['content'] ?? '');
    $category = trim($_POST['category'] ?? 'Research');
    $status = trim($_POST['status'] ?? 'draft');
    
    // Existing attachment
    $existing_file = $_POST['existing_file'] ?? '';

    if (empty($title) || empty($content)) {
        $error = 'Blog Title and Content are required.';
    } else {
        // Handle PDF attachment upload
        $uploaded_file = $existing_file;
        if (isset($_FILES['attachment_file']) && $_FILES['attachment_file']['error'] === UPLOAD_ERR_OK) {
            $uploaded_path = upload_file($_FILES['attachment_file'], 'blog', 'pdf');
            if ($uploaded_path !== false) {
                $uploaded_file = $uploaded_path;
            }
        }

        try {
            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO `blog_posts` (`title`, `content`, `category`, `file_path`, `status`) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$title, $content, $category, $uploaded_file, $status]);
                flash('alert', 'Blog post created successfully!', 'success');
                redirect('blog.php');
            } elseif ($action === 'edit' && $id > 0) {
                $stmt = $pdo->prepare("UPDATE `blog_posts` SET `title` = ?, `content` = ?, `category` = ?, `file_path` = ?, `status` = ? WHERE `id` = ?");
                $stmt->execute([$title, $content, $category, $uploaded_file, $status, $id]);
                flash('alert', 'Blog post updated successfully!', 'success');
                redirect('blog.php');
            }
        } catch (PDOException $e) {
            $error = 'A database error occurred. Please try again.';
        }
    }
}

// Handle Delete Operation
if ($action === 'delete' && $id > 0) {
    try {
        // Get attachment file path to unlink
        $stmt = $pdo->prepare("SELECT `file_path` FROM `blog_posts` WHERE `id` = ?");
        $stmt->execute([$id]);
        $file_path = $stmt->fetchColumn();
        
        if ($file_path) {
            $full_path = dirname(__DIR__) . '/' . $file_path;
            if (file_exists($full_path)) {
                unlink($full_path);
            }
        }

        $stmt = $pdo->prepare("DELETE FROM `blog_posts` WHERE `id` = ?");
        $stmt->execute([$id]);
        flash('alert', 'Blog post deleted successfully!', 'success');
    } catch (PDOException $e) {
        flash('alert', 'Failed to delete blog post. Please try again.', 'danger');
    }
    redirect('blog.php');
}

// Load data for editing
$post = null;
if ($action === 'edit' && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM `blog_posts` WHERE `id` = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch();
    if (!$post) {
        flash('alert', 'Blog post not found.', 'danger');
        redirect('blog.php');
    }
}

// Load all blog posts for list view
$posts = [];
if ($action === 'list') {
    try {
        $stmt = $pdo->query("SELECT * FROM `blog_posts` ORDER BY `published_at` DESC, `id` DESC");
        $posts = $stmt->fetchAll();
    } catch (PDOException $e) {
        $error = 'Failed to load posts. Please try again.';
    }
}
?>

<?php require_once __DIR__ . '/admin_header.php'; ?>

<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h2 class="admin-title font-outfit">Manage Blog & Resources</h2>
        <p class="text-muted">Publish research papers, guides, company news, and attach PDF resources.</p>
    </div>
    <div class="col-sm-6 text-sm-end">
        <?php if ($action === 'list'): ?>
            <a href="blog.php?action=add" class="btn btn-premium-orange px-4 py-2 font-outfit fw-semibold">
                <i class="bi bi-pencil-fill me-1"></i> Write New Post
            </a>
        <?php else: ?>
            <a href="blog.php" class="btn btn-outline-secondary px-4 py-2 font-outfit fw-medium">
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
        <?php if (empty($posts)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-journal-x fs-1 mb-2 d-block"></i>
                No blog posts created yet. Click "Write New Post" to start.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-premium align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Post Title</th>
                            <th>Category</th>
                            <th>Attachment Resource</th>
                            <th>Publish Date</th>
                            <th style="width: 120px;">Status</th>
                            <th style="width: 150px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $p): ?>
                            <tr>
                                <td>
                                    <strong><?php echo escape($p['title']); ?></strong>
                                </td>
                                <td><span class="badge bg-secondary bg-opacity-10 text-secondary-emphasis px-2 py-1"><?php echo escape($p['category']); ?></span></td>
                                <td>
                                    <?php if ($p['file_path']): ?>
                                        <a href="<?php echo SITE_URL . escape($p['file_path']); ?>" target="_blank" class="btn btn-xs btn-outline-info py-1 px-2 rounded small">
                                            <i class="bi bi-filetype-pdf me-1 text-danger"></i> PDF File
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted small">None</span>
                                    <?php endif; ?>
                                </td>
                                <td><small class="text-muted"><?php echo escape(date('M d, Y', strtotime($p['published_at']))); ?></small></td>
                                <td>
                                    <?php if ($p['status'] === 'published'): ?>
                                        <span class="badge bg-success-subtle text-success px-2 py-1">Published</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning-subtle text-warning px-2 py-1">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: right;">
                                    <a href="blog.php?action=edit&id=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="blog.php?action=delete&id=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this blog post?');">
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
                    <?php echo ($action === 'add') ? 'Write New Blog Post' : 'Edit Blog Post'; ?>
                </h5>
                
                <form action="" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="existing_file" value="<?php echo escape($post['file_path'] ?? ''); ?>">
                    
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold text-secondary">Blog Post Title</label>
                            <input type="text" class="form-control form-control-premium" name="title" value="<?php echo escape($post['title'] ?? ''); ?>" required placeholder="e.g. Integrating Digital Solutions in WASH Capacity Building">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-secondary">Category</label>
                            <select class="form-select form-control-premium" name="category">
                                <option value="Research" <?php echo (isset($post) && $post['category'] === 'Research') ? 'selected' : ''; ?>>Research Studies</option>
                                <option value="Policy" <?php echo (isset($post) && $post['category'] === 'Policy') ? 'selected' : ''; ?>>Policy Briefs</option>
                                <option value="Guides" <?php echo (isset($post) && $post['category'] === 'Guides') ? 'selected' : ''; ?>>Practical Guides</option>
                                <option value="News" <?php echo (isset($post) && $post['category'] === 'News') ? 'selected' : ''; ?>>Company News</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-semibold text-secondary">Blog Content</label>
                            <textarea class="form-control form-control-premium" name="content" rows="12" required placeholder="Write the main blog body or research paper abstract here..."><?php echo escape($post['content'] ?? ''); ?></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Attach PDF/Resource File</label>
                            <input type="file" class="form-control form-control-premium" name="attachment_file" accept=".pdf,application/pdf">
                            <div class="form-text small text-muted">Upload reports or policy papers. Maximum size 5MB. Format: PDF only.</div>
                            
                            <?php if (!empty($post['file_path'])): ?>
                                <div class="mt-3">
                                    <span class="small text-muted d-block mb-1">Current Attachment:</span>
                                    <a href="<?php echo SITE_URL . escape($post['file_path']); ?>" target="_blank" class="btn btn-sm btn-light border">
                                        <i class="bi bi-filetype-pdf text-danger me-1"></i> Open PDF
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-secondary">Publishing Status</label>
                            <select class="form-select form-control-premium" name="status">
                                <option value="draft" <?php echo (isset($post) && $post['status'] === 'draft') ? 'selected' : ''; ?>>Draft (Hidden from site)</option>
                                <option value="published" <?php echo (isset($post) && $post['status'] === 'published') ? 'selected' : ''; ?>>Published (Live on site)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top text-end">
                        <button type="submit" class="btn btn-premium-orange px-4">
                            <i class="bi bi-save me-1"></i> Save Post
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
