# Software Requirements Specification (SRS)

**Project:** MMCS Web Platform — MM Consultancy Solutions (Private) Limited
**Version:** 1.0
**Date:** January 2026
**Prepared by:** Systems Analysis Team
**Reference:** Business Requirements Document (BRD) v1.0

---

## 1. Introduction

### 1.1 Purpose

This Software Requirements Specification (SRS) defines the functional and non-functional requirements for the MMCS Web Platform. It translates the business objectives outlined in the BRD into detailed technical specifications that guide design, development, testing, and acceptance.

### 1.2 Scope

The MMCS Web Platform consists of two primary subsystems:

1. **Public Website** — A multi-page, responsive website accessible to all internet users, providing information about MMCS services, expertise, portfolio, and career opportunities.
2. **Administrative Panel** — A secure, authentication-gated content management system enabling authorized administrators to manage all website content, view and respond to inquiries, and configure site settings.

### 1.3 Definitions & Abbreviations

| Term | Definition |
|------|-----------|
| CMS | Content Management System |
| CRUD | Create, Read, Update, Delete |
| CSRF | Cross-Site Request Forgery |
| XSS | Cross-Site Scripting |
| EEPS | End-to-End Project Support (MMCS service model) |
| MEAL | Monitoring, Evaluation, Accountability & Learning |
| WQA | Water Quality Analysis |
| Admin | Authenticated administrator of the platform |
| Public Visitor | Unauthenticated internet user browsing the public website |

### 1.4 Document Conventions

- **Must Have** — Critical requirement; project cannot be delivered without it
- **Should Have** — Important requirement; will be included unless significant constraints arise
- **Could Have** — Desirable enhancement; included if time and budget permit
- **Won't Have (this release)** — Explicitly deferred to a future phase

---

## 2. Overall Description

### 2.1 Product Perspective

The MMCS Web Platform is a standalone, self-hosted PHP/MySQL web application. It does not depend on external software systems, third-party APIs (beyond CDN delivery for frontend assets), or cloud services. The system follows a file-based routing architecture with procedural PHP, Bootstrap CSS for responsive design, and a relational database for content persistence.

### 2.2 User Classes & Characteristics

| User Class | Description | Technical Proficiency | Authentication |
|------------|-------------|----------------------|----------------|
| **Public Visitor** | General internet user browsing the MMCS website | Low to Medium | None |
| **Prospective Client** | NGO/INGO staff evaluating MMCS services | Medium | None (may submit forms) |
| **Job Applicant** | Individual applying for expert/intern/volunteer positions | Low to Medium | None |
| **Administrator** | MMCS staff managing website content | Low to Medium | Username/password |

### 2.3 Operating Environment

| Component | Requirement |
|-----------|-------------|
| Server OS | Linux or Windows (XAMPP development environment) |
| Web Server | Apache 2.4+ with `mod_rewrite` and `AllowOverride All` |
| PHP Version | 8.0 or higher |
| Database | MySQL 5.7+ or MariaDB 10.3+ |
| PHP Extensions | PDO, pdo_mysql, mbstring, fileinfo, openssl |
| Browser Support | Chrome 90+, Firefox 90+, Safari 14+, Edge 90+ |
| Mobile | Responsive design for iOS Safari 14+ and Android Chrome 90+ |

### 2.4 Design & Implementation Constraints

1. No PHP framework — vanilla procedural PHP for simplicity and zero-dependency deployment
2. No package manager (no Composer, no npm) — all dependencies via CDN
3. Single administrative user model — no multi-user RBAC
4. File-based sessions — no Redis/Memcached
5. Local filesystem for file uploads — no cloud storage
6. No build tools — CSS and JS served as-is

### 2.5 Assumptions & Dependencies

- Apache with `.htaccess` support is available on the deployment server
- PHP 8.0+ with required extensions is installed
- MySQL/MariaDB is available with CREATE TABLE privileges
- A valid SMTP account (or PHP `mail()` function) is available for email notifications
- SSL certificate is available for HTTPS (recommended but not strictly required for MVP)

---

## 3. Functional Requirements

### 3.1 Public Website — Homepage (FR-HOME)

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-HOME-01 | The homepage shall display a hero banner with the company name, tagline, and a call-to-action button | Must Have |
| FR-HOME-02 | The homepage shall display key business statistics (years of experience, projects completed, clients served) | Must Have |
| FR-HOME-03 | The homepage shall display the top 6 active services with icons and brief descriptions, loaded from the database | Must Have |
| FR-HOME-04 | The homepage shall display an "About MMCS" callout section with mission summary and key differentiators | Must Have |
| FR-HOME-05 | The homepage shall display a testimonial carousel showing approved client testimonials from the database | Must Have |
| FR-HOME-06 | The homepage shall include a call-to-action banner encouraging visitors to request a quote | Must Have |
| FR-HOME-07 | The homepage shall include a sticky navigation bar with links to all major pages | Must Have |

### 3.2 Public Website — About Page (FR-ABOUT)

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-ABOUT-01 | The About page shall display the company's vision, mission, and three core pillars | Must Have |
| FR-ABOUT-02 | The About page shall list technical expertise areas (minimum 9 areas) | Must Have |
| FR-ABOUT-03 | The About page shall display the company profile including SECP registration, NTN, and banking details | Must Have |
| FR-ABOUT-04 | The About page shall display the organizational hierarchy chart (org chart) rendered from database records | Must Have |
| FR-ABOUT-05 | The About page shall display a geographic coverage map or description | Should Have |
| FR-ABOUT-06 | The About page shall display partner/client logos with links, loaded from the database | Must Have |
| FR-ABOUT-07 | The About page shall include a "Why Partner With Us" section highlighting key differentiators | Should Have |

### 3.3 Public Website — Services (FR-SERVICE)

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-SERVICE-01 | The services listing page shall display all active services as cards with icon, title, and description | Must Have |
| FR-SERVICE-02 | The services page shall explain the EEPS (End-to-End Project Support) model with its 6 stages | Must Have |
| FR-SERVICE-03 | Each service shall have a detail page accessible via link from the listing page | Must Have |
| FR-SERVICE-04 | The service detail page shall display the full service description | Must Have |
| FR-SERVICE-05 | The service detail page shall include a sidebar with a "Get Proposal Quote" CTA linking to the inquiry form | Must Have |
| FR-SERVICE-06 | The service detail page shall display navigation to other services | Should Have |

### 3.4 Public Website — Portfolio (FR-PORT)

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-PORT-01 | The portfolio page shall display project cards with title, client, sector, location, year, and description | Must Have |
| FR-PORT-02 | Each project card shall display an associated image (if provided) | Must Have |
| FR-PORT-03 | The portfolio shall support client-side filtering by sector categories (WASH, Education, Climate, Gender, Research, Livelihoods) | Must Have |
| FR-PORT-04 | Filter transitions shall be animated (fade/scale effect) for a polished user experience | Should Have |
| FR-PORT-05 | A default/placeholder image shall be displayed when no project image is provided | Must Have |

### 3.5 Public Website — Team (FR-TEAM)

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-TEAM-01 | The team page shall display profiles of active team members with photo, name, role, bio, and expertise tags | Must Have |
| FR-TEAM-02 | Only team members marked as active shall be displayed | Must Have |
| FR-TEAM-03 | A default avatar shall be displayed when no photo is provided | Must Have |

### 3.6 Public Website — Get Involved & Applications (FR-CAREER)

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-CAREER-01 | The Get Involved page shall display open opportunities grouped by type (expert, intern, volunteer) | Must Have |
| FR-CAREER-02 | Only opportunities with status "open" and whose deadline has not passed (or is null) shall be displayed | Must Have |
| FR-CAREER-03 | Each opportunity shall display title, description, requirements, location, and deadline | Must Have |
| FR-CAREER-04 | Each opportunity shall include an "Apply Now" link to the application form | Must Have |
| FR-CAREER-05 | The application form shall accept: name, email, phone (optional), cover letter (optional), and resume PDF (optional) | Must Have |
| FR-CAREER-06 | The application form shall validate that the linked opportunity is open and not expired before accepting submissions | Must Have |
| FR-CAREER-07 | Uploaded resumes shall be stored in a dedicated uploads directory with randomized filenames | Must Have |
| FR-CAREER-08 | The application form shall display a success confirmation after submission | Must Have |

### 3.7 Public Website — Blog & Publications (FR-BLOG)

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-BLOG-01 | The blog page shall display published blog posts sorted by publication date (newest first) | Must Have |
| FR-BLOG-02 | Only posts with status "published" shall be visible on the public blog page | Must Have |
| FR-BLOG-03 | Each blog post shall display title, category, publication date, and content preview | Must Have |
| FR-BLOG-04 | Blog posts with PDF attachments shall provide a download link | Must Have |
| FR-BLOG-05 | Blog posts shall be categorized (Research, Policy, Guides, News) | Must Have |

### 3.8 Public Website — Contact Form (FR-CONTACT)

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-CONTACT-01 | The contact page shall display a form with fields: Name (required), Organization, Email (required), Message (required) | Must Have |
| FR-CONTACT-02 | The contact page shall display office addresses (head office and field office) | Must Have |
| FR-CONTACT-03 | The contact page shall display an embedded Google Map showing the Mithi, Tharparkar office location | Should Have |
| FR-CONTACT-04 | Form submission shall validate all required fields client-side and server-side | Must Have |
| FR-CONTACT-05 | Email format shall be validated using standard email validation | Must Have |
| FR-CONTACT-06 | Successful submission shall save the inquiry to the database with type="contact" and status="New" | Must Have |
| FR-CONTACT-07 | Successful submission shall attempt to send a notification email to the site administrator | Should Have |
| FR-CONTACT-08 | The form shall display a success or error message after submission | Must Have |

### 3.9 Public Website — Quote Request (FR-INQUIRY)

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-INQUIRY-01 | The inquiry page shall display a form with fields: Name (required), Organization (required), Email (required), Service Type (required), Budget Range, Message (required) | Must Have |
| FR-INQUIRY-02 | The Service Type dropdown shall be dynamically populated from active services in the database | Must Have |
| FR-INQUIRY-03 | The Service Type may be pre-selected via a query parameter (e.g., from service detail page CTA) | Should Have |
| FR-INQUIRY-04 | Budget Range options shall include: Not Specified, Under $5K, $5K-$15K, $15K-$50K, Over $50K | Must Have |
| FR-INQUIRY-05 | The page shall display the consultation fee from site settings | Should Have |
| FR-INQUIRY-06 | Successful submission shall save to the database with type="inquiry" and status="Pending" | Must Have |
| FR-INQUIRY-07 | The form shall display a success or error message after submission | Must Have |

### 3.10 Public Website — Health Check (FR-HEALTH)

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-HEALTH-01 | The system shall provide a JSON health check endpoint for uptime monitoring | Could Have |
| FR-HEALTH-02 | The health check shall verify database connectivity and report latency | Could Have |

### 3.11 Admin Panel — Authentication (FR-AUTH)

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-AUTH-01 | The admin login page shall accept username and password credentials | Must Have |
| FR-AUTH-02 | Passwords shall be stored using bcrypt hashing (PHP `password_hash()`) | Must Have |
| FR-AUTH-03 | Passwords shall be verified using `password_verify()` | Must Have |
| FR-AUTH-04 | Successful login shall regenerate the session ID to prevent session fixation | Must Have |
| FR-AUTH-05 | The system shall implement brute-force protection: maximum 5 failed attempts per IP within 15 minutes | Must Have |
| FR-AUTH-06 | Failed login attempts shall be logged with timestamp, username, IP, and attempt count | Must Have |
| FR-AUTH-07 | A CSRF token shall be required on the login form | Must Have |
| FR-AUTH-08 | All admin pages (except login) shall require an active, authenticated session | Must Have |
| FR-AUTH-09 | Sessions shall expire after 30 minutes of inactivity (configurable) | Must Have |
| FR-AUTH-10 | Upon session expiry, the user shall be redirected to the login page with an informative message | Must Have |

### 3.12 Admin Panel — Dashboard (FR-DASH)

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-DASH-01 | The dashboard shall display summary statistics: total contact inquiries, quote requests, projects, and team members | Must Have |
| FR-DASH-02 | The dashboard shall display the 5 most recent contact inquiries | Must Have |
| FR-DASH-03 | The dashboard shall display the 5 most recent quote requests | Must Have |
| FR-DASH-04 | The dashboard shall provide a logout function | Must Have |

### 3.13 Admin Panel — Services CRUD (FR-SVC)

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-SVC-01 | Admin shall be able to view all services in a list with title, icon, status, and sort order | Must Have |
| FR-SVC-02 | Admin shall be able to add a new service with: title, description, icon (Bootstrap Icon class), sort order, active toggle | Must Have |
| FR-SVC-03 | Admin shall be able to edit existing service details | Must Have |
| FR-SVC-04 | Admin shall be able to delete a service (with confirmation dialog) | Must Have |
| FR-SVC-05 | All CRUD operations shall require a valid CSRF token | Must Have |

### 3.14 Admin Panel — Portfolio CRUD (FR-PROJ)

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-PROJ-01 | Admin shall be able to view all projects in a list | Must Have |
| FR-PROJ-02 | Admin shall be able to add a new project with: title, client, sector (dropdown), location, year, description, and image upload | Must Have |
| FR-PROJ-03 | Admin shall be able to edit existing project details including replacing the image | Must Have |
| FR-PROJ-04 | Admin shall be able to delete a project (with confirmation dialog and file cleanup) | Must Have |

### 3.15 Admin Panel — Team CRUD (FR-TEAM-A)

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-TEAM-A-01 | Admin shall be able to view all team members in a list | Must Have |
| FR-TEAM-A-02 | Admin shall be able to add a new team member with: name, role, photo upload, bio, expertise tags, active toggle | Must Have |
| FR-TEAM-A-03 | Admin shall be able to edit existing team member details | Must Have |
| FR-TEAM-A-04 | Admin shall be able to delete a team member (with file cleanup) | Must Have |

### 3.16 Admin Panel — Blog CRUD (FR-BLOG-A)

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-BLOG-A-01 | Admin shall be able to view all blog posts in a list showing title, category, status, and publication date | Must Have |
| FR-BLOG-A-02 | Admin shall be able to add a new post with: title, content (rich text), category, PDF attachment upload, status (draft/published) | Must Have |
| FR-BLOG-A-03 | Admin shall be able to edit existing posts including replacing the PDF attachment | Must Have |
| FR-BLOG-A-04 | Admin shall be able to delete a post (with file cleanup) | Must Have |

### 3.17 Admin Panel — Testimonials CRUD (FR-TEST)

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-TEST-01 | Admin shall be able to view all testimonials with approval status | Must Have |
| FR-TEST-02 | Admin shall be able to add a new testimonial with: client name, organization, quote, photo upload, approval toggle | Must Have |
| FR-TEST-03 | Admin shall be able to toggle approval status (approved/pending) without opening the edit form | Must Have |
| FR-TEST-04 | Admin shall be able to edit and delete testimonials | Must Have |

### 3.18 Admin Panel — Opportunities CRUD (FR-OPP)

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-OPP-01 | Admin shall be able to view all opportunities in a list | Must Have |
| FR-OPP-02 | Admin shall be able to add a new opportunity with: title, type (expert/intern/volunteer), description, requirements, location, deadline, status (open/closed) | Must Have |
| FR-OPP-03 | Admin shall be able to edit and delete opportunities | Must Have |
| FR-OPP-04 | Admin shall be able to view all applications for a specific opportunity | Must Have |
| FR-OPP-05 | Admin shall be able to view applicant details (name, email, phone, cover letter) and download resume | Must Have |

### 3.19 Admin Panel — Inquiries Management (FR-INQ)

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-INQ-01 | Admin shall be able to view all contact inquiries (type="contact") in an inbox-style list | Must Have |
| FR-INQ-02 | New (unread) inquiries shall be visually distinguished from read inquiries | Must Have |
| FR-INQ-03 | Viewing an inquiry detail shall auto-change status from "New" to "Read" | Must Have |
| FR-INQ-04 | Admin shall be able to manually change inquiry status (New / Read / Replied) | Must Have |
| FR-INQ-05 | Admin shall be able to export all contact inquiries as a CSV file | Should Have |
| FR-INQ-06 | Admin shall be able to compose a reply via email client integration (mailto: link) | Should Have |
| FR-INQ-07 | Admin shall be able to delete inquiries (with confirmation) | Must Have |

### 3.20 Admin Panel — Quotes Management (FR-QT)

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-QT-01 | Admin shall be able to view all quote requests (type="inquiry") in an inbox-style list | Must Have |
| FR-QT-02 | Pending quotes shall be visually distinguished | Must Have |
| FR-QT-03 | Viewing a quote detail shall auto-change status from "Pending" to "In Progress" | Must Have |
| FR-QT-04 | Admin shall be able to manually change quote status (Pending / In Progress / Closed) | Must Have |
| FR-QT-05 | Admin shall be able to generate a print-friendly view of a quote request | Should Have |
| FR-QT-06 | Admin shall be able to compose a reply via email client integration | Should Have |

### 3.21 Admin Panel — Partners CRUD (FR-PART)

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-PART-01 | Admin shall be able to view all partners in a list | Must Have |
| FR-PART-02 | Admin shall be able to add a new partner with: name, logo upload, website URL, sort order, active toggle | Must Have |
| FR-PART-03 | Admin shall be able to edit and delete partners (with file cleanup) | Must Have |

### 3.22 Admin Panel — Organogram CRUD (FR-ORG)

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-ORG-01 | Admin shall be able to view all organizational chart entries in a hierarchical list | Must Have |
| FR-ORG-02 | Admin shall be able to add a new entry with: name, designation, photo upload, parent selection (nullable for root), display order | Must Have |
| FR-ORG-03 | Admin shall be able to edit existing entries | Must Have |
| FR-ORG-04 | The system shall prevent self-referencing parent selection (a person cannot report to themselves) | Must Have |
| FR-ORG-05 | The system shall detect and prevent circular hierarchy (parent chain validation) | Must Have |
| FR-ORG-06 | The system shall prevent deletion of an entry that has child members | Must Have |
| FR-ORG-07 | Deleting a parent entry shall set child entries' parent_id to NULL (orphaned to root level) | Must Have |

### 3.23 Admin Panel — Site Settings (FR-SET)

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-SET-01 | Admin shall be able to update global settings: company name, phone, email, head office address, field office address, social links (LinkedIn, Twitter, Facebook), stats (years, projects, clients), footer text, consultation fee | Must Have |
| FR-SET-02 | Settings changes shall be persisted using an upsert mechanism (insert or update) | Must Have |
| FR-SET-03 | Admin shall be able to change the admin password | Must Have |
| FR-SET-04 | Password change shall require the current password for verification | Must Have |
| FR-SET-05 | New passwords shall enforce: minimum 12 characters, at least 3 of 4 character classes (lowercase, uppercase, number, symbol), and must not contain the username or the words mmcs/admin/password/tharparkar/consultancy/welcome. Enforced by `validate_password_strength()` in `includes/functions.php`, shared by `scripts/set_admin.php` and the Security Gate form | Must Have |

---

## 4. Non-Functional Requirements

### 4.1 Security

| ID | Requirement | Priority |
|----|-------------|----------|
| NFR-SEC-01 | All database queries shall use parameterized prepared statements to prevent SQL injection | Must Have |
| NFR-SEC-02 | All user-generated output shall be escaped using `htmlspecialchars()` to prevent XSS | Must Have |
| NFR-SEC-03 | All state-changing operations (POST) shall require a valid CSRF token | Must Have |
| NFR-SEC-04 | File uploads shall validate MIME type, extension, and size (max 5MB) | Must Have |
| NFR-SEC-05 | The uploads directory shall have an `.htaccess` rule blocking PHP/script execution | Must Have |
| NFR-SEC-06 | The uploads directory shall disable directory listing (`Options -Indexes`) | Must Have |
| NFR-SEC-07 | Security headers shall be set: X-Content-Type-Options, X-Frame-Options, Referrer-Policy | Must Have |
| NFR-SEC-08 | Uploaded filenames shall be randomized to prevent path guessing | Must Have |
| NFR-SEC-09 | The configuration file shall block direct browser access | Must Have |
| NFR-SEC-10 | Session cookies shall be set with httponly flag | Must Have |

### 4.2 Performance

| ID | Requirement | Priority |
|----|-------------|----------|
| NFR-PERF-01 | Public pages shall load within 3 seconds on a standard broadband connection | Should Have |
| NFR-PERF-02 | The homepage shall make no more than 5 database queries | Should Have |
| NFR-PERF-03 | Site settings shall be cached per-request to avoid repeated database queries | Should Have |
| NFR-PERF-04 | Frontend assets (CSS, JS) shall be served from a CDN where possible | Should Have |

### 4.3 Usability

| ID | Requirement | Priority |
|----|-------------|----------|
| NFR-USE-01 | The admin panel shall be usable by non-technical staff without training beyond the user guide | Must Have |
| NFR-USE-02 | All admin forms shall provide clear field labels, placeholders, and validation messages | Must Have |
| NFR-USE-03 | All destructive actions (delete) shall require user confirmation | Must Have |
| NFR-USE-04 | All CRUD operations shall provide success/error feedback via flash messages | Must Have |
| NFR-USE-05 | The admin panel shall include a sidebar navigation with clear module labels and icons | Must Have |

### 4.4 Reliability & Availability

| ID | Requirement | Priority |
|----|-------------|----------|
| NFR-REL-01 | Database connection failures shall display a user-friendly error message | Must Have |
| NFR-REL-02 | Public page database failures shall degrade gracefully (sections silently omitted) | Should Have |
| NFR-REL-03 | Admin page database failures shall display an informative error alert | Must Have |

### 4.5 Maintainability

| ID | Requirement | Priority |
|----|-------------|----------|
| NFR-MNT-01 | The codebase shall follow consistent file naming and directory structure conventions | Must Have |
| NFR-MNT-02 | Shared logic (DB connection, utilities, auth) shall be extracted into reusable include files | Must Have |
| NFR-MNT-03 | The database schema shall be version-controlled in a single SQL file | Must Have |
| NFR-MNT-04 | An admin user guide shall be provided documenting all CMS operations | Must Have |

### 4.6 Compatibility

| ID | Requirement | Priority |
|----|-------------|----------|
| NFR-CMP-01 | The platform shall function on XAMPP (Apache + PHP + MySQL) development environments | Must Have |
| NFR-CMP-02 | The platform shall function on standard shared hosting environments supporting PHP 8.0+ | Must Have |
| NFR-CMP-03 | The platform shall not require any PHP framework or Composer dependencies | Must Have |

---

## 5. Data Requirements

### 5.1 Data Entities

The system shall persist the following core data entities:

| Entity | Description |
|--------|-------------|
| Services | Consultancy service offerings |
| Projects | Portfolio/project showcase entries |
| Team Members | Expert/staff profiles |
| Blog Posts | Articles, publications, and resources |
| Inquiries | Contact messages and quote requests |
| Testimonials | Client testimonial quotes |
| Admins | Administrator credentials |
| Opportunities | Job/volunteer/intern postings |
| Applications | Job applications |
| Partners | Client/partner organization logos |
| Settings | Global site configuration (key-value) |
| Organogram | Organizational hierarchy entries |

### 5.2 Data Retention

- All submitted inquiries and applications shall be retained indefinitely unless manually deleted by an administrator.
- Uploaded files shall be retained until the associated record is deleted.
- Failed login logs shall be retained for security audit purposes.

---

## 6. External Interface Requirements

### 6.1 User Interface

- The public website shall use a responsive Bootstrap 5.3 grid system with custom CSS theming.
- The admin panel shall use a fixed sidebar + topbar layout with a responsive content area.
- The design shall use a dark navy (#0f172a) and orange (#FF5C00) brand color scheme.

### 6.2 Email Interface

- The system shall attempt to send email notifications via PHP `mail()` upon form submissions.
- SMTP configuration shall be supported for future upgrade to a dedicated mail library.

### 6.3 Maps Interface

- The contact page shall embed a Google Maps iframe showing the Mithi, Tharparkar office location.

---

## 7. Traceability Matrix

| BRD Requirement | SRS Functional Requirements |
|-----------------|-----------------------------|
| BR-01 through BR-05 | FR-AUTH, FR-DASH, FR-SVC, FR-PROJ, FR-TEAM-A, FR-BLOG-A, FR-TEST, FR-OPP, FR-INQ, FR-QT, FR-PART, FR-ORG, FR-SET |
| BR-06 through BR-10 | FR-HOME, FR-ABOUT, FR-SERVICE, FR-PORT, FR-TEAM, FR-CAREER, FR-BLOG, FR-CONTACT |
| BR-11 through BR-15 | FR-CONTACT, FR-INQUIRY, FR-INQ, FR-QT |
| BR-16 through BR-20 | FR-PORT, FR-TEAM, FR-ABOUT |
| BR-21 through BR-24 | FR-CAREER, FR-OPP |
| BR-25 through BR-27 | FR-BLOG, FR-BLOG-A |
| BR-28 through BR-29 | FR-TEST |
| BR-30 through BR-34 | FR-AUTH, NFR-SEC |

---

## 8. Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Business Analyst | _____________ | _____________ | ____/____/2026 |
| Systems Architect | _____________ | _____________ | ____/____/2026 |
| Project Sponsor | Maha Dev Makwano | _____________ | ____/____/2026 |
| Lead Developer | Vishwas Suthar | _____________ | ____/____/2026 |

---

*This document specifies the software requirements for the MMCS Web Platform. All functional and non-functional requirements herein shall be traceable to the Business Requirements Document (BRD) and validated during system testing.*
