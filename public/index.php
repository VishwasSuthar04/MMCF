<?php
/**
 * MMCS HOMEPAGE
 * =============
 * Hero banner, stats counters, featured services, about callout, testimonial,
 * and client logos. Content is pulled from the database where possible.
 * 
 * SECTIONS (in order):
 *   1. Hero        — tagline + CTA button
 *   2. Stats       — years / projects / clients counters
 *   3. Services    — first 6 active services (from DB)
 *   4. About       — company description + checklist
 *   5. Testimonial — quote block
 *   6. Clients     — placeholder logo strip
 */

$page_title = "Development Consultancy, MEAL & Research Solutions";
$page_desc = "Providing state-of-the-art MEAL, training, research baselines, proposal writing, and digital solutions for international development and NGO sectors.";

require_once dirname(__DIR__) . '/includes/header.php';

// Fetch active services (limit to 6 for the homepage overview)
$services = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM `services` WHERE `is_active` = 1 ORDER BY `sort_order` ASC LIMIT 6");
    $stmt->execute();
    $services = $stmt->fetchAll();
} catch (PDOException $e) {
    // Fail silently in public view
}

// Fetch approved testimonials
$testimonials = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM `testimonials` WHERE `is_approved` = 1 ORDER BY `id` DESC");
    $stmt->execute();
    $testimonials = $stmt->fetchAll();
} catch (PDOException $e) {
    // Fail silently
}
?>

<!-- Hero Banner Section -->
<section class="hero-banner d-flex align-items-center">
    <div class="container position-relative" style="z-index: 2;">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="hero-subtitle mb-2 d-inline-block font-outfit">A One-Stop Development Consultancy Platform</span>
                <h1 class="hero-title font-outfit text-white mb-3">
                    A <span class="text-orange">One-Stop Development Consultancy</span> Platform
                </h1>
                <p class="lead text-gray mb-4 fs-5" style="max-width: 600px;">
                    MMCS provides professional services to NGOs, INGOs, government programs, corporate CSR initiatives, and development partners — delivering integrated solutions across project design, implementation, research, training, and advisory services.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?php echo SITE_URL; ?>public/inquiry.php" class="btn btn-orange btn-lg px-4 py-3 rounded-pill fw-semibold font-outfit shadow">
                        Request Quote <i class="bi bi-arrow-right-short ms-1"></i>
                    </a>
                    <a href="<?php echo SITE_URL; ?>public/services.php" class="btn btn-outline-light btn-lg px-4 py-3 rounded-pill fw-medium font-outfit">
                        Our Services
                    </a>
                </div>
            </div>
            
            <!-- Hero Stats Grid -->
            <div class="col-lg-5">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="stats-badge-card">
                            <div class="stats-number font-outfit"><?php echo escape(get_setting('stats_years')); ?></div>
                            <div class="small text-gray-muted text-uppercase tracking-wider fw-medium">Experience</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stats-badge-card">
                            <div class="stats-number font-outfit"><?php echo escape(get_setting('stats_projects')); ?></div>
                            <div class="small text-gray-muted text-uppercase tracking-wider fw-medium">Projects Done</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stats-badge-card">
                            <div class="stats-number font-outfit"><?php echo escape(get_setting('stats_clients')); ?></div>
                            <div class="small text-gray-muted text-uppercase tracking-wider fw-medium">Satisfied Clients</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stats-badge-card">
                            <div class="stats-number font-outfit text-white">SECP</div>
                            <div class="small text-gray-muted text-uppercase tracking-wider fw-medium">Registered Firm</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Core Services Section -->
<section class="py-5 bg-gray-soft">
    <div class="container py-4">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <span class="text-orange fw-bold text-uppercase tracking-wider small">What We Deliver</span>
                <h2 class="font-outfit fs-2 tracking-tight mt-1 mb-3">Our Service Portfolio</h2>
                <p class="text-muted" style="max-width: 600px; margin: 0 auto;">
                    End-to-end consultancy solutions from concept to completion — one team, one retainer, one accountable partner.
                </p>
            </div>
        </div>

        <div class="row g-4">
            <?php if (empty($services)): ?>
                <div class="col-12 text-center text-muted">No services published at this time.</div>
            <?php else: ?>
                <?php foreach ($services as $s): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="premium-card h-100 d-flex flex-column justify-content-between">
                            <div>
                                <div class="premium-card-icon">
                                    <i class="bi <?php echo escape($s['icon_path'] ?: 'bi-gear'); ?>"></i>
                                </div>
                                <h4 class="font-outfit fs-5 mb-2"><?php echo escape($s['title']); ?></h4>
                                <p class="text-muted small mb-4">
                                    <?php echo escape(substr($s['description'], 0, 140)) . (strlen($s['description']) > 140 ? '...' : ''); ?>
                                </p>
                            </div>
                            <a href="<?php echo SITE_URL; ?>public/service-detail.php?id=<?php echo $s['id']; ?>" class="text-orange text-decoration-none fw-semibold small mt-auto">
                                Read Details <i class="bi bi-chevron-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-5">
            <a href="<?php echo SITE_URL; ?>public/services.php" class="btn btn-outline-orange px-4 py-2 rounded-pill fw-semibold">
                Explore All Services <i class="bi bi-arrow-right-short ms-1"></i>
            </a>
        </div>
    </div>
</section>

<!-- About Callout Section -->
<section class="py-5">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="text-orange fw-bold text-uppercase tracking-wider small">About MMCS</span>
                <h2 class="font-outfit tracking-tight mt-1 mb-4">A Trusted Development Consultancy Partner</h2>
                <p class="text-secondary mb-3">
                    MM Consultancy Solutions (Private) Limited (MMCS) is a development consultancy firm registered with SECP under the Companies Act, 2017. We provide professional services to NGOs, INGOs, government programs, and development partners — delivering integrated solutions across project design, implementation, research, training, and advisory services.
                </p>
                <p class="text-secondary mb-3">
                    Through our End-to-End (E2E) Project Support (EEPS) Model, we cover every stage of the project lifecycle under one retainer, one team, and one accountable partner.
                </p>
                <div class="row g-3 mt-2">
                    <div class="col-sm-6 d-flex">
                        <i class="bi bi-check-circle-fill text-orange me-2 fs-5"></i>
                        <span>End-to-End Project Support (EEPS)</span>
                    </div>
                    <div class="col-sm-6 d-flex">
                        <i class="bi bi-check-circle-fill text-orange me-2 fs-5"></i>
                        <span>MEAL Systems & Evaluations</span>
                    </div>
                    <div class="col-sm-6 d-flex">
                        <i class="bi bi-check-circle-fill text-orange me-2 fs-5"></i>
                        <span>Proposal & Grant Writing</span>
                    </div>
                    <div class="col-sm-6 d-flex">
                        <i class="bi bi-check-circle-fill text-orange me-2 fs-5"></i>
                        <span>Training, Research & Documentation</span>
                    </div>
                </div>
                <div class="mt-4 pt-2">
                    <a href="<?php echo SITE_URL; ?>public/about.php" class="btn btn-navy px-4 py-3 rounded-pill fw-semibold text-white">
                        Learn More About Us
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <!-- Standard visual block with gradients -->
                <div class="p-5 rounded-4 shadow" style="background: linear-gradient(135deg, var(--navy-light) 0%, var(--navy-dark) 100%); color: #ffffff;">
                    <div class="mb-4">
                        <i class="bi bi-quote fs-1 text-orange opacity-50"></i>
                    </div>
                    <h4 class="font-outfit text-white mb-3" style="line-height: 1.4;">"To support development actors with expert knowledge, professional systems, and practical solutions that enhance development impact from concept to completion."</h4>
                    <div class="d-flex align-items-center mt-4">
                        <div class="bg-orange bg-opacity-25 rounded-circle p-2 me-3">
                            <i class="bi bi-person-fill text-orange fs-4"></i>
                        </div>
                        <div>
                            <h6 class="font-outfit mb-0 text-white">Our Mission</h6>
                            <small class="text-gray-muted">MM Consultancy Solutions (Pvt.) Ltd.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Who We Serve Section -->
<section class="py-5">
    <div class="container py-4">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <span class="text-orange fw-bold text-uppercase tracking-wider small">Who We Serve</span>
                <h2 class="font-outfit tracking-tight mt-1 mb-3">Our Clientele & Partners</h2>
                <p class="text-muted" style="max-width: 700px; margin: 0 auto;">
                    NGOs • INGOs • Government programs • Corporate CSR initiatives • Development partners • Research institutions • Social enterprises
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Carousel Section -->
<?php if (!empty($testimonials)): ?>
<section class="py-5 bg-gray-soft">
    <div class="container py-4">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <span class="text-orange fw-bold text-uppercase tracking-wider small">Feedback</span>
                <h2 class="font-outfit tracking-tight mt-1 mb-3">What Our Partners Say</h2>
            </div>
        </div>

        <div id="testimonialsCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($testimonials as $index => $t): ?>
                    <div class="carousel-item <?php echo ($index === 0) ? 'active' : ''; ?>">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="testimonial-bubble">
                                    <p class="text-secondary fs-5 mb-0" style="line-height: 1.6; font-style: italic;">
                                        "<?php echo escape($t['quote']); ?>"
                                    </p>
                                </div>
                                <div class="d-flex align-items-center ms-4">
                                    <?php 
                                    $photo_src = SITE_URL . (empty($t['photo']) || $t['photo'] === 'default-avatar.png' ? 'assets/images/default-avatar.png' : $t['photo']);
                                    ?>
                                    <img src="<?php echo escape($photo_src); ?>" class="rounded-circle shadow-sm me-3" style="width: 55px; height: 55px; object-fit: cover;" alt="Client Headshot">
                                    <div>
                                        <h6 class="font-outfit mb-0 fw-bold"><?php echo escape($t['client_name']); ?></h6>
                                        <small class="text-muted"><?php echo escape($t['org']); ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <button class="carousel-control-prev" type="button" data-bs-target="#testimonialsCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon bg-navy rounded-circle p-3" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#testimonialsCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon bg-navy rounded-circle p-3" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Call To Action Inquiry Card -->
<section class="py-5 bg-navy text-white text-center position-relative overflow-hidden">
    <div class="container py-4 position-relative" style="z-index: 2;">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="font-outfit text-white fs-2 mb-3">Partner with MMCS for Your Next Project</h2>
                <p class="text-gray mb-4 fs-5" style="max-width: 600px; margin: 0 auto;">
                    Ready to request a comprehensive quote or consult with our project experts? Get in touch today.
                </p>
                <a href="<?php echo SITE_URL; ?>public/inquiry.php" class="btn btn-orange btn-lg px-5 py-3 rounded-pill fw-semibold font-outfit shadow">
                    Request NGO Quote <i class="bi bi-file-earmark-pdf-fill ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<?php
require_once dirname(__DIR__) . '/includes/footer.php';
?>
