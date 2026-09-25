# MM Consultancy Solutions (MMCS) — Web Platform

A PHP/MySQL content management system for MM Consultancy Solutions (Private) Limited, a Tharparkar-based consultancy firm serving NGOs and INGOs in MEAL systems, research, capacity building, and digital solutions.

**Design & Development:** Vishwas Suthar

---

## Tech Stack

- **Backend:** PHP 8.x, MySQL 8.x
- **Frontend:** Bootstrap 5.3, Bootstrap Icons, Google Fonts (Inter + Outfit)
- **Server:** Apache (XAMPP)

## Project Structure

```
MMCF/
├── admin/              # Admin panel (CRUD for all content types)
│   ├── admin_header.php  # Layout: sidebar nav, top bar, auth gate
│   ├── admin_footer.php  # Layout: closes wrapper, includes JS
│   ├── login.php         # Admin authentication
│   ├── dashboard.php     # Stats overview, logout handler
│   ├── services.php      # Manage service offerings
│   ├── team.php          # Manage expert/staff profiles
│   ├── portfolio.php     # Manage project showcase entries
│   ├── blog.php          # Manage blog posts & PDF resources
│   ├── testimonials.php  # Manage client testimonials
│   ├── opportunities.php # Manage jobs/volunteer/intern postings
│   ├── inquiries.php     # Contact form submissions inbox
│   ├── quotes.php        # Service quote requests inbox
│   └── settings.php      # Site config + admin password change
├── includes/
│   ├── db.php            # PDO database connection
│   ├── functions.php     # Utility: escape(), flash(), upload_file(), CSRF
│   ├── auth.php          # Auth gate + session idle timeout
│   ├── header.php        # Public site <head> + navbar
│   └── footer.php        # Public site footer
├── public/              # Public-facing pages
│   ├── index.php         # Homepage
│   ├── about.php         # Company profile
│   ├── services.php      # Service listings
│   ├── portfolio.php     # Project portfolio
│   ├── team.php          # Expert directory
│   ├── get-involved.php  # Career/opportunity listings
│   ├── apply.php         # Application form (with resume upload)
│   ├── blog.php          # Blog & resource library
│   ├── contact.php       # Contact form + office details
│   ├── inquiry.php       # Quote request form
│   └── health.php        # JSON health-check endpoint
├── assets/
│   ├── css/style.css     # Custom styles
│   └── js/main.js        # Client-side scripts
├── scripts/
│   ├── setup_check.php   # Pre-delivery configuration checker
│   └── backup.bat        # Windows scheduled backup script (DB + uploads)
├── uploads/              # User-uploaded images & PDFs
├── config.php            # Database, SMTP, session, dev mode config
├── schema.sql            # Database schema + seed data
├── .env.example          # Environment config template
├── .gitignore            # Git ignore rules
├── robots.txt            # Search engine crawl rules
├── favicon.xml           # SVG favicon
├── favicon.ico           # Fallback favicon
├── ADMIN_GUIDE.md        # Admin panel user guide
└── README.md
```

## Installation

1. **Clone or copy** files into your web root (e.g. `htdocs/MMCF/`).

2. **Create the database** using the provided schema:
   ```sql
   mysql -u root -p < schema.sql
   ```

3. **Configure** `config.php`:
   - Update `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` for your MySQL setup
   - Set `DEV_MODE` to `false` on production
   - Update SMTP credentials if email notifications are needed

4. **Access the site:**
   - Public: `http://localhost/MMCF/public/index.php`
   - Admin:  `http://localhost/MMCF/admin/login.php`

### Admin Credentials

No default password is shipped with this repository. After importing `schema.sql`,
create the first admin using the `ADMIN ACCOUNT` instructions in that file:

```bash
php -r "echo password_hash('YOUR_PASSWORD_HERE', PASSWORD_DEFAULT), PHP_EOL;"
```

- **Username:** `MahaDev` (or any username you choose)
- **Password:** one you set yourself — this account has full control over the site
- **⚠️ Change it immediately after first login** via Admin → Site Settings → Security Gate

## Security Features

- **CSRF Protection** — All admin write operations require a session-bound CSRF token
- **Rate Limiting** — Login brute-force protection (5 attempts / 15 min lockout)
- **Session Security** — Idle timeout (30 min), session regeneration after login
- **Password Policy** — Min 6 chars, requires uppercase + lowercase + digit
- **File Upload** — MIME type + extension whitelist, random filenames, `.htaccess` blocks PHP in uploads/
- **Error Handling** — No stack traces or DB details exposed to users
- **XSS Prevention** — All output escaped via `htmlspecialchars()`
- **SQL Injection** — All queries use PDO prepared statements
- **Security Headers** — `X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy`

## Health Check

```
GET /public/health.php
```
Returns JSON: `{"status":"ok","checks":{"database":{"status":"ok","latency_ms":2.15}}}`

## Services (Seed Data)

The platform ships with 9 core service categories:
1. MEAL Systems Design
2. Third-Party Monitoring
3. Baseline & End line Evaluations
4. Capacity Building & Training
5. Research & Survey Solutions
6. Climate Change & Environment
7. Humanitarian & Emergency Response
8. Gender & Social Inclusion
9. Data Analytics & Digital Solutions

## License

Proprietary — MM Consultancy Solutions (Private) Limited
