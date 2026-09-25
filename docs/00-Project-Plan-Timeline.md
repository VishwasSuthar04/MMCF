# Project Plan & Timeline

**Project:** MMCS Web Platform — MM Consultancy Solutions (Private) Limited
**Version:** 1.0
**Date:** January 2026
**Prepared by:** Project Management Team
**Reference:** BRD v1.0, SRS v1.0

---

## 1. Project Overview

### 1.1 Project Summary

| Attribute | Value |
|-----------|-------|
| Project Name | MMCS Web Platform |
| Client | MM Consultancy Solutions (Private) Limited |
| Executive Director | Maha Dev Makwano |
| Developer | Vishwas Suthar |
| Project Type | Web application (CMS + corporate website) |
| Methodology | Iterative/Incremental |
| Planned Duration | 6 weeks |
| Planned Start | February 2026 |
| Planned End | March 2026 |

### 1.2 Project Objectives

1. Deliver a fully functional CMS-backed corporate website for MMCS
2. Enable non-technical staff to manage all website content through an admin panel
3. Provide online client intake via contact forms and quote request forms
4. Establish a career portal for opportunity posting and application collection
5. Implement industry-standard security measures from day one
6. Deliver comprehensive documentation and handover materials

---

## 2. Project Phases & Milestones

### 2.1 Phase Overview

| Phase | Name | Duration | Dates (Planned) |
|-------|------|----------|-----------------|
| Phase 1 | Planning & Design | 1 week | Feb 3 – Feb 7, 2026 |
| Phase 2 | Core Infrastructure | 1 week | Feb 10 – Feb 14, 2026 |
| Phase 3 | Public Website Development | 2 weeks | Feb 17 – Feb 28, 2026 |
| Phase 4 | Admin Panel Development | 1.5 weeks | Mar 3 – Mar 14, 2026 |
| Phase 5 | Testing & Deployment | 0.5 weeks | Mar 17 – Mar 21, 2026 |

**Total:** 6 weeks (30 working days)

---

## 3. Detailed Phase Plans

### 3.1 Phase 1: Planning & Design (Week 1)

**Objective:** Finalize all requirements, create design system, and establish project structure.

| # | Task | Duration | Deliverable | Owner |
|---|------|----------|-------------|-------|
| 1.1 | Finalize Business Requirements Document | 1 day | BRD v1.0 approved | Business Analyst |
| 1.2 | Finalize Software Requirements Specification | 1 day | SRS v1.0 approved | Systems Analyst |
| 1.3 | Design system architecture | 0.5 day | Architecture Design doc | Architect |
| 1.4 | Design database schema | 0.5 day | Database Design doc, schema.sql draft | DB Designer |
| 1.5 | Create UI/UX wireframes & design system | 1 day | Wireframe Specification, color palette, typography | UI/UX Designer |
| 1.6 | Set up development environment | 0.5 day | XAMPP, database, project scaffold | Developer |
| 1.7 | Create project plan & timeline | 0.5 day | This document | PM |

**Milestone M1:** All planning documents approved, development environment operational.
**Gate Review:** Requirements freeze — no new requirements accepted without change request.

---

### 3.2 Phase 2: Core Infrastructure (Week 2)

**Objective:** Build the foundational layers that all features depend on.

| # | Task | Duration | Deliverable | Owner |
|---|------|----------|-------------|-------|
| 2.1 | Create database schema in MySQL | 0.5 day | 12 tables created, seed data inserted | Developer |
| 2.2 | Implement config.php (DB, session, constants) | 0.5 day | Configuration system | Developer |
| 2.3 | Implement includes/db.php (PDO singleton) | 0.25 day | Database connection layer | Developer |
| 2.4 | Implement includes/functions.php (escape, CSRF, upload, flash) | 0.5 day | Utility function library | Developer |
| 2.5 | Implement includes/auth.php (session check, timeout) | 0.25 day | Authentication gate | Developer |
| 2.6 | Implement admin login page (rate limiting, brute-force protection) | 0.5 day | Secure login system | Developer |
| 2.7 | Implement public header (includes/header.php) | 0.5 day | Navbar, head section, responsive | Developer |
| 2.8 | Implement public footer (includes/footer.php) | 0.5 day | 4-column footer, social links | Developer |
| 2.9 | Implement admin layout (admin_header.php, admin_footer.php) | 0.5 day | Sidebar, topbar, responsive admin | Developer |
| 2.10 | Create custom CSS (assets/css/style.css) | 0.5 day | Brand theming, components | Developer |
| 2.11 | Create client JS (assets/js/main.js) | 0.25 day | Navbar scroll, form validation | Developer |

**Milestone M2:** Database operational, authentication working, layouts rendered, CSS/JS foundation in place.

---

### 3.3 Phase 3: Public Website Development (Weeks 3–4)

**Objective:** Build all 12 public-facing pages.

#### Week 3: Content Pages

| # | Task | Duration | Deliverable | Owner |
|---|------|----------|-------------|-------|
| 3.1 | Homepage (public/index.php) | 1 day | Hero, stats, services, about, testimonials, CTA | Developer |
| 3.2 | About Us (public/about.php) | 1 day | Vision/mission, pillars, expertise, company profile, org chart, partners | Developer |
| 3.3 | Services Listing (public/services.php) | 0.5 day | Service cards, EEPS model, expertise areas | Developer |
| 3.4 | Service Detail (public/service-detail.php) | 0.5 day | Single service view, sidebar CTA | Developer |
| 3.5 | Portfolio (public/portfolio.php) | 0.5 day | Project cards, sector filtering | Developer |
| 3.6 | Team (public/team.php) | 0.5 day | Expert profiles from DB | Developer |

#### Week 4: Interactive Pages

| # | Task | Duration | Deliverable | Owner |
|---|------|----------|-------------|-------|
| 3.7 | Get Involved (public/get-involved.php) | 0.5 day | Opportunity listings grouped by type | Developer |
| 3.8 | Application Form (public/apply.php) | 0.5 day | Form with resume upload, validation | Developer |
| 3.9 | Blog (public/blog.php) | 0.5 day | Published posts, PDF downloads | Developer |
| 3.10 | Contact Form (public/contact.php) | 0.5 day | Form + office info + map embed | Developer |
| 3.11 | Quote Request Form (public/inquiry.php) | 0.5 day | Dynamic service dropdown, budget range | Developer |
| 3.12 | Health Check (public/health.php) | 0.25 day | JSON endpoint, DB connectivity check | Developer |

**Milestone M3:** All 12 public pages functional, responsive, and tested on desktop/mobile.

---

### 3.4 Phase 4: Admin Panel Development (Weeks 5–6)

**Objective:** Build all 11 admin CRUD modules and management interfaces.

#### Week 5: Core Admin Modules

| # | Task | Duration | Deliverable | Owner |
|---|------|----------|-------------|-------|
| 4.1 | Admin Dashboard (admin/dashboard.php) | 0.5 day | Stats cards, recent items, logout | Developer |
| 4.2 | Services CRUD (admin/services.php) | 0.5 day | Full CRUD, icon selector, sort order | Developer |
| 4.3 | Portfolio CRUD (admin/portfolio.php) | 0.5 day | Full CRUD, image upload | Developer |
| 4.4 | Team CRUD (admin/team.php) | 0.5 day | Full CRUD, photo upload, expertise tags | Developer |
| 4.5 | Blog CRUD (admin/blog.php) | 0.5 day | Full CRUD, PDF upload, draft/published | Developer |
| 4.6 | Organogram CRUD (admin/organogram.php) | 0.5 day | Full CRUD, hierarchy, circular prevention | Developer |

#### Week 6: Management Interfaces

| # | Task | Duration | Deliverable | Owner |
|---|------|----------|-------------|-------|
| 4.7 | Opportunities CRUD + Applications (admin/opportunities.php) | 0.5 day | Full CRUD, application viewer, resume download | Developer |
| 4.8 | Testimonials CRUD (admin/testimonials.php) | 0.5 day | Full CRUD, approval toggle | Developer |
| 4.9 | Partners CRUD (admin/partners.php) | 0.5 day | Full CRUD, logo upload | Developer |
| 4.10 | Inquiries Manager (admin/inquiries.php) | 0.5 day | Inbox, status workflow, CSV export | Developer |
| 4.11 | Quotes Manager (admin/quotes.php) | 0.5 day | Inbox, status workflow, print layout | Developer |
| 4.12 | Site Settings + Password Change (admin/settings.php) | 0.5 day | 13 settings, password policy | Developer |

**Milestone M4:** All admin modules functional, CRUD operations verified, settings management operational.

---

### 3.5 Phase 5: Testing & Deployment (Week 6, final days)

**Objective:** Quality assurance, documentation, and production deployment.

| # | Task | Duration | Deliverable | Owner |
|---|------|----------|-------------|-------|
| 5.1 | Functional testing (all CRUD + forms) | 0.5 day | Test results document | QA / Developer |
| 5.2 | Security testing (XSS, SQLi, CSRF, auth) | 0.25 day | Security checklist verified | Developer |
| 5.3 | Responsive testing (mobile, tablet, desktop) | 0.25 day | Cross-browser report | Developer |
| 5.4 | Create setup_check.php (pre-delivery validator) | 0.25 day | Configuration validation script | Developer |
| 5.5 | Write README.md | 0.25 day | Installation and usage guide | Developer |
| 5.6 | Write ADMIN_GUIDE.md | 0.25 day | Admin user manual | Developer |
| 5.7 | Create .env.example, .gitignore, robots.txt | 0.25 day | Deployment configuration files | Developer |
| 5.8 | Production deployment & verification | 0.5 day | Live site running | Developer |
| 5.9 | Client walkthrough & handover | 0.25 day | Handover meeting | PM / Developer |

**Milestone M5:** Production deployment complete, client trained, project delivered.

---

## 4. Gantt Chart Overview

```
Week:    1       2       3       4       5       6
        Feb 3   Feb 10  Feb 17  Feb 24  Mar 3   Mar 10
        ─────── ─────── ─────── ─────── ─────── ───────

Phase 1 ████████
Plan    ████────
Design  ────████

Phase 2         ████████
DB              ██──────
Config          ──██────
Auth            ────██──
Layout          ──────██

Phase 3                 ████████████████
Homepage                ██──────────────
About                   ──██────────────
Services                ─────█──────────
Portfolio               ──────█─────────
Team                    ───────█────────
Forms                   ─────────██─────
Blog                    ───────────█────

Phase 4                                 ████████████████
Dashboard                               ██──────────────
CRUD Modules                            ──████──────────
Inquiries                               ────────██──────
Settings                                ──────────██────

Phase 5                                         ████████
Testing                                         ██──────
Documentation                                   ──██────
Deploy                                          ────████
Handover                                        ──────██
```

---

## 5. Resource Allocation

### 5.1 Team Roles

| Role | Person | Responsibility | Allocation |
|------|--------|---------------|------------|
| Project Manager | TBD | Planning, coordination, client communication | 20% |
| Business Analyst | TBD | Requirements gathering, BRD/SRS authoring | 30% (Phase 1 only) |
| UI/UX Designer | Vishwas Suthar | Wireframes, design system, CSS | 50% (Phase 1–3) |
| Full-Stack Developer | Vishwas Suthar | All PHP, database, frontend, backend | 100% (Phase 2–5) |
| QA / Testing | TBD | Functional and security testing | 50% (Phase 5) |

### 5.2 Effort Summary

| Phase | Estimated Effort (person-days) |
|-------|-------------------------------|
| Phase 1: Planning & Design | 5 |
| Phase 2: Core Infrastructure | 4.5 |
| Phase 3: Public Website | 8 |
| Phase 4: Admin Panel | 7 |
| Phase 5: Testing & Deployment | 3 |
| **Total** | **27.5 person-days** |

---

## 6. Risk Register

| # | Risk | Probability | Impact | Mitigation |
|---|------|-------------|--------|------------|
| R1 | Client delays in providing content (text, images) | High | Medium | Use seed data as placeholder; deliver with sample content |
| R2 | Hosting environment incompatibility | Medium | High | Target XAMPP-compatible stack; provide setup_check.php |
| R3 | Scope creep (new feature requests) | High | High | Freeze requirements after Phase 1; use change request process |
| R4 | Developer availability constraints | Medium | High | Prioritize Must Have features; defer Should/Could Have |
| R5 | SMTP/email configuration issues | Medium | Medium | Implement best-effort mail(); document SMTP setup in guide |
| R6 | Security vulnerabilities discovered late | Low | High | Security-first approach from Phase 2; security review in Phase 5 |
| R7 | Database performance at scale | Low | Low | Current scale is small (<1000 records per table); add indexes if needed |

---

## 7. Quality Assurance Plan

### 7.1 Testing Strategy

| Test Type | Scope | When | Method |
|-----------|-------|------|--------|
| Unit Testing | Database queries, utility functions | During development | Manual verification |
| Integration Testing | Form → DB → Admin panel flow | After each phase | End-to-end testing |
| Functional Testing | All CRUD operations, all forms | Phase 5 | Checklist-based testing |
| Security Testing | XSS, SQLi, CSRF, auth, uploads | Phase 5 | Manual penetration testing |
| Responsive Testing | All pages on mobile/tablet/desktop | Phase 5 | Cross-device testing |
| Performance Testing | Page load times, DB query count | Phase 5 | Visual inspection, browser dev tools |

### 7.2 Acceptance Criteria

The project shall be accepted as complete when:

| # | Criterion | Verification Method |
|---|-----------|-------------------|
| 1 | All 12 public pages render without errors | Manual page-by-page review |
| 2 | All admin CRUD modules (11 modules) functional | Create/read/update/delete each module |
| 3 | Contact form saves to database | Submit form, verify DB record |
| 4 | Quote request form saves with correct type/status | Submit form, verify DB record |
| 5 | Application form accepts resume upload | Submit application, verify file saved |
| 6 | Admin login with authentication works | Login/logout/session timeout |
| 7 | Rate limiting blocks after 5 failed attempts | Attempt 6 logins with wrong password |
| 8 | CSRF tokens required on all POST forms | Inspect form HTML + submit without token |
| 9 | File uploads validate MIME type and size | Attempt invalid uploads |
| 10 | Settings changes reflect on public site | Change setting, verify on public page |
| 11 | Password change with policy enforcement | Attempt weak passwords, verify rejection |
| 12 | Responsive on mobile, tablet, desktop | Visual inspection at 375px, 768px, 1200px |
| 13 | Documentation delivered (README, Admin Guide) | Document review |
| 14 | Seed data populated | Verify DB tables have initial data |

---

## 8. Change Management

### 8.1 Change Request Process

1. Any stakeholder may submit a change request in writing.
2. The Project Manager assesses impact on scope, timeline, and budget.
3. Must Have features from the approved SRS may not be descoped.
4. Changes are categorized as:
   - **In-scope:** No timeline/budget impact → implement in current phase
   - **Deferrable:** Can wait → add to Phase 2 roadmap
   - **Out-of-scope:** Requires re-planning → requires formal approval

### 8.2 Requirements Freeze

After the completion of Phase 1 (Planning & Design), the BRD and SRS are frozen. New requirements will be:
- Logged in a change request register
- Assessed for impact
- Deferred to Phase 2 (unless critical)

---

## 9. Communication Plan

| Event | Frequency | Participants | Purpose |
|-------|-----------|-------------|---------|
| Daily Standup | Daily (Phase 2–5) | Developer, PM | Progress update, blockers |
| Weekly Status Report | Weekly | PM → Client | Progress summary, risks |
| Milestone Review | At each milestone | Full team + Client | Demo, feedback, gate review |
| Handover Meeting | End of Phase 5 | Developer + Client | Walkthrough, documentation, training |

---

## 10. Deliverables Summary

| # | Deliverable | Format | Phase |
|---|------------|--------|-------|
| 1 | Business Requirements Document (BRD) | Markdown | Phase 1 |
| 2 | Software Requirements Specification (SRS) | Markdown | Phase 1 |
| 3 | System Architecture Design | Markdown | Phase 1 |
| 4 | Database Design Document | Markdown | Phase 1 |
| 5 | UI/UX Wireframe Specification | Markdown | Phase 1 |
| 6 | Project Plan & Timeline | Markdown | Phase 1 |
| 7 | Database Schema (schema.sql) | SQL | Phase 2 |
| 8 | MMCS Web Platform (source code) | PHP/HTML/CSS/JS | Phase 2–4 |
| 9 | Seed Data | SQL | Phase 2 |
| 10 | Custom CSS (style.css) | CSS | Phase 2–3 |
| 11 | Client-side JavaScript (main.js) | JS | Phase 2–3 |
| 12 | Setup Checker (setup_check.php) | PHP | Phase 5 |
| 13 | README.md | Markdown | Phase 5 |
| 14 | ADMIN_GUIDE.md | Markdown | Phase 5 |
| 15 | Configuration Files (.env.example, .gitignore, robots.txt) | Config | Phase 5 |

---

## 11. Budget Considerations

| Category | Notes |
|----------|-------|
| Development | In-house (Vishwas Suthar) — no external cost |
| Hosting | Client-provided shared hosting (XAMPP for dev, Linux for prod) |
| Domain | Client-provided |
| SSL Certificate | Free (Let's Encrypt) or hosting-provider included |
| Email (SMTP) | Gmail free tier or hosting-provider mail |
| Stock Images | Use client-provided images; placeholders for demo |
| Third-party Licenses | None — all dependencies are free/open-source |

---

## 12. Post-Delivery Support

| Support Type | Duration | Scope |
|-------------|----------|-------|
| Bug Fixes | 30 days post-delivery | Fix any defects in delivered functionality |
| Configuration Assistance | 30 days post-delivery | Help with hosting, SMTP, SSL setup |
| Feature Enhancements | Phase 2 roadmap (separate project) | Search, pagination, multi-image, etc. |

---

*This project plan shall guide the execution of the MMCS Web Platform project. All phases, milestones, and deliverables are subject to the change management process defined in Section 8.*
