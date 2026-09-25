# System Architecture Design

**Project:** MMCS Web Platform — MM Consultancy Solutions (Private) Limited
**Version:** 1.0
**Date:** January 2026
**Prepared by:** Systems Architecture Team
**Reference:** SRS v1.0

---

## 1. Architecture Overview

### 1.1 Architectural Approach

The MMCS Web Platform follows a **procedural, file-based PHP architecture** with no MVC framework, no routing library, and no ORM. This architecture is chosen for the following reasons:

- **Zero-dependency deployment:** No Composer, no npm, no build step. Any PHP host with Apache and MySQL can run the platform.
- **Maintainability for non-developers:** Simple PHP files with mixed HTML/PHP are straightforward for a handover developer to understand and modify.
- **Budget constraint:** No framework license fees or recurring cloud service costs.
- **Scale appropriateness:** The platform serves a single organization with modest traffic; enterprise architecture patterns are unnecessary overhead.

### 1.2 High-Level Architecture Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                         CLIENT                               │
│                                                              │
│   ┌──────────┐    ┌──────────┐    ┌──────────────────┐     │
│   │ Desktop  │    │ Mobile   │    │ Search Engines   │     │
│   │ Browser  │    │ Browser  │    │ (robots.txt)     │     │
│   └────┬─────┘    └────┬─────┘    └────────┬─────────┘     │
│        └───────────────┼───────────────────┘                │
│                        │ HTTPS                              │
└────────────────────────┼────────────────────────────────────┘
                         │
┌────────────────────────┼────────────────────────────────────┐
│                    WEB SERVER                                │
│                     Apache 2.4+                              │
│                                                              │
│   ┌─────────────────────────────────────────────────────┐   │
│   │                  URL ROUTING                         │   │
│   │   Browser Request → Direct File Path Mapping         │   │
│   │   (No router — each URL maps to a PHP file)         │   │
│   └──────────────────────┬──────────────────────────────┘   │
│                          │                                   │
│   ┌──────────────────────┼──────────────────────────────┐   │
│   │              PHP APPLICATION LAYER                   │   │
│   │                                                      │   │
│   │   ┌──────────┐  ┌────────────┐  ┌──────────────┐   │   │
│   │   │ config   │  │ includes/  │  │ Page Files   │   │   │
│   │   │ .php     │  │ db.php     │  │ public/*.php │   │   │
│   │   │          │  │ functions  │  │ admin/*.php  │   │   │
│   │   │ (boot)   │  │ auth.php   │  │              │   │   │
│   │   │          │  │ header.php │  │ (business    │   │   │
│   │   │          │  │ footer.php │  │  logic +     │   │   │
│   │   │          │  │            │  │  HTML render)│   │   │
│   │   └──────────┘  └────────────┘  └──────────────┘   │   │
│   │                                                      │   │
│   └──────────────────────┬──────────────────────────────┘   │
│                          │                                   │
│   ┌──────────────────────┼──────────────────────────────┐   │
│   │                    DATA LAYER                        │   │
│   │                                                      │   │
│   │   ┌──────────────┐     ┌────────────────────────┐   │   │
│   │   │ MySQL /      │     │ Filesystem             │   │   │
│   │   │ MariaDB      │     │ uploads/               │   │   │
│   │   │ (PDO)        │     │ (images, PDFs)         │   │   │
│   │   └──────────────┘     └────────────────────────┘   │   │
│   │                                                      │   │
│   └──────────────────────────────────────────────────────┘   │
│                                                              │
└──────────────────────────────────────────────────────────────┘
```

---

## 2. Layered Architecture

### 2.1 Presentation Layer

Responsible for HTML rendering and client-side behavior.

| Component | Technology | Responsibility |
|-----------|------------|----------------|
| HTML Templates | PHP mixed with HTML | Server-side rendering of all pages |
| CSS Framework | Bootstrap 5.3.2 (CDN) | Grid system, components, responsive utilities |
| Custom CSS | `assets/css/style.css` | Brand theming, custom components, animations |
| Admin CSS | Inline `<style>` in `admin_header.php` | Admin panel layout, sidebar, tables |
| Client JS | Vanilla JavaScript (`assets/js/main.js`) | Navbar scroll effect, portfolio filter, form validation |
| Icons | Bootstrap Icons 1.11.2 (CDN) | All iconography |

### 2.2 Application Layer

Responsible for business logic, request processing, and response generation.

| Component | Files | Responsibility |
|-----------|-------|----------------|
| Configuration | `config.php` | Constants, session setup, environment config |
| Database Connection | `includes/db.php` | PDO singleton connection |
| Utility Functions | `includes/functions.php` | Input escaping, CSRF token management, file upload, flash messages |
| Authentication | `includes/auth.php` | Session validation, idle timeout enforcement |
| Public Pages | `public/*.php` (12 files) | Each file handles its own GET/POST, queries, and HTML output |
| Admin Pages | `admin/*.php` (14 files) | Each file handles CRUD operations, queries, and HTML output |
| Shared Layouts | `includes/header.php`, `includes/footer.php`, `admin/admin_header.php`, `admin/admin_footer.php` | Common HTML structure, navigation, footer |

### 2.3 Data Layer

Responsible for data persistence, retrieval, and storage.

| Component | Technology | Responsibility |
|-----------|------------|----------------|
| Relational Database | MySQL / MariaDB | Persistent storage for all content, users, settings |
| Database Driver | PDO (PHP Data Objects) | Secure, parameterized query execution |
| File Storage | Local filesystem (`uploads/`) | Image uploads, PDF attachments, resumes |
| Settings Cache | Static variable in PHP | Per-request caching of all settings (avoids repeated queries) |

---

## 3. Request Processing Flow

### 3.1 Public Page Request

```
1. Browser sends GET/POST to /public/<page>.php
2. Apache maps request to physical file (no routing)
3. Page file includes config.php (session start, constants)
4. config.php triggers includes/functions.php → includes/db.php
   (PDO singleton connection established)
5. Page-specific PHP code executes:
   a. Parses $_GET/$_POST parameters
   b. Executes database queries
   c. Processes form submissions (if POST)
6. HTML is rendered with includes/header.php + includes/footer.php
7. Response sent to browser
```

### 3.2 Admin Page Request

```
1. Browser sends GET/POST to /admin/<page>.php
2. Apache maps request to physical file
3. admin/admin_header.php includes includes/auth.php
4. auth.php checks $_SESSION['admin_logged_in']:
   a. If not set → flash warning → redirect to login.php
   b. If expired → destroy session → redirect to login.php
   c. If active → update last_activity timestamp → continue
5. Page-specific PHP code executes (same as public)
6. HTML rendered with admin/admin_header.php + admin/admin_footer.php
7. Response sent to browser
```

### 3.3 Login Request

```
1. Browser sends POST to admin/login.php
2. CSRF token validated
3. Rate limit check (5 attempts per 15 minutes per IP)
4. Username looked up in admins table (PDO prepared statement)
5. password_verify() validates submitted password against stored bcrypt hash
6. On success:
   a. Rate limit file cleared
   b. session_regenerate_id(true) — prevents session fixation
   c. Session variables set (admin_logged_in, admin_user_id, admin_username, last_activity)
   d. New CSRF token generated
   e. last_login timestamp updated
   f. Redirect to dashboard.php
7. On failure:
   a. Rate limit counter incremented (file-based)
   b. Failed attempt logged to admin/logs/failed_logins.log
   c. Generic error message displayed
```

---

## 4. Directory Structure & File Organization

### 4.1 Proposed Structure

```
MMCF/
├── config.php                    # Central configuration (DB, SMTP, session, uploads)
├── schema.sql                    # Database DDL + seed data
├── .env.example                  # Environment variable template
│
├── includes/                     # Shared PHP components
│   ├── db.php                    # PDO connection singleton
│   ├── functions.php             # Utility functions (escape, CSRF, upload, flash)
│   ├── auth.php                  # Auth gate + session timeout
│   ├── header.php                # Public <head> + navbar
│   └── footer.php                # Public footer with DB-driven content
│
├── public/                       # Public-facing pages
│   ├── index.php                 # Homepage
│   ├── about.php                 # About Us
│   ├── services.php              # Services listing
│   ├── service-detail.php        # Single service detail
│   ├── portfolio.php             # Portfolio with filtering
│   ├── team.php                  # Expert directory
│   ├── get-involved.php          # Opportunity listings
│   ├── apply.php                 # Application form
│   ├── blog.php                  # Blog & publications
│   ├── contact.php               # Contact form + office info
│   ├── inquiry.php               # Quote request form
│   └── health.php                # JSON health check endpoint
│
├── admin/                        # Admin panel
│   ├── admin_header.php          # Auth gate + sidebar + topbar
│   ├── admin_footer.php          # Closing tags + JS
│   ├── login.php                 # Admin login
│   ├── dashboard.php             # Stats overview + logout
│   ├── services.php              # Services CRUD
│   ├── team.php                  # Team CRUD
│   ├── portfolio.php             # Portfolio CRUD
│   ├── blog.php                  # Blog CRUD
│   ├── testimonials.php          # Testimonials CRUD
│   ├── opportunities.php         # Opportunities CRUD + applications
│   ├── inquiries.php             # Contact inbox + CSV export
│   ├── quotes.php                # Quote inbox + print
│   ├── partners.php              # Partners CRUD
│   ├── organogram.php            # Org chart CRUD
│   ├── settings.php              # Site config + password change
│   └── logs/                     # Rate limit files + failed login log
│       └── .htaccess             # Block direct browser access
│
├── assets/
│   ├── css/style.css             # Custom CSS (public site)
│   ├── js/main.js                # Client-side JavaScript
│   └── images/                   # Static images (logo, placeholders)
│
├── uploads/                      # User-uploaded content
│   ├── .htaccess                 # Block PHP execution
│   ├── team/                     # Team member photos
│   ├── projects/                 # Project images
│   ├── blog/                     # Blog PDF attachments
│   ├── partners/                 # Partner logos
│   ├── applications/             # Resume PDFs
│   └── organogram/               # Org chart photos
│
├── scripts/
│   └── setup_check.php           # Pre-delivery configuration validator
│
├── robots.txt                    # Search engine directives
├── favicon.ico / favicon.xml     # Favicons
├── README.md                     # Project documentation
└── ADMIN_GUIDE.md                # Admin user manual
```

### 4.2 Naming Conventions

| Category | Convention | Example |
|----------|-----------|---------|
| Public pages | Lowercase, hyphenated | `service-detail.php`, `get-involved.php` |
| Admin pages | Lowercase, singular nouns | `services.php`, `team.php`, `portfolio.php` |
| Include files | Lowercase, underscored | `admin_header.php`, `admin_footer.php` |
| Upload directories | Lowercase, plural | `uploads/team/`, `uploads/projects/` |
| CSS classes | Bootstrap conventions + custom prefixed | `.premium-card`, `.btn-orange`, `.hero-banner` |
| Database tables | Lowercase, plural, snake_case | `team_members`, `blog_posts`, `page_views` |

---

## 5. Security Architecture

### 5.1 Authentication & Session Management

| Mechanism | Implementation |
|-----------|---------------|
| Password Storage | bcrypt via `password_hash()` with `PASSWORD_DEFAULT` |
| Password Verification | `password_verify()` on login |
| Session Fixation Prevention | `session_regenerate_id(true)` on successful login |
| Session Idle Timeout | 30 minutes (configurable via `SESSION_TIMEOUT` constant) |
| Session Cookie Security | httponly=1, use_only_cookies=1, secure=1 (HTTPS) |
| CSRF Protection | 64-char hex token via `random_bytes(32)`, validated with `hash_equals()` |
| Brute-force Protection | File-based: 5 attempts / 15 minutes per IP |

### 5.2 Input/Output Protection

| Vulnerability | Countermeasure |
|---------------|---------------|
| SQL Injection | PDO prepared statements with `ATTR_EMULATE_PREPARES => false` |
| XSS (Reflected) | All output escaped via `htmlspecialchars($var, ENT_QUOTES, 'UTF-8')` |
| XSS (Stored) | Same escaping applied at output time (stored XSS prevention) |
| CSRF | Token required on all POST forms; timing-safe comparison |
| File Upload Abuse | MIME type validation (finfo), extension whitelist, 5MB limit, randomized names |
| Directory Traversal | Randomized upload filenames; no user-controlled paths in file operations |
| Direct File Access | `config.php` blocks direct browser access via `get_included_files()` check |

### 5.3 HTTP Security Headers

| Header | Value | Purpose |
|--------|-------|---------|
| `X-Content-Type-Options` | `nosniff` | Prevent MIME type sniffing |
| `X-Frame-Options` | `SAMEORIGIN` | Prevent clickjacking |
| `Referrer-Policy` | `strict-origin-when-cross-origin` | Control referrer leakage |

### 5.4 Upload Sandbox

```
uploads/
├── .htaccess          # Deny all script execution
│   Rules: Block .php, .php3, .php4, .php5, .php7, .phtml,
│          .pl, .py, .jsp, .asp, .htm, .html, .shtml
│   Allow: Images (.jpg, .png, .webp, .jfif) and PDFs
│   Options: -Indexes (no directory listing)
```

---

## 6. Database Architecture

### 6.1 Connection Strategy

- **Singleton pattern** via `global $pdo` — single PDO instance shared across all includes.
- **Prepared statements** with `ATTR_EMULATE_PREPARES => false` for real parameterized queries.
- **Charset:** `utf8mb4` with `utf8mb4_unicode_ci` collation for full Unicode support.
- **Engine:** InnoDB for foreign key support and transaction capabilities.

### 6.2 Settings Cache Strategy

All site settings are loaded in a single `SELECT` query on the first call to `get_setting()`, cached in a PHP static variable for the request lifetime. This avoids N+1 queries when multiple settings are needed on a single page.

### 6.3 Entity-Relationship Overview

The system manages 12 core entities (see Database Design Document for full specification):

```
services ─────────────────────────────── (standalone)
projects ─────────────────────────────── (standalone)
team_members ─────────────────────────── (standalone)
blog_posts ───────────────────────────── (standalone)
testimonials ─────────────────────────── (standalone)
admins ───────────────────────────────── (standalone)
settings ─────────────────────────────── (key-value store)
partners ─────────────────────────────── (standalone)
organogram ──┐────────────────────────── (self-referencing FK)
             └── parent_id → organogram.id
opportunities ─┐────────────────────────
               └── applications (FK, CASCADE DELETE)
```

---

## 7. Frontend Architecture

### 7.1 CSS Strategy

| Layer | Source | Purpose |
|-------|--------|---------|
| Bootstrap 5.3.2 | CDN (jsDelivr) | Grid, components, utilities |
| Custom Theme | `assets/css/style.css` | Brand colors, premium components, animations |
| Admin Styles | Inline in `admin_header.php` | Sidebar, tables, badges, responsive admin layout |
| Page-Specific | Inline `<style>` in page files | Unique page layouts (About page org chart, etc.) |

### 7.2 JavaScript Strategy

| Layer | Source | Purpose |
|-------|--------|---------|
| Bootstrap Bundle | CDN (jsDelivr) | Carousel, collapse, tooltips |
| Custom JS | `assets/js/main.js` | Navbar scroll effect, portfolio filter, form validation |

No build step. No transpilation. No module bundler. JavaScript is written in vanilla ES6+ and served directly.

### 7.3 Responsive Design Strategy

- Bootstrap 5.3 grid system with standard breakpoints: sm (576px), md (768px), lg (992px), xl (1200px)
- Mobile-first approach for public site
- Admin panel: sidebar hidden below lg, toggleable via hamburger button
- Hero title scales from 48px (desktop) to 36px (mobile)
- Card grids collapse from multi-column to single-column on mobile

---

## 8. Error Handling Strategy

| Scenario | Public Site | Admin Panel |
|----------|-------------|-------------|
| Database connection failure | Generic error message displayed | Generic error message displayed |
| Query failure on page load | Affected section silently omitted | Alert div with error message |
| File upload failure | Flash message with error reason | Alert div with error reason |
| 404 / missing page | Apache default 404 | Apache default 404 |
| Uncaught PHP exception | Caught at query level; generic message | Caught at query level; generic message |

The `DEV_MODE` constant in `config.php` controls whether raw error details are logged. In production, `DEV_MODE` shall be set to `false`.

---

## 9. Deployment Architecture

### 9.1 Development Environment

| Component | Configuration |
|-----------|--------------|
| Server | XAMPP (Apache + PHP 8.x + MySQL) |
| Document Root | `D:\xampp\htdocs\MMCF\` |
| Database | `mmcs_db` on localhost |
| PHP Version | 8.0+ |
| Composer | Not used |
| npm | Not used |

### 9.2 Production Environment (Target)

| Component | Configuration |
|-----------|--------------|
| Server | Linux shared hosting or VPS with Apache |
| PHP | 8.0+ with PDO, pdo_mysql, mbstring, fileinfo, openssl |
| MySQL | 5.7+ or MariaDB 10.3+ |
| SSL | HTTPS certificate (Let's Encrypt or provider-issued) |
| Domain | To be configured by client |
| Document Root | Public web root of hosting account |
| Backup | Weekly database dump + uploads archive |

### 9.3 Deployment Steps

1. Export development database to SQL dump
2. Upload all files to production server
3. Import SQL dump into production database
4. Update `config.php` with production credentials (DB user, password, SMTP)
5. Set `DEV_MODE` to `false`
6. Verify `uploads/` directory is writable by web server
7. Verify `.htaccess` rules are enforced (AllowOverride All)
8. Change admin default password
9. Test all public pages and admin CRUD operations
10. Run `scripts/setup_check.php` to verify configuration

---

## 10. Technology Decisions & Rationale

| Decision | Alternative Considered | Rationale |
|----------|----------------------|-----------|
| Procedural PHP (no framework) | Laravel, CodeIgniter, Symfony | Zero-dependency deployment; no Composer; any PHP host works; maintainable by non-framework developers |
| Bootstrap 5 (CDN) | Tailwind CSS, custom CSS only | Rapid responsive design; well-documented components; no build step |
| Vanilla JS | jQuery, React, Alpine.js | Only 72 lines of custom JS needed; no build tools; lightweight |
| PDO (native) | Eloquent ORM, Doctrine | Direct SQL control; no framework dependency; prepared statements for security |
| File-based sessions | Redis, Memcached | Single-server deployment; no additional infrastructure needed |
| File-based rate limiting | Database, Redis | Same reason — no additional infrastructure needed |
| Key/value settings table | Hardcoded config, .env only | Admin-editable without code changes; flexible for adding new settings |
| Self-referencing organogram | Nested set, closure table | Simplest implementation for a 3-level hierarchy; no complex tree algorithms |
| bcrypt password hashing | Argon2, SHA-256 | Industry standard via PHP native `password_hash()`; future-proof |

---

## 11. Future Considerations

The following architectural improvements are noted for potential future implementation but are explicitly out of scope for v1.0:

| Enhancement | Current Gap | Future Approach |
|-------------|-------------|-----------------|
| MVC Framework | Procedural file structure | Migrate to Laravel or Slim for better separation of concerns |
| REST API | No API layer | Add API endpoints for headless/mobile app integration |
| Caching layer | No page/output caching | Add file-based or Redis caching for performance |
| Queue system | Synchronous email sending | Add queue for email notifications |
| Multi-admin RBAC | Single admin role | Add role and permission tables with middleware |
| Search engine | No search | Implement full-text MySQL search or Elasticsearch |
| CDN for assets | Bootstrap via CDN, custom assets local | CloudFlare or similar for custom assets |

---

*This document defines the system architecture for the MMCS Web Platform. All design decisions herein shall guide implementation and be validated during code review and testing.*
