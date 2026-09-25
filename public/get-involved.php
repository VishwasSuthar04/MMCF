<?php
$page_title = "Get Involved";
$page_desc = "Explore career, internship, and volunteer opportunities at MM Consultancy Solutions.";

require_once dirname(__DIR__) . '/includes/header.php';

$opportunities = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM `opportunities` WHERE `status` = 'open' AND (`deadline` IS NULL OR `deadline` >= CURDATE()) ORDER BY FIELD(`type`, 'expert', 'intern', 'volunteer'), `created_at` DESC");
    $stmt->execute();
    $opportunities = $stmt->fetchAll();
} catch (PDOException $e) {
    // fail silently
}

$grouped = ['expert' => [], 'intern' => [], 'volunteer' => []];
foreach ($opportunities as $o) {
    $grouped[$o['type']][] = $o;
}
?>

<section class="py-5 bg-navy text-white text-center">
    <div class="container py-4">
        <h1 class="font-outfit text-white tracking-tight fw-bold mb-2">Get Involved</h1>
        <p class="text-gray lead mb-0">Join MMCS and contribute to impactful development projects across Pakistan.</p>
    </div>
</section>

<section class="py-5 bg-gray-soft">
    <div class="container py-4">

        <?php if (empty($opportunities)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-clock-history fs-1 mb-3 d-block"></i>
                <h4 class="font-outfit">No Open Opportunities Right Now</h4>
                <p class="mb-0">We currently have no open positions. Please check back later or follow us on social media for updates.</p>
            </div>
        <?php else: ?>

            <?php foreach (['expert' => 'Expert Positions', 'intern' => 'Internships', 'volunteer' => 'Volunteer Opportunities'] as $type_key => $type_label): ?>
                <?php if (!empty($grouped[$type_key])): ?>
                    <div class="mb-5">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <?php
                            $icons = ['expert' => 'bi-star-fill text-warning', 'intern' => 'bi-mortarboard-fill text-primary', 'volunteer' => 'bi-heart-fill text-info'];
                            ?>
                            <i class="bi <?php echo $icons[$type_key]; ?> fs-2"></i>
                            <h3 class="font-outfit fw-bold mb-0"><?php echo $type_label; ?></h3>
                        </div>
                        <div class="row g-4">
                            <?php foreach ($grouped[$type_key] as $o): ?>
                                <div class="col-lg-6">
                                    <div class="card border-0 shadow-sm rounded-4 h-100">
                                        <div class="card-body p-4 d-flex flex-column">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h5 class="font-outfit fw-bold text-dark mb-0"><?php echo escape($o['title']); ?></h5>
                                            </div>
                                            <div class="d-flex flex-wrap gap-2 mb-3">
                                                <?php if ($o['location']): ?>
                                                    <span class="small text-muted"><i class="bi bi-geo-alt me-1"></i><?php echo escape($o['location']); ?></span>
                                                <?php endif; ?>
                                                <?php if ($o['deadline']): ?>
                                                    <span class="small <?php echo (strtotime($o['deadline']) < strtotime('+7 days')) ? 'text-danger fw-semibold' : 'text-muted'; ?>">
                                                        <i class="bi bi-calendar me-1"></i>Deadline: <?php echo date('M j, Y', strtotime($o['deadline'])); ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="small text-muted"><i class="bi bi-calendar me-1"></i>Rolling application</span>
                                                <?php endif; ?>
                                            </div>
                                            <p class="text-secondary small mb-3"><?php echo escape(substr($o['description'], 0, 200)) . (strlen($o['description']) > 200 ? '...' : ''); ?></p>
                                            <div class="mt-auto">
                                                <a href="<?php echo SITE_URL; ?>public/apply.php?id=<?php echo $o['id']; ?>" class="btn btn-orange px-4 py-2 rounded-pill fw-semibold text-white">
                                                    Apply Now <i class="bi bi-arrow-right-short ms-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

        <?php endif; ?>
    </div>
</section>

<?php
require_once dirname(__DIR__) . '/includes/footer.php';
?>
