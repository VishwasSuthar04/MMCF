<?php
$page_title = "Our Consultancy Services";
$page_desc = "Explore our comprehensive service portfolio including EEPS, MEAL, proposal writing, training, research, and field documentation for NGOs and development organizations.";

require_once dirname(__DIR__) . '/includes/header.php';

$services = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM `services` WHERE `is_active` = 1 ORDER BY `sort_order` ASC");
    $stmt->execute();
    $services = $stmt->fetchAll();
} catch (PDOException $e) {
}
?>

<section class="py-5 bg-navy text-white text-center">
    <div class="container py-4">
        <h1 class="font-outfit text-white tracking-tight fw-bold mb-2">Service Portfolio</h1>
        <p class="text-gray lead mb-0">End-to-end consultancy solutions — from concept to completion.</p>
    </div>
</section>

<section class="py-5">
    <div class="container py-4">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <span class="text-orange fw-bold text-uppercase tracking-wider small">Our Programs</span>
                <h2 class="font-outfit tracking-tight mt-1 mb-3">What We Do</h2>
                <p class="text-muted" style="max-width: 700px; margin: 0 auto;">
                    MMCS delivers integrated solutions across project design, implementation, research, training, and advisory services through three core programs.
                </p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="premium-card h-100 text-center">
                    <div class="premium-card-icon mx-auto">
                        <i class="bi bi-journal-check"></i>
                    </div>
                    <h4 class="font-outfit fs-5 mb-3">Development Consultancy Services</h4>
                    <p class="text-muted small">Technical and operational consultancy for design, planning, and implementation of development projects — proposal development, strategic planning, and technical advisory.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="premium-card h-100 text-center">
                    <div class="premium-card-icon mx-auto">
                        <i class="bi bi-heart-pulse-fill"></i>
                    </div>
                    <h4 class="font-outfit fs-5 mb-3">Humanitarian & Community Initiatives</h4>
                    <p class="text-muted small">Community-based humanitarian interventions responding to local needs — awareness campaigns, grassroots engagement, and targeted support for community resilience.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="premium-card h-100 text-center">
                    <div class="premium-card-icon mx-auto">
                        <i class="bi bi-search"></i>
                    </div>
                    <h4 class="font-outfit fs-5 mb-3">Research, Policy & Knowledge Platform</h4>
                    <p class="text-muted small">High-quality research, policy analysis, and knowledge products for evidence-based decision-making — sector studies, evaluations, and lessons learned documentation.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-gray-soft">
    <div class="container py-4">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-10 text-center">
                <span class="text-orange fw-bold text-uppercase tracking-wider small">Service Approach</span>
                <h2 class="font-outfit tracking-tight mt-1 mb-3">End-to-End (E2E) Project Support</h2>
                <p class="text-muted" style="max-width: 700px; margin: 0 auto;">
                    From Concept to Completion — We're With You.
                </p>
            </div>
        </div>
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <p class="text-secondary lh-lg">
                    The End-to-End (E2E) Project Support (EEPS) Model is MMCS's signature service framework, designed to function as the complete external technical arm of NGOs, INGOs, government programs, and development organizations. Rather than managing multiple vendors across different functions, the EEPS Model consolidates every critical technical service under one platform, one team, and one retainer — eliminating gaps, reducing overhead, and ensuring consistent quality at every stage.
                </p>
                <h5 class="font-outfit mt-4 mb-3">Why EEPS?</h5>
                <p class="text-secondary">
                    Development organizations face mounting pressure, tighter donor requirements, complex compliance standards, and limited in-house capacity. MMCS embeds itself as a dedicated technical partner, giving organizations access to specialized expertise across every function without the burden of building it internally.
                </p>
            </div>
            <div class="col-lg-6">
                <div class="p-4 rounded-4 bg-white border shadow-sm">
                    <h5 class="font-outfit text-dark mb-4">EEPS Coverage — Every Stage</h5>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-orange text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px; flex-shrink: 0;">01</div>
                        <div><strong class="text-dark">Inception</strong><br><small class="text-muted">Proposal writing, project design & resource mobilization</small></div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-orange text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px; flex-shrink: 0;">02</div>
                        <div><strong class="text-dark">Implementation</strong><br><small class="text-muted">Implementation support & technical advisory</small></div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-orange text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px; flex-shrink: 0;">03</div>
                        <div><strong class="text-dark">MEAL</strong><br><small class="text-muted">MEAL systems & donor reporting</small></div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-orange text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px; flex-shrink: 0;">04</div>
                        <div><strong class="text-dark">Reporting</strong><br><small class="text-muted">Communications, digital media & IEC materials</small></div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-orange text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px; flex-shrink: 0;">05</div>
                        <div><strong class="text-dark">Capacity Building</strong><br><small class="text-muted">Capacity building, training & TOT</small></div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="bg-orange text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px; flex-shrink: 0;">06</div>
                        <div><strong class="text-dark">Impact</strong><br><small class="text-muted">Research & final impact documentation</small></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container py-4">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <h2 class="font-outfit tracking-tight mt-1 mb-3">Our Service Portfolio</h2>
                <p class="text-muted" style="max-width: 700px; margin: 0 auto;">
                    MMCS provides a comprehensive range of consultancy services under one platform, one team, and one retainer.
                </p>
            </div>
        </div>

        <div class="row g-4">

            <div class="col-lg-6">
                <div class="premium-card h-100">
                    <div class="premium-card-icon">
                        <i class="bi bi-diagram-3"></i>
                    </div>
                    <h4 class="font-outfit fs-5 mb-2">Monthly Reporting & E-newsletter</h4>
                    <p class="text-muted small">A retainer communications service delivering polished outputs every month — monthly progress reports, narrative reports, donor e-newsletters, field visit reports, presentations, social media content calendars, and annual reports. Positioned as an ongoing monthly service rather than a one-off task, with MMCS functioning as your dedicated reporting and communications desk.</p>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="premium-card h-100">
                    <div class="premium-card-icon">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <h4 class="font-outfit fs-5 mb-2">Monitoring, Evaluation, Accountability & Learning (MEAL)</h4>
                    <p class="text-muted small">A full MEAL function built around you — MEAL framework and indicator development, monthly monitoring visits and reports, baseline/midline/endline surveys, digital data collection tools (KoBoToolbox), and third-party verification for donors. Immediate access to trained MEAL specialists with standardized, donor-compliant data systems.</p>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="premium-card h-100">
                    <div class="premium-card-icon">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <h4 class="font-outfit fs-5 mb-2">Proposal & Grant Writing</h4>
                    <p class="text-muted small">From concept note to full submission — competitive, compliant, on time. Full proposal writing, budget development and narrative justification, EOI and RFP responses, log frame and Theory of Change design. Familiarity with major donor formats and requirements with dedicated writing capacity to meet tight deadlines.</p>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="premium-card h-100">
                    <div class="premium-card-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <h4 class="font-outfit fs-5 mb-2">Training & Capacity Building</h4>
                    <p class="text-muted small">Skills that stay after the workshop ends — inception trainings, Training of Trainers (ToT), MEAL training for field staff, report writing workshops, technical trainings in WASH, NFE, and Gender, and community mobilization techniques. Practical, facilitator-led sessions with customized content delivered as standalone or bundled packages.</p>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="premium-card h-100">
                    <div class="premium-card-icon">
                        <i class="bi bi-search"></i>
                    </div>
                    <h4 class="font-outfit fs-5 mb-2">Research & Knowledge Products</h4>
                    <p class="text-muted small">Turning field experience into credible, reusable knowledge — thematic research papers, sector-specific studies (WASH, Education, Livelihoods, Health), lessons learned and best practice documentation, knowledge briefs, data visualization and infographics, and impact assessments. Rigorous research design with clear, accessible writing for technical and non-technical audiences.</p>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="premium-card h-100">
                    <div class="premium-card-icon">
                        <i class="bi bi-droplet"></i>
                    </div>
                    <h4 class="font-outfit fs-5 mb-2">Water Quality Analysis (WQA)</h4>
                    <p class="text-muted small">Led by our in-house water quality expert holding a Master's degree in Environmental Engineering (Water/SDG-6). On-site testing using portable meters per APHA Standard Methods — TDS, pH, Turbidity, EC, Temperature, Color, Taste, Odor, Total Coliform, E. Coli. Clear suitability classification from village studies to multi-district surveys.</p>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="premium-card h-100">
                    <div class="premium-card-icon">
                        <i class="bi bi-clipboard-data"></i>
                    </div>
                    <h4 class="font-outfit fs-5 mb-2">Independent Evaluations, Assessments & Evidence Products</h4>
                    <p class="text-muted small">End-of-project evaluations and rapid assessments delivered as standalone engagements or as part of a broader EEPS retainer. Credible evidence products ensuring partner organizations always have access to verified, donor-ready data when it's needed most.</p>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="premium-card h-100">
                    <div class="premium-card-icon">
                        <i class="bi bi-camera"></i>
                    </div>
                    <h4 class="font-outfit fs-5 mb-2">Field Documentation & Visibility</h4>
                    <p class="text-muted small">The story behind the statistics — field photography and videography, success and human interest stories, short documentary/project film production, branding and communication materials. Dedicated visual documentation at field events with storytelling that captures real beneficiary impact.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="py-5 bg-gray-soft">
    <div class="container py-4">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <span class="text-orange fw-bold text-uppercase tracking-wider small">Expertise Areas</span>
                <h2 class="font-outfit tracking-tight mt-1 mb-3">Technical Expertise Areas</h2>
                <p class="text-muted" style="max-width: 600px; margin: 0 auto;">
                    MMCS maintains a dedicated pool of qualified experts for each technical area, capable of performing every task within their domain.
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
                ['icon' => 'bi-graph-up-arrow', 'label' => 'Research & Knowledge Management'],
                ['icon' => 'bi-bank', 'label' => 'Governance & Policy'],
            ];
            foreach ($expertise_areas as $area): ?>
            <div class="col-lg-3 col-md-4 col-6">
                <div class="expertise-card p-3 rounded-3 bg-white text-center h-100 d-flex flex-column align-items-center justify-content-center">
                    <i class="bi <?php echo $area['icon']; ?> expertise-icon text-orange fs-3 mb-2"></i>
                    <span class="small fw-semibold text-secondary"><?php echo $area['label']; ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="text-orange fw-bold text-uppercase tracking-wider small">Who Is EEPS For?</span>
                <h2 class="font-outfit tracking-tight mt-1 mb-4">Built for Organizations That Need a Reliable Technical Partner</h2>
                <ul class="list-unstyled">
                    <li class="d-flex mb-3">
                        <i class="bi bi-check-circle-fill text-orange me-2 fs-5"></i>
                        <span class="text-secondary">NGOs and INGOs seeking institutional strengthening</span>
                    </li>
                    <li class="d-flex mb-3">
                        <i class="bi bi-check-circle-fill text-orange me-2 fs-5"></i>
                        <span class="text-secondary">Development organizations managing donor-funded projects</span>
                    </li>
                    <li class="d-flex mb-3">
                        <i class="bi bi-check-circle-fill text-orange me-2 fs-5"></i>
                        <span class="text-secondary">Humanitarian programs requiring integrated MEAL support</span>
                    </li>
                    <li class="d-flex mb-3">
                        <i class="bi bi-check-circle-fill text-orange me-2 fs-5"></i>
                        <span class="text-secondary">Government programs needing structured documentation</span>
                    </li>
                    <li class="d-flex">
                        <i class="bi bi-check-circle-fill text-orange me-2 fs-5"></i>
                        <span class="text-secondary">Any organization without the bandwidth for large in-house teams</span>
                    </li>
                </ul>
            </div>
            <div class="col-lg-6">
                <div class="p-5 rounded-4 shadow" style="background: linear-gradient(135deg, var(--navy-light) 0%, var(--navy-dark) 100%); color: #ffffff;">
                    <div class="mb-4">
                        <i class="bi bi-quote fs-1 text-orange opacity-50"></i>
                    </div>
                    <h4 class="font-outfit text-white mb-3" style="line-height: 1.4;">"From project design and proposal development through implementation support, MEAL systems, reporting, communications, and research — MMCS covers every stage of the project lifecycle under one retainer, one team, and one accountable partner."</h4>
                    <div class="d-flex align-items-center mt-4">
                        <div class="bg-orange bg-opacity-25 rounded-circle p-2 me-3">
                            <i class="bi bi-person-fill text-orange fs-4"></i>
                        </div>
                        <div>
                            <h6 class="font-outfit mb-0 text-white">Executive Director</h6>
                            <small class="text-gray-muted">MM Consultancy Solutions (Pvt.) Ltd.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once dirname(__DIR__) . '/includes/footer.php';
?>
