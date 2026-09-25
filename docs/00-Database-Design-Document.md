# Database Design Document

**Project:** MMCS Web Platform — MM Consultancy Solutions (Private) Limited
**Version:** 1.0
**Date:** January 2026
**Prepared by:** Database Design Team
**Reference:** SRS v1.0, System Architecture Design v1.0

---

## 1. Database Overview

| Property | Value |
|----------|-------|
| DBMS | MySQL 5.7+ / MariaDB 10.3+ |
| Engine | InnoDB (for foreign key support) |
| Charset | `utf8mb4` (full Unicode support, including emoji) |
| Collation | `utf8mb4_unicode_ci` |
| Database Name | `mmcs_db` |
| Connection Method | PDO (PHP Data Objects) with singleton pattern |
| Access Method | Prepared statements with `ATTR_EMULATE_PREPARES => false` |

---

## 2. Design Principles

1. **Simplicity:** Tables are designed to be understood and maintained by non-database-specialist developers.
2. **Normalization:** The schema is normalized to 3NF where practical, with deliberate denormalization for performance (e.g., comma-separated expertise tags).
3. **Referential Integrity:** Foreign keys are used where data integrity is critical (applications → opportunities, organogram → organogram).
4. **Soft Deletion:** Content visibility is controlled via `is_active` or `status` flags rather than hard deletes, preserving referential integrity.
5. **Auditability:** All tables include `created_at` timestamps for audit trails.

---

## 3. Entity-Relationship Diagram

```
                          ┌──────────────────┐
                          │     services      │
                          │──────────────────│
                          │ id          PK   │
                          │ title             │
                          │ description       │
                          │ icon_path         │
                          │ is_active         │
                          │ sort_order        │
                          │ created_at        │
                          └──────────────────┘

┌──────────────────┐     ┌──────────────────┐     ┌──────────────────┐
│    projects       │     │  team_members    │     │   blog_posts     │
│──────────────────│     │──────────────────│     │──────────────────│
│ id          PK   │     │ id          PK   │     │ id          PK   │
│ title            │     │ name             │     │ title            │
│ client           │     │ role             │     │ content          │
│ sector           │     │ photo            │     │ category         │
│ location         │     │ bio              │     │ file_path        │
│ year             │     │ expertise_tags   │     │ published_at     │
│ description      │     │ is_active        │     │ status           │
│ images           │     │ created_at       │     │ created_at       │
│ created_at       │     └──────────────────┘     └──────────────────┘
└──────────────────┘

┌──────────────────┐     ┌──────────────────┐     ┌──────────────────┐
│   inquiries       │     │  testimonials    │     │     admins       │
│──────────────────│     │──────────────────│     │──────────────────│
│ id          PK   │     │ id          PK   │     │ id          PK   │
│ name             │     │ client_name      │     │ username    UQ   │
│ org              │     │ org              │     │ password_hash    │
│ email            │     │ quote            │     │ last_login       │
│ message          │     │ photo            │     │ created_at       │
│ type             │     │ is_approved      │     └──────────────────┘
│ status           │     │ created_at       │
│ service_type     │     └──────────────────┘
│ budget_range     │
│ created_at       │     ┌──────────────────┐
└──────────────────┘     │    settings       │
                         │──────────────────│
┌──────────────────┐     │ id          PK   │
│  opportunities   │     │ key         UQ   │
│──────────────────│     │ value            │
│ id          PK   │     └──────────────────┘
│ title            │
│ type             │     ┌──────────────────┐
│ description      │     │    partners       │
│ requirements     │     │──────────────────│
│ location         │     │ id          PK   │
│ deadline         │     │ name             │
│ status           │     │ logo             │
│ created_at       │     │ website          │
└────────┬─────────┘     │ is_active        │
         │               │ sort_order       │
         │ FK            │ created_at       │
         │ ON DELETE     └──────────────────┘
         │ CASCADE
┌────────┴─────────┐     ┌──────────────────┐
│  applications    │     │   organogram     │
│──────────────────│     │──────────────────│
│ id          PK   │     │ id          PK   │
│ opportunity_id FK│     │ name             │
│ name             │     │ designation      │
│ email            │     │ photo            │
│ phone            │     │ parent_id    FK  │──┐ (self-ref)
│ cover_letter     │     │ display_order    │  │
│ resume_path      │     │ created_at       │  │
│ created_at       │     │ updated_at       │  │
└──────────────────┘     └──────────────────┘  │
                                               │
                         Referential: parent_id ─┘
                         ON DELETE SET NULL
```

---

## 4. Table Definitions

### 4.1 `services` — Consultancy Service Cards

**Purpose:** Stores MMCS's service offerings displayed on the services page and homepage.

| Column | Data Type | Constraints | Default | Description |
|--------|-----------|-------------|---------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | — | Unique identifier |
| `title` | VARCHAR(255) | NOT NULL | — | Service name (e.g., "MEAL Framework Design") |
| `description` | TEXT | NOT NULL | — | Full service description |
| `icon_path` | VARCHAR(255) | — | `'bi-gear'` | Bootstrap Icons class name for display |
| `is_active` | TINYINT(1) | — | `1` | Show on public site (1=yes, 0=no) |
| `sort_order` | INT | — | `0` | Display ordering (ascending) |
| `created_at` | TIMESTAMP | — | `CURRENT_TIMESTAMP` | Record creation time |

**Business Rules:**
- Services with `is_active = 0` are hidden from public pages but remain in admin list.
- `sort_order` controls the display sequence (lower numbers appear first).
- The homepage displays the top 6 active services ordered by `sort_order`.

**Seed Data (9 records):**

| ID | Title | Icon | Sort |
|----|-------|------|------|
| 1 | End-to-End Project Support (EEPS) | bi-diagram-3 | 1 |
| 2 | Monthly Reporting & Documentation | bi-file-earmark-text | 2 |
| 3 | MEAL Framework Design & Implementation | bi-graph-up-arrow | 3 |
| 4 | Proposal & Grant Writing | bi-pencil-square | 4 |
| 5 | Training & Capacity Building | bi-people | 5 |
| 6 | Research, Surveys & Assessments | bi-search | 6 |
| 7 | Water Quality Analysis (WQA) | bi-droplet | 7 |
| 8 | Field Documentation & Media | bi-camera | 8 |
| 9 | Program Evaluations | bi-clipboard-data | 9 |

---

### 4.2 `projects` — Portfolio / Project Showcase

**Purpose:** Stores project case studies displayed on the portfolio page with sector-based filtering.

| Column | Data Type | Constraints | Default | Description |
|--------|-----------|-------------|---------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | — | Unique identifier |
| `title` | VARCHAR(255) | NOT NULL | — | Project name |
| `client` | VARCHAR(255) | NOT NULL | — | Client organization name |
| `sector` | VARCHAR(100) | NOT NULL | — | Sector classification |
| `location` | VARCHAR(255) | NOT NULL | — | Geographic location |
| `year` | INT | NOT NULL | — | Project year |
| `description` | TEXT | NOT NULL | — | Project description |
| `images` | TEXT | NOT NULL | — | Relative path to project image |
| `created_at` | TIMESTAMP | — | `CURRENT_TIMESTAMP` | Record creation time |

**Sector Values (enumerated):**
- WASH (Water, Sanitation & Hygiene)
- Education
- Climate Change
- Gender
- Research
- Livelihoods

**Business Rules:**
- Sector filtering is performed client-side via JavaScript (all projects loaded, hidden/shown by sector).
- A default placeholder image is displayed when `images` column is empty or the referenced file is missing.

**Seed Data (3 records):**

| ID | Title | Client | Sector | Location | Year |
|----|-------|--------|--------|----------|------|
| 1 | Clean Water Initiative | UNICEF Pakistan | WASH | Tharparkar, Sindh | 2024 |
| 2 | Primary Education Quality Enhancement | Sindh Education Foundation | Education | Rural Sindh | 2023 |
| 3 | Climate Resilient Agriculture Program | FAO Pakistan | Climate Change | Tharparkar, Sindh | 2024 |

---

### 4.3 `team_members` — Expert / Staff Directory

**Purpose:** Stores profiles of MMCS experts and staff displayed on the team page.

| Column | Data Type | Constraints | Default | Description |
|--------|-----------|-------------|---------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | — | Unique identifier |
| `name` | VARCHAR(255) | NOT NULL | — | Full name |
| `role` | VARCHAR(255) | NOT NULL | — | Job title or role |
| `photo` | VARCHAR(255) | — | `'default-avatar.png'` | Relative path to photo |
| `bio` | TEXT | NOT NULL | — | Biographical description |
| `expertise_tags` | VARCHAR(255) | NOT NULL | — | Comma-separated expertise areas |
| `is_active` | TINYINT(1) | — | `1` | Show on public site |
| `created_at` | TIMESTAMP | — | `CURRENT_TIMESTAMP` | Record creation time |

**Business Rules:**
- Only active members (`is_active = 1`) are displayed on the public team page.
- `expertise_tags` stores comma-separated values (denormalized for simplicity). Example: `"MEAL,Proposal Writing,Training"`
- A default avatar image is used when no photo is uploaded.

---

### 4.4 `blog_posts` — Articles & Publications

**Purpose:** Stores blog articles and downloadable publications displayed on the blog page.

| Column | Data Type | Constraints | Default | Description |
|--------|-----------|-------------|---------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | — | Unique identifier |
| `title` | VARCHAR(255) | NOT NULL | — | Post title |
| `content` | LONGTEXT | NOT NULL | — | Post body (HTML content) |
| `category` | VARCHAR(100) | NOT NULL | — | Content category |
| `file_path` | VARCHAR(255) | — | `NULL` | Relative path to PDF attachment |
| `published_at` | TIMESTAMP | — | `CURRENT_TIMESTAMP` | Publication date |
| `status` | VARCHAR(20) | — | `'draft'` | Publication status |
| `created_at` | TIMESTAMP | — | `CURRENT_TIMESTAMP` | Record creation time |

**Category Values:**
- Research
- Policy
- Guides
- News

**Status Values:**
- `draft` — Hidden from public blog page
- `published` — Visible on public blog page

**Business Rules:**
- Only published posts appear on the public blog page.
- Posts are sorted by `published_at` descending (newest first).
- PDF attachments are optional; if present, a download link is displayed.
- `published_at` is set on initial creation and is not updated on subsequent edits.

**Seed Data (2 records):**

| ID | Title | Category | Status |
|----|-------|----------|--------|
| 1 | Importance of MEAL in Development Projects | Research | published |
| 2 | A Guide to Effective Grant Proposal Writing | Guides | published |

---

### 4.5 `inquiries` — Contact Messages & Quote Requests

**Purpose:** Dual-purpose table storing both general contact messages and detailed service quote requests.

| Column | Data Type | Constraints | Default | Description |
|--------|-----------|-------------|---------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | — | Unique identifier |
| `name` | VARCHAR(255) | NOT NULL | — | Submitter's full name |
| `org` | VARCHAR(255) | — | `NULL` | Organization name |
| `email` | VARCHAR(255) | NOT NULL | — | Submitter's email |
| `message` | TEXT | NOT NULL | — | Message body |
| `type` | VARCHAR(50) | — | `'contact'` | Submission type |
| `status` | VARCHAR(20) | — | `'New'` | Processing status |
| `service_type` | VARCHAR(100) | — | `NULL` | Requested service (inquiries only) |
| `budget_range` | VARCHAR(100) | — | `NULL` | Budget range (inquiries only) |
| `created_at` | TIMESTAMP | — | `CURRENT_TIMESTAMP` | Submission time |

**Type Values:**
- `contact` — General contact form submission
- `inquiry` — Detailed quote request

**Status Workflows:**

| Type | Statuses | Transitions |
|------|----------|-------------|
| Contact | New → Read → Replied | Auto: New→Read when admin views detail; Manual: Read→Replied |
| Inquiry | Pending → In Progress → Closed | Auto: Pending→In Progress when admin views detail; Manual: In Progress→Closed |

**Business Rules:**
- Contact submissions set `type='contact'` and `status='New'`.
- Quote submissions set `type='inquiry'` and `status='Pending'`.
- `service_type` and `budget_range` are only populated for inquiry-type records.
- CSV export is available for contact-type records only.
- Print/PDF generation is available for inquiry-type records only.

---

### 4.6 `testimonials` — Client Testimonials

**Purpose:** Stores client testimonials displayed on the homepage carousel.

| Column | Data Type | Constraints | Default | Description |
|--------|-----------|-------------|---------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | — | Unique identifier |
| `client_name` | VARCHAR(255) | NOT NULL | — | Testimonial author name |
| `org` | VARCHAR(255) | NOT NULL | — | Author's organization |
| `quote` | TEXT | NOT NULL | — | Testimonial text |
| `photo` | VARCHAR(255) | — | `'default-avatar.png'` | Author photo path |
| `is_approved` | TINYINT(1) | — | `0` | Approval status |
| `created_at` | TIMESTAMP | — | `CURRENT_TIMESTAMP` | Record creation time |

**Business Rules:**
- Only approved testimonials (`is_approved = 1`) appear on the public homepage carousel.
- Admin can toggle approval status from the list view without opening the edit form.
- Default state for new testimonials is unapproved (`is_approved = 0`).

**Seed Data (2 records):** Both approved.

---

### 4.7 `admins` — Administrator Credentials

**Purpose:** Stores admin login credentials for the CMS.

| Column | Data Type | Constraints | Default | Description |
|--------|-----------|-------------|---------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | — | Unique identifier |
| `username` | VARCHAR(100) | UNIQUE, NOT NULL | — | Login username |
| `password_hash` | VARCHAR(255) | NOT NULL | — | bcrypt hash of password |
| `last_login` | TIMESTAMP | — | `NULL` | Last successful login timestamp |
| `created_at` | TIMESTAMP | — | `CURRENT_TIMESTAMP` | Account creation time |

**Business Rules:**
- Passwords are stored as bcrypt hashes (never plaintext).
- The system ships with one default admin account; the password must be changed before production use.
- New admin accounts can be added by inserting rows directly into the database.

**Seed Data (1 record):** Username: `MahaDev`

---

### 4.8 `opportunities` — Job / Volunteer / Intern Postings

**Purpose:** Stores career and volunteer opportunities displayed on the Get Involved page.

| Column | Data Type | Constraints | Default | Description |
|--------|-----------|-------------|---------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | — | Unique identifier |
| `title` | VARCHAR(255) | NOT NULL | — | Opportunity title |
| `type` | VARCHAR(50) | NOT NULL | — | Opportunity category |
| `description` | TEXT | NOT NULL | — | Full description |
| `requirements` | TEXT | — | `NULL` | Requirements text |
| `location` | VARCHAR(255) | — | `NULL` | Work location |
| `deadline` | DATE | — | `NULL` | Application deadline |
| `status` | VARCHAR(20) | — | `'open'` | Application status |
| `created_at` | TIMESTAMP | — | `CURRENT_TIMESTAMP` | Record creation time |

**Type Values:**
- `volunteer` — Volunteer position
- `intern` — Internship
- `expert` — Expert/consultant position

**Status Values:**
- `open` — Accepting applications
- `closed` — No longer accepting applications

**Business Rules:**
- Only opportunities with `status = 'open'` AND (`deadline IS NULL` OR `deadline >= CURDATE()`) are displayed publicly.
- When an opportunity is deleted, all associated applications are cascade-deleted.

**Seed Data (3 records):** 1 volunteer, 1 intern, 1 expert.

---

### 4.9 `applications` — Job Applications

**Purpose:** Stores applications submitted by individuals for open opportunities.

| Column | Data Type | Constraints | Default | Description |
|--------|-----------|-------------|---------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | — | Unique identifier |
| `opportunity_id` | INT | FOREIGN KEY → `opportunities.id`, NOT NULL | — | Linked opportunity |
| `name` | VARCHAR(255) | NOT NULL | — | Applicant name |
| `email` | VARCHAR(255) | NOT NULL | — | Applicant email |
| `phone` | VARCHAR(50) | — | `NULL` | Phone number |
| `cover_letter` | TEXT | — | `NULL` | Cover letter text |
| `resume_path` | VARCHAR(255) | — | `NULL` | Relative path to PDF resume |
| `created_at` | TIMESTAMP | — | `CURRENT_TIMESTAMP` | Application time |

**Foreign Key:** `opportunity_id` → `opportunities.id` with `ON DELETE CASCADE`

**Business Rules:**
- Applications are only accepted for opportunities that are open and not expired.
- Resume uploads are PDF-only, max 5MB.
- Name and email are required; phone, cover letter, and resume are optional.
- Applications are viewable in the admin panel under the associated opportunity.

---

### 4.10 `partners` — Client & Partner Organizations

**Purpose:** Stores partner organization logos displayed on the About page.

| Column | Data Type | Constraints | Default | Description |
|--------|-----------|-------------|---------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | — | Unique identifier |
| `name` | VARCHAR(255) | NOT NULL | — | Partner organization name |
| `logo` | VARCHAR(255) | — | `NULL` | Relative path to logo image |
| `website` | VARCHAR(255) | — | `NULL` | Partner website URL |
| `is_active` | TINYINT(1) | — | `1` | Show on public site |
| `sort_order` | INT | — | `0` | Display ordering |
| `created_at` | TIMESTAMP | — | `CURRENT_TIMESTAMP` | Record creation time |

**Seed Data (3 records):** SHDS, HUMANS, Baanh Beli.

---

### 4.11 `settings` — Global Site Configuration

**Purpose:** Key-value store for all site-wide configuration values, editable through the admin panel.

| Column | Data Type | Constraints | Default | Description |
|--------|-----------|-------------|---------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | — | Unique identifier |
| `key` | VARCHAR(100) | UNIQUE, NOT NULL | — | Setting identifier |
| `value` | TEXT | NOT NULL | — | Setting value |

**Seeded Settings (14 records):**

| Key | Default Value | Purpose |
|-----|---------------|---------|
| `site_logo` | `assets/images/logo.png` | Logo image path |
| `company_name` | `MM Consultancy Solutions (Private) Limited` | Company legal name |
| `phone` | `0334-2656314` | Primary contact phone |
| `email` | `mmconsultancysolutions@gmail.com` | Primary contact email |
| `address` | `Suther Colony East, Mithi-PO Box-69230, Tharparkar, Sindh` | Head office address |
| `field_address` | `Al Rahim Villas, Qasimabad, Hyderabad, Sindh` | Field office address |
| `social_linkedin` | LinkedIn profile URL | LinkedIn link |
| `social_twitter` | Twitter profile URL | Twitter link |
| `social_facebook` | Facebook page URL | Facebook link |
| `stats_years` | `10+` | Years of experience stat |
| `stats_projects` | `150+` | Projects completed stat |
| `stats_clients` | `50+` | Clients served stat |
| `footer_text` | `© 2026 MM Consultancy Solutions (Private) Limited.` | Footer copyright text |
| `consultation_fee` | `Free` | Consultation fee display text |

**Business Rules:**
- Settings are loaded once per request via `get_setting()` and cached in a PHP static variable.
- Admin uses `INSERT ... ON DUPLICATE KEY UPDATE` for upsert operations.
- Changes to settings are reflected on the public site on the next page load.

---

### 4.12 `organogram` — Organizational Hierarchy

**Purpose:** Stores the organizational hierarchy chart with parent-child relationships.

| Column | Data Type | Constraints | Default | Description |
|--------|-----------|-------------|---------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | — | Unique identifier |
| `name` | VARCHAR(255) | NOT NULL | — | Person name |
| `designation` | VARCHAR(255) | NOT NULL | — | Job title |
| `photo` | VARCHAR(255) | — | `NULL` | Photo path |
| `parent_id` | INT | FOREIGN KEY → `organogram.id` | `NULL` | Parent entry (NULL = root) |
| `display_order` | INT | — | `0` | Sibling ordering |
| `created_at` | TIMESTAMP | — | `CURRENT_TIMESTAMP` | Record creation time |
| `updated_at` | TIMESTAMP | — | `CURRENT_TIMESTAMP ON UPDATE` | Last update time |

**Foreign Key:** `parent_id` → `organogram.id` with `ON DELETE SET NULL`

**Business Rules:**
- This is a self-referencing hierarchy (adjacency list model).
- Root entries have `parent_id = NULL`.
- Maximum depth: 3 levels (Executive Director → Department Heads → Specialists).
- Circular reference prevention: The system validates that setting a parent does not create a cycle.
- Self-reference prevention: An entry cannot be its own parent.
- Cascade behavior: When a parent is deleted, children's `parent_id` is set to NULL (promoted to root level).
- Deletion prevention: Entries with active children cannot be deleted.

**Seed Data (11 records):**

| Level | Name | Designation |
|-------|------|-------------|
| 1 | Maha Dev Makwano | Executive Director |
| 2 | [Department Heads] | Heads of various departments |
| 3 | [Specialists] | Field-level specialists |

---

## 5. Relationships & Constraints Summary

### 5.1 Foreign Key Relationships

| Parent Table | Child Table | FK Column | ON DELETE | ON UPDATE |
|-------------|-------------|-----------|-----------|-----------|
| `opportunities` | `applications` | `opportunity_id` | CASCADE | RESTRICT |
| `organogram` | `organogram` | `parent_id` | SET NULL | RESTRICT |

### 5.2 Implicit Relationships (Application-Level)

| Parent | Child | Linkage | Notes |
|--------|-------|---------|-------|
| `services` | `inquiries` | `inquiries.service_type` matches `services.title` | String match, no FK constraint |

### 5.3 Unique Constraints

| Table | Column | Purpose |
|-------|--------|---------|
| `admins` | `username` | Prevent duplicate admin accounts |
| `settings` | `key` | Prevent duplicate setting keys |

---

## 6. Recommended Indexes

The following indexes are recommended for performance optimization. They are not part of the initial schema but should be added as data volume grows:

| Table | Column(s) | Index Type | Rationale |
|-------|-----------|------------|-----------|
| `inquiries` | `type`, `status` | Composite | Dashboard queries filter by type |
| `inquiries` | `created_at` | B-tree | All list views sort by date DESC |
| `services` | `is_active`, `sort_order` | Composite | Homepage/service listing filter+sort |
| `team_members` | `is_active` | B-tree | Active member filtering |
| `blog_posts` | `status`, `published_at` | Composite | Public blog listing filter+sort |
| `projects` | `sector` | B-tree | Portfolio sector filtering |
| `opportunities` | `status`, `deadline` | Composite | Public listing filter |
| `testimonials` | `is_approved` | B-tree | Homepage carousel filter |
| `applications` | `opportunity_id` | B-tree | Applications by opportunity lookup |

---

## 7. Upload File Schema

### 7.1 Upload Directory Mapping

| Module | Table Column | Storage Path | Max Size | Allowed Types |
|--------|-------------|--------------|----------|---------------|
| Team photos | `team_members.photo` | `uploads/team/` | 5MB | jpg, jpeg, png, webp, jfif |
| Project images | `projects.images` | `uploads/projects/` | 5MB | jpg, jpeg, png, webp, jfif |
| Blog PDFs | `blog_posts.file_path` | `uploads/blog/` | 5MB | pdf |
| Testimonial photos | `testimonials.photo` | `uploads/team/` | 5MB | jpg, jpeg, png, webp, jfif |
| Partner logos | `partners.logo` | `uploads/partners/` | 5MB | jpg, jpeg, png, webp, jfif |
| Org chart photos | `organogram.photo` | `uploads/organogram/` | 5MB | jpg, jpeg, png, webp, jfif |
| Resumes | `applications.resume_path` | `uploads/applications/` | 5MB | pdf |

### 7.2 Filename Convention

Uploaded files are renamed to prevent collisions and path guessing:
```
{cleaned_original_name}_{unix_timestamp}_{random_hex}.{extension}
```

Example: `team_photo_1706140800_a3f8b2c1.jpg`

### 7.3 Security

- `.htaccess` in `uploads/` blocks all script execution (PHP, ASP, JSP, Perl, Python, etc.)
- `Options -Indexes` prevents directory listing
- MIME type validated via `finfo(FILEINFO_MIME_TYPE)` — not just file extension

---

## 8. Data Seed Summary

| Table | Records | Notes |
|-------|---------|-------|
| `services` | 9 | All active, sorted 1–9 |
| `projects` | 3 | WASH, Education, Climate |
| `team_members` | 0 | Empty; to be populated by admin |
| `blog_posts` | 2 | Both published |
| `inquiries` | 0 | Empty; populated by form submissions |
| `testimonials` | 2 | Both approved |
| `admins` | 1 | Default account (password must be changed) |
| `settings` | 14 | Company info, social links, stats, footer |
| `partners` | 3 | All active |
| `organogram` | 11 | 3-level hierarchy |
| `opportunities` | 3 | 1 volunteer, 1 intern, 1 expert |
| `applications` | 1 | Example application |
| **Total** | **48** | |

---

## 9. Schema Migration Strategy

As this is a v1.0 release with no prior schema, migration strategy is straightforward:

1. **Initial deployment:** Execute `schema.sql` against a fresh database.
2. **Future changes:** Modify `schema.sql` with ALTER TABLE statements; document changes in a changelog.
3. **No ORM migrations:** Since no framework is used, schema changes are managed via manual SQL scripts.

---

*This document defines the database design for the MMCS Web Platform. All table definitions and relationships shall guide schema implementation and be validated during testing.*
