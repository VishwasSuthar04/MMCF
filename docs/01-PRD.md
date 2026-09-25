# 01 — Product Requirements Document (PRD)

**Product:** MMCS (MM Consultancy Solutions) Web Platform
**Version:** 1.0.0
**Last Updated:** 2026-07-14
**Prepared for:** Client Handover — MM Consultancy Solutions (Private) Limited

---

## 1. Product Overview & Purpose

MMCS is a PHP/MySQL content management system serving as the public-facing website and administrative backend for **MM Consultancy Solutions (Private) Limited**, a Tharparkar-based SECP-registered development consultancy firm. The platform enables MMCS to:

- Showcase services, portfolio, team expertise, and organizational structure to prospective NGO/INGO clients
- Accept contact inquiries and service quote requests from development organizations
- Manage blog/publication content and career opportunity postings
- Allow public visitors to apply for job, volunteer, and expert opportunities
- Provide administrators with a full CMS to manage all content types without code changes

**Primary business purpose:** Serve as a digital storefront and client acquisition channel for MMCS consultancy services (MEAL, proposal writing, training, research, field documentation, water quality analysis).

(Confirmed in code — `public/index.php`, `admin/dashboard.php`, `schema.sql`)

---

## 2. Target Users & User Roles

### User Roles Identified in Code

| Role | Access Level | Source |
|------|-------------|--------|
| **Public Visitor** | Read-only access to public pages; can submit contact forms, quote requests, and job applications | `public/*.php` pages |
| **Admin (authenticated)** | Full CRUD access to all content modules, site settings, inquiry management | `includes/auth.php` gate, `$_SESSION['admin_logged_in']` |

> ⚠️ There is only **one role** in the system: Admin. No multi-role or permission granularity exists. All admin users have identical full access.

(Confirmed in code — `includes/auth.php:30`, `includes/functions.php:101-103`)

### Default Admin Account

| Field | Value |
|-------|-------|
| Username | `MahaDev` (intended) |
| Password | *none — not seeded; operator sets their own on install* |
| Hash algorithm | `PASSWORD_DEFAULT` (bcrypt via `password_hash()`) |

(Confirmed in code — `schema.sql` `ADMIN ACCOUNT` block)

---

## 3. Core Features List

### 3.1 Public Website Features

| # | Feature | Source File | Description |
|---|---------|-------------|-------------|
| P1 | Homepage | `public/index.php` | Hero banner, stats counters, featured services (top 6), about callout, testimonials carousel, CTA |
| P2 | About Us | `public/about.php` | Vision/mission, three core pillars, technical expertise areas, company profile (SECP reg, bank details), org chart, geographic footprint, partner logos |
| P3 | Services Listing | `public/services.php` | EEPS model explanation, all 9 service cards, technical expertise areas |
| P4 | Service Detail | `public/service-detail.php` | Single service view with sidebar (quote CTA + other services navigation) |
| P5 | Portfolio | `public/portfolio.php` | Project cards with sector-based client-side filtering (WASH, Education, Climate, Gender, Research, Livelihoods) |
| P6 | Team / Experts | `public/team.php` | Active team member profiles with photo, role, bio, expertise tags |
| P7 | Get Involved | `public/get-involved.php` | Open opportunities grouped by type (expert, intern, volunteer) |
| P8 | Apply for Opportunity | `public/apply.php` | Application form with resume PDF upload and cover letter |
| P9 | Blog / Publications | `public/blog.php` | Published blog posts with PDF attachment downloads |
| P10 | Contact Form | `public/contact.php` | Name, org, email, message — saves to `inquiries` table |
| P11 | Quote Request Form | `public/inquiry.php` | Detailed service inquiry with service type dropdown, budget range, project scope |
| P12 | Health Check | `public/health.php` | JSON endpoint for uptime monitoring |

### 3.2 Admin Panel Features

| # | Feature | Source File | Description |
|---|---------|-------------|-------------|
| A1 | Login / Auth | `admin/login.php` | Username/password auth, brute-force rate limiting (5 attempts/15 min lockout) |
| A2 | Dashboard | `admin/dashboard.php` | Stats cards (messages, quotes, projects, team), recent inquiries/quotes tables |
| A3 | Services CRUD | `admin/services.php` | List/add/edit/delete services with icon, sort order, active toggle |
| A4 | Portfolio CRUD | `admin/portfolio.php` | List/add/edit/delete projects with image upload |
| A5 | Team CRUD | `admin/team.php` | List/add/edit/delete team members with photo upload |
| A6 | Organogram CRUD | `admin/organogram.php` | List/add/edit/delete org chart entries with parent hierarchy |
| A7 | Blog CRUD | `admin/blog.php` | List/add/edit/delete posts with PDF attachment, draft/published status |
| A8 | Opportunities CRUD | `admin/opportunities.php` | List/add/edit/delete job/volunteer/intern postings |
| A9 | Applications Viewer | `admin/opportunities.php?action=applications` | View applicants, cover letters, resume downloads |
| A10 | Inquiries Manager | `admin/inquiries.php` | View/delete contact messages, status workflow (New→Read→Replied), CSV export |
| A11 | Quotes Manager | `admin/quotes.php` | View/delete quote requests, status workflow (Pending→In Progress→Closed), print/PDF |
| A12 | Testimonials CRUD | `admin/testimonials.php` | List/add/edit/delete/approve testimonials |
| A13 | Partners CRUD | `admin/partners.php` | List/add/edit/delete partner logos |
| A14 | Site Settings | `admin/settings.php` | Global config (company name, phone, email, addresses, social links, stats, footer text) |
| A15 | Password Change | `admin/settings.php` (Security Gate) | Password change with policy enforcement |

---

## 4. Feature-by-Feature Breakdown

### 4.1 Contact Form Submission (P10)

**What it does:** Public visitors submit name, organization, email, and message. Data is saved to `inquiries` table with `type='contact'` and `status='New'`. A notification email is attempted via PHP `mail()`.

**Accessible by:** Public Visitor (no auth)

**Key business rules (from code):**
- Name, email, and message are required fields
- Email is validated via `FILTER_VALIDATE_EMAIL`
- CSRF token is required on form submission
- Flash success/error messages displayed after redirect
- Email notification sent to site admin email (best-effort, uses `@mail()`)

(Confirmed in code — `public/contact.php:22-53`)

### 4.2 Quote Request (P11)

**What it does:** Organizations submit a detailed service inquiry with service type selection, budget range, and project scope description. Saved to `inquiries` with `type='inquiry'` and `status='Pending'`.

**Accessible by:** Public Visitor

**Key business rules:**
- Name, org, email, service type, and message required
- Email validated
- Service type dropdown populated from active `services` table
- Pre-selection possible via `?service=` query parameter
- Budget range options: Not Specified, Under $5K, $5K-$15K, $15K-$50K, Over $50K
- Consultation fee badge displayed from settings
- Auto-status on admin view: Pending → In Progress when admin opens detail

(Confirmed in code — `public/inquiry.php:33-65`, `admin/quotes.php:148-152`)

### 4.3 Opportunity Application (P8)

**What it does:** Public visitors apply for open positions by submitting name, email, phone, cover letter, and PDF resume.

**Accessible by:** Public Visitor

**Key business rules:**
- Only shows opportunities with `status='open'` AND deadline is NULL or in the future
- Name and email are required; phone, cover letter, resume are optional
- Resume upload: PDF only, max 5MB
- Applications stored in `applications` table linked by `opportunity_id` (FK with `ON DELETE CASCADE`)
- Success message displayed after submission

(Confirmed in code — `public/apply.php:9-67`)

### 4.4 Testimonial Approval Workflow

**What it does:** Admin can add testimonials and toggle approval status. Only `is_approved=1` testimonials appear on the public homepage carousel.

**Accessible by:** Admin

**Key business rules:**
- Status is binary: Approved (1) / Pending (0)
- Toggle via `?toggle_id=N` URL parameter
- Admin can also set approval at creation time via checkbox
- Photo upload supported (saved to `uploads/team/` directory)

(Confirmed in code — `admin/testimonials.php:22-37`)

### 4.5 Blog Post Status Workflow

**What it does:** Admin creates posts with draft/published status. Only `status='published'` posts appear on the public blog page.

**Accessible by:** Admin (create/edit/delete), Public Visitor (read published)

**Key business rules:**
- Two statuses: `draft` (hidden) and `published` (visible)
- PDF attachment upload supported (saved to `uploads/blog/`)
- Categories: Research, Policy, Guides, News
- Published date auto-set on creation (`CURRENT_TIMESTAMP`)
- Published date NOT updated on edit (Inferred from code — no explicit update in edit query)

(Confirmed in code — `admin/blog.php:43-58`, `public/blog.php:19`)

### 4.6 Inquiry Status Workflows

**Contact Messages:**
- Statuses: New → Read → Replied
- Auto-marks as "Read" when admin views message detail
- Manual status change via dropdown
- CSV export of all contact messages

(Confirmed in code — `admin/inquiries.php:60-73,99-104`)

**Quote Requests:**
- Statuses: Pending → In Progress → Closed
- Auto-marks as "In Progress" when admin views quote detail
- Manual status change via dropdown
- Print/PDF generation for individual quotes

(Confirmed in code — `admin/quotes.php:107-121,147-152`)

### 4.7 Organogram Hierarchy Management

**What it does:** Admin builds a multi-level organizational chart with parent-child relationships.

**Key business rules:**
- Self-referencing parent is blocked (person cannot report to themselves)
- Circular hierarchy detection (walks up parent chain to prevent cycles)
- Deletion blocked if entry has child members
- Photo upload supported
- Display order controls sibling ordering

(Confirmed in code — `admin/organogram.php:32,56-73,99-106`)

### 4.8 Site Settings Management

**What it does:** Admin updates global site configuration (company info, contacts, social links, stats, footer text, consultation fee).

**Key business rules:**
- Uses `INSERT ... ON DUPLICATE KEY UPDATE` to upsert settings
- Settings are cached per-request via static variable in `get_setting()`
- Password change enforces: min 12 chars, at least 3 of 4 character classes, no username or site words (shared `validate_password_strength()` policy)
- Password change requires current password verification

(Confirmed in code — `admin/settings.php:30-56,61-97`)

---

## 5. Out-of-Scope / Not Implemented

| Item | Status | Evidence |
|------|--------|----------|
| Multi-admin roles / permissions | Not implemented | Only single `admins` table with no role column |
| Email notifications (SMTP) | Placeholder only | Uses `@mail()` with suppressed errors; SMTP constants in config are placeholders |
| Blog detail page | Not implemented | `blog.php` shows full content inline; no separate detail view |
| Service detail dynamic content | Partially implemented | "Typical Project Deliverables" section in `service-detail.php` is hardcoded, not from DB |
| Search functionality | Not implemented | No search feature across any content type |
| Pagination | Not implemented | All list views load all records |
| Image gallery / multiple images per project | Not implemented | `projects.images` column described as "JSON or Comma-separated" in schema comment but only stores single path in code |
| User registration / public accounts | Not implemented | No public user accounts |
| Password reset / forgot password | Not implemented | ADMIN_GUIDE.md mentions "Click Forgot Password?" but no code implements it |
| Backup automation | Partially implemented | README mentions `scripts/backup.bat` but file not present in codebase |
| RSS feed | Not implemented | No RSS/Atom feed |
| Multi-language support | Not implemented | English only |
| API / REST endpoints | Not implemented | Only health check returns JSON |

---

## 6. Success Criteria / Acceptance Criteria

### Homepage
- [ ] Loads within 3 seconds on standard connection
- [ ] Hero banner displays with correct company name and stats
- [ ] Top 6 active services render with icons and truncated descriptions
- [ ] Testimonials carousel displays only approved testimonials
- [ ] Mobile hamburger menu functions correctly

### Admin Panel
- [ ] Login with default credentials succeeds
- [ ] Session expires after 30 minutes of inactivity
- [ ] Rate limiting blocks after 5 failed login attempts for 15 minutes
- [ ] All CRUD operations (services, team, portfolio, blog, testimonials, partners, organogram, opportunities) function correctly
- [ ] CSRF tokens are required on all POST forms
- [ ] File uploads validate MIME type and size (5MB max)
- [ ] Inquiries and quotes can be viewed, status changed, and deleted
- [ ] CSV export produces valid downloadable file
- [ ] Settings changes reflect on public site after page refresh
- [ ] Password change enforces policy and updates session

### Public Forms
- [ ] Contact form saves to database and shows success message
- [ ] Quote request form saves with correct type='inquiry' and status='Pending'
- [ ] Application form saves with linked opportunity_id and optional resume upload
- [ ] All forms validate required fields and email format client-side and server-side
- [ ] CSRF tokens prevent cross-site request forgery

### Security
- [ ] No PHP execution possible in uploads directory (verified via .htaccess)
- [ ] All output escaped via `htmlspecialchars()` (XSS prevention)
- [ ] All database queries use PDO prepared statements (SQL injection prevention)
- [ ] Security headers present: X-Content-Type-Options, X-Frame-Options, Referrer-Policy
- [ ] Direct access to config.php blocked

---

*Document generated from codebase analysis. All features marked as "Confirmed in code" are traced to specific file locations.*
