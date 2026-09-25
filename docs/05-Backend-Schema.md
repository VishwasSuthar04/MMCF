# 05 — Backend Schema & Data Architecture

**Product:** MMCS (MM Consultancy Solutions) Web Platform
**Version:** 1.0.0
**Last Updated:** 2026-07-14

---

## 1. Database Overview

| Property | Value |
|----------|-------|
| Engine | MySQL / MariaDB |
| Charset | `utf8mb4` (full Unicode) |
| Collation | `utf8mb4_unicode_ci` |
| Database Name | `mmcs_db` |
| Connection | PDO singleton (`$pdo`) via `includes/db.php` |
| Schema File | `schema.sql` (249 lines) |
| Total Tables | 12 |

---

## 2. Entity Relationship Diagram

```
┌──────────────┐     ┌──────────────┐     ┌──────────────────┐
│   services    │     │   projects   │     │  team_members    │
│──────────────│     │──────────────│     │──────────────────│
│ id (PK)      │     │ id (PK)      │     │ id (PK)          │
│ title        │     │ title        │     │ name             │
│ description  │     │ client       │     │ role             │
│ icon_path    │     │ sector       │     │ photo            │
│ is_active    │     │ location     │     │ bio              │
│ sort_order   │     │ year         │     │ expertise_tags   │
│ created_at   │     │ description  │     │ is_active        │
└──────────────┘     │ images       │     │ created_at       │
                     │ created_at   │     └──────────────────┘
                     └──────────────┘

┌──────────────┐     ┌──────────────┐     ┌──────────────────┐
│ blog_posts   │     │ inquiries    │     │  testimonials    │
│──────────────│     │──────────────│     │──────────────────│
│ id (PK)      │     │ id (PK)      │     │ id (PK)          │
│ title        │     │ name         │     │ client_name      │
│ content      │     │ org          │     │ org              │
│ category     │     │ email        │     │ quote            │
│ file_path    │     │ message      │     │ photo            │
│ published_at │     │ type         │     │ is_approved      │
│ status       │     │ status       │     │ created_at       │
│ created_at   │     │ service_type │     └──────────────────┘
└──────────────┘     │ budget_range │
                     │ created_at   │
                     └──────────────┘

┌──────────────┐     ┌──────────────┐     ┌──────────────────┐
│   admins     │     │  settings    │     │    partners      │
│──────────────│     │──────────────│     │──────────────────│
│ id (PK)      │     │ id (PK)      │     │ id (PK)          │
│ username     │     │ key (UNIQUE) │     │ name             │
│ password_hash│     │ value        │     │ logo             │
│ last_login   │     └──────────────┘     │ website          │
│ created_at   │                          │ is_active        │
└──────────────┘     ┌──────────────┐     │ sort_order       │
                     │organogram    │     │ created_at       │
                     │──────────────│     └──────────────────┘
                     │ id (PK)      │
                     │ name         │
                     │ designation  │
                     │ photo        │
                     │ parent_id(FK)│──┐ (self-referencing)
                     │ display_order│  │
                     │ created_at   │  │
                     │ updated_at   │  │
                     └──────────────┘  │
                                       │
┌──────────────┐     ┌─────────────────┘
│opportunities │     │
│──────────────│     │
│ id (PK)      │     │
│ title        │     │
│ type         │     │
│ description  │     │
│ requirements │     │
│ location     │     │
│ deadline     │     │
│ status       │     │
│ created_at   │     │
└──────┬───────┘     │
       │             │
       │ FK ON DELETE CASCADE
       │             │
┌──────┴───────┐     │
│ applications │     │
│──────────────│     │
│ id (PK)      │     │
│ opportunity_ │     │
│   id (FK)    │     │
│ name         │     │
│ email        │     │
│ phone        │     │
│ cover_letter │     │
│ resume_path  │     │
│ created_at   │     │
└──────────────┘     │
```

---

## 3. Table Definitions

### 3.1 `services` — Consultancy Service Cards

| Column | Type | Constraints | Default | Description |
|--------|------|-------------|---------|-------------|
| `id` | INT | PK, AUTO_INCREMENT | — | Unique identifier |
| `title` | VARCHAR(255) | NOT NULL | — | Service name |
| `description` | TEXT | NOT NULL | — | Full service description |
| `icon_path` | VARCHAR(255) | — | `'bi-gear'` | Bootstrap Icons class name |
| `is_active` | TINYINT(1) | — | `1` | Visibility toggle (0/1) |
| `sort_order` | INT | — | `0` | Display ordering (ASC) |
| `created_at` | TIMESTAMP | — | `CURRENT_TIMESTAMP` | Creation timestamp |

**Seed Data:** 9 services (EEPS, Monthly Reporting, MEAL, Proposal Writing, Training, Research, WQA, Field Documentation, Evaluations)

### 3.2 `projects` — Portfolio / Project Showcase

| Column | Type | Constraints | Default | Description |
|--------|------|-------------|---------|-------------|
| `id` | INT | PK, AUTO_INCREMENT | — | Unique identifier |
| `title` | VARCHAR(255) | NOT NULL | — | Project name |
| `client` | VARCHAR(255) | NOT NULL | — | Client organization |
| `sector` | VARCHAR(100) | NOT NULL | — | Sector tag (WASH, Education, Climate, Gender, Research, Livelihoods) |
| `location` | VARCHAR(255) | NOT NULL | — | Project location |
| `year` | INT | NOT NULL | — | Project year |
| `description` | TEXT | NOT NULL | — | Project description |
| `images` | TEXT | NOT NULL | — | Single image path (despite column name suggesting multi-image) |
| `created_at` | TIMESTAMP | — | `CURRENT_TIMESTAMP` | Creation timestamp |

> ⚠️ Column `images` is documented as "JSON or Comma-separated" but code stores a single file path string.

**Seed Data:** 3 projects (Clean Water Initiative, Primary Education Quality Enhancement, Climate Resilient Agriculture)

### 3.3 `team_members` — Expert / Staff Directory

| Column | Type | Constraints | Default | Description |
|--------|------|-------------|---------|-------------|
| `id` | INT | PK, AUTO_INCREMENT | — | Unique identifier |
| `name` | VARCHAR(255) | NOT NULL | — | Full name |
| `role` | VARCHAR(255) | NOT NULL | — | Job title / role |
| `photo` | VARCHAR(255) | — | `'default-avatar.png'` | Photo path (relative to uploads/) |
| `bio` | TEXT | NOT NULL | — | Biography |
| `expertise_tags` | VARCHAR(255) | NOT NULL | — | Comma-separated tags |
| `is_active` | TINYINT(1) | — | `1` | Visibility toggle |
| `created_at` | TIMESTAMP | — | `CURRENT_TIMESTAMP` | Creation timestamp |

### 3.4 `blog_posts` — Articles & Publications

| Column | Type | Constraints | Default | Description |
|--------|------|-------------|---------|-------------|
| `id` | INT | PK, AUTO_INCREMENT | — | Unique identifier |
| `title` | VARCHAR(255) | NOT NULL | — | Post title |
| `content` | LONGTEXT | NOT NULL | — | Post body (HTML) |
| `category` | VARCHAR(100) | NOT NULL | — | Category (Research, Policy, Guides, News) |
| `file_path` | VARCHAR(255) | — | `NULL` | PDF attachment path |
| `published_at` | TIMESTAMP | — | `CURRENT_TIMESTAMP` | Publication date (not updated on edit) |
| `status` | VARCHAR(20) | — | `'draft'` | Status: `draft` or `published` |
| `created_at` | TIMESTAMP | — | `CURRENT_TIMESTAMP` | Creation timestamp |

**Seed Data:** 2 posts (MEAL importance, Grant proposal guide)

### 3.5 `inquiries` — Contact Messages & Quote Requests

| Column | Type | Constraints | Default | Description |
|--------|------|-------------|---------|-------------|
| `id` | INT | PK, AUTO_INCREMENT | — | Unique identifier |
| `name` | VARCHAR(255) | NOT NULL | — | Submitter name |
| `org` | VARCHAR(255) | — | `NULL` | Organization name |
| `email` | VARCHAR(255) | NOT NULL | — | Email address |
| `message` | TEXT | NOT NULL | — | Message body |
| `type` | VARCHAR(50) | — | `'contact'` | Type: `contact` or `inquiry` (quote) |
| `status` | VARCHAR(20) | — | `'New'` | Status (see workflows below) |
| `service_type` | VARCHAR(100) | — | `NULL` | Service requested (inquiry only) |
| `budget_range` | VARCHAR(100) | — | `NULL` | Budget range (inquiry only) |
| `created_at` | TIMESTAMP | — | `CURRENT_TIMESTAMP` | Submission timestamp |

**Status Workflows:**
- Contact messages: `New` → `Read` → `Replied`
- Quote requests: `Pending` → `In Progress` → `Closed`

### 3.6 `testimonials` — Client Testimonials

| Column | Type | Constraints | Default | Description |
|--------|------|-------------|---------|-------------|
| `id` | INT | PK, AUTO_INCREMENT | — | Unique identifier |
| `client_name` | VARCHAR(255) | NOT NULL | — | Client name |
| `org` | VARCHAR(255) | NOT NULL | — | Client organization |
| `quote` | TEXT | NOT NULL | — | Testimonial text |
| `photo` | VARCHAR(255) | — | `'default-avatar.png'` | Photo path |
| `is_approved` | TINYINT(1) | — | `0` | Approval toggle (0=pending, 1=approved) |
| `created_at` | TIMESTAMP | — | `CURRENT_TIMESTAMP` | Creation timestamp |

**Seed Data:** 2 testimonials (both approved)

### 3.7 `admins` — Administrator Credentials

| Column | Type | Constraints | Default | Description |
|--------|------|-------------|---------|-------------|
| `id` | INT | PK, AUTO_INCREMENT | — | Unique identifier |
| `username` | VARCHAR(100) | UNIQUE, NOT NULL | — | Login username |
| `password_hash` | VARCHAR(255) | NOT NULL | — | bcrypt hash |
| `last_login` | TIMESTAMP | — | `NULL` | Last successful login |
| `created_at` | TIMESTAMP | — | `CURRENT_TIMESTAMP` | Account creation |

**Seed Data:** none — the first admin is created manually on install (see the `ADMIN ACCOUNT` block in `schema.sql`) to avoid shipping a guessable credential

### 3.8 `opportunities` — Job / Volunteer / Intern Postings

| Column | Type | Constraints | Default | Description |
|--------|------|-------------|---------|-------------|
| `id` | INT | PK, AUTO_INCREMENT | — | Unique identifier |
| `title` | VARCHAR(255) | NOT NULL | — | Opportunity title |
| `type` | VARCHAR(50) | NOT NULL | — | Type: `volunteer`, `intern`, `expert` |
| `description` | TEXT | NOT NULL | — | Full description |
| `requirements` | TEXT | — | `NULL` | Requirements text |
| `location` | VARCHAR(255) | — | `NULL` | Work location |
| `deadline` | DATE | — | `NULL` | Application deadline |
| `status` | VARCHAR(20) | — | `'open'` | Status: `open` or `closed` |
| `created_at` | TIMESTAMP | — | `CURRENT_TIMESTAMP` | Creation timestamp |

**Seed Data:** 3 opportunities (1 volunteer, 1 intern, 1 expert)

### 3.9 `applications` — Job Applications

| Column | Type | Constraints | Default | Description |
|--------|------|-------------|---------|-------------|
| `id` | INT | PK, AUTO_INCREMENT | — | Unique identifier |
| `opportunity_id` | INT | FK → `opportunities.id`, NOT NULL | — | Linked opportunity |
| `name` | VARCHAR(255) | NOT NULL | — | Applicant name |
| `email` | VARCHAR(255) | NOT NULL | — | Applicant email |
| `phone` | VARCHAR(50) | — | `NULL` | Phone number |
| `cover_letter` | TEXT | — | `NULL` | Cover letter text |
| `resume_path` | VARCHAR(255) | — | `NULL` | PDF resume path |
| `created_at` | TIMESTAMP | — | `CURRENT_TIMESTAMP` | Application timestamp |

**Foreign Key:** `opportunity_id` → `opportunities.id` with `ON DELETE CASCADE`

**Seed Data:** 1 example application (Ali Khan)

### 3.10 `partners` — Client & Partner Organizations

| Column | Type | Constraints | Default | Description |
|--------|------|-------------|---------|-------------|
| `id` | INT | PK, AUTO_INCREMENT | — | Unique identifier |
| `name` | VARCHAR(255) | NOT NULL | — | Partner name |
| `logo` | VARCHAR(255) | — | `NULL` | Logo image path |
| `website` | VARCHAR(255) | — | `NULL` | Website URL |
| `is_active` | TINYINT(1) | — | `1` | Visibility toggle |
| `sort_order` | INT | — | `0` | Display ordering |
| `created_at` | TIMESTAMP | — | `CURRENT_TIMESTAMP` | Creation timestamp |

**Seed Data:** 3 partners (SHDS, HUMANS, Baanh Beli)

### 3.11 `settings` — Global Site Configuration (Key/Value)

| Column | Type | Constraints | Default | Description |
|--------|------|-------------|---------|-------------|
| `id` | INT | PK, AUTO_INCREMENT | — | Unique identifier |
| `key` | VARCHAR(100) | UNIQUE, NOT NULL | — | Setting identifier |
| `value` | TEXT | NOT NULL | — | Setting value |

**Seeded Settings:**

| Key | Default Value |
|-----|---------------|
| `site_logo` | `assets/images/logo.png` |
| `company_name` | `MM Consultancy Solutions (Private) Limited` |
| `phone` | `0334-2656314` |
| `email` | `mmconsultancysolutions@gmail.com` |
| `address` | `Suther Colony East, Mithi-PO Box-69230, Tharparkar, Sindh` |
| `field_address` | `Al Rahim Villas, Qasimabad, Hyderabad, Sindh` |
| `social_linkedin` | LinkedIn URL |
| `social_twitter` | Twitter URL |
| `social_facebook` | Facebook URL |
| `stats_years` | `10+` |
| `stats_projects` | `150+` |
| `stats_clients` | `50+` |
| `footer_text` | `© 2026 MM Consultancy Solutions (Private) Limited. All rights reserved.` |
| `consultation_fee` | `Free` |

**Access Pattern:** All settings loaded in a single query on first `get_setting()` call, cached in a static variable for the request lifetime. Upserts use `INSERT ... ON DUPLICATE KEY UPDATE`.

### 3.12 `organogram` — Organizational Hierarchy

| Column | Type | Constraints | Default | Description |
|--------|------|-------------|---------|-------------|
| `id` | INT | PK, AUTO_INCREMENT | — | Unique identifier |
| `name` | VARCHAR(255) | NOT NULL | — | Person name |
| `designation` | VARCHAR(255) | NOT NULL | — | Job title |
| `photo` | VARCHAR(255) | — | `NULL` | Photo path |
| `parent_id` | INT | FK → `organogram.id` | `NULL` | Parent entry (NULL = root) |
| `display_order` | INT | — | `0` | Sibling ordering |
| `created_at` | TIMESTAMP | — | `CURRENT_TIMESTAMP` | Creation timestamp |
| `updated_at` | TIMESTAMP | — | `CURRENT_TIMESTAMP ON UPDATE` | Last update timestamp |

**Foreign Key:** `parent_id` → `organogram.id` with `ON DELETE SET NULL`

**Self-referencing hierarchy:** Up to 3 levels deep in seed data (ED → Heads of Dept → Specialists)

**Seed Data:** 11 entries forming a 3-level org chart

---

## 4. Relationships & Constraints

### 4.1 Foreign Key Relationships

| Parent Table | Child Table | FK Column | On Delete | On Update |
|-------------|-------------|-----------|-----------|-----------|
| `opportunities` | `applications` | `opportunity_id` | CASCADE | RESTRICT |
| `organogram` | `organogram` | `parent_id` | SET NULL | RESTRICT |

### 4.2 Implicit Relationships (no FK, application-level)

| Parent | Child | Linkage |
|--------|-------|---------|
| `services` | `inquiries` | `inquiries.service_type` matches `services.title` (string match, no FK) |

### 4.3 Constraints Summary

- **Primary Keys:** All tables have auto-incrementing integer PKs
- **Unique:** `admins.username`, `settings.key`
- **NOT NULL:** Enforced on all critical fields (title, description, name, email, message)
- **Cascading deletes:** Applications deleted when their opportunity is deleted
- **NULL on parent delete:** Organogram children remain but become root-level if parent is deleted

---

## 5. Indexes

> ⚠️ No explicit indexes are defined beyond PKs and UNIQUE constraints. The schema relies on InnoDB default behavior for primary key lookups.

**Recommended indexes (not currently implemented):**

| Table | Column(s) | Reason |
|-------|-----------|--------|
| `inquiries` | `type`, `status` | Dashboard queries filter by type and sort by status |
| `inquiries` | `created_at` | All list views sort by date DESC |
| `services` | `is_active`, `sort_order` | Homepage and service listing filter/sort |
| `team_members` | `is_active` | Active member filtering |
| `blog_posts` | `status`, `published_at` | Public blog listing |
| `projects` | `sector` | Portfolio filtering |
| `opportunities` | `status`, `deadline` | Public listing filter |
| `testimonials` | `is_approved` | Homepage carousel filter |

---

## 6. File Upload Schema

### 6.1 Upload Directory Structure

```
uploads/
├── .htaccess                 # Blocks PHP execution
├── applications/             # Resume PDFs
├── blog/                     # Blog PDF attachments
├── organogram/               # Org chart photos
├── partners/                 # Partner logos
├── projects/                 # Project images
└── team/                     # Team member photos
```

### 6.2 Upload Security

- `.htaccess` rules: Deny all script execution (`php`, `php3`, `php4`, `php5`, `php7`, `phtml`, `pl`, `py`, `jsp`, `asp`, `htm`, `html`, `shtml`)
- `Options -Indexes` prevents directory listing
- Filenames randomized: `{cleaned_name}_{timestamp}_{random_hex}.{ext}`

### 6.3 File Type Validation

| Upload Context | Allowed MIME Types | Allowed Extensions | Max Size |
|---------------|--------------------|--------------------|----------|
| Images (team, partners, portfolio, organogram, testimonials) | `image/jpeg`, `image/png`, `image/webp`, `image/jfif` | `jpg`, `jpeg`, `png`, `webp`, `jfif` | 5MB |
| PDFs (blog attachments, resumes) | `application/pdf` | `pdf` | 5MB |

### 6.4 Upload Storage Mapping

| Module | Table Column | Storage Path | Naming Pattern |
|--------|-------------|--------------|----------------|
| Team photos | `team_members.photo` | `uploads/team/` | `{name}_{ts}_{hex}.{ext}` |
| Portfolio images | `projects.images` | `uploads/projects/` | `{name}_{ts}_{hex}.{ext}` |
| Blog PDFs | `blog_posts.file_path` | `uploads/blog/` | `{name}_{ts}_{hex}.{ext}` |
| Testimonial photos | `testimonials.photo` | `uploads/team/` (shared) | `{name}_{ts}_{hex}.{ext}` |
| Partner logos | `partners.logo` | `uploads/partners/` | `{name}_{ts}_{hex}.{ext}` |
| Org chart photos | `organogram.photo` | `uploads/organogram/` | `{name}_{ts}_{hex}.{ext}` |
| Resumes | `applications.resume_path` | `uploads/applications/` | `{name}_{ts}_{hex}.{ext}` |

---

## 7. Settings Cache Strategy

```php
function get_setting($key) {
    global $pdo;
    static $settings = [];  // Per-request cache

    if (empty($settings)) {
        // Single query loads ALL settings on first call
        $stmt = $pdo->query("SELECT `key`, `value` FROM `settings`");
        while ($row = $stmt->fetch()) {
            $settings[$row['key']] = $row['value'];
        }
    }

    return $settings[$key] ?? '';
}
```

**Characteristics:**
- First call: single `SELECT` loads all rows into static array
- Subsequent calls: pure array lookup (no DB hits)
- Scope: per-request only (no cross-request cache)
- Update: reflected on next page load

---

## 8. CSRF Token Management

| Aspect | Implementation |
|--------|---------------|
| Generation | `bin2hex(random_bytes(32))` — 64-char hex string |
| Storage | `$_SESSION['csrf_token']` |
| Field output | `<input type="hidden" name="csrf_token" value="...">` via `csrf_field()` |
| Validation | `hash_equals()` for timing-safe comparison |
| Regeneration | New token generated on admin login |
| Scope | Per-session; all POST forms require valid token |

---

## 9. Session Management

| Aspect | Value |
|--------|-------|
| Cookie `httponly` | `1` (always) |
| Cookie `use_only_cookies` | `1` (always) |
| Cookie `secure` | `1` (HTTPS only) |
| Idle timeout | 1800 seconds (30 minutes) |
| Session keys used | `admin_logged_in`, `admin_user_id`, `admin_username`, `last_activity`, `csrf_token`, `flash` |

---

## 10. Authentication & Rate Limiting

### 10.1 Authentication Flow

1. Admin submits username + password via `admin/login.php`
2. CSRF token validated
3. Username looked up in `admins` table via PDO prepared statement
4. `password_verify()` compares against stored bcrypt hash
5. On success: `session_regenerate_id(true)`, set session vars, regenerate CSRF
6. On failure: increment file-based rate counter, log to `failed_logins.log`

### 10.2 Rate Limiting (File-Based)

| Parameter | Value |
|-----------|-------|
| Max attempts | 5 |
| Window | 15 minutes |
| Storage | `admin/logs/rate_limit_{md5(ip)}.tmp` (JSON) |
| Lockout message | "Too many failed attempts. Please try again in 15 minutes." |

### 10.3 Failed Login Log

Format: `admin/logs/failed_logins.log`
```
[timestamp] Username: xxx | IP: xxx | Attempt: x
```

---

## 11. Query Patterns

### 11.1 Common Read Patterns

| Page | Query | Notes |
|------|-------|-------|
| Homepage services | `SELECT * FROM services WHERE is_active=1 ORDER BY sort_order ASC LIMIT 6` | Top 6 active |
| Homepage testimonials | `SELECT * FROM testimonials WHERE is_approved=1 ORDER BY id DESC` | Approved only |
| Portfolio filter | `SELECT * FROM projects ORDER BY created_at DESC` | Client-side JS filter by sector |
| Team listing | `SELECT * FROM team_members WHERE is_active=1` | Active members |
| Blog listing | `SELECT * FROM blog_posts WHERE status='published' ORDER BY published_at DESC` | Published only |
| Opportunities | `SELECT * FROM opportunities WHERE status='open' AND (deadline IS NULL OR deadline >= CURDATE())` | Open + not expired |
| Dashboard stats | 4 separate `SELECT COUNT(*)` queries | One per metric |
| Recent inquiries | `SELECT * FROM inquiries WHERE type='contact' ORDER BY created_at DESC LIMIT 5` | Latest 5 |

### 11.2 Common Write Patterns

| Operation | Pattern |
|-----------|---------|
| Insert (contact/inquiry) | Prepared INSERT with bound parameters |
| Update status | `UPDATE inquiries SET status=? WHERE id=?` |
| Upsert settings | `INSERT INTO settings (key, value) VALUES (?, ?) ON DUPLICATE KEY UPDATE value=?` |
| Delete with file cleanup | `unlink()` file → `DELETE FROM table WHERE id=?` |
| Organogram insert | Self-referencing INSERT with parent_id validation |

### 11.3 Error Handling in Queries

| Context | Behavior |
|---------|----------|
| Public pages | Silent failure — sections don't render, no error shown |
| Admin pages | `alert-danger` div with generic message |
| DB connection | Generic message (DEV_MODE shows raw error via `error_log`) |

---

## 12. Data Export

### 12.1 CSV Export (Inquiries)

- Triggered by "Export CSV" button on `admin/inquiries.php`
- PHP generates CSV via `fputcsv()` with headers:
  - `ID`, `Name`, `Organization`, `Email`, `Message`, `Status`, `Date`
- Forces download via `Content-Type: text/csv` + `Content-Disposition: attachment`

---

## 13. Seed Data Summary

| Table | Record Count | Notes |
|-------|-------------|-------|
| `services` | 9 | All active, sorted 1–9 |
| `projects` | 3 | WASH, Education, Climate |
| `team_members` | 0 | (Seeded via organogram references) |
| `blog_posts` | 2 | Both published |
| `inquiries` | 0 | Empty at seed |
| `testimonials` | 2 | Both approved |
| `admins` | 1 | `MahaDev` with default password |
| `settings` | 15 | Company info, social links, stats |
| `partners` | 3 | All active |
| `organogram` | 11 | 3-level hierarchy |
| `opportunities` | 3 | 1 volunteer, 1 intern, 1 expert |
| `applications` | 1 | Example application |

---

*Document generated from schema.sql and codebase analysis. All column definitions and seed data are confirmed in source.*
