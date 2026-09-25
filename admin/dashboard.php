<?php
/**
 * MMCS ADMIN DASHBOARD
 * ====================
 * Main admin landing page. Shows counts from key tables and provides
 * quick links / summary cards.
 * 
 * Also handles the logout action (?action=logout).
 */

require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';

// -----------------------------------------------------------
//  LOGOUT HANDLER — called via sidebar "Sign Out" link
// -----------------------------------------------------------
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    
    session_start();  // Restart just to flash the logout message
    flash('alert', 'You have been logged out successfully.', 'success');
    redirect(SITE_URL . 'admin/login.php');
}

require_once __DIR__ . '/admin_header.php';

// -----------------------------------------------------------
//  STATS — query counts from each table for the dashboard
// -----------------------------------------------------------
try {
    // 1. Total Contact Inquiries
    $stmt = $pdo->query("SELECT COUNT(*) FROM `inquiries` WHERE `type` = 'contact'");
    $count_contacts = $stmt->fetchColumn();

    // 2. Total Quote Requests
    $stmt = $pdo->query("SELECT COUNT(*) FROM `inquiries` WHERE `type` = 'inquiry'");
    $count_quotes = $stmt->fetchColumn();

    // 3. Active Projects count
    $stmt = $pdo->query("SELECT COUNT(*) FROM `projects`");
    $count_projects = $stmt->fetchColumn();

    // 4. Team count
    $stmt = $pdo->query("SELECT COUNT(*) FROM `team_members` WHERE `is_active` = 1");
    $count_team = $stmt->fetchColumn();

    // Fetch 5 most recent contact inquiries
    $stmt = $pdo->query("SELECT * FROM `inquiries` WHERE `type` = 'contact' ORDER BY `created_at` DESC LIMIT 5");
    $recent_contacts = $stmt->fetchAll();

    // Fetch 5 most recent quote requests
    $stmt = $pdo->query("SELECT * FROM `inquiries` WHERE `type` = 'inquiry' ORDER BY `created_at` DESC LIMIT 5");
    $recent_quotes = $stmt->fetchAll();
    
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Failed to load dashboard statistics. Please try again.</div>";
    $count_contacts = $count_quotes = $count_projects = $count_team = 0;
    $recent_contacts = $recent_quotes = [];
}
?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="admin-title font-outfit">Welcome Back, <?php echo escape($_SESSION['admin_username']); ?>!</h2>
        <p class="text-muted">Here is an overview of MMCS operations dashboard.</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <!-- Contacts Count Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-body d-flex align-items-center p-4">
                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3 me-3">
                    <i class="bi bi-envelope-paper-fill fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1 text-uppercase tracking-wider small">Messages</h6>
                    <h3 class="font-outfit fw-bold mb-0"><?php echo $count_contacts; ?></h3>
                </div>
            </div>
            <div class="bg-primary bg-opacity-25 py-2 px-4 text-end">
                <a href="inquiries.php" class="small text-primary text-decoration-none fw-medium">View Inbox <i class="bi bi-arrow-right-short"></i></a>
            </div>
        </div>
    </div>

    <!-- Quote Requests Count Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-body d-flex align-items-center p-4">
                <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3 me-3">
                    <i class="bi bi-file-earmark-pdf-fill fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1 text-uppercase tracking-wider small">Quotes (NGO)</h6>
                    <h3 class="font-outfit fw-bold mb-0"><?php echo $count_quotes; ?></h3>
                </div>
            </div>
            <div class="bg-warning bg-opacity-25 py-2 px-4 text-end">
                <a href="quotes.php" class="small text-warning-emphasis text-decoration-none fw-medium">View Quotes <i class="bi bi-arrow-right-short"></i></a>
            </div>
        </div>
    </div>

    <!-- Projects Count Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-body d-flex align-items-center p-4">
                <div class="bg-success bg-opacity-10 text-success rounded-3 p-3 me-3">
                    <i class="bi bi-check-circle-fill fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1 text-uppercase tracking-wider small">Projects</h6>
                    <h3 class="font-outfit fw-bold mb-0"><?php echo $count_projects; ?></h3>
                </div>
            </div>
            <div class="bg-success bg-opacity-25 py-2 px-4 text-end">
                <a href="portfolio.php" class="small text-success-emphasis text-decoration-none fw-medium">Manage Portfolio <i class="bi bi-arrow-right-short"></i></a>
            </div>
        </div>
    </div>

    <!-- Experts Count Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-body d-flex align-items-center p-4">
                <div class="bg-info bg-opacity-10 text-info rounded-3 p-3 me-3">
                    <i class="bi bi-people-fill fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1 text-uppercase tracking-wider small">Team Experts</h6>
                    <h3 class="font-outfit fw-bold mb-0"><?php echo $count_team; ?></h3>
                </div>
            </div>
            <div class="bg-info bg-opacity-25 py-2 px-4 text-end">
                <a href="team.php" class="small text-info-emphasis text-decoration-none fw-medium">Manage Experts <i class="bi bi-arrow-right-short"></i></a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Messages -->
    <div class="col-xl-6">
        <div class="admin-card">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="admin-title mb-0 font-outfit"><i class="bi bi-chat-left-text me-2 text-primary"></i>Recent Contact Inbox</h5>
                <a href="inquiries.php" class="btn btn-sm btn-outline-secondary px-3 py-1 rounded-pill">View All</a>
            </div>
            
            <?php if (empty($recent_contacts)): ?>
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-chat-left-dots fs-3 mb-2 d-block"></i>
                    No recent inquiries.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="small text-muted border-bottom">
                                <th>From</th>
                                <th>Org</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_contacts as $contact): ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-dark"><?php echo escape($contact['name']); ?></div>
                                        <small class="text-muted"><?php echo escape($contact['email']); ?></small>
                                    </td>
                                    <td><?php echo escape($contact['org'] ?: '—'); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower($contact['status']); ?>">
                                            <?php echo escape($contact['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="inquiries.php?view=<?php echo $contact['id']; ?>" class="btn btn-sm btn-light border" title="Read message">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Quote Requests -->
    <div class="col-xl-6">
        <div class="admin-card">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="admin-title mb-0 font-outfit"><i class="bi bi-file-earmark-spreadsheet me-2 text-warning"></i>Recent Service Inquiries (Quotes)</h5>
                <a href="quotes.php" class="btn btn-sm btn-outline-secondary px-3 py-1 rounded-pill">View All</a>
            </div>
            
            <?php if (empty($recent_quotes)): ?>
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-file-earmark-excel fs-3 mb-2 d-block"></i>
                    No recent quote requests.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="small text-muted border-bottom">
                                <th>Organization</th>
                                <th>Service Required</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_quotes as $quote): ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-dark"><?php echo escape($quote['org'] ?: $quote['name']); ?></div>
                                        <small class="text-muted">By: <?php echo escape($quote['name']); ?></small>
                                    </td>
                                    <td><small class="fw-semibold text-secondary"><?php echo escape($quote['service_type']); ?></small></td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower(str_replace(' ', '', $quote['status'])); ?>">
                                            <?php echo escape($quote['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="quotes.php?view=<?php echo $quote['id']; ?>" class="btn btn-sm btn-light border" title="View details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/admin_footer.php';
?>
