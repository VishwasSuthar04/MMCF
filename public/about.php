<?php
/**
 * MMCS ABOUT PAGE
 * ===============
 * Company story, vision & mission, three core pillars, technical expertise
 * areas, corporate profile (registration, CEO, bank), and "Why Partner" section.
 * 
 * SECTIONS (in order):
 *   1. Vision & Mission
 *   2. Three Core Pillars
 *   3. Technical Expertise Areas
 *   4. Company Profile (reg details, CEO, bank)
 *   5. Why Partner with Us
 */

$page_title = "About Our Consultancy";
$page_desc = "Learn about MM Consultancy Solutions (Private) Limited — our vision, mission, strategic outlook, core pillars, expertise areas, and corporate profile.";

require_once dirname(__DIR__) . '/includes/header.php';

// Fetch active partners from DB
$partners = [];
try {
    $stmt = $pdo->query("SELECT * FROM `partners` WHERE `is_active` = 1 ORDER BY `sort_order` ASC, `id` DESC");
    $partners = $stmt->fetchAll();
} catch (PDOException $e) {
    // Fail silently — section will simply not render
}

// Fetch organogram entries from DB
$organogram = [];
try {
    $stmt = $pdo->query("SELECT * FROM `organogram` ORDER BY `display_order` ASC, `id` ASC");
    $organogram = $stmt->fetchAll();
} catch (PDOException $e) {
    // Fail silently
}
?>

<!-- Inner Header Banner -->
<section class="py-5 bg-navy text-white text-center">
    <div class="container py-4">
        <h1 class="font-outfit text-white tracking-tight fw-bold mb-2">About Us</h1>
        <p class="text-gray lead mb-0">Learn about our vision, core pillars, and consultancy culture.</p>
    </div>
</section>

<!-- Vision & Mission -->
<section class="py-5">
    <div class="container py-4">
        <div class="row g-5">
            <div class="col-lg-6">
                <div class="p-4 rounded-4 bg-gray-soft border h-100">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-orange bg-opacity-10 text-orange rounded-3 p-2 me-3">
                            <i class="bi bi-eye-fill fs-3"></i>
                        </div>
                        <h3 class="font-outfit mb-0">Our Vision</h3>
                    </div>
                    <p class="text-secondary leading-relaxed">
                        To be a trusted and results-driven partner for organizations seeking high quality, compliant, and impactful consultancy solutions that strengthen project performance, institutional capacity, and long-term development outcomes.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="p-4 rounded-4 bg-gray-soft border h-100">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-orange bg-opacity-10 text-orange rounded-3 p-2 me-3">
                            <i class="bi bi-rocket-takeoff-fill fs-3"></i>
                        </div>
                        <h3 class="font-outfit mb-0">Our Mission</h3>
                    </div>
                    <p class="text-secondary leading-relaxed">
                        To support development actors with expert knowledge, professional systems, and practical solutions that enhance development impact from concept to completion.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Strategic Outlook -->
<section class="py-5 bg-gray-soft">
    <div class="container py-4">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <span class="text-orange fw-bold text-uppercase tracking-wider small">Strategic Outlook</span>
                <h2 class="font-outfit tracking-tight mt-1 mb-3">Our Service Approach</h2>
            </div>
        </div>
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <p class="text-secondary lh-lg">
                    MMCS works through a network of sector specialists and development professionals, delivering fully integrated technical support to NGOs, INGOs, and development organizations under our <strong>End-to-End (E2E) Project Support (EEPS) Model</strong>. From project design and proposal development, through implementation support, MEAL systems, reporting, communications, and research, to final impact documentation — MMCS covers every stage of the project lifecycle under one retainer, one team, and one accountable partner.
                </p>
            </div>
            <div class="col-lg-6">
                <div class="p-4 rounded-4 bg-white border shadow-sm">
                    <h5 class="font-outfit text-dark mb-3">Who We Are</h5>
                    <p class="text-secondary small">MM Consultancy Solutions (Private) Limited (MMCS) is a development consultancy firm registered with the Securities and Exchange Commission of Pakistan (SECP) under the Companies Act, 2017. MMCS provides professional services to NGOs, INGOs, government programs, corporate CSR initiatives, and development partners, delivering integrated solutions across project design, implementation, research, training, and advisory services.</p>
                    <p class="text-secondary small mb-0">Operating as a one-stop consultancy platform, MMCS provides clients with access to specialized expertise, structured systems, and implementation support without the need to maintain large in-house teams. This flexible model ensures efficiency, scalability, and high-quality delivery across projects.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Three Core Pillars -->
<section class="py-5 bg-gray-soft">
    <div class="container py-4">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <span class="text-orange fw-bold text-uppercase tracking-wider small">Our Core Pillars</span>
                <h2 class="font-outfit tracking-tight mt-1 mb-3">The Three Pillars of MMCS</h2>
                <p class="text-muted" style="max-width: 600px; margin: 0 auto;">
                    Our operations are anchored around three distinct domains of social and technical interventions.
                </p>
            </div>
        </div>

        <div class="row g-4">
            <!-- Pillar 1 -->
            <div class="col-lg-4 col-md-6">
                <div class="premium-card h-100 text-center">
                    <div class="premium-card-icon mx-auto">
                        <i class="bi bi-journal-check"></i>
                    </div>
                    <h4 class="font-outfit fs-5 mb-3">Development<br>Consultancy Services</h4>
                    <p class="text-muted small">
                        MMCS provides comprehensive technical and operational consultancy services to support the design, planning, and implementation of development projects. This includes proposal development, strategic planning, project management systems, and technical advisory support, ensuring that programs are efficient, compliant, and results-oriented.
                    </p>
                </div>
            </div>

            <!-- Pillar 2 -->
            <div class="col-lg-4 col-md-6">
                <div class="premium-card h-100 text-center">
                    <div class="premium-card-icon mx-auto">
                        <i class="bi bi-heart-pulse-fill"></i>
                    </div>
                    <h4 class="font-outfit fs-5 mb-3">Humanitarian, Community &<br>CSR Initiatives</h4>
                    <p class="text-muted small">
                        MMCS designs and supports community-based and humanitarian interventions that respond to local needs and vulnerabilities. Through awareness campaigns, grassroots engagement, and targeted support initiatives, the firm promotes inclusive, context-sensitive solutions that strengthen community resilience and social impact.
                    </p>
                </div>
            </div>

            <!-- Pillar 3 -->
            <div class="col-lg-4 col-md-6">
                <div class="premium-card h-100 text-center">
                    <div class="premium-card-icon mx-auto">
                        <i class="bi bi-search"></i>
                    </div>
                    <h4 class="font-outfit fs-5 mb-3">Research, Policy &<br>Knowledge Platform</h4>
                    <p class="text-muted small">
                        MMCS delivers high-quality research, policy analysis, and knowledge products to inform evidence-based decision-making. This includes sector studies, evaluations, policy dialogue, and documentation of lessons learned, enabling organizations to generate insights, influence policy, and improve program effectiveness.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Technical Expertise Areas -->
<section class="py-5">
    <div class="container py-4">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <span class="text-orange fw-bold text-uppercase tracking-wider small">Our Coverage</span>
                <h2 class="font-outfit tracking-tight mt-1 mb-3">Technical Expertise Areas</h2>
                <p class="text-muted" style="max-width: 600px; margin: 0 auto;">
                    We bring cross-sectoral experience across the following technical domains.
                </p>
            </div>
        </div>
        <div class="row g-3 justify-content-center">
            <style>
                .expertise-card {
                    transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease, background-color 0.3s ease;
                    border: 2px solid #e9ecef !important;
                    cursor: default;
                }
                .expertise-card:hover {
                    transform: translateY(-6px);
                    box-shadow: 0 10px 25px rgba(230, 126, 34, 0.15);
                    border-color: #e67e22 !important;
                    background-color: #fff !important;
                }
                .expertise-card:hover .expertise-icon {
                    transform: scale(1.15) rotate(-5deg);
                    color: #e67e22 !important;
                }
                .expertise-icon {
                    transition: transform 0.3s ease, color 0.3s ease;
                }
            </style>
            <?php
            $expertise_areas = [
                ['icon' => 'bi-droplet', 'label' => 'Water, Sanitation & Hygiene (WASH)'],
                ['icon' => 'bi-book', 'label' => 'Education'],
                ['icon' => 'bi-mortarboard', 'label' => 'Non-Formal Education (NFE)'],
                ['icon' => 'bi-tree', 'label' => 'Climate Resilience & Environment'],
                ['icon' => 'bi-briefcase', 'label' => 'Livelihoods & Economic Development'],
                ['icon' => 'bi-heart-pulse', 'label' => 'Health & Nutrition'],
                ['icon' => 'bi-people', 'label' => 'Gender & Social Inclusion'],
                ['icon' => 'bi-graph-up-arrow', 'label' => 'Monitoring, Evaluation, Accountability & Learning (MEAL)'],
                ['icon' => 'bi-search', 'label' => 'Research & Knowledge Management'],
                ['icon' => 'bi-camera', 'label' => 'Media & Communications'],
                ['icon' => 'bi-laptop', 'label' => 'Digital & Social Media'],
                ['icon' => 'bi-bank', 'label' => 'Governance & Policy'],
            ];
            foreach ($expertise_areas as $area): ?>
            <div class="col-lg-3 col-md-4 col-6">
                <div class="expertise-card p-3 rounded-3 bg-gray-soft text-center h-100 d-flex flex-column align-items-center justify-content-center">
                    <i class="bi <?php echo $area['icon']; ?> expertise-icon text-orange fs-3 mb-2"></i>
                    <span class="small fw-semibold text-secondary"><?php echo $area['label']; ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Company Profile -->
<section class="py-5 bg-gray-soft">
    <div class="container py-4">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <span class="text-orange fw-bold text-uppercase tracking-wider small">Corporate Information</span>
                <h2 class="font-outfit tracking-tight mt-1 mb-3">Company Profile</h2>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="p-4 rounded-4 bg-white border h-100">
                    <h5 class="font-outfit text-dark mb-3"><i class="bi bi-building text-orange me-2"></i>Registration & Legal</h5>
                    <table class="table table-sm table-borderless mb-0">
                        <tr><td class="fw-semibold text-secondary" style="width: 140px;">Legal Status</td><td>Private Limited Company, Limited by Shares</td></tr>
                        <tr><td class="fw-semibold text-secondary">SECP Reg. No.</td><td>(CUI) 330906</td></tr>
                        <tr><td class="fw-semibold text-secondary">NTN / Tax Reg.</td><td>1757080</td></tr>
                    </table>
                    <hr class="my-3">
                    <h5 class="font-outfit text-dark mb-3"><i class="bi bi-geo-alt text-orange me-2"></i>Registered Offices</h5>
                    <table class="table table-sm table-borderless mb-0">
                        <tr><td class="fw-semibold text-secondary" style="width: 100px; vertical-align: top;">Head Office</td><td>Suther Colony East, Mithi-69230, Tharparkar</td></tr>
                        <tr><td class="fw-semibold text-secondary" style="vertical-align: top;">Liaison Office</td><td>C-63, Alrahim Villas, Qasimabad, Hyderabad</td></tr>
                    </table>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="p-4 rounded-4 bg-white border h-100">
                    <h5 class="font-outfit text-dark mb-3"><i class="bi bi-person-badge text-orange me-2"></i>Executive Director</h5>
                    <p class="fw-bold text-dark mb-1 fs-5">Maha Dev Makwano</p>
                    <p class="text-muted small mb-0">Executive Director (ED)</p>
                    <hr class="my-3">
                    <h5 class="font-outfit text-dark mb-3"><i class="bi bi-envelope text-orange me-2"></i>Contacts</h5>
                    <div class="small mb-1"><a href="mailto:mahadevmakwano@gmail.com" class="text-decoration-none">mahadevmakwano@gmail.com</a></div>
                    <div class="small mb-1"><a href="mailto:mmconsultancysolutions@gmail.com" class="text-decoration-none">mmconsultancysolutions@gmail.com</a></div>
                    <div class="small"><a href="tel:03342656314" class="text-decoration-none">0334-2656314</a></div>
                    <hr class="my-3">
                    <h5 class="font-outfit text-dark mb-3"><i class="bi bi-bank text-orange me-2"></i>Bank Account Details</h5>
                    <table class="table table-sm table-borderless mb-0">
                        <tr><td class="fw-semibold text-secondary" style="width: 120px;">Account Title</td><td>MM CONSULTANCY SOLUTIONS (PRIVATE) LIMITED</td></tr>
                        <tr><td class="fw-semibold text-secondary">Account Number</td><td>24167001581803</td></tr>
                        <tr><td class="fw-semibold text-secondary">IBAN</td><td class="font-monospace small">PK06HABB0024167001581803</td></tr>
                        <tr><td class="fw-semibold text-secondary">Bank</td><td>Habib Bank Limited, Mithi Branch (2416)</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Organizational Structure -->
<?php if (!empty($organogram)): ?>
<section class="py-5 bg-gray-soft">
    <div class="container py-4">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <span class="text-orange fw-bold text-uppercase tracking-wider small">Our Team Structure</span>
                <h2 class="font-outfit tracking-tight mt-1 mb-3">Organizational Chart</h2>
                <p class="text-muted" style="max-width: 600px; margin: 0 auto;">
                    A clear view of who leads what and how our teams are structured.
                </p>
            </div>
        </div>

        <style>
            .org-chart-wrapper {
                overflow-x: auto;
                padding-bottom: 1rem;
            }
            .org-chart {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 0;
                min-width: fit-content;
            }
            .org-level {
                display: flex;
                justify-content: center;
                gap: 1.5rem;
                position: relative;
                padding-top: 2rem;
            }
            .org-level:first-child {
                padding-top: 0;
            }
            .org-node {
                position: relative;
                text-align: center;
                flex-shrink: 0;
            }
            .org-node-card {
                background: #fff;
                border: 2px solid #e9ecef;
                border-radius: 12px;
                padding: 1rem 1.25rem;
                min-width: 160px;
                max-width: 200px;
                transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
                position: relative;
                z-index: 1;
            }
            .org-node-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 8px 20px rgba(230, 126, 34, 0.12);
                border-color: #e67e22;
            }
            .org-node-avatar {
                width: 64px;
                height: 64px;
                border-radius: 50%;
                object-fit: cover;
                border: 3px solid #f0f0f0;
                margin-bottom: 0.5rem;
            }
            .org-node-avatar-placeholder {
                width: 64px;
                height: 64px;
                border-radius: 50%;
                background: #e9ecef;
                border: 3px solid #f0f0f0;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 0.5rem;
                color: #999;
                font-size: 1.5rem;
            }
            .org-node-name {
                font-family: 'Outfit', sans-serif;
                font-weight: 600;
                font-size: 0.9rem;
                color: #1e293b;
                margin-bottom: 0.2rem;
                line-height: 1.3;
            }
            .org-node-designation {
                font-size: 0.78rem;
                color: #64748b;
                line-height: 1.3;
            }
            .org-connector-group {
                display: flex;
                justify-content: center;
                position: relative;
                height: 2rem;
            }
            .org-connector-group::before {
                content: '';
                position: absolute;
                top: 0;
                left: 50%;
                width: 2px;
                height: 100%;
                background: #d1d5db;
                transform: translateX(-50%);
            }
            .org-branch-lines {
                position: relative;
                height: 1.5rem;
                display: flex;
                align-items: flex-start;
                justify-content: center;
            }
            .org-branch-lines::before {
                content: '';
                position: absolute;
                top: 0;
                height: 2px;
                background: #d1d5db;
            }
            .org-branch-lines .branch-down {
                position: absolute;
                top: 0;
                width: 2px;
                height: 100%;
                background: #d1d5db;
            }
            @media (max-width: 767.98px) {
                .org-level {
                    flex-direction: column;
                    align-items: center;
                    gap: 1rem;
                    padding-top: 1rem;
                }
                .org-level::before {
                    display: none;
                }
                .org-node-card {
                    max-width: 260px;
                    min-width: 200px;
                }
                .org-connector-group,
                .org-branch-lines {
                    display: none;
                }
            }
        </style>

        <?php
        // Build hierarchy
        $by_id = [];
        $children = [];
        $root_ids = [];
        foreach ($organogram as $entry) {
            $by_id[$entry['id']] = $entry;
            $children[$entry['id']] = $children[$entry['id']] ?? [];
            if ($entry['parent_id'] === null) {
                $root_ids[] = $entry['id'];
            } else {
                $children[$entry['parent_id']][] = $entry['id'];
            }
        }

        // Recursive render function
        function render_org_node($entry, $by_id, $children) {
            $photo_html = '';
            if (!empty($entry['photo'])) {
                $photo_html = '<img src="' . SITE_URL . escape($entry['photo']) . '" class="org-node-avatar" alt="' . escape($entry['name']) . '">';
            } else {
                $photo_html = '<div class="org-node-avatar-placeholder"><i class="bi bi-person-fill"></i></div>';
            }
            echo '<div class="org-node">';
            echo '<div class="org-node-card">';
            echo $photo_html;
            echo '<div class="org-node-name">' . escape($entry['name']) . '</div>';
            echo '<div class="org-node-designation">' . escape($entry['designation']) . '</div>';
            echo '</div>';
            echo '</div>';
        }

        function render_org_level($ids, $by_id, $children, $depth = 0) {
            if (empty($ids)) return;
            echo '<div class="org-level">';
            foreach ($ids as $id) {
                render_org_node($by_id[$id], $by_id, $children);
            }
            echo '</div>';

            // If any node at this level has children, render connector + next level
            $next_ids = [];
            foreach ($ids as $id) {
                if (!empty($children[$id])) {
                    $next_ids = array_merge($next_ids, $children[$id]);
                }
            }
            if (!empty($next_ids)) {
                echo '<div class="org-connector-group"></div>';
                render_org_level($next_ids, $by_id, $children, $depth + 1);
            }
        }
        ?>

        <div class="org-chart-wrapper">
            <div class="org-chart">
                <?php render_org_level($root_ids, $by_id, $children); ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Geographic Footprint -->
<section class="py-5">
    <div class="container py-4">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <span class="text-orange fw-bold text-uppercase tracking-wider small">Our Presence</span>
                <h2 class="font-outfit tracking-tight mt-1 mb-3">Geographic Footprint & Service Coverage</h2>
                <p class="text-muted" style="max-width: 700px; margin: 0 auto;">
                    MMCS is headquartered in Tharparkar with a Liaison Office in Hyderabad — positioned at the heart of the communities it serves while maintaining strong access to donors, government bodies, and partner organizations across Sindh.
                </p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="p-4 rounded-4 bg-white border shadow-sm h-100">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-orange bg-opacity-10 text-orange rounded-3 p-2 me-3">
                            <i class="bi bi-building fs-4"></i>
                        </div>
                        <div>
                            <h5 class="font-outfit mb-0">Head Office</h5>
                            <small class="text-muted">Tharparkar</small>
                        </div>
                    </div>
                    <p class="text-secondary small mb-0">Suther Colony East, Mithi-69230, Tharparkar</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-4 rounded-4 bg-white border shadow-sm h-100">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-orange bg-opacity-10 text-orange rounded-3 p-2 me-3">
                            <i class="bi bi-geo-alt fs-4"></i>
                        </div>
                        <div>
                            <h5 class="font-outfit mb-0">Liaison Office</h5>
                            <small class="text-muted">Hyderabad</small>
                        </div>
                    </div>
                    <p class="text-secondary small mb-0">C-63, Alrahim Villas, Qasimabad, Hyderabad</p>
                </div>
            </div>
        </div>
        <style>
            .coverage-chip {
                transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease, background-color 0.3s ease;
                cursor: default;
            }
            .coverage-chip:hover {
                transform: translateY(-6px);
                box-shadow: 0 10px 25px rgba(230, 126, 34, 0.2);
                border-color: #e67e22 !important;
                background-color: #fff !important;
            }
            .coverage-chip:hover .coverage-label {
                color: #e67e22;
            }
            .coverage-label {
                transition: color 0.3s ease;
            }
        </style>
        <div class="row g-2 mt-4 justify-content-center">
            <?php
            $coverage_areas = ['Umerkot', 'Nangarparkar', 'Thatta', 'Badin', 'Sujawal', 'Mirpurkhas', 'Kashmore', 'Dadu', 'Nawabshah', 'Larkana'];
            foreach ($coverage_areas as $area): ?>
            <div class="col-lg-2 col-md-3 col-4">
                <div class="coverage-chip p-2 rounded-3 bg-gray-soft border text-center">
                    <span class="small fw-semibold coverage-label text-secondary"><?php echo $area; ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Clients & Partners -->
<?php if (!empty($partners)): ?>
<section class="py-5 bg-gray-soft">
    <div class="container py-4">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <span class="text-orange fw-bold text-uppercase tracking-wider small">Partners & Clients</span>
                <h2 class="font-outfit tracking-tight mt-1 mb-3">Our Clients & Partners</h2>
            </div>
        </div>
        <style>
            .partner-card {
                transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
                border: 2px solid #e9ecef !important;
            }
            .partner-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
                border-color: #e67e22 !important;
            }
            .partner-logo {
                transition: transform 0.4s ease, box-shadow 0.3s ease;
            }
            .partner-card:hover .partner-logo {
                transform: scale(1.08);
                box-shadow: 0 6px 20px rgba(230, 126, 34, 0.3);
            }
        </style>
        <div class="row g-4 justify-content-center">
            <?php foreach ($partners as $p): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="partner-card p-4 rounded-4 bg-white text-center h-100 d-flex flex-column align-items-center justify-content-center">
                        <?php if (!empty($p['logo'])): ?>
                            <?php if (!empty($p['website'])): ?>
                                <a href="<?php echo escape($p['website']); ?>" target="_blank" rel="noopener noreferrer" class="text-decoration-none d-block mb-3">
                                    <img src="<?php echo SITE_URL . escape($p['logo']); ?>" alt="<?php echo escape($p['name']); ?> Logo" class="partner-logo rounded-circle" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #f0f0f0;">
                                </a>
                            <?php else: ?>
                                <img src="<?php echo SITE_URL . escape($p['logo']); ?>" alt="<?php echo escape($p['name']); ?> Logo" class="partner-logo mb-3 rounded-circle" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #f0f0f0;">
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if (!empty($p['website'])): ?>
                            <h5 class="font-outfit mb-1"><a href="<?php echo escape($p['website']); ?>" target="_blank" rel="noopener noreferrer" class="text-decoration-none text-dark"><?php echo escape($p['name']); ?></a></h5>
                        <?php else: ?>
                            <h5 class="font-outfit mb-1"><?php echo escape($p['name']); ?></h5>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- General Information -->
<section class="py-5">
    <div class="container py-4">
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8 text-center">
                <span class="text-orange fw-bold text-uppercase tracking-wider small">Information</span>
                <h2 class="font-outfit tracking-tight mt-1 mb-3">General Information</h2>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="p-4 rounded-4 bg-white border shadow-sm">
                    <table class="table table-sm table-borderless mb-0">
                        <tr><td class="fw-semibold text-secondary" style="width: 200px;">Company Name</td><td>MM Consultancy Solutions (Private) Limited (MMCS)</td></tr>
                        <tr><td class="fw-semibold text-secondary">Legal Status</td><td>Private Limited Company, Limited by Shares</td></tr>
                        <tr><td class="fw-semibold text-secondary">SECP Registration No. (CUI)</td><td>330906</td></tr>
                        <tr><td class="fw-semibold text-secondary">NTN / Tax Registration</td><td>1757080</td></tr>
                        <tr><td class="fw-semibold text-secondary">Core Expertise Areas</td><td>Agriculture Value Chain, Food Security, Livelihoods, Enterprise Development, WASH, Education, Climate Resilience, Gender & Inclusion, MEAL, Media & Communications, Governance & Policy</td></tr>
                        <tr><td class="fw-semibold text-secondary">Executive Director (ED)</td><td>Maha Dev Makwano</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values & Excellence -->
<section class="py-5">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h2 class="font-outfit mb-3">Why Partner with Us?</h2>
                <p class="text-secondary">
                    Our team of experienced researchers, field supervisors, and data analysts ensure the highest level of detail in data acquisition. We utilize advanced mobile data gathering (ODK/KoboToolbox) to assure real-time tracking, geolocation verifications, and quality auditable results.
                </p>
                <div class="mt-4">
                    <div class="d-flex align-items-start mb-3">
                        <div class="text-orange me-3"><i class="bi bi-shield-fill-check fs-4"></i></div>
                        <div>
                            <h5 class="font-outfit mb-1">Quality Assurance</h5>
                            <p class="text-muted small mb-0">Double entry data logs and auditable supervisor records.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start">
                        <div class="text-orange me-3"><i class="bi bi-clock-history fs-4"></i></div>
                        <div>
                            <h5 class="font-outfit mb-1">Timely Delivery</h5>
                            <p class="text-muted small mb-0">Committed project lifecycles managed via agile workflows.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <img src="<?php echo SITE_URL; ?>assets/images/why%20partner%20us.jpg" class="img-fluid rounded-4 shadow border" alt="Why Partner with Us" onerror="this.src='https://placehold.co/600x400/0f172a/ffffff?text=MMCS+Consulting'">
            </div>
        </div>
    </div>
</section>

<?php
require_once dirname(__DIR__) . '/includes/footer.php';
?>
