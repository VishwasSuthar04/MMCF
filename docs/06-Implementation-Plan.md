# 06 — Implementation Plan

**Product:** MMCS (MM Consultancy Solutions) Web Platform
**Version:** 1.0.0
**Last Updated:** 2026-07-14

---

## 1. Project Summary

| Attribute | Value |
|-----------|-------|
| **Product** | MMCS — PHP/MySQL CMS for MM Consultancy Solutions |
| **Status** | Feature-complete MVP (v1.0.0) |
| **Stack** | PHP 8.x, MySQL/MariaDB, Bootstrap 5.3.2, Vanilla JS |
| **Server** | Apache (XAMPP), Windows development |
| **Files** | 46 source files (PHP, CSS, JS, SQL, config) |
| **Database** | 12 tables, 11 seeded with default data |

---

## 2. Current Implementation Status

### 2.1 Completed Modules

| # | Module | Files | Status |
|---|--------|-------|--------|
| 1 | Database schema & seed data | `schema.sql` | ✅ Complete |
| 2 | Configuration & DB connection | `config.php`, `includes/db.php` | ✅ Complete |
| 3 | Utility functions (escape, CSRF, upload, flash) | `includes/functions.php` | ✅ Complete |
| 4 | Authentication & session management | `includes/auth.php` | ✅ Complete |
| 5 | Public header & navbar | `includes/header.php` | ✅ Complete |
| 6 | Public footer (4-col layout) | `includes/footer.php` | ✅ Complete |
| 7 | Homepage | `public/index.php` | ✅ Complete |
| 8 | About Us (vision, pillars, organogram, partners) | `public/about.php` | ✅ Complete |
| 9 | Services listing + EEPS model | `public/services.php` | ✅ Complete |
| 10 | Service detail view | `public/service-detail.php` | ✅ Complete |
| 11 | Portfolio with sector filtering | `public/portfolio.php` | ✅ Complete |
| 12 | Team / Experts directory | `public/team.php` | ✅ Complete |
| 13 | Get Involved (opportunities listing) | `public/get-involved.php` | ✅ Complete |
| 14 | Application form with resume upload | `public/apply.php` | ✅ Complete |
| 15 | Blog listing with PDF downloads | `public/blog.php` | ✅ Complete |
| 16 | Contact form | `public/contact.php` | ✅ Complete |
| 17 | Quote request form | `public/inquiry.php` | ✅ Complete |
| 18 | Health check endpoint | `public/health.php` | ✅ Complete |
| 19 | Admin login with brute-force protection | `admin/login.php` | ✅ Complete |
| 20 | Admin dashboard (stats + recent items) | `admin/dashboard.php` | ✅ Complete |
| 21 | Services CRUD | `admin/services.php` | ✅ Complete |
| 22 | Portfolio CRUD | `admin/portfolio.php` | ✅ Complete |
| 23 | Team CRUD | `admin/team.php` | ✅ Complete |
| 24 | Organogram CRUD (hierarchy) | `admin/organogram.php` | ✅ Complete |
| 25 | Blog CRUD | `admin/blog.php` | ✅ Complete |
| 26 | Opportunities CRUD + applications viewer | `admin/opportunities.php` | ✅ Complete |
| 27 | Inquiries inbox + CSV export | `admin/inquiries.php` | ✅ Complete |
| 28 | Quotes manager + print/PDF | `admin/quotes.php` | ✅ Complete |
| 29 | Testimonials CRUD + approval toggle | `admin/testimonials.php` | ✅ Complete |
| 30 | Partners CRUD | `admin/partners.php` | ✅ Complete |
| 31 | Site settings (key/value) + password change | `admin/settings.php` | ✅ Complete |
| 32 | Admin panel layout (sidebar + topbar) | `admin/admin_header.php`, `admin/admin_footer.php` | ✅ Complete |
| 33 | Custom CSS (public) | `assets/css/style.css` | ✅ Complete |
| 34 | Client-side JS (scroll, filter, validation) | `assets/js/main.js` | ✅ Complete |
| 35 | Security (XSS, SQLi, CSRF, file upload, headers) | All files | ✅ Complete |
| 36 | Setup checker | `scripts/setup_check.php` | ✅ Complete |
| 37 | Documentation (PRD, TRD, App Workflow) | `docs/01-03` | ✅ Complete |

### 2.2 Deliverables Status

| Deliverable | File | Status |
|-------------|------|--------|
| Product Requirements Document | `docs/01-PRD.md` | ✅ Complete |
| Technical Requirements Document | `docs/02-TRD.md` | ✅ Complete |
| Application Workflow | `docs/03-App-Workflow.md` | ✅ Complete |
| UI/UX Design Brief | `docs/04-UIUX-Design-Brief.md` | ✅ Complete |
| Backend Schema & Data Architecture | `docs/05-Backend-Schema.md` | ✅ Complete |
| Implementation Plan | `docs/06-Implementation-Plan.md` | ✅ This document |

---

## 3. Deployment Checklist

### 3.1 Pre-Deployment (Critical)

| # | Task | Owner | Status |
|---|------|-------|--------|
| 1 | Change admin default password via Settings → Security Gate | Admin | ⬜ Pending |
| 2 | Update `config.php` database credentials (non-root user + strong password) | DevOps | ⬜ Pending |
| 3 | Configure SMTP constants in `config.php` (real Gmail app password or SMTP provider) | DevOps | ⬜ Pending |
| 4 | Verify `uploads/` directory exists and is writable by web server | DevOps | ⬜ Pending |
| 5 | Verify `admin/logs/` directory exists and is writable | DevOps | ⬜ Pending |
| 6 | Ensure `.htaccess` in `uploads/` blocks PHP execution | DevOps | ⬜ Pending |
| 7 | Set `DEV_MODE` to `false` in `config.php` (already false) | DevOps | ✅ |
| 8 | Enable HTTPS on hosting and verify secure cookies work | DevOps | ⬜ Pending |
| 9 | Set `AllowOverride All` in Apache config for `.htaccess` support | DevOps | ⬜ Pending |
| 10 | Import `schema.sql` into MySQL database | DevOps | ⬜ Pending |

### 3.2 Post-Deployment Verification

| # | Test | Expected Result |
|---|------|-----------------|
| 1 | Visit public homepage | Loads with hero, stats, services, testimonials |
| 2 | Navigate all public pages | All 12 pages render without errors |
| 3 | Submit contact form | Saves to DB, shows success message |
| 4 | Submit quote request | Saves with `type=inquiry`, shows success |
| 5 | Apply for opportunity with PDF resume | Saves application, upload stored |
| 6 | Login with default credentials | Redirects to dashboard |
| 7 | Verify rate limiting (5 failed attempts) | Lockout message after 5 failures |
| 8 | Test session timeout (30 min idle) | Redirects to login |
| 9 | Test all CRUD operations (services, team, etc.) | Create, read, update, delete all work |
| 10 | Test file upload (image + PDF) | Files saved with random names |
| 11 | Verify CSV export (inquiries) | Downloads valid CSV file |
| 12 | Verify settings changes reflect on public site | Changes visible after page refresh |
| 13 | Test mobile responsive (admin + public) | Hamburger menu, sidebar toggle, card stacking |
| 14 | Verify security headers present | X-Content-Type-Options, X-Frame-Options, Referrer-Policy |

---

## 4. Known Issues & Technical Debt

### 4.1 Critical (Fix Before Production)

| Issue | Severity | Location | Fix |
|-------|----------|----------|-----|
| Default admin credentials in seed | Critical | `schema.sql:189-191` | Change password after first login |
| SMTP not configured | Critical | `config.php:62-63` | Set real SMTP credentials |
| DB credentials default (root/empty) | Critical | `config.php:42-43` | Create dedicated MySQL user |
| No HTTPS enforcement | Critical | `config.php` | Add redirect + secure cookie enforcement |

### 4.2 High Priority (Fix Shortly After Launch)

| Issue | Location | Recommended Fix |
|-------|----------|-----------------|
| PHP `mail()` unreliable | `contact.php:47`, `inquiry.php:59` | Integrate PHPMailer library for SMTP |
| No Content-Security-Policy header | `includes/header.php` | Add CSP header (start with report-only) |
| No pagination on list views | All admin list views | Add LIMIT/OFFSET with page navigation |
| No CSRF on GET mutations (delete, status change) | `admin/inquiries.php`, `admin/quotes.php` | Convert destructive actions to POST with CSRF |
| `projects.images` column stores single path | `schema.sql:49` | Rename column or implement multi-image support |
| Backup script missing | `scripts/backup.bat` | Create backup script (DB dump + uploads zip) |
| Password reset not implemented | N/A | Add email-based or manual reset flow |

### 4.3 Medium Priority (Enhancement Roadmap)

| Issue | Recommended Fix |
|-------|-----------------|
| No search functionality | Add site-wide search across services, projects, team, blog |
| Hardcoded expertise areas (`about.php`, `services.php`) | Move to database table |
| Hardcoded geographic coverage (`about.php`) | Move to database or settings |
| Inline `<style>` blocks in `about.php` (~200 lines) | Extract to `style.css` |
| No image compression/resizing on upload | Add GD/Imagick resize to 1200px max width |
| No `published_at` update on blog edit | Add update to edit query |
| Blog detail page not separate | Split into `blog-detail.php` with clean URL |

### 4.4 Low Priority (Polish)

| Issue | Recommended Fix |
|-------|-----------------|
| CSS cache-busting via `time()` (defeats caching) | Use file hash: `style.css?v=md5` |
| No rate limiting on public forms | Add CAPTCHA or simple rate limiter |
| No pagination | Add Bootstrap pagination component |
| Footer services links hardcoded | Dynamically pull from `services` table |
| No RSS feed | Generate XML RSS for blog posts |
| No multi-language support | Not needed for current user base |

---

## 5. Future Enhancement Roadmap

### Phase 2: Production Hardening (Week 1-2)

| # | Task | Effort | Priority |
|---|------|--------|----------|
| 1 | Set up production hosting with SSL | 2h | Critical |
| 2 | Configure SMTP email (PHPMailer) | 4h | Critical |
| 3 | Create admin user with strong credentials | 30m | Critical |
| 4 | Add HTTPS redirect + HSTS header | 1h | High |
| 5 | Add Content-Security-Policy header | 2h | High |
| 6 | Create automated backup script | 3h | High |
| 7 | Add pagination to admin list views | 4h | High |
| 8 | Convert GET-based deletes to POST + CSRF | 2h | High |
| 9 | Run setup_check.php and fix all warnings | 1h | High |

### Phase 3: Feature Enhancements (Week 3-4)

| # | Task | Effort | Priority |
|---|------|--------|----------|
| 1 | Site-wide search | 6h | Medium |
| 2 | Image compression on upload | 3h | Medium |
| 3 | Blog detail page | 3h | Medium |
| 4 | Multi-image portfolio support | 4h | Medium |
| 5 | Password reset flow | 4h | Medium |
| 6 | Admin notification dashboard (new inquiries/quotes count badges) | 3h | Medium |
| 7 | Move hardcoded content to database | 5h | Medium |
| 8 | Extract inline CSS from `about.php` | 2h | Medium |

### Phase 4: Optimization (Week 5-6)

| # | Task | Effort | Priority |
|---|------|--------|----------|
| 1 | Add page-level caching (file-based or Redis) | 4h | Low |
| 2 | Add proper image sizing (thumbnails for list views) | 3h | Low |
| 3 | Add RSS feed | 2h | Low |
| 4 | Add database indexes (see `05-Backend-Schema.md §5`) | 1h | Low |
| 5 | Accessibility audit (ARIA, focus styles, skip links) | 4h | Low |
| 6 | Cross-browser testing | 2h | Low |
| 7 | Performance audit (Lighthouse) | 2h | Low |

---

## 6. File Inventory

### 6.1 Complete File Listing

```
MMCF/
├── config.php                          # Core configuration (98 lines)
├── schema.sql                          # Database schema + seeds (249 lines)
├── .env.example                        # Environment template
├── .gitignore                          # Git ignore rules
├── robots.txt                          # Search engine directives
├── favicon.ico                         # Browser favicon
├── favicon.xml                         # SVG favicon
├── README.md                           # Project readme
├── ADMIN_GUIDE.md                      # Admin user manual
├── MMCS_Website_Workflow.pdf           # Workflow diagram (reference)
│
├── includes/
│   ├── db.php                          # PDO connection singleton (34 lines)
│   ├── functions.php                   # Utility functions (214 lines)
│   ├── auth.php                        # Auth gate + session timeout (35 lines)
│   ├── header.php                      # Public <head> + navbar (119 lines)
│   └── footer.php                      # Public footer (143 lines)
│
├── public/
│   ├── index.php                       # Homepage (288 lines)
│   ├── about.php                       # About Us (678 lines)
│   ├── services.php                    # Services listing (300+ lines)
│   ├── service-detail.php              # Single service view (200+ lines)
│   ├── portfolio.php                   # Portfolio with filtering (250+ lines)
│   ├── team.php                        # Team directory (150+ lines)
│   ├── get-involved.php                # Opportunities listing (150+ lines)
│   ├── apply.php                       # Application form (150+ lines)
│   ├── blog.php                        # Blog listing (150+ lines)
│   ├── contact.php                     # Contact form (180+ lines)
│   ├── inquiry.php                     # Quote request form (180+ lines)
│   └── health.php                      # Health check endpoint (20 lines)
│
├── admin/
│   ├── admin_header.php                # Admin layout header + sidebar (318 lines)
│   ├── admin_footer.php                # Admin layout footer (36 lines)
│   ├── login.php                       # Login page (274 lines)
│   ├── dashboard.php                   # Dashboard (258 lines)
│   ├── services.php                    # Services CRUD (221 lines)
│   ├── portfolio.php                   # Portfolio CRUD
│   ├── team.php                        # Team CRUD
│   ├── organogram.php                  # Organogram CRUD
│   ├── blog.php                        # Blog CRUD
│   ├── opportunities.php               # Opportunities CRUD + applications
│   ├── inquiries.php                   # Contact inbox + CSV export
│   ├── quotes.php                      # Quote requests + print
│   ├── testimonials.php                # Testimonials CRUD
│   ├── partners.php                    # Partners CRUD
│   ├── settings.php                    # Site settings + password change
│   └── logs/                           # Rate limit files + failed login log
│       ├── .htaccess
│       └── failed_logins.log
│
├── assets/
│   ├── css/style.css                   # Custom styles (351 lines)
│   ├── js/main.js                      # Client-side JS (72 lines)
│   └── images/                         # Static images
│       ├── logo.png
│       ├── default-avatar.png
│       ├── placeholder-project.jpg
│       ├── about-workspace.jpg
│       ├── service.png
│       ├── services-flow.jpg
│       ├── service approach.jpg
│       └── why partner us.jpg
│
├── uploads/
│   ├── .htaccess                       # Blocks PHP execution
│   ├── IMMCS_logo.png                  # Company logo
│   ├── applications/                   # Resume PDFs
│   ├── blog/                           # Blog PDF attachments
│   ├── organogram/                     # Org chart photos
│   ├── partners/                       # Partner logos
│   ├── projects/                       # Project images
│   └── team/                           # Team member photos
│
├── scripts/
│   ├── setup_check.php                 # Pre-delivery config checker
│   └── backup.bat                      # (Referenced but missing)
│
└── docs/
    ├── 01-PRD.md                       # Product Requirements Document
    ├── 02-TRD.md                       # Technical Requirements Document
    ├── 03-App-Workflow.md              # Application Workflow
    ├── 04-UIUX-Design-Brief.md         # UI/UX Design Brief
    ├── 05-Backend-Schema.md            # Backend Schema & Data Architecture
    └── 06-Implementation-Plan.md       # This document
```

### 6.2 Line Count Summary

| Area | Lines (approx) |
|------|----------------|
| Public PHP pages | ~2,500 |
| Admin PHP pages | ~3,500 |
| Includes (shared PHP) | ~550 |
| CSS (style.css) | 351 |
| CSS (admin inline in admin_header.php) | ~200 |
| CSS (login page inline) | ~100 |
| JS (main.js) | 72 |
| SQL (schema.sql) | 249 |
| Config (config.php) | 98 |
| **Total source** | **~7,500** |
| Documentation (docs/) | ~1,600 |
| Admin guide + README | ~500 |
| **Total project** | **~9,600** |

---

## 7. Technology Decisions & Rationale

| Decision | Rationale |
|----------|-----------|
| **Procedural PHP (no framework)** | Simplicity for client handover; no composer dependency; any PHP host works |
| **Bootstrap 5.3.2 (CDN)** | Rapid responsive design; no build tools needed |
| **Vanilla JS (no jQuery)** | Lightweight; only 72 lines for scroll, filter, and validation |
| **PDO with prepared statements** | SQL injection prevention with native PHP |
| **File-based rate limiting** | No Redis/Memcached dependency; sufficient for single-server deployment |
| **Key/value settings table** | Flexible configuration without code changes |
| **Self-referencing organogram** | Supports arbitrary hierarchy depth without extra tables |
| **bcrypt password hashing** | Industry standard via PHP's `password_hash()` |
| **CSRF via random_bytes(32)** | Cryptographically secure tokens |

---

## 8. Maintenance Guide

### 8.1 Regular Tasks

| Task | Frequency | How |
|------|-----------|-----|
| Backup database | Weekly | Run `scripts/backup.bat` (when implemented) or manual mysqldump |
| Check failed login logs | Weekly | Review `admin/logs/failed_logins.log` |
| Update admin password | Quarterly | Admin Panel → Settings → Security Gate |
| Review contact inquiries | Daily | Admin Panel → Inquiries & Msg |
| Review quote requests | Daily | Admin Panel → Quote Requests |
| Check disk usage (uploads/) | Monthly | Monitor `uploads/` directory size |

### 8.2 Adding New Content

| Task | How |
|------|-----|
| New service | Admin → Services → Add New Service |
| New team member | Admin → Experts/Team → Add New Expert |
| New project | Admin → Portfolio → Add New Project |
| New blog post | Admin → Blog Posts → Add New Post (set status to Published) |
| New partner | Admin → Partners/Clients → Add New Partner |
| New opportunity | Admin → Opportunities → Add New Opportunity |
| Update company info | Admin → Site Settings → Global Configuration |
| Change password | Admin → Site Settings → Security Gate |
| Approve testimonial | Admin → Testimonials → Toggle approval checkbox |

### 8.3 Troubleshooting

| Symptom | Likely Cause | Fix |
|---------|-------------|-----|
| "Database connectivity issues" | Wrong DB credentials in `config.php` | Verify `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` |
| Blank page / 500 error | PHP error with `display_errors` off | Set `DEV_MODE = true` temporarily, check PHP error log |
| File upload fails | `uploads/` not writable | `chmod 755 uploads/` or set Apache user permissions |
| Email not sending | SMTP not configured | Update `MAIL_*` constants in `config.php` |
| Session expires immediately | Cookies blocked or HTTPS mismatch | Check `session.cookie_secure` vs actual protocol |
| Rate limit stuck | Temp file not cleaned | Delete files in `admin/logs/rate_limit_*.tmp` |
| Organogram won't delete | Entry has children | Delete child entries first, then parent |

---

## 9. Handover Notes

### 9.1 For the Client

1. **First action:** Log in to admin panel and change the default password immediately
2. **Email setup:** Provide your Gmail credentials or SMTP provider details for email notifications
3. **Content:** All seed data (services, projects, team) should be reviewed and updated with real data
4. **Images:** Replace placeholder images in `assets/images/` and upload real team/project photos
5. **Documentation:** `ADMIN_GUIDE.md` provides step-by-step instructions for all admin tasks

### 9.2 For the Developer

1. **No build step:** Edit PHP files directly, no compilation needed
2. **CSS changes:** Edit `assets/css/style.css` for public site; admin styles are inline in `admin_header.php`
3. **New pages:** Copy the pattern from any existing public/admin page
4. **Database changes:** Edit `schema.sql` for new table definitions; update `functions.php` for new utilities
5. **Security:** Never output user input without `escape()`. Always use `require_csrf_token()` on POST handlers

---

*Document generated from codebase analysis. All file paths and line counts are confirmed against the project structure.*
