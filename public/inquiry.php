<?php
/**
 * MMCS INQUIRY / QUOTE REQUEST PAGE
 * ==================================
 * A detailed form for requesting a service quote. Saves to `inquiries` table
 * with type='inquiry'. Includes service selection, budget range, and message.
 * 
 * TO CUSTOMISE:
 *   - Service dropdown is populated from the `services` DB table
 *   - Budget options can be edited in the HTML <select> below
 */

$page_title = "Request a Quote";
$page_desc = "Submit a formal quote request for MEAL systems, baseline evaluations, proposal drafting, or custom training programs.";

require_once dirname(__DIR__) . '/includes/header.php';

$success_msg = '';
$error_msg = '';

// Optional query parameter to pre-select service
$pre_selected_service = $_GET['service'] ?? '';

// Fetch active services for the dropdown list
$services_list = [];
try {
    $stmt = $pdo->query("SELECT `title` FROM `services` WHERE `is_active` = 1 ORDER BY `sort_order` ASC");
    $services_list = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    // Fail silently
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf_token();
    $name = trim($_POST['name'] ?? '');
    $org = trim($_POST['org'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $service_type = trim($_POST['service_type'] ?? '');
    $budget_range = trim($_POST['budget_range'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($service_type) || empty($message)) {
        $error_msg = 'Please fill out all required fields marked with an asterisk (*).';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = 'Please enter a valid email address.';
    } else {
        try {
            // Save submission to inquiries database with type = 'inquiry'
            $stmt = $pdo->prepare("INSERT INTO `inquiries` (`name`, `org`, `email`, `message`, `type`, `status`, `service_type`, `budget_range`) VALUES (?, ?, ?, ?, 'inquiry', 'Pending', ?, ?)");
            $stmt->execute([$name, $org, $email, $message, $service_type, $budget_range]);
            
            $success_msg = 'Thank you! Your quote request has been received. Our estimating team will draft a response proposal shortly.';
            
            // Try sending email notification (PHPMailer placeholder / PHP mail fallback)
            $to = get_setting('email');
            $subject = "New Quote Request: " . $service_type;
            $body = "Name: $name\nOrganization: $org\nEmail: $email\nService Requested: $service_type\nBudget Range: $budget_range\n\nProject Scope:\n$message";
            
            @mail($to, $subject, $body, "From: " . MAIL_FROM);
            
        } catch (PDOException $e) {
            $error_msg = 'Failed to submit quote request. Please try again later.';
        }
    }
}
?>

<!-- Inner Header Banner -->
<section class="py-5 bg-navy text-white text-center">
    <div class="container py-4">
        <h1 class="font-outfit text-white tracking-tight fw-bold mb-2">NGO Quote Request Gateway</h1>
        <p class="text-gray lead mb-0">Submit your project details to obtain structural and financial proposals.</p>
    </div>
</section>

<!-- Quote Request Form -->
<section class="py-5 bg-gray-soft">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="p-4 p-md-5 rounded-4 bg-white border shadow-sm">
                    
                    <?php $consultation_fee = get_setting('consultation_fee'); ?>
                    <div class="border-bottom pb-3 mb-4 text-center">
                        <i class="bi bi-file-earmark-pdf text-orange fs-1"></i>
                        <h2 class="font-outfit tracking-tight mt-2 mb-0">Service Inquiry Specification Form</h2>
                        <p class="small text-muted mb-0">NGO & INGO Quote Requests Desk</p>
                        <?php if ($consultation_fee): ?>
                            <span class="badge bg-orange bg-opacity-10 text-orange px-3 py-2 mt-2 rounded-pill fw-semibold">
                                <i class="bi bi-tag-fill me-1"></i> Consultation Fee: <?php echo escape($consultation_fee); ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($success_msg)): ?>
                        <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success rounded-3 py-3 px-4 mb-4">
                            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                            <span><?php echo escape($success_msg); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($error_msg)): ?>
                        <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-3 py-3 px-4 mb-4">
                            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                            <span><?php echo escape($error_msg); ?></span>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST" class="needs-validation" novalidate>
                        <?php echo csrf_field(); ?>
                        <div class="row g-3">
                            <!-- Contact Name -->
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary">Contact Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-input-premium" name="name" required placeholder="e.g. Dr. Muhammad Ali">
                                <div class="invalid-feedback">Please enter contact name.</div>
                            </div>
                            
                            <!-- Organization Name -->
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary">Organization Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-input-premium" name="org" required placeholder="e.g. UNICEF Pakistan / WASH NGO">
                                <div class="invalid-feedback">Please enter organization name.</div>
                            </div>

                            <!-- Email Address -->
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control form-input-premium" name="email" required placeholder="e.g. procurement@ngo.org">
                                <div class="invalid-feedback">Please enter a valid email address.</div>
                            </div>

                            <!-- Service Category Dropdown -->
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary">Service Needed <span class="text-danger">*</span></label>
                                <select class="form-select form-input-premium" name="service_type" required>
                                    <option value="" disabled selected>Select service category</option>
                                    <?php foreach ($services_list as $title): ?>
                                        <option value="<?php echo escape($title); ?>" <?php echo ($pre_selected_service === $title) ? 'selected' : ''; ?>><?php echo escape($title); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Please choose service type.</div>
                            </div>

                            <!-- Budget Range Dropdown -->
                            <div class="col-12">
                                <label class="form-label small fw-semibold text-secondary">Estimated Project Budget Range</label>
                                <select class="form-select form-input-premium" name="budget_range">
                                    <option value="Not Specified" selected>Select budget range (Optional)</option>
                                    <option value="Under $5,000">Under $5,000</option>
                                    <option value="$5,000 - $15,000">$5,000 - $15,000</option>
                                    <option value="$15,000 - $50,000">$15,000 - $50,000</option>
                                    <option value="Over $50,000">Over $50,000</option>
                                </select>
                            </div>

                            <!-- Scope of Work Description -->
                            <div class="col-12">
                                <label class="form-label small fw-semibold text-secondary">Project Details & Terms of Reference (ToR) <span class="text-danger">*</span></label>
                                <textarea class="form-control form-input-premium" name="message" rows="8" required placeholder="Describe target geographical locations, sample size, field timelines, deliverables requested, or details of ToRs..."></textarea>
                                <div class="invalid-feedback">Please enter project scope.</div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top text-center">
                            <button type="submit" class="btn btn-orange btn-lg px-5 py-3 rounded-pill fw-semibold font-outfit shadow-sm">
                                <i class="bi bi-file-earmark-check-fill me-2"></i> Submit Inquiry Request
                            </button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once dirname(__DIR__) . '/includes/footer.php';
?>
