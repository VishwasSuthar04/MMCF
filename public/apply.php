<?php
$page_title = "Apply for Opportunity";
$page_desc = "Submit your application for a position at MM Consultancy Solutions.";

require_once dirname(__DIR__) . '/includes/header.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$opportunity = null;
if ($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM `opportunities` WHERE `id` = ? AND `status` = 'open' AND (`deadline` IS NULL OR `deadline` >= CURDATE())");
    $stmt->execute([$id]);
    $opportunity = $stmt->fetch();
}

if (!$opportunity) {
    ?>
    <section class="py-5 bg-navy text-white text-center">
        <div class="container py-4">
            <h1 class="font-outfit text-white tracking-tight fw-bold mb-2">Opportunity Not Found</h1>
            <p class="text-gray lead mb-0">This position is no longer accepting applications.</p>
            <a href="<?php echo SITE_URL; ?>public/get-involved.php" class="btn btn-orange px-4 py-2 rounded-pill fw-semibold text-white mt-4">
                <i class="bi bi-arrow-left me-1"></i> View All Opportunities
            </a>
        </div>
    </section>
    <?php
    require_once dirname(__DIR__) . '/includes/footer.php';
    exit;
}

$submitted = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf_token();
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $cover_letter = trim($_POST['cover_letter'] ?? '');

    if (empty($name) || empty($email)) {
        $error = 'Name and Email are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $resume_path = null;
        if (isset($_FILES['resume_file']) && $_FILES['resume_file']['error'] === UPLOAD_ERR_OK) {
            $uploaded = upload_file($_FILES['resume_file'], 'applications', 'pdf');
            if ($uploaded !== false) {
                $resume_path = $uploaded;
            } else {
                $error = 'Resume upload failed. Please ensure the file is a PDF under 5MB.';
            }
        }

        if (empty($error)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO `applications` (`opportunity_id`, `name`, `email`, `phone`, `cover_letter`, `resume_path`) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$id, $name, $email, $phone, $cover_letter, $resume_path]);
                $submitted = true;
            } catch (PDOException $e) {
                $error = 'Failed to submit application. Please try again later.';
            }
        }
    }
}
?>

<section class="py-5 bg-navy text-white text-center">
    <div class="container py-4">
        <h1 class="font-outfit text-white tracking-tight fw-bold mb-2">Apply: <?php echo escape($opportunity['title']); ?></h1>
        <p class="text-gray lead mb-0">
            <?php echo ucfirst(escape($opportunity['type'])); ?> position
            <?php if ($opportunity['location']): ?> &bull; <?php echo escape($opportunity['location']); ?><?php endif; ?>
            <?php if ($opportunity['deadline']): ?> &bull; Deadline: <?php echo date('M j, Y', strtotime($opportunity['deadline'])); ?><?php endif; ?>
        </p>
    </div>
</section>

<section class="py-5 bg-gray-soft">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <?php if ($submitted): ?>
                    <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                        <i class="bi bi-check-circle-fill text-success fs-1 mb-3"></i>
                        <h3 class="font-outfit fw-bold mb-2">Application Submitted!</h3>
                        <p class="text-muted mb-4">Thank you, <?php echo escape($name); ?>. We have received your application for <strong><?php echo escape($opportunity['title']); ?></strong>. Our team will review it and get back to you if your profile matches our requirements.</p>
                        <div>
                            <a href="<?php echo SITE_URL; ?>public/get-involved.php" class="btn btn-orange px-4 py-2 rounded-pill fw-semibold text-white me-2">
                                View More Opportunities
                            </a>
                            <a href="<?php echo SITE_URL; ?>public/index.php" class="btn btn-outline-secondary px-4 py-2 rounded-pill fw-medium">
                                Back to Home
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <div class="mb-4">
                            <h5 class="font-outfit fw-bold mb-1"><?php echo escape($opportunity['title']); ?></h5>
                            <p class="text-secondary small mb-0"><?php echo nl2br(escape($opportunity['description'])); ?></p>
                            <?php if ($opportunity['requirements']): ?>
                                <div class="mt-3 p-3 bg-light rounded-3">
                                    <strong class="small text-uppercase text-muted">Requirements:</strong>
                                    <p class="text-secondary small mb-0 mt-1"><?php echo nl2br(escape($opportunity['requirements'])); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-3 py-2 px-3 mb-4">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <span><?php echo escape($error); ?></span>
                            </div>
                        <?php endif; ?>

                        <form action="" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-secondary">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-premium" name="name" value="<?php echo escape($_POST['name'] ?? ''); ?>" required placeholder="Your full name">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-secondary">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control form-control-premium" name="email" value="<?php echo escape($_POST['email'] ?? ''); ?>" required placeholder="your@email.com">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-secondary">Phone</label>
                                    <input type="text" class="form-control form-control-premium" name="phone" value="<?php echo escape($_POST['phone'] ?? ''); ?>" placeholder="03XX-XXXXXXX">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-secondary">Resume / CV (PDF)</label>
                                    <input type="file" class="form-control form-control-premium" name="resume_file" accept=".pdf,application/pdf">
                                    <div class="form-text small text-muted">Max 5MB. PDF only.</div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-semibold text-secondary">Cover Letter / Message</label>
                                    <textarea class="form-control form-control-premium" name="cover_letter" rows="5" placeholder="Tell us why you are interested in this position and how your skills match..."><?php echo escape($_POST['cover_letter'] ?? ''); ?></textarea>
                                </div>
                            </div>
                            <div class="mt-4 pt-3 border-top text-end">
                                <button type="submit" class="btn btn-orange px-5 py-2 rounded-pill fw-semibold text-white">
                                    <i class="bi bi-send-fill me-1"></i> Submit Application
                                </button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php
require_once dirname(__DIR__) . '/includes/footer.php';
?>
