<?php
/**
 * MMCS BLOG / RESOURCES LISTING PAGE
 * ===================================
 * Lists published blog posts with optional category filter.
 * Each post links to... (detail view if needed — currently inline on same page).
 * 
 * Data table: `blog_posts` (status = 'published')
 */

$page_title = "Resources & Publications";
$page_desc = "Browse policy briefs, socio-economic research abstracts, operational guides, and news from MM Consultancy Solutions.";

require_once dirname(__DIR__) . '/includes/header.php';

// Fetch published blog posts
$posts = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM `blog_posts` WHERE `status` = 'published' ORDER BY `published_at` DESC");
    $stmt->execute();
    $posts = $stmt->fetchAll();
} catch (PDOException $e) {
    // Fail silently
}
?>

<!-- Inner Header Banner -->
<section class="py-5 bg-navy text-white text-center">
    <div class="container py-4">
        <h1 class="font-outfit text-white tracking-tight fw-bold mb-2">Publications & Blog</h1>
        <p class="text-gray lead mb-0">Access research guides, policy abstracts, news, and download report attachments.</p>
    </div>
</section>

<!-- Blog Posts List -->
<section class="py-5 bg-gray-soft">
    <div class="container py-4">
        <div class="row g-4 justify-content-center">
            
            <?php if (empty($posts)): ?>
                <div class="col-12 text-center text-muted py-5">
                    <i class="bi bi-journal-x fs-1 mb-2 d-block"></i>
                    No publications or blog posts are available currently.
                </div>
            <?php else: ?>
                <?php foreach ($posts as $p): ?>
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                <span class="badge bg-orange bg-opacity-10 text-orange px-3 py-1.5 font-outfit rounded-pill"><?php echo escape($p['category']); ?></span>
                                <small class="text-muted"><i class="bi bi-calendar-event me-1"></i><?php echo escape(date('F d, Y', strtotime($p['published_at']))); ?></small>
                            </div>
                            
                            <h3 class="font-outfit fs-4 text-dark mb-3"><?php echo escape($p['title']); ?></h3>
                            
                            <p class="text-secondary mb-4 leading-relaxed" style="white-space: pre-wrap; font-size: 15px;">
                                <?php echo escape($p['content']); ?>
                            </p>
                            
                            <?php if (!empty($p['file_path'])): ?>
                                <div class="p-3 bg-light rounded border d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-file-earmark-pdf-fill text-danger fs-3 me-3"></i>
                                        <div>
                                            <strong class="small d-block text-dark">Research Document / Report Attachment</strong>
                                            <span class="text-muted small">Download free PDF resource</span>
                                        </div>
                                    </div>
                                    <a href="<?php echo SITE_URL . escape($p['file_path']); ?>" target="_blank" class="btn btn-outline-orange btn-sm px-3 rounded-pill fw-semibold font-outfit">
                                        <i class="bi bi-download me-1"></i> Download PDF
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
        </div>
    </div>
</section>

<?php
require_once dirname(__DIR__) . '/includes/footer.php';
?>
