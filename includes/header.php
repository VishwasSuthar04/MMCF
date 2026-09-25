<?php
/**
 * MMCS GLOBAL HEADER
 * ==================
 * Renders <head>, CSS/JS links, and the top navigation bar.
 * Included by every public page via:
 *   require_once dirname(__DIR__) . '/includes/header.php';
 * 
 * Expected globals (set in the page BEFORE requiring this file):
 *   $page_title  (string)  - <title> suffix
 *   $page_desc   (string)  - meta description content
 * 
 * DESIGN & DEVELOPMENT: Vishwas Suthar
 */

require_once dirname(__DIR__) . '/config.php';
require_once __DIR__ . '/functions.php';

$current_page = basename($_SERVER['PHP_SELF']); // Used to highlight active nav link
$company_name = get_setting('company_name');
// Short brand for navbar to prevent overflow with long company name
$nav_brand = 'MM Consultancy Solutions';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <meta http-equiv="X-Frame-Options" content="SAMEORIGIN">
    <title><?php echo isset($page_title) ? escape($page_title) . " | " . escape($company_name) : escape($company_name); ?></title>
    
    <!-- Meta SEO — override $page_desc in each page for custom descriptions -->
    <meta name="description" content="<?php echo isset($page_desc) ? escape($page_desc) : 'MM Consultancy Solutions provides MEAL, research, capacity building, and digital solution consultancy for NGOs, INGOs, and corporates.'; ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?php echo SITE_URL; ?>favicon.xml">
    <link rel="alternate icon" href="<?php echo SITE_URL; ?>favicon.ico">

    <!-- Google Fonts: Inter (body) + Outfit (headings) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS + Icons (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <!-- Custom styles — edit assets/css/style.css to change colors, spacing, etc. -->
    <link href="<?php echo SITE_URL; ?>assets/css/style.css?v=<?php echo time(); ?>" rel="stylesheet">
</head>
<body>

    <!-- ============================================ -->
    <!--  NAVBAR — Sticky top, brand + nav links       -->
    <!--  To add/remove nav items, edit the <ul> below -->
    <!-- ============================================ -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-navy sticky-top py-3">
        <div class="container">
            <!-- Brand / Home link -->
            <a class="navbar-brand d-flex align-items-center" href="<?php echo SITE_URL; ?>public/index.php">
                <img src="<?php echo SITE_URL; ?>uploads/IMMCS_logo.png" alt="MMCS Logo" height="60" class="me-2">
                <span class="font-outfit fw-bold tracking-tight"><?php echo escape($nav_brand); ?></span>
            </a>
            
            <!-- Mobile hamburger toggle -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Navigation links — $current_page highlights the active item -->
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page === 'index.php') ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>public/index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page === 'about.php') ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>public/about.php">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page === 'services.php' || $current_page === 'service-detail.php') ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>public/services.php">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page === 'portfolio.php') ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>public/portfolio.php">Portfolio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page === 'team.php') ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>public/team.php">Our Experts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page === 'get-involved.php' || $current_page === 'apply.php') ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>public/get-involved.php">Get Involved</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page === 'blog.php') ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>public/blog.php">Blog</a>
                    </li>
                    <li class="nav-item me-lg-3">
                        <a class="nav-link <?php echo ($current_page === 'contact.php') ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>public/contact.php">Contact</a>
                    </li>
                    <!-- CTA button — links to the inquiry / quote page -->
                    <li class="nav-item mt-2 mt-lg-0">
                        <a class="btn btn-orange px-4 py-2 rounded-pill fw-semibold text-white tracking-wide shadow-sm" href="<?php echo SITE_URL; ?>public/inquiry.php">
                            Get Quote <i class="bi bi-arrow-right-short ms-1"></i>
                        </a>
                    </li>
                    <!-- Admin shortcut — only visible when logged in -->
                    <?php if (is_logged_in()): ?>
                        <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                            <a class="btn btn-outline-light px-3 py-2 rounded-pill fw-medium text-white shadow-sm" href="<?php echo SITE_URL; ?>admin/dashboard.php">
                                <i class="bi bi-speedometer2 me-1"></i> Admin
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Main content wrapper — closed by includes/footer.php -->
    <main>
