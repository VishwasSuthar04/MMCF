<?php
/**
 * MMCS TEAM / EXPERTS PAGE
 * =========================
 * Shows active team members with their photo, role, bio, and expertise tags.
 * 
 * Data table: `team_members` (is_active = 1)
 */

$page_title = "Our Technical Experts";
$page_desc = "Meet our multidisciplinary team of monitoring experts, proposal writers, capacity trainers, and social development researchers.";

require_once dirname(__DIR__) . '/includes/header.php';

// Fetch active team members
$team = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM `team_members` WHERE `is_active` = 1 ORDER BY `id` DESC");
    $stmt->execute();
    $team = $stmt->fetchAll();
} catch (PDOException $e) {
    // Fail silently
}
?>

<!-- Inner Header Banner -->
<section class="py-5 bg-navy text-white text-center">
    <div class="container py-4">
        <h1 class="font-outfit text-white tracking-tight fw-bold mb-2">Our Experts</h1>
        <p class="text-gray lead mb-0">Meet the consultants and field leaders driving evidence-based research at MMCS.</p>
    </div>
</section>

<!-- Team Grid Section -->
<section class="py-5 bg-gray-soft">
    <div class="container py-4">
        <div class="row g-4">
            <?php if (empty($team)): ?>
                <div class="col-12 text-center text-muted py-5">
                    <i class="bi bi-people fs-1 mb-2 d-block"></i>
                    Team profile information is currently being updated.
                </div>
            <?php else: ?>
                <?php foreach ($team as $m): ?>
                    <div class="col-lg-6 col-md-12">
                        <div class="card border-0 shadow-sm rounded-4 p-4 h-100" style="transition: all 0.3s ease;">
                            <div class="row align-items-center g-4">
                                <div class="col-sm-4 text-center">
                                    <?php 
                                    $photo_src = SITE_URL . (empty($m['photo']) || $m['photo'] === 'default-avatar.png' ? 'assets/images/default-avatar.png' : $m['photo']);
                                    ?>
                                    <img src="<?php echo escape($photo_src); ?>" class="rounded-circle shadow border border-3 border-white img-fluid" style="width: 130px; height: 130px; object-fit: cover;" alt="<?php echo escape($m['name']); ?>" onerror="this.src='https://placehold.co/200x200/0f172a/ffffff?text=MMCS+Expert'">
                                </div>
                                <div class="col-sm-8">
                                    <h4 class="font-outfit text-dark mb-1 fs-5"><?php echo escape($m['name']); ?></h4>
                                    <span class="text-orange small fw-semibold d-block mb-3"><?php echo escape($m['role']); ?></span>
                                    
                                    <p class="text-secondary small mb-3 leading-relaxed">
                                        <?php echo escape($m['bio']); ?>
                                    </p>
                                    
                                    <div class="d-flex flex-wrap gap-1">
                                        <?php 
                                        $tags = explode(',', $m['expertise_tags'] ?? '');
                                        foreach ($tags as $tag):
                                            if (empty(trim($tag))) continue;
                                        ?>
                                            <span class="badge bg-light text-dark px-3 py-1 small border"><?php echo escape(trim($tag)); ?></span>
                                        <?php endforeach; ?>
                                    </div>
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
