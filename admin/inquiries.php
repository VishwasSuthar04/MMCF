<?php
/**
 * MMCS ADMIN — CONTACT INQUIRIES MANAGER
 * ========================================
 * View and manage contact form submissions. Supports CSV export,
 * status changes (New, Read, Replied), and individual message view.
 * 
 * Data table: `inquiries` (type='contact')
 */

require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$action = $_GET['action'] ?? 'list';
$view_id = isset($_GET['view']) ? intval($_GET['view']) : 0;
$delete_id = isset($_GET['delete']) ? intval($_GET['delete']) : 0;
$status_id = isset($_GET['status_id']) ? intval($_GET['status_id']) : 0;

// 1. Handle CSV Export Action
if ($action === 'export_csv') {
    // Check authentication manually since we bypass admin_header
    require_once dirname(__DIR__) . '/includes/auth.php';

    try {
        $stmt = $pdo->query("SELECT `id`, `name`, `org`, `email`, `message`, `status`, `created_at` FROM `inquiries` WHERE `type` = 'contact' ORDER BY `id` DESC");
        $results = $stmt->fetchAll();

        // Clear any previous outputs to avoid headers issues
        if (ob_get_level()) ob_end_clean();

        // Set response headers
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=contact_inquiries_' . date('Y-m-d') . '.csv');

        $output = fopen('php://output', 'w');
        
        // Write header row
        fputcsv($output, ['ID', 'Contact Name', 'Organization', 'Email Address', 'Message Body', 'Status', 'Submitted At']);

        foreach ($results as $row) {
            fputcsv($output, [
                $row['id'],
                $row['name'],
                $row['org'] ?: 'N/A',
                $row['email'],
                $row['message'],
                $row['status'],
                $row['created_at']
            ]);
        }
        fclose($output);
        exit;
    } catch (PDOException $e) {
        http_response_code(500);
        die("An error occurred while generating the CSV export.");
    }
}

// 2. Handle Status Update Toggle
if ($status_id > 0 && isset($_GET['set_status'])) {
    require_once dirname(__DIR__) . '/includes/auth.php';
    $new_status = trim($_GET['set_status']);
    if (in_array($new_status, ['New', 'Read', 'Replied'])) {
        try {
            $stmt = $pdo->prepare("UPDATE `inquiries` SET `status` = ? WHERE `id` = ? AND `type` = 'contact'");
            $stmt->execute([$new_status, $status_id]);
            flash('alert', 'Message status updated to ' . $new_status, 'success');
        } catch (PDOException $e) {
            flash('alert', 'Failed to update status.', 'danger');
        }
        redirect('inquiries.php' . ($view_id > 0 ? '?view=' . $view_id . '&status_updated=1' : ''));
    }
}

// 3. Handle Delete Action
if ($delete_id > 0) {
    require_once dirname(__DIR__) . '/includes/auth.php';
    try {
        $stmt = $pdo->prepare("DELETE FROM `inquiries` WHERE `id` = ? AND `type` = 'contact'");
        $stmt->execute([$delete_id]);
        flash('alert', 'Inquiry deleted successfully.', 'success');
    } catch (PDOException $e) {
        flash('alert', 'Failed to delete message.', 'danger');
    }
    redirect('inquiries.php');
}

// Ensure the user is logged in before output
require_once __DIR__ . '/admin_header.php';

// 4. Fetch Details if viewing a single inquiry
$msg_detail = null;
if ($view_id > 0) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM `inquiries` WHERE `id` = ? AND `type` = 'contact'");
        $stmt->execute([$view_id]);
        $msg_detail = $stmt->fetch();
        
        // Auto mark as Read if it was New (skip if returning from a manual status change)
        if ($msg_detail && $msg_detail['status'] === 'New' && !isset($_GET['status_updated'])) {
            $update = $pdo->prepare("UPDATE `inquiries` SET `status` = 'Read' WHERE `id` = ?");
            $update->execute([$view_id]);
            $msg_detail['status'] = 'Read';
        }
    } catch (PDOException $e) {
        flash('alert', 'Error fetching message details.', 'danger');
    }
}

// 5. Fetch all contact inquiries for the list view
$messages = [];
try {
    $stmt = $pdo->query("SELECT * FROM `inquiries` WHERE `type` = 'contact' ORDER BY `created_at` DESC");
    $messages = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = 'Failed to load messages inbox.';
}
?>

<div class="row mb-4 align-items-center">
    <div class="col-sm-6">
        <h2 class="admin-title font-outfit">Contact Inbox</h2>
        <p class="text-muted">Review, organize, and reply to client inquiries submitted via the public contact form.</p>
    </div>
    <div class="col-sm-6 text-sm-end">
        <?php if ($view_id > 0): ?>
            <a href="inquiries.php" class="btn btn-outline-secondary px-4 py-2 font-outfit fw-medium">
                <i class="bi bi-arrow-left me-1"></i> Back to Inbox
            </a>
        <?php else: ?>
            <a href="inquiries.php?action=export_csv" class="btn btn-dark px-4 py-2 font-outfit fw-semibold me-2">
                <i class="bi bi-file-earmark-spreadsheet-fill me-1 text-success"></i> Export CSV
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <!-- Messages List -->
    <div class="<?php echo ($view_id > 0) ? 'col-lg-6' : 'col-12'; ?>">
        <div class="admin-card">
            <h5 class="admin-title font-outfit text-primary mb-4"><i class="bi bi-inbox-fill me-2"></i>Inbox Submissions</h5>
            
            <?php if (empty($messages)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-envelope-open-fill fs-1 mb-2 d-block"></i>
                    No contact submissions in the database.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover table-premium align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Sender</th>
                                <th class="d-none d-md-table-cell">Organization</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th style="width: 130px; text-align: right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($messages as $m): ?>
                                <tr class="<?php echo ($m['status'] === 'New') ? 'table-warning fw-bold' : ''; ?> <?php echo ($view_id === $m['id']) ? 'table-primary' : ''; ?>">
                                    <td>
                                        <div class="text-dark mb-0"><?php echo escape($m['name']); ?></div>
                                        <small class="text-muted font-monospace"><?php echo escape($m['email']); ?></small>
                                    </td>
                                    <td class="d-none d-md-table-cell"><?php echo escape($m['org'] ?: '—'); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower($m['status']); ?>">
                                            <?php echo escape($m['status']); ?>
                                        </span>
                                    </td>
                                    <td><small class="text-muted"><?php echo escape(date('M d, Y', strtotime($m['created_at']))); ?></small></td>
                                    <td style="text-align: right;">
                                        <a href="inquiries.php?view=<?php echo $m['id']; ?>" class="btn btn-sm btn-outline-primary me-1" title="View details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="inquiries.php?delete=<?php echo $m['id']; ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this message?');">
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

    <!-- Message Detail Panel -->
    <?php if ($view_id > 0 && $msg_detail): ?>
        <div class="col-lg-6">
            <div class="admin-card border-primary">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
                    <h5 class="admin-title font-outfit text-primary mb-0"><i class="bi bi-envelope-open-fill me-2"></i>Inquiry Details</h5>
                    <span class="status-badge status-<?php echo strtolower($msg_detail['status']); ?>"><?php echo escape($msg_detail['status']); ?></span>
                </div>
                
                <div class="mb-4">
                    <span class="small text-muted d-block">Sender Name</span>
                    <strong class="text-dark fs-5"><?php echo escape($msg_detail['name']); ?></strong>
                </div>

                <div class="row mb-4">
                    <div class="col-sm-6">
                        <span class="small text-muted d-block">Organization</span>
                        <strong><?php echo escape($msg_detail['org'] ?: 'N/A'); ?></strong>
                    </div>
                    <div class="col-sm-6">
                        <span class="small text-muted d-block">Email Address</span>
                        <a href="mailto:<?php echo escape($msg_detail['email']); ?>?subject=RE: MMCS Inquiry" class="text-decoration-none fw-semibold">
                            <?php echo escape($msg_detail['email']); ?> <i class="bi bi-reply-fill ms-1"></i>
                        </a>
                    </div>
                </div>

                <div class="mb-4 bg-light p-3 rounded border">
                    <span class="small text-muted d-block mb-2 fw-semibold">Message Body</span>
                    <p class="mb-0 text-secondary" style="white-space: pre-wrap; line-height: 1.6;"><?php echo escape($msg_detail['message']); ?></p>
                </div>

                <div class="mb-2">
                    <span class="small text-muted d-block mb-1">Received On</span>
                    <small class="text-dark"><?php echo escape(date('F d, Y h:i A', strtotime($msg_detail['created_at']))); ?></small>
                </div>

                <div class="mt-4 pt-3 border-top d-flex gap-2">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Change Status
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-menu-item dropdown-item" href="inquiries.php?status_id=<?php echo $msg_detail['id']; ?>&set_status=New&view=<?php echo $msg_detail['id']; ?>">New</a></li>
                            <li><a class="dropdown-menu-item dropdown-item" href="inquiries.php?status_id=<?php echo $msg_detail['id']; ?>&set_status=Read&view=<?php echo $msg_detail['id']; ?>">Read</a></li>
                            <li><a class="dropdown-menu-item dropdown-item" href="inquiries.php?status_id=<?php echo $msg_detail['id']; ?>&set_status=Replied&view=<?php echo $msg_detail['id']; ?>">Replied</a></li>
                        </ul>
                    </div>

                    <a href="mailto:<?php echo escape($msg_detail['email']); ?>?subject=RE: MMCS Inquiry" class="btn btn-primary px-3">
                        <i class="bi bi-envelope-at me-1"></i> Compose Reply
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . '/admin_footer.php';
?>
