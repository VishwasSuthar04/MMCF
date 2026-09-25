<?php
/**
 * MMCS ADMIN PANEL LAYOUT — HEADER
 * =================================
 * Loads authentication, renders the sidebar nav, top bar, and opens
 * the .main-content wrapper. Every admin page includes this file.
 * 
 * SIDEBAR NAV ITEMS are defined in the <ul class="sidebar-nav"> below.
 * To add a new menu item, copy an <li> block and set the href + active check.
 * 
 * STYLES are inlined in a <style> block. To change admin colours, update
 * the :root variables at lines 25-33.
 * 
 * DESIGN & DEVELOPMENT: Vishwas Suthar
 */

require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/includes/auth.php';

$admin_page = basename($_SERVER['PHP_SELF']);  // Used to highlight active sidebar item
$company_name = get_setting('company_name');

send_security_headers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | <?php echo escape($company_name); ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?php echo SITE_URL; ?>favicon.xml">
    <link rel="alternate icon" href="<?php echo SITE_URL; ?>favicon.ico">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Admin panel custom styles -->
    <style>
        :root {
            --navy-dark: #0f172a;
            --navy-sidebar: #1e293b;
            --orange-primary: #FF5C00;      /* Brand colour */
            --orange-hover: #cc4a00;
            --slate-gray: #64748b;
            --light-bg: #f8fafc;
            --border-color: #e2e8f0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg);
            color: #1e293b;
            overflow-x: hidden;
        }

        .font-outfit {
            font-family: 'Outfit', sans-serif;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            background-color: var(--navy-sidebar);
            height: 100vh;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 24px 0;
            width: 260px;
            box-shadow: 4px 0 10px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .sidebar-brand {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            color: #ffffff;
            font-size: 20px;
            padding: 0 24px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
        }

        .sidebar-brand i {
            color: var(--orange-primary);
            margin-right: 8px;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
            flex: 1;
            overflow-y: auto;
        }

        .sidebar-nav-item a {
            display: flex;
            align-items: center;
            padding: 12px 24px;
            color: #94a3b8;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .sidebar-nav-item a i {
            font-size: 18px;
            margin-right: 12px;
            transition: transform 0.3s ease;
        }

        .sidebar-nav-item a:hover {
            color: #ffffff;
            background-color: rgba(255,255,255,0.03);
        }

        .sidebar-nav-item a:hover i {
            transform: translateX(3px);
        }

        .sidebar-nav-item.active a {
            color: #ffffff;
            background-color: rgba(234, 88, 12, 0.1);
            border-left-color: var(--orange-primary);
            font-weight: 600;
        }

        .sidebar-nav-item.active a i {
            color: var(--orange-primary);
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: 260px;
            padding: 40px;
            min-height: 100vh;
        }

        .admin-navbar {
            background-color: #ffffff;
            border-bottom: 1px solid var(--border-color);
            padding: 16px 40px;
            margin: -40px -40px 40px -40px;
        }

        .admin-card {
            background-color: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02), 0 2px 4px -1px rgba(0,0,0,0.01);
            margin-bottom: 30px;
        }

        .admin-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            color: var(--navy-dark);
            margin-bottom: 8px;
        }

        /* ===== TABLES & INPUTS ===== */
        .table-premium th {
            background-color: var(--light-bg);
            color: var(--slate-gray);
            font-weight: 600;
            border-bottom: 2px solid var(--border-color);
            padding: 14px;
        }

        .table-premium td {
            padding: 14px;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
        }

        .form-control-premium {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 10px 14px;
            transition: all 0.3s ease;
        }

        .form-control-premium:focus {
            border-color: var(--orange-primary);
            box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.15);
        }

        .btn-premium-orange {
            background-color: var(--orange-primary);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-premium-orange:hover {
            background-color: var(--orange-hover);
            color: #ffffff;
            transform: translateY(-1px);
        }

        .status-badge {
            font-size: 11px;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 50px;
            text-transform: uppercase;
        }

        .status-new { background-color: #dbeafe; color: #1e40af; }
        .status-read { background-color: #f1f5f9; color: #475569; }
        .status-replied { background-color: #dcfce7; color: #15803d; }
        .status-pending { background-color: #fef9c3; color: #854d0e; }
        .status-inprogress { background-color: #ffedd5; color: #c2410c; }
        .status-closed { background-color: #dcfce7; color: #15803d; }

        /* Mobile responsive */
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 20px; }
            .admin-navbar { margin: -20px -20px 20px -20px; padding: 16px 20px; }
        }
    </style>
</head>
<body>

    <!-- ===== ADMIN SIDEBAR ===== -->
    <div class="sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <i class="bi bi-shield-lock-fill"></i>
            <span>MMCS Console</span>
        </div>
        
        <!-- Sidebar navigation — active item is auto-highlighted via $admin_page -->
        <ul class="sidebar-nav">
            <li class="sidebar-nav-item <?php echo ($admin_page === 'dashboard.php') ? 'active' : ''; ?>">
                <a href="dashboard.php"><i class="bi bi-speedometer2"></i>Dashboard</a>
            </li>
            <li class="sidebar-nav-item <?php echo ($admin_page === 'services.php') ? 'active' : ''; ?>">
                <a href="services.php"><i class="bi bi-gear-fill"></i>Services</a>
            </li>
            <li class="sidebar-nav-item <?php echo ($admin_page === 'portfolio.php') ? 'active' : ''; ?>">
                <a href="portfolio.php"><i class="bi bi-image"></i>Portfolio</a>
            </li>
            <li class="sidebar-nav-item <?php echo ($admin_page === 'team.php') ? 'active' : ''; ?>">
                <a href="team.php"><i class="bi bi-people-fill"></i>Experts / Team</a>
            </li>
            <li class="sidebar-nav-item <?php echo ($admin_page === 'organogram.php') ? 'active' : ''; ?>">
                <a href="organogram.php"><i class="bi bi-diagram-3-fill"></i>Organogram</a>
            </li>
            <li class="sidebar-nav-item <?php echo ($admin_page === 'blog.php') ? 'active' : ''; ?>">
                <a href="blog.php"><i class="bi bi-journal-text"></i>Blog Posts</a>
            </li>
            <li class="sidebar-nav-item <?php echo ($admin_page === 'opportunities.php') ? 'active' : ''; ?>">
                <a href="opportunities.php"><i class="bi bi-megaphone-fill"></i>Opportunities</a>
            </li>
            <li class="sidebar-nav-item <?php echo ($admin_page === 'inquiries.php') ? 'active' : ''; ?>">
                <a href="inquiries.php">
                    <i class="bi bi-envelope-fill"></i>Inquiries & Msg
                </a>
            </li>
            <li class="sidebar-nav-item <?php echo ($admin_page === 'quotes.php') ? 'active' : ''; ?>">
                <a href="quotes.php"><i class="bi bi-file-earmark-pdf-fill"></i>Quote Requests</a>
            </li>
            <li class="sidebar-nav-item <?php echo ($admin_page === 'testimonials.php') ? 'active' : ''; ?>">
                <a href="testimonials.php"><i class="bi bi-chat-quote-fill"></i>Testimonials</a>
            </li>
            <li class="sidebar-nav-item <?php echo ($admin_page === 'partners.php') ? 'active' : ''; ?>">
                <a href="partners.php"><i class="bi bi-building"></i>Partners / Clients</a>
            </li>
            <li class="sidebar-nav-item <?php echo ($admin_page === 'settings.php') ? 'active' : ''; ?>">
                <a href="settings.php"><i class="bi bi-sliders"></i>Site Settings</a>
            </li>
            <!-- Logout — separate from main nav items -->
            <li class="sidebar-nav-item mt-4">
                <a href="dashboard.php?action=logout" class="text-danger-emphasis"><i class="bi bi-box-arrow-right"></i>Sign Out</a>
            </li>
        </ul>
    </div>

    <!-- ===== MAIN WRAPPER ===== -->
    <div class="main-content">
        <!-- Top bar: sidebar toggle + logged-in user + "View Site" link -->
        <header class="admin-navbar d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-secondary d-lg-none me-3" type="button" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <h5 class="mb-0 fw-semibold text-slate font-outfit d-none d-sm-block">Console Admin Desk</h5>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <span class="small text-muted d-none d-md-block">Active: <strong><?php echo escape($_SESSION['admin_username']); ?></strong></span>
                <a href="<?php echo SITE_URL; ?>public/index.php" class="btn btn-sm btn-outline-primary px-3 rounded-pill" target="_blank">
                    <i class="bi bi-globe me-1"></i>View Site
                </a>
            </div>
        </header>
        
        <?php render_flash('alert'); ?>
