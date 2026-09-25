<?php
/**
 * MMCS ADMIN — QUOTE REQUESTS MANAGER
 * =====================================
 * View and manage service quote/inquiry submissions (separate from contact msgs).
 * Supports CSV export, status workflow, and individual detail view with print.
 * 
 * Data table: `inquiries` (type='inquiry')
 */

require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$action = $_GET['action'] ?? 'list';
$view_id = isset($_GET['view']) ? intval($_GET['view']) : 0;
$delete_id = isset($_GET['delete']) ? intval($_GET['delete']) : 0;
$status_id = isset($_GET['status_id']) ? intval($_GET['status_id']) : 0;

// 1. Handle Print Layout (PDF download alternative)
if ($action === 'print' && $view_id > 0) {
    require_once dirname(__DIR__) . '/includes/auth.php';
    try {
        $stmt = $pdo->prepare("SELECT * FROM `inquiries` WHERE `id` = ? AND `type` = 'inquiry'");
        $stmt->execute([$view_id]);
        $quote = $stmt->fetch();
        
        if (!$quote) {
            die("Quote request not found.");
        }
        
        $company_name = get_setting('company_name');
        $phone = get_setting('phone');
        $email = get_setting('email');
    } catch (PDOException $e) {
        die("Failed to load quote request details.");
    }
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Quote Request #<?php echo $quote['id']; ?> | <?php echo escape($company_name); ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body { font-family: 'Inter', sans-serif; padding: 40px; color: #1e293b; }
            .invoice-title { font-size: 26px; font-weight: 700; color: #0f172a; border-bottom: 2px solid #ea580c; padding-bottom: 10px; }
            .meta-section { margin-top: 30px; margin-bottom: 30px; }
            .meta-label { font-size: 12px; text-transform: uppercase; color: #64748b; font-weight: 600; }
            .quote-content { background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 20px; border-radius: 8px; margin-top: 20px; }
        </style>
    </head>
    <body onload="window.print()">
        <div class="container">
            <div class="row">
                <div class="col-8">
                    <h1 class="invoice-title">SERVICE INQUIRY QUOTE REQUEST</h1>
                    <p class="text-muted">MM Consultancy Solutions (Private) Limited (MMCS)</p>
                </div>
                <div class="col-4 text-end">
                    <h5 class="fw-bold">Quote Request #<?php echo $quote['id']; ?></h5>
                    <p class="small text-muted mb-0">Date: <?php echo date('M d, Y', strtotime($quote['created_at'])); ?></p>
                    <p class="small text-muted">Status: <?php echo $quote['status']; ?></p>
                </div>
            </div>

            <div class="row meta-section">
                <div class="col-6">
                    <div class="meta-label">Client Information</div>
                    <h5 class="fw-bold mb-1"><?php echo escape($quote['name']); ?></h5>
                    <p class="mb-1 text-secondary"><?php echo escape($quote['org'] ?: 'Individual'); ?></p>
                    <p class="small text-secondary"><?php echo escape($quote['email']); ?></p>
                </div>
                <div class="col-6 text-end">
                    <div class="meta-label">Assigned Consultant</div>
                    <h5 class="fw-bold mb-1"><?php echo escape($company_name); ?></h5>
                    <p class="mb-1 text-secondary">Office: <?php echo escape($phone); ?></p>
                    <p class="small text-secondary"><?php echo escape($email); ?></p>
                </div>
            </div>

            <div class="row border-top border-bottom py-3">
                <div class="col-6">
                    <div class="meta-label">Service Category Requested</div>
                    <h5 class="fw-bold text-primary"><?php echo escape($quote['service_type']); ?></h5>
                </div>
                <div class="col-6 text-end">
                    <div class="meta-label">Project Budget Range</div>
                    <h5 class="fw-bold text-success"><?php echo escape($quote['budget_range'] ?: 'Not Specified'); ?></h5>
                </div>
            </div>

            <div class="quote-content mt-4">
                <div class="meta-label mb-2">Scope of Work & Project Details</div>
                <p style="white-space: pre-wrap; line-height: 1.6; color: #334155;"><?php echo escape($quote['message']); ?></p>
            </div>

            <div class="mt-5 text-center text-muted small border-top pt-4">
                This document is generated automatically by the MMCS Admin Gate portal. Save or print to PDF for records.
            </div>
        </div>
    </body>
    </html>
<?php
    exit;
}

// 2. Handle Status Update Toggle
if ($status_id > 0 && isset($_GET['set_status'])) {
    require_once dirname(__DIR__) . '/includes/auth.php';
    $new_status = trim($_GET['set_status']);
    if (in_array($new_status, ['Pending', 'In Progress', 'Closed'])) {
        try {
            $stmt = $pdo->prepare("UPDATE `inquiries` SET `status` = ? WHERE `id` = ? AND `type` = 'inquiry'");
            $stmt->execute([$new_status, $status_id]);
            flash('alert', 'Quote status updated to ' . $new_status, 'success');
        } catch (PDOException $e) {
            flash('alert', 'Failed to update quote status.', 'danger');
        }
        redirect('quotes.php' . ($view_id > 0 ? '?view=' . $view_id . '&status_updated=1' : ''));
    }
}

// 3. Handle Delete Action
if ($delete_id > 0) {
    require_once dirname(__DIR__) . '/includes/auth.php';
    try {
        $stmt = $pdo->prepare("DELETE FROM `inquiries` WHERE `id` = ? AND `type` = 'inquiry'");
        $stmt->execute([$delete_id]);
        flash('alert', 'Quote request deleted successfully.', 'success');
    } catch (PDOException $e) {
        flash('alert', 'Failed to delete quote request.', 'danger');
    }
    redirect('quotes.php');
}

// Ensure the user is logged in before output
require_once __DIR__ . '/admin_header.php';

// 4. Fetch Details if viewing a single quote
$quote_detail = null;
if ($view_id > 0) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM `inquiries` WHERE `id` = ? AND `type` = 'inquiry'");
        $stmt->execute([$view_id]);
        $quote_detail = $stmt->fetch();
        
        // Auto mark as In Progress if it was Pending (skip if returning from a manual status change)
        if ($quote_detail && $quote_detail['status'] === 'Pending' && !isset($_GET['status_updated'])) {
            $update = $pdo->prepare("UPDATE `inquiries` SET `status` = 'In Progress' WHERE `id` = ?");
            $update->execute([$view_id]);
            $quote_detail['status'] = 'In Progress';
        }
    } catch (PDOException $e) {
        flash('alert', 'Error fetching quote request details.', 'danger');
    }
}

// 5. Fetch all quote requests for the list view
$quotes = [];
try {
    $stmt = $pdo->query("SELECT * FROM `inquiries` WHERE `type` = 'inquiry' ORDER BY `created_at` DESC");
    $quotes = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = 'Failed to load quote requests.';
}
?>

<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h2 class="admin-title font-outfit">NGO Quote Requests</h2>
        <p class="text-muted">Review client-requested project parameters, budgets, and operational requirements.</p>
    </div>
    <div class="col-sm-6 text-sm-end">
        <?php if ($view_id > 0): ?>
            <a href="quotes.php" class="btn btn-outline-secondary px-4 py-2 font-outfit fw-medium">
                <i class="bi bi-arrow-left me-1"></i> Back to List
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <!-- Quotes List -->
    <div class="<?php echo ($view_id > 0) ? 'col-lg-6' : 'col-12'; ?>">
        <div class="admin-card">
            <h5 class="admin-title font-outfit text-primary mb-4"><i class="bi bi-file-earmark-pdf-fill me-2"></i>Service Inquiries</h5>
            
            <?php if (empty($quotes)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-journal-check fs-1 mb-2 d-block"></i>
                    No quote requests submitted yet.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover table-premium align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Client / Org</th>
                                <th>Service Needed</th>
                                <th>Budget</th>
                                <th>Status</th>
                                <th style="width: 130px; text-align: right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($quotes as $q): ?>
                                <tr class="<?php echo ($q['status'] === 'Pending') ? 'table-warning fw-bold' : ''; ?> <?php echo ($view_id === $q['id']) ? 'table-primary' : ''; ?>">
                                    <td>
                                        <div class="text-dark mb-0"><?php echo escape($q['org'] ?: $q['name']); ?></div>
                                        <small class="text-muted">By: <?php echo escape($q['name']); ?></small>
                                    </td>
                                    <td><small class="fw-semibold text-secondary"><?php echo escape($q['service_type']); ?></small></td>
                                    <td><span class="text-success small fw-medium"><?php echo escape($q['budget_range']); ?></span></td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower(str_replace(' ', '', $q['status'])); ?>">
                                            <?php echo escape($q['status']); ?>
                                        </span>
                                    </td>
                                    <td style="text-align: right;">
                                        <a href="quotes.php?view=<?php echo $q['id']; ?>" class="btn btn-sm btn-outline-primary me-1" title="View details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="quotes.php?delete=<?php echo $q['id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this quote request?');">
                                            <i class="bi bi-trash"></i>
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

    <!-- Quote Detail Panel -->
    <?php if ($view_id > 0 && $quote_detail): ?>
        <div class="col-lg-6">
            <div class="admin-card border-primary">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
                    <h5 class="admin-title font-outfit text-primary mb-0"><i class="bi bi-file-earmark-ruled me-2"></i>Quote Specification</h5>
                    <span class="status-badge status-<?php echo strtolower(str_replace(' ', '', $quote_detail['status'])); ?>"><?php echo escape($quote_detail['status']); ?></span>
                </div>
                
                <div class="mb-4">
                    <span class="small text-muted d-block">Requested By</span>
                    <strong class="text-dark fs-5"><?php echo escape($quote_detail['name']); ?></strong>
                    <div class="text-secondary"><?php echo escape($quote_detail['org'] ?: 'Individual'); ?></div>
                </div>

                <div class="row mb-4">
                    <div class="col-sm-6">
                        <span class="small text-muted d-block">Service Required</span>
                        <strong class="text-primary"><?php echo escape($quote_detail['service_type']); ?></strong>
                    </div>
                    <div class="col-sm-6">
                        <span class="small text-muted d-block">Budget Range</span>
                        <strong class="text-success"><?php echo escape($quote_detail['budget_range']); ?></strong>
                    </div>
                </div>

                <div class="mb-4">
                    <span class="small text-muted d-block">Email Address</span>
                    <a href="mailto:<?php echo escape($quote_detail['email']); ?>?subject=RE: MMCS Quote Request" class="text-decoration-none fw-semibold">
                        <?php echo escape($quote_detail['email']); ?> <i class="bi bi-reply-fill ms-1"></i>
                    </a>
                </div>

                <div class="mb-4 bg-light p-3 rounded border">
                    <span class="small text-muted d-block mb-2 fw-semibold">Project Scope Description</span>
                    <p class="mb-0 text-secondary" style="white-space: pre-wrap; line-height: 1.6;"><?php echo escape($quote_detail['message']); ?></p>
                </div>

                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <span class="small text-muted d-block mb-1">Submitted On</span>
                        <small class="text-dark"><?php echo escape(date('F d, Y h:i A', strtotime($quote_detail['created_at']))); ?></small>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <a href="quotes.php?action=print&view=<?php echo $quote_detail['id']; ?>" class="btn btn-dark btn-sm rounded-pill px-3" target="_blank">
                            <i class="bi bi-printer-fill me-1"></i> Print / Save PDF
                        </a>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top d-flex gap-2">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Set Status
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-menu-item dropdown-item" href="quotes.php?status_id=<?php echo $quote_detail['id']; ?>&set_status=Pending&view=<?php echo $quote_detail['id']; ?>">Pending</a></li>
                            <li><a class="dropdown-menu-item dropdown-item" href="quotes.php?status_id=<?php echo $quote_detail['id']; ?>&set_status=In Progress&view=<?php echo $quote_detail['id']; ?>">In Progress</a></li>
                            <li><a class="dropdown-menu-item dropdown-item" href="quotes.php?status_id=<?php echo $quote_detail['id']; ?>&set_status=Closed&view=<?php echo $quote_detail['id']; ?>">Closed</a></li>
                        </ul>
                    </div>

                    <a href="mailto:<?php echo escape($quote_detail['email']); ?>?subject=RE: MMCS Quote Request" class="btn btn-primary px-3">
                        <i class="bi bi-envelope-at me-1"></i> Reply Quote
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . '/admin_footer.php';
?>
