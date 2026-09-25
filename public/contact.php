<?php
/**
 * MMCS CONTACT PAGE
 * =================
 * Contact form (saves to `inquiries` table) + office addresses, phone, email,
 * and an embedded Google Map pointing to the Mithi head office.
 * 
 * SECTIONS (in order):
 *   1. Contact Form — name / org / email / message (POST to self)
 *   2. Office Details — head office + field office from DB settings
 *   3. Google Map — iframe embed
 */

$page_title = "Contact Our Desk";
$page_desc = "Get in touch with MM Consultancy Solutions. Send a message to our consultants or visit our head office in Tharparkar, Sindh.";

require_once dirname(__DIR__) . '/includes/header.php';

$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf_token();
    $name = trim($_POST['name'] ?? '');
    $org = trim($_POST['org'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        $error_msg = 'Please fill out all required fields (Name, Email, and Message).';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = 'Please supply a valid email address.';
    } else {
        try {
            // Save submission to inquiries database
            $stmt = $pdo->prepare("INSERT INTO `inquiries` (`name`, `org`, `email`, `message`, `type`, `status`) VALUES (?, ?, ?, ?, 'contact', 'New')");
            $stmt->execute([$name, $org, $email, $message]);
            
            $success_msg = 'Thank you! Your message has been logged. Our consultancy desk will contact you shortly.';
            
            // Try sending email notification (PHPMailer placeholder / PHP mail fallback)
            $to = get_setting('email');
            $subject = "New Contact Form Submission: " . $name;
            $body = "Name: $name\nOrganization: $org\nEmail: $email\n\nMessage:\n$message";
            
            // Safe execution of mail utility
            @mail($to, $subject, $body, "From: " . MAIL_FROM);
            
        } catch (PDOException $e) {
            $error_msg = 'Failed to submit form. Please try again later.';
        }
    }
}
?>

<!-- Inner Header Banner -->
<section class="py-5 bg-navy text-white text-center">
    <div class="container py-4">
        <h1 class="font-outfit text-white tracking-tight fw-bold mb-2">Contact Us</h1>
        <p class="text-gray lead mb-0">Get in touch with our team for operational inquiries.</p>
    </div>
</section>

<!-- Form & Office Details -->
<section class="py-5">
    <div class="container py-4">
        <div class="row g-5">
            
            <!-- Left Panel - Contact Form -->
            <div class="col-lg-7">
                <div class="p-4 p-md-5 rounded-4 bg-white border shadow-sm">
                    <h3 class="font-outfit mb-4">Send Us a Message</h3>
                    
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
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-input-premium" name="name" required placeholder="e.g. John Doe">
                                <div class="invalid-feedback">Please enter your name.</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary">Organization Name</label>
                                <input type="text" class="form-control form-input-premium" name="org" placeholder="e.g. WASH Initiative NGO">
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-semibold text-secondary">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control form-input-premium" name="email" required placeholder="e.g. john@org.org">
                                <div class="invalid-feedback">Please enter a valid email address.</div>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-semibold text-secondary">Message Description <span class="text-danger">*</span></label>
                                <textarea class="form-control form-input-premium" name="message" rows="6" required placeholder="Write details about your operational requirement or questions..."></textarea>
                                <div class="invalid-feedback">Please enter your message.</div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-orange px-5 py-3 rounded-pill fw-semibold font-outfit shadow-sm">
                                <i class="bi bi-send-fill me-2"></i> Submit Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Panel - Address Details & Map -->
            <div class="col-lg-5">
                <div class="p-4 p-md-5 rounded-4 bg-navy text-white h-100 d-flex flex-column justify-content-between">
                    <div>
                        <h3 class="font-outfit text-white mb-4">Our Offices</h3>
                        
                        <div class="mb-4 d-flex align-items-start">
                            <div class="bg-orange bg-opacity-25 rounded-circle p-2 me-3 text-orange"><i class="bi bi-building fs-5"></i></div>
                            <div>
                                <h6 class="font-outfit text-white mb-1">Head Office</h6>
                                <p class="small text-gray mb-0"><?php echo escape(get_setting('address')); ?></p>
                            </div>
                        </div>

                        <?php $field_address = get_setting('field_address'); if ($field_address): ?>
                        <div class="mb-4 d-flex align-items-start">
                            <div class="bg-orange bg-opacity-25 rounded-circle p-2 me-3 text-orange"><i class="bi bi-geo-alt-fill fs-5"></i></div>
                            <div>
                                <h6 class="font-outfit text-white mb-1">Field Office</h6>
                                <p class="small text-gray mb-0"><?php echo escape($field_address); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="mb-4 d-flex align-items-start">
                            <div class="bg-orange bg-opacity-25 rounded-circle p-2 me-3 text-orange"><i class="bi bi-telephone-fill fs-5"></i></div>
                            <div>
                                <h6 class="font-outfit text-white mb-1">Phone Line</h6>
                                <p class="small text-gray mb-0"><?php echo escape(get_setting('phone')); ?></p>
                            </div>
                        </div>

                        <div class="mb-4 d-flex align-items-start">
                            <div class="bg-orange bg-opacity-25 rounded-circle p-2 me-3 text-orange"><i class="bi bi-envelope-fill fs-5"></i></div>
                            <div>
                                <h6 class="font-outfit text-white mb-1">Email Helpline</h6>
                                <p class="small text-gray mb-0"><?php echo escape(get_setting('email')); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Interactive Map iframe (Mithi, Tharparkar - Head Office) -->
                    <div class="rounded-3 overflow-hidden border border-secondary border-opacity-25 mt-4" style="height: 220px;">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3573.204977103712!2d69.798542!3d24.745105!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x394dcc5e2b8b5e2f%3A0x4f0b8f5e2b8b5e2f!2sMithi%2C%20Tharparkar%2C%20Sindh%2C%20Pakistan!5e0!3m2!1sen!2s!4v1700000000000" 
                            width="100%" 
                            height="100%" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php
require_once dirname(__DIR__) . '/includes/footer.php';
?>
