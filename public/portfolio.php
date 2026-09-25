<?php
/**
 * MMCS PORTFOLIO / PROJECTS PAGE
 * ===============================
 * Shows past projects with sector-based filter buttons.
 * Each project card shows title, client, sector, year, and description excerpt.
 * 
 * Data table: `projects`
 */

$page_title = "Our Projects Portfolio";
$page_desc = "Browse through our past consultancy and research projects in WASH, education, climate, and gender sectors.";

require_once dirname(__DIR__) . '/includes/header.php';

// Fetch all projects
$projects = [];
$sectors = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM `projects` ORDER BY `year` DESC, `id` DESC");
    $stmt->execute();
    $projects = $stmt->fetchAll();
    
    // Extract unique sectors for category filtering
    foreach ($projects as $p) {
        $sectors[] = $p['sector'];
    }
    $sectors = array_unique($sectors);
} catch (PDOException $e) {
    // Fail silently
}
?>

<!-- Inner Header Banner -->
<section class="py-5 bg-navy text-white text-center">
    <div class="container py-4">
        <h1 class="font-outfit text-white tracking-tight fw-bold mb-2">Our Portfolio</h1>
        <p class="text-gray lead mb-0">Explore our past projects and third-party monitoring programs across diverse developmental sectors.</p>
    </div>
</section>

<!-- Projects Gallery Section -->
<section class="py-5 bg-gray-soft">
    <div class="container py-4">
        
        <!-- Filter Buttons (Triggers client-side JS animations in assets/js/main.js) -->
        <?php if (!empty($projects)): ?>
            <div class="portfolio-filters">
                <button type="button" class="filter-btn active" data-filter="all">All Sectors</button>
                <?php foreach ($sectors as $sec): ?>
                    <button type="button" class="filter-btn" data-filter="<?php echo escape($sec); ?>"><?php echo escape($sec); ?></button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Project Grid -->
        <div class="row g-4">
            <?php if (empty($projects)): ?>
                <div class="col-12 text-center text-muted py-5">
                    <i class="bi bi-image fs-1 mb-2 d-block"></i>
                    No portfolio projects available at the moment.
                </div>
            <?php else: ?>
                <?php foreach ($projects as $p): ?>
                    <div class="col-lg-4 col-md-6 project-card-item" data-sector="<?php echo escape($p['sector']); ?>" style="transition: all 0.4s ease;">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden" style="transition: all 0.3s ease;">
                            <?php 
                            $img_src = SITE_URL . (empty($p['images']) ? 'assets/images/placeholder-project.jpg' : $p['images']);
                            ?>
                            <div class="position-relative">
                                <img src="<?php echo escape($img_src); ?>" class="card-img-top" style="height: 220px; object-fit: cover;" alt="<?php echo escape($p['title']); ?>" onerror="this.src='https://placehold.co/600x400/0f172a/ffffff?text=MMCS+Project'">
                                <span class="position-absolute top-0 end-0 bg-orange text-white px-3 py-1 rounded-start-pill small font-outfit fw-semibold mt-3"><?php echo escape($p['sector']); ?></span>
                            </div>
                            <div class="card-body p-4 d-flex flex-column">
                                <span class="small text-muted d-block mb-1"><?php echo escape($p['location']); ?> • <?php echo escape($p['year']); ?></span>
                                <h5 class="card-title font-outfit mb-3"><?php echo escape($p['title']); ?></h5>
                                <p class="card-text text-secondary small mb-4">
                                    <?php echo escape($p['description']); ?>
                                </p>
                                <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                    <span class="small text-muted">Funder:</span>
                                    <strong class="small text-dark"><?php echo escape($p['client']); ?></strong>
                                </div>
                            </div>
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
