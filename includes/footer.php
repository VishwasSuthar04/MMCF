<?php
/**
 * MMCS GLOBAL FOOTER
 * ==================
 * Closes the <main> tag opened by header.php and renders the full footer
 * (brand, quick links, contact details, social icons, sub-footer).
 * 
 * Settings are fetched from the database via get_setting().
 * To update: go to Admin Panel → Site Settings.
 * 
 * DESIGN & DEVELOPMENT: Vishwas Suthar
 */

require_once dirname(__DIR__) . '/config.php';
require_once __DIR__ . '/functions.php';

// --- Load settings from database (edit via admin/settings.php) ---
$company_name = get_setting('company_name');
$phone        = get_setting('phone');
$email        = get_setting('email');
$address      = get_setting('address');        // Head office
$field_address = get_setting('field_address'); // Liaison / field office
$footer_text  = get_setting('footer_text');

$social_linkedin = get_setting('social_linkedin');
$social_twitter  = get_setting('social_twitter');
$social_facebook = get_setting('social_facebook');
?>
    </main> <!-- /.main — opened by includes/header.php -->

    <!-- ================================================================ -->
    <!--  FOOTER — 4-column grid: Brand / Services / Navigation / Contact -->
    <!-- ================================================================ -->
    <footer class="bg-dark-navy text-gray pt-5 pb-3">
        <div class="container">
            <div class="row g-4 mb-4">
                
                <!-- ---- COL 1: Brand Profile ---- -->
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-briefcase-fill text-orange me-2 fs-3"></i>
                        <h4 class="font-outfit text-white fw-bold mb-0"><?php echo escape($company_name); ?></h4>
                    </div>
                    <p class="small text-gray-muted">
                        A one-stop development consultancy platform providing end-to-end project support — from design and proposal development to implementation, MEAL, reporting, research, and impact documentation for NGOs, INGOs, and development partners.
                    </p>
                    <!-- Social media icons — hidden when link is empty -->
                    <div class="d-flex gap-2 mt-3">
                        <?php if ($social_linkedin): ?>
                            <a href="<?php echo escape($social_linkedin); ?>" class="social-icon" target="_blank"><i class="bi bi-linkedin"></i></a>
                        <?php endif; ?>
                        <?php if ($social_twitter): ?>
                            <a href="<?php echo escape($social_twitter); ?>" class="social-icon" target="_blank"><i class="bi bi-twitter-x"></i></a>
                        <?php endif; ?>
                        <?php if ($social_facebook): ?>
                            <a href="<?php echo escape($social_facebook); ?>" class="social-icon" target="_blank"><i class="bi bi-facebook"></i></a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- ---- COL 2: Services Quick Links ---- -->
                <div class="col-lg-2 col-md-6">
                    <h5 class="font-outfit text-white fw-semibold mb-3">Services</h5>
                    <ul class="list-unstyled footer-links">
                        <li><a href="<?php echo SITE_URL; ?>public/services.php">EEPS Model</a></li>
                        <li><a href="<?php echo SITE_URL; ?>public/services.php">MEAL & Evaluations</a></li>
                        <li><a href="<?php echo SITE_URL; ?>public/services.php">Proposal & Grant Writing</a></li>
                        <li><a href="<?php echo SITE_URL; ?>public/services.php">Training & Capacity Building</a></li>
                        <li><a href="<?php echo SITE_URL; ?>public/services.php">Research & Knowledge</a></li>
                    </ul>
                </div>

                <!-- ---- COL 3: Navigation ---- -->
                <div class="col-lg-2 col-md-6">
                    <h5 class="font-outfit text-white fw-semibold mb-3">Navigation</h5>
                    <ul class="list-unstyled footer-links">
                        <li><a href="<?php echo SITE_URL; ?>public/about.php">About Us</a></li>
                        <li><a href="<?php echo SITE_URL; ?>public/portfolio.php">Our Projects</a></li>
                        <li><a href="<?php echo SITE_URL; ?>public/team.php">Our Experts</a></li>
                        <li><a href="<?php echo SITE_URL; ?>public/get-involved.php">Get Involved</a></li>
                        <li><a href="<?php echo SITE_URL; ?>public/blog.php">Blog & News</a></li>
                    </ul>
                </div>

                <!-- ---- COL 4: Contact Details ---- -->
                <div class="col-lg-4 col-md-6">
                    <h5 class="font-outfit text-white fw-semibold mb-3">Contact Desk</h5>
                    <ul class="list-unstyled text-small text-gray-muted footer-contact">
                        <!-- Head Office address (from DB setting) -->
                        <li class="mb-2 d-flex align-items-start">
                            <i class="bi bi-building text-orange me-2 mt-1"></i>
                            <span><strong>Head Office:</strong> <?php echo escape($address); ?></span>
                        </li>
                        <!-- Field Office address (hidden if empty) -->
                        <?php if ($field_address): ?>
                        <li class="mb-2 d-flex align-items-start">
                            <i class="bi bi-geo-alt-fill text-orange me-2 mt-1"></i>
                            <span><strong>Field Office:</strong> <?php echo escape($field_address); ?></span>
                        </li>
                        <?php endif; ?>
                        <!-- Phone -->
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-telephone-fill text-orange me-2"></i>
                            <a href="tel:<?php echo escape($phone); ?>" class="text-gray-muted text-decoration-none"><?php echo escape($phone); ?></a>
                        </li>
                        <!-- Email -->
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-envelope-fill text-orange me-2"></i>
                            <a href="mailto:<?php echo escape($email); ?>" class="text-gray-muted text-decoration-none"><?php echo escape($email); ?></a>
                        </li>
                    </ul>
                </div>
            </div>

            <hr class="border-secondary opacity-25">

            <!-- Sub-footer: copyright + staff gateway link -->
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-1 small text-gray-muted"><?php echo escape($footer_text); ?></p>
                    <p class="mb-0 small text-gray-muted">
                        Designed & Developed by
                        <a href="https://www.linkedin.com/in/vishwassutharuiux" target="_blank" rel="noopener noreferrer" class="text-gray-muted text-decoration-none hover-orange">
                            Vishwas Suthar <i class="bi bi-linkedin ms-1"></i>
                        </a>
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                    <a href="<?php echo SITE_URL; ?>admin/login.php" class="text-gray-muted text-decoration-none small hover-orange">
                        <i class="bi bi-lock-fill me-1"></i>Staff Gateway
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS — add site-wide JS in this file -->
    <script src="<?php echo SITE_URL; ?>assets/js/main.js"></script>
</body>
</html>
