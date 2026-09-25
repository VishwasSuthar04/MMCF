# MM Consultancy Solutions — Web Platform

A PHP/MySQL content management platform for **MM Consultancy Solutions (Private)
Limited**, a Tharparkar-based consultancy serving NGOs and INGOs with MEAL
systems, research, capacity building, proposal writing, and field documentation.

Public marketing site plus a password-protected admin panel that manages every
piece of content on it — no database edits required for day-to-day changes.

**Design & Development:** Vishwas Suthar

---

## Contents

- [Features](#features)
- [Tech stack](#tech-stack)
- [Quick start](#quick-start)
- [Creating the admin account](#creating-the-admin-account)
- [Project structure](#project-structure)
- [Configuration](#configuration)
- [Security](#security)
- [Operations](#operations)
- [Documentation](#documentation)
- [License](#license)

---

## Features

**Public site** — home, about, services (with per-service detail pages),
portfolio, expert directory, blog & resource library with PDF downloads,
opportunities board, job application form with resume upload, contact form,
and a quote-request form.

**Admin panel** — dashboard with content statistics, plus CRUD screens for
services, team members, projects, blog posts, testimonials, opportunities,
partners, the organisation chart, site settings, and read-only inboxes for
contact inquiries and quote requests.

**Platform**

- Single-admin model with a shared password policy across every entry point
- JSON health-check endpoint for monitoring
- Pre-delivery configuration checker
- Scheduled backup script (database + uploads)
- No build step, no framework, no package manager — plain PHP 8

## Tech stack

| Layer | Choice |
|---|---|
| Backend | PHP 8.x, PDO/MySQL, vanilla procedural PHP |
| Database | MySQL 8.x (InnoDB, `utf8mb4`) |
| Frontend | Bootstrap 5.3.2, Bootstrap Icons 1.11.2 (CDN) |
| Fonts | Inter + Outfit (Google Fonts) |
| Server | Apache with `.htaccess` (developed on XAMPP) |

## Quick start

**1. Get the files** into your web root:

```bash
git clone https://github.com/VishwasSuthar04/MMCF.git
```

**2. Create the database** (12 tables, no admin account is seeded):

```bash
mysql -u root -p < schema.sql
```

**3. Configure the app.** `config.php` is git-ignored, so create it from the PHP
template and fill in your own values:

```bash
cp config.example.php config.php
```

| Constant | Notes |
|---|---|
| `DB_HOST` / `DB_NAME` / `DB_USER` / `DB_PASS` | Your MySQL connection |
| `DEV_MODE` | Must be `false` in production — controls error display |
| `MAIL_USER` / `MAIL_PASS` | SMTP app password; placeholder values send nothing |
| `SITE_URL` | Auto-detected; override only if detection is wrong |

> `.env.example` is a **reference only** — it lists every setting and its default
> for reference. It is not loaded by the code, and copying it to `config.php`
> will not work: its `KEY=value` format is not valid PHP, so no constant would
> be defined. Always start from `config.example.php`.

**4. Create the admin account** (see the next section).

**5. Verify the installation:**

```bash
php scripts/setup_check.php
```

It checks dev mode, admin accounts, SMTP, database connectivity, upload
permissions, the uploads `.htaccess`, HTTPS, PHP version, and required
extensions. Fix anything marked `FAIL` before going live.

**6. Open the site:**

```
Public  http://localhost/MMCF/public/index.php
Admin   http://localhost/MMCF/admin/login.php
```

## Creating the admin account

No default credential is shipped in this repository — a shared password in a
public repo would be readable by anyone. Create your own after importing the
schema:

```bash
php scripts/set_admin.php
```

The script enforces a **single-admin** policy (any other account is removed),
stores only a bcrypt hash, and reads the password from a hidden prompt so it
never touches a file. For automation, set `MMCS_ADMIN_USERNAME` and
`MMCS_ADMIN_PASSWORD` and pass `--yes`.

| Flag | Effect |
|---|---|
| `--yes` | Skip the confirmation prompt (needed for non-interactive runs) |
| `--keep-others` | Do not delete other admin accounts |
| `--force` | Apply a password that fails the strength policy (last resort) |

### Password policy

`validate_password_strength()` in `includes/functions.php` is the single source
of truth, called from both `set_admin.php` and the **Admin → Site Settings →
Security Gate** form, so the two can never drift apart:

- at least **12 characters**
- at least **3 of 4** character classes — lowercase, uppercase, number, symbol
- must not contain the username, or the words `mmcs`, `admin`, `password`,
  `tharparkar`, `consultancy`, `welcome`

## Project structure

```
MMCF/
├── public/              # Public-facing pages (web root entry points)
│   ├── index.php          # Homepage
│   ├── about.php          # Company profile
│   ├── services.php       # Service listings
│   ├── service-detail.php # Individual service page
│   ├── portfolio.php      # Project showcase
│   ├── team.php           # Expert directory
│   ├── blog.php           # Blog & resource library
│   ├── get-involved.php   # Opportunity listings
│   ├── apply.php          # Application form (resume upload)
│   ├── contact.php        # Contact form + office details
│   ├── inquiry.php        # Quote request form
│   └── health.php         # JSON health check
├── admin/               # Admin panel (auth-gated)
│   ├── login.php          # Authentication + brute-force rate limiting
│   ├── dashboard.php      # Statistics overview, logout
│   ├── services.php       # Manage services
│   ├── team.php           # Manage experts/staff
│   ├── portfolio.php      # Manage projects
│   ├── blog.php           # Manage posts & PDF resources
│   ├── testimonials.php   # Manage testimonials
│   ├── opportunities.php  # Manage jobs/volunteer/intern postings
│   ├── partners.php       # Manage partner organisations
│   ├── organogram.php     # Manage org chart
│   ├── inquiries.php      # Contact submissions inbox
│   ├── quotes.php         # Quote request inbox
│   ├── settings.php       # Site settings + password change
│   ├── admin_header.php   # Sidebar, top bar, auth gate
│   └── admin_footer.php   # Closes wrapper, includes JS
├── includes/
│   ├── db.php            # PDO singleton + error handling
│   ├── functions.php     # escape(), flash(), CSRF, upload_file(), password policy
│   ├── auth.php          # Auth gate + idle timeout
│   ├── header.php        # Public <head> + navbar
│   └── footer.php        # Public footer
├── assets/
│   ├── css/style.css     # Custom styles
│   ├── images/           # Static imagery
│   └── js/main.js        # Client-side behaviour
├── scripts/
│   ├── set_admin.php     # Create/update the single admin account
│   ├── setup_check.php   # Pre-delivery configuration checker
│   └── backup.bat        # Scheduled backup (database + uploads, 30-day retention)
├── uploads/              # User-uploaded files (git-ignored)
├── docs/                 # Requirements, architecture, design & planning docs
├── config.php            # Local configuration (git-ignored)
├── schema.sql            # Schema + seed content
├── .env.example          # Configuration template
├── ADMIN_GUIDE.md        # Admin panel user guide
└── README.md
```

### Database tables

`services` · `projects` · `team_members` · `blog_posts` · `inquiries` ·
`testimonials` · `admins` · `opportunities` · `applications` · `partners` ·
`settings` · `organogram`

## Configuration

All runtime configuration lives in `config.php` (git-ignored). `.env.example`
documents every value. The ones that matter most in production:

| Constant | Default | Purpose |
|---|---|---|
| `DEV_MODE` | `false` | When `true`, shows raw errors. Never enable publicly. |
| `SESSION_TIMEOUT` | `1800` | Idle timeout in seconds (30 minutes). |
| `MAX_FILE_SIZE` | 5 MB | Upload size ceiling. |
| `ALLOWED_IMAGE_TYPES` | JPEG/PNG/WebP | MIME whitelist for image uploads. |
| `ALLOWED_DOC_TYPES` | PDF | MIME whitelist for document uploads. |

## Security

Implemented:

- **SQL injection** — every query uses PDO prepared statements; emulated
  prepares are disabled
- **XSS** — all output escaped through `escape()` (`htmlspecialchars` with
  `ENT_QUOTES`, UTF-8)
- **CSRF** — session-bound token required on admin writes
- **Brute force** — login limited to 5 attempts, then a 15-minute lockout
- **Sessions** — 30-minute idle timeout, `HttpOnly` + `Secure` (on HTTPS)
  cookies, session ID regenerated on login
- **Passwords** — bcrypt via `password_hash()`, single shared strength policy
- **Uploads** — MIME *and* extension whitelists, randomised filenames, and an
  `.htaccess` that blocks PHP execution inside `uploads/`
- **Error handling** — production shows generic messages; details go to
  `error_log` only
- **Logs** — `admin/logs/.htaccess` denies web access to the failed-login log
- **Response headers** — `send_security_headers()` in `includes/functions.php`
  sets `X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy`, and a
  `frame-ancestors` CSP on every public page, admin page, the login screen, and
  the quote print view

The headers were previously emitted only as `<meta http-equiv>` tags. Browsers
ignore `X-Frame-Options` and `X-Content-Type-Options` in that form, so the
clickjacking and MIME-sniffing protection they appeared to provide was not in
effect. They are now real response headers.

Known gaps, stated plainly rather than claimed as features:

- Only `frame-ancestors` is set in the CSP. A full `Content-Security-Policy`
  would need `script-src`/`style-src` with nonces or hashes, because the pages
  currently rely on inline styles and scripts.
- The seeded testimonials are placeholder names (`John Doe`, `Sarah Jenkins`)
  and should be replaced before launch.
- The seeded admin account is not created by `schema.sql` at all, by design —
  see [Creating the admin account](#creating-the-admin-account).

## Operations

**Health check** — for monitoring or a load balancer:

```
GET /public/health.php
```

Returns `200` with `{"status":"ok", ...}` when the database is reachable, or
`503` with `"status":"degraded"` when it is not:

```json
{
  "status": "ok",
  "timestamp": "2026-09-25T21:29:08+05:30",
  "service": "MMCS Web Platform",
  "version": "1.0.0",
  "checks": { "database": { "status": "ok", "latency_ms": 2.15 } }
}
```

**Backups** — `scripts/backup.bat` dumps the database and copies `uploads/`,
pruning anything older than 30 days. Schedule it with Task Scheduler:

```bat
schtasks /create /tn "MMCS Backup" /tr "D:\xampp\htdocs\MMCF\scripts\backup.bat" /sc daily /st 02:00
```

Edit the paths at the top of the script first. Backups are written outside the
web root — keep them off-server too.

## Documentation

| Document | Contents |
|---|---|
| [`ADMIN_GUIDE.md`](ADMIN_GUIDE.md) | How to use the admin panel |
| [`docs/00-Business-Requirements-Document.md`](docs/00-Business-Requirements-Document.md) | Business requirements |
| [`docs/00-Software-Requirements-Specification.md`](docs/00-Software-Requirements-Specification.md) | SRS, incl. non-functional requirements |
| [`docs/00-System-Architecture-Design.md`](docs/00-System-Architecture-Design.md) | Architecture |
| [`docs/00-Database-Design-Document.md`](docs/00-Database-Design-Document.md) | Data model |
| [`docs/00-UIUX-Wireframe-Specification.md`](docs/00-UIUX-Wireframe-Specification.md) | Wireframes |
| [`docs/00-Project-Plan-Timeline.md`](docs/00-Project-Plan-Timeline.md) | Project plan |
| [`docs/01-PRD.md`](docs/01-PRD.md) | Product requirements |
| [`docs/02-TRD.md`](docs/02-TRD.md) | Technical requirements & risk register |
| [`docs/03-App-Workflow.md`](docs/03-App-Workflow.md) | Application workflows |
| [`docs/04-UIUX-Design-Brief.md`](docs/04-UIUX-Design-Brief.md) | UI/UX brief |
| [`docs/05-Backend-Schema.md`](docs/05-Backend-Schema.md) | Backend/schema reference |
| [`docs/06-Implementation-Plan.md`](docs/06-Implementation-Plan.md) | Implementation plan |
| `MMCS_Website_Workflow.pdf` | Workflow diagram |

## License

Proprietary — © MM Consultancy Solutions (Private) Limited. All rights reserved.

No open-source licence is granted. This code is published for portfolio and
review purposes; contact the author before any reuse.
