<?php
/**
 * MMCS SERVICE DETAIL PAGE
 * ========================
 * Shows full description of a single service, plus other services sidebar.
 * Accessed via ?id=N from the services grid.
 * 
 * Data table: `services`
 */

require_once dirname(__DIR__) . '/includes/functions.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    redirect(SITE_URL . 'public/services.php');
}

// Fetch selected service
try {
    $stmt = $pdo->prepare("SELECT * FROM `services` WHERE `id` = ? AND `is_active` = 1");
    $stmt->execute([$id]);
    $service = $stmt->fetch();
    
    if (!$service) {
        flash('alert', 'The requested service was not found or is no longer active.', 'danger');
        redirect(SITE_URL . 'public/services.php');
    }
    
    // Fetch other active services for navigation widget
    $stmt = $pdo->prepare("SELECT `id`, `title` FROM `services` WHERE `id` != ? AND `is_active` = 1 ORDER BY `sort_order` ASC LIMIT 5");
    $stmt->execute([$id]);
    $other_services = $stmt->fetchAll();
    
} catch (PDOException $e) {
    redirect(SITE_URL . 'public/services.php');
}

$page_title = $service['title'];
$page_desc = substr($service['description'] ?? '', 0, 150);

require_once dirname(__DIR__) . '/includes/header.php';
?>

<!-- Inner Header Banner -->
<section class="py-5 bg-navy text-white text-center">
    <div class="container py-4">
        <h1 class="font-outfit text-white tracking-tight fw-bold mb-2"><?php echo escape($service['title']); ?></h1>
        <p class="text-gray lead mb-0">Detailed scope and consultancy parameters.</p>
    </div>
</section>

<!-- Detail Content & Sidebar -->
<section class="py-5">
    <div class="container py-4">
        <div class="row g-5">
            <!-- Main Content Area -->
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-orange bg-opacity-10 text-orange rounded-3 p-3 me-3 fs-3">
                        <i class="bi <?php echo escape($service['icon_path'] ?: 'bi-gear'); ?>"></i>
                    </div>
                    <h2 class="font-outfit mb-0"><?php echo escape($service['title']); ?> Scope of Work</h2>
                </div>

                <div class="lh-lg text-secondary mb-5" style="white-space: pre-wrap; font-size: 16px;">
                    <?php echo escape($service['description']); ?>
                </div>

                <!-- Sub-services lists or deliverables (dummy details for premium look) -->
                <div class="border-top pt-4">
                    <h4 class="font-outfit mb-3 text-dark">Typical Project Deliverables</h4>
                    <div class="row g-3">
                        <div class="col-md-6 d-flex align-items-start">
                            <i class="bi bi-patch-check-fill text-orange me-2 mt-1"></i>
                            <div>
                                <strong>Detailed Inception Report</strong>
                                <p class="small text-muted mb-0">Methodology, sampling frameworks, and field work plan outlines.</p>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-start">
                            <i class="bi bi-patch-check-fill text-orange me-2 mt-1"></i>
                            <div>
                                <strong>Mobile Data Collection Tools</strong>
                                <p class="small text-muted mb-0">Secure cloud forms built using KoBoToolbox or ODK aggregates.</p>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-start">
                            <i class="bi bi-patch-check-fill text-orange me-2 mt-1"></i>
                            <div>
                                <strong>Clean Audited Dataset</strong>
                                <p class="small text-muted mb-0">SPSS/Excel formatted clean files ready for donor audits.</p>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-start">
                            <i class="bi bi-patch-check-fill text-orange me-2 mt-1"></i>
                            <div>
                                <strong>Final Narrative Review Report</strong>
                                <p class="small text-muted mb-0">Evidence-rich analysis including infographics and case studies.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Widgets -->
            <div class="col-lg-4">
                <!-- Request Quote Box -->
                <div class="p-4 rounded-4 bg-navy text-white mb-4">
                    <h5 class="font-outfit text-white mb-3"><i class="bi bi-file-earmark-pdf-fill me-2 text-orange"></i>Need a Quote?</h5>
                    <p class="small text-gray mb-4">
                        Request a customized operational or financial proposal matching your project requirements.
                    </p>
                    <a href="<?php echo SITE_URL; ?>public/inquiry.php?service=<?php echo urlencode($service['title']); ?>" class="btn btn-orange w-100 rounded-pill font-outfit py-2 shadow-sm">
                        Get Proposal Quote
                    </a>
                </div>

                <!-- Navigation widget -->
                <div class="p-4 rounded-4 bg-light border">
                    <h5 class="font-outfit text-dark mb-3">Other Services</h5>
                    <div class="list-group list-group-flush bg-transparent">
                        <?php foreach ($other_services as $os): ?>
                            <a href="<?php echo SITE_URL; ?>public/service-detail.php?id=<?php echo $os['id']; ?>" class="list-group-item list-group-item-action bg-transparent px-0 border-bottom d-flex justify-content-between align-items-center">
                                <span class="small text-secondary"><?php echo escape($os['title']); ?></span>
                                <i class="bi bi-chevron-right text-orange fs-7"></i>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once dirname(__DIR__) . '/includes/footer.php';
?>
