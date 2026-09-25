# 02 — Technical Requirements Document (TRD)

**Product:** MMCS (MM Consultancy Solutions) Web Platform
**Version:** 1.0.0
**Last Updated:** 2026-07-14

---

## 1. Tech Stack

| Layer | Technology | Version / Details |
|-------|-----------|-------------------|
| **Backend Language** | PHP | 8.x (enforced ≥8.0 by setup checker) |
| **Database** | MySQL / MariaDB | `utf8mb4` charset, InnoDB engine |
| **Frontend Framework** | Bootstrap | 5.3.2 (CDN) |
| **Icons** | Bootstrap Icons | 1.11.2 (CDN) |
| **Fonts** | Google Fonts | Inter (body: 300–800) + Outfit (headings: 400–800) |
| **Server** | Apache (XAMPP) | Apache required for `.htaccess` rewrite rules |
| **JS Libraries** | Vanilla JS only | No jQuery, no React, no build tools |
| **Package Manager** | None | No `composer.json`, no `package.json` |
| **CSS Framework** | Bootstrap 5 + custom | Single custom `style.css` (351 lines) |

(Confirmed in code — `includes/header.php:44-48`, `admin/admin_header.php:38-42`, `scripts/setup_check.php:107`)

---

## 2. System Architecture

### 2.1 Architectural Pattern

**Procedural PHP** — no MVC framework, no routing library, no ORM. Each page is a standalone PHP file that handles its own routing (via `$_GET['action']` parameters), business logic, and HTML rendering.

### 2.2 Folder Structure

```
MMCF/
├── admin/                  # Admin panel — all CRUD + auth
│   ├── admin_header.php    # Shared layout: auth gate, sidebar, topbar
│   ├── admin_footer.php    # Shared layout: closing tags, JS
│   ├── login.php           # Standalone login (no admin_header)
│   ├── dashboard.php       # Stats overview + logout handler
│   ├── services.php        # Services CRUD
│   ├── team.php            # Team Members CRUD
│   ├── portfolio.php       # Projects CRUD
│   ├── blog.php            # Blog Posts CRUD
│   ├── testimonials.php    # Testimonials CRUD + approval toggle
│   ├── opportunities.php   # Opportunities CRUD + applications viewer
│   ├── inquiries.php       # Contact messages inbox
│   ├── quotes.php          # Quote requests inbox + print
│   ├── partners.php        # Partners CRUD
│   ├── organogram.php      # Org Chart CRUD
│   ├── settings.php        # Site config + password change
│   └── logs/               # Rate limit files, failed login logs
├── includes/               # Shared PHP includes
│   ├── db.php              # PDO connection singleton
│   ├── functions.php       # Utility functions (escape, flash, upload, CSRF)
│   ├── auth.php            # Auth gate + session timeout
│   ├── header.php          # Public site <head> + navbar
│   └── footer.php          # Public site footer
├── public/                 # Public-facing pages
│   ├── index.php           # Homepage
│   ├── about.php           # About Us
│   ├── services.php        # Services listing
│   ├── service-detail.php  # Single service detail
│   ├── portfolio.php       # Portfolio with filtering
│   ├── team.php            # Team directory
│   ├── get-involved.php    # Opportunity listings
│   ├── apply.php           # Application form
│   ├── blog.php            # Blog listing
│   ├── contact.php         # Contact form
│   ├── inquiry.php         # Quote request form
│   └── health.php          # JSON health check endpoint
├── assets/
│   ├── css/style.css       # Custom styles (351 lines)
│   └── js/main.js          # Client-side JS (72 lines)
├── scripts/
│   └── setup_check.php     # Pre-delivery configuration checker
├── uploads/                # User-uploaded content
│   ├── .htaccess           # Blocks PHP execution
│   ├── applications/       # Resume PDFs
│   ├── blog/               # Blog PDF attachments
│   ├── organogram/         # Org chart photos
│   ├── partners/           # Partner logos
│   ├── projects/           # Project images
│   └── team/               # Team member photos
├── config.php              # Central configuration
├── schema.sql              # Database schema + seed data
├── .env.example            # Environment template
├── .gitignore
├── robots.txt
├── favicon.xml / favicon.ico
└── README.md / ADMIN_GUIDE.md
```

### 2.3 Request Flow

```
Browser Request
    ↓
Apache (mod_rewrite, .htaccess)
    ↓
PHP File (direct path mapping — no router)
    ↓
config.php (session start, constants)
    ↓
includes/functions.php → includes/db.php (PDO connection)
    ↓
[If admin page] includes/auth.php (session check)
    ↓
Page-specific business logic (queries, validation, CRUD)
    ↓
HTML rendering (mixed PHP + HTML)
    ↓
Response to browser
```

(Confirmed in code — all PHP files follow this exact include chain)

---

## 3. Environment / Config Requirements

### 3.1 Required Configuration (from `config.php`)

| Constant | Purpose | Default Value | Production Action Required |
|----------|---------|---------------|---------------------------|
| `DB_HOST` | MySQL hostname | `localhost` | Update if remote DB |
| `DB_NAME` | Database name | `mmcs_db` | Verify created |
| `DB_USER` | MySQL username | `root` | **Change to non-root user** |
| `DB_PASS` | MySQL password | `''` (empty) | **Set strong password** |
| `MAIL_HOST` | SMTP server | `smtp.gmail.com` | Update for production SMTP |
| `MAIL_PORT` | SMTP port | `587` | Usually keep as-is |
| `MAIL_USER` | SMTP username | `your-email@gmail.com` | **Set real email** |
| `MAIL_PASS` | SMTP password | `your-gmail-app-password` | **Set real app password** |
| `MAIL_FROM` | From address | `mmconsultancysolutions@gmail.com` | Verify |
| `MAIL_FROM_NAME` | From name | `MMCS Web Desk` | Keep as-is |
| `MAX_FILE_SIZE` | Upload limit | `5 * 1024 * 1024` (5MB) | Adjust if needed |
| `SESSION_TIMEOUT` | Idle timeout | `1800` (30 min) | Adjust if needed |
| `DEV_MODE` | Error display | `false` | **Must be `false` in production** |

(Confirmed in code — `config.php:40-91`)

### 3.2 PHP Extensions Required

| Extension | Purpose |
|-----------|---------|
| `pdo` | Database abstraction |
| `pdo_mysql` | MySQL driver |
| `mbstring` | Multi-byte string handling |
| `fileinfo` | MIME type detection for file uploads |
| `openssl` | HTTPS / session security |

(Confirmed in code — `scripts/setup_check.php:112`)

### 3.3 Server Requirements

- Apache 2.4+ with `mod_rewrite` enabled (for `.htaccess` support)
- PHP 8.0+ with above extensions
- MySQL 5.7+ or MariaDB 10.3+
- `uploads/` directory must be writable by web server process
- SSL certificate for HTTPS (recommended)
- `AllowOverride All` in Apache config for `.htaccess` processing

---

## 4. Third-Party Integrations

| Integration | Status | Implementation |
|-------------|--------|----------------|
| **Email (SMTP)** | Placeholder | `config.php` defines SMTP constants but code uses PHP `mail()` function as fallback (`public/contact.php:47`, `public/inquiry.php:59`). No PHPMailer or SMTP library is included. |
| **Google Maps** | Embedded | Google Maps iframe embed on contact page (`public/contact.php:167-175`). Uses static embed URL, no API key required. |
| **Google Fonts** | CDN | Fonts loaded via Google Fonts CDN in `includes/header.php:42-44` |
| **Bootstrap CDN** | CDN | Bootstrap 5.3.2 CSS + JS + Icons loaded from jsDelivr CDN |
| **Payment** | Not implemented | No payment processing of any kind |
| **File Storage** | Local filesystem | All uploads stored in `uploads/` subdirectories on the server |
| **External APIs** | None | No REST API calls to external services |

---

## 5. Security Measures

### 5.1 Authentication & Session

| Measure | Implementation | Status |
|---------|---------------|--------|
| Password hashing | `password_hash()` with `PASSWORD_DEFAULT` (bcrypt) | ✅ Implemented |
| Password verification | `password_verify()` on login | ✅ Implemented |
| Session fixation prevention | `session_regenerate_id(true)` on successful login | ✅ Implemented |
| Session idle timeout | 30-minute timeout checked via `$_SESSION['last_activity']` | ✅ Implemented |
| Secure session cookies | `httponly=1`, `use_only_cookies=1`, `secure=1` on HTTPS | ✅ Implemented |
| CSRF protection | Token generated via `random_bytes(32)`, validated on all POST forms via `hash_equals()` | ✅ Implemented |
| Brute-force protection | File-based rate limiting: 5 attempts / 15 minutes per IP | ✅ Implemented |

(Confirmed in code — `config.php:28-35`, `includes/auth.php:16-28`, `includes/functions.php:109-145`, `admin/login.php:28-47`)

### 5.2 Input/Output Protection

| Measure | Implementation | Status |
|---------|---------------|--------|
| XSS prevention | All output escaped via `htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8')` in `escape()` function | ✅ Implemented |
| SQL injection prevention | All queries use PDO prepared statements with `ATTR_EMULATE_PREPARES => false` | ✅ Implemented |
| File upload validation | MIME type check via `finfo(FILEINFO_MIME_TYPE)`, extension whitelist, 5MB size limit | ✅ Implemented |
| Direct access blocking | `config.php` blocks direct browser access via `get_included_files()` check | ✅ Implemented |

### 5.3 HTTP Security Headers

Sent as real HTTP response headers by `send_security_headers()` in
`includes/functions.php`, called from all four entry points: the public
layout (`includes/header.php`), the admin layout (`admin/admin_header.php`),
the standalone login screen (`admin/login.php`), and the quote print view
(`admin/quotes.php`):

```
X-Content-Type-Options: nosniff
X-Frame-Options: SAMEORIGIN
Referrer-Policy: strict-origin-when-cross-origin
Content-Security-Policy: frame-ancestors 'self'
```

> These were previously only `<meta http-equiv>` tags, which browsers ignore
> for `X-Frame-Options` and `X-Content-Type-Options`, so that protection was
> not actually in effect. Only `frame-ancestors` is set in the CSP: the pages
> rely on inline styles and scripts, so a full `script-src`/`style-src` policy
> would require nonces or hashes first.

### 5.4 Upload Security

- `.htaccess` in `uploads/` blocks PHP/script execution (`uploads/.htaccess:5-8`)
- Upload filenames randomized: `{cleaned_name}_{timestamp}_{random_hex}.{ext}` (`includes/functions.php:204-205`)
- `Options -Indexes` prevents directory listing

### 5.5 Security Headers Not Implemented

| Header | Status |
|--------|--------|
| `Content-Security-Policy` | ❌ Missing |
| `Strict-Transport-Security` (HSTS) | ❌ Missing |
| `X-XSS-Protection` | ❌ Missing (deprecated but still useful for older browsers) |

---

## 6. Non-Functional Requirements

### 6.1 Performance

- **Page load:** No caching mechanism implemented. Settings are cached per-request via static variable in `get_setting()`, but no page/output caching exists.
- **Database queries:** Simple queries with minimal joins. No query optimization needed at current scale.
- **Asset delivery:** CSS/JS from CDN (Bootstrap), custom assets served directly. Cache-busting via `?v=<?php echo time() ?>` on style.css (Inferred from code — `includes/header.php:51`)
- **Image optimization:** None. Uploaded images served at original size.

### 6.2 Error Handling

| Context | Behavior |
|---------|----------|
| Database connection failure | Generic error message shown to users; raw error logged in DEV_MODE (`includes/db.php:24-33`) |
| Query failures in public pages | Silent failure — sections simply don't render (e.g., `public/index.php:28-30`) |
| Query failures in admin pages | Alert div shown with generic error message |
| File upload failures | Flash message with specific error reason |
| Uncaught exceptions | PDO exceptions caught at query level; no global exception handler |

### 6.3 Logging

| Type | Implementation |
|------|---------------|
| Failed login attempts | File-based: `admin/logs/failed_logins.log` with timestamp, username, IP, attempt count |
| Rate limit tracking | File-based: `admin/logs/rate_limit_{md5(ip)}.tmp` as JSON |
| PHP error logging | `error_log()` used in `config.php:97` for default credentials warning |
| Application-level logging | None beyond the above |

### 6.4 Backup

- `README.md` references `scripts/backup.bat` for Windows scheduled DB + uploads backup
- **File not present in codebase** (listed in README but glob search found no `.bat` files)

---

## 7. Known Technical Debt / Production Blockers

### 7.1 Critical (Must Fix Before Delivery)

| Issue | Location | Description |
|-------|----------|-------------|
| ~~Default admin credentials~~ **RESOLVED** | `schema.sql` `ADMIN ACCOUNT` block | No default password is seeded or documented anywhere in the repo. Operator creates the first admin on install. Residual risk: an operator may still choose a weak password — `setup_check.php` warns if the `admins` table is empty, and flags a weak password if listed in `scripts/.flagged_admin_password` |
| SMTP not configured | `config.php:62-63` | Placeholder values `your-email@gmail.com` — emails will fail silently |
| Database credentials default | `config.php:42-43` | `root` with empty password — deployment warning logged but no hard block |
| `.htaccess` for uploads may not exist in deployment | `setup_check.php:85-92` | Checker warns if missing but deployment script not automated |

### 7.2 High Priority

| Issue | Location | Description |
|-------|----------|-------------|
| No HTTPS enforcement | `config.php` | No redirect to HTTPS; secure cookies conditional on `$_SERVER['HTTPS']` |
| PHP `mail()` unreliable | `contact.php:47`, `inquiry.php:59` | Email notifications use native `mail()` which often fails on shared hosting |
| No Content-Security-Policy header | `includes/header.php` | Vulnerable to XSS via injected scripts if escape is missed |
| No pagination | All list views | All records loaded at once — performance degrades with data volume |
| `projects.images` column misuse | `schema.sql:49` | Comment says "JSON or Comma-separated" but code stores a single file path string |

### 7.3 Medium Priority

| Issue | Location | Description |
|-------|----------|-------------|
| No search functionality | All pages | No way to search services, projects, team, or blog |
| Hardcoded expertise areas | `about.php:199-212`, `services.php:250-260` | Expertise areas are PHP arrays, not from database |
| Hardcoded geographic coverage | `about.php:549` | Coverage areas are hardcoded PHP array |
| Inline `<style>` blocks | `about.php:178-197,289-422,529-546,571-588` | Significant CSS embedded in page files instead of stylesheet |
| No `published_at` update on blog edit | `admin/blog.php:50` | Edit query doesn't update `published_at`, so edited posts keep original date |
| Backup script missing | N/A | Referenced in README but not in codebase |
| Password reset not implemented | N/A | ADMIN_GUIDE.md mentions it but no code exists |

### 7.4 Low Priority

| Issue | Location | Description |
|-------|----------|-------------|
| CSS cache-busting via `time()` | `includes/header.php:51` | Changes cache key every page load, defeating browser cache entirely |
| No image compression/resizing | `includes/functions.php` | Uploaded images stored at original size |
| No CSRF on CSV export/status changes | `admin/inquiries.php:60,76` | Status changes and deletes via GET parameters without CSRF token |
| Services page has redundant content | `public/services.php` | EEPS explanation duplicated between `services.php` and `service-detail.php` |
| No rate limiting on public forms | `contact.php`, `inquiry.php`, `apply.php` | Public forms have no spam protection (no CAPTCHA, no rate limiting) |

---

*Document generated from full codebase analysis. All items marked with specific file:line references are confirmed in code.*
