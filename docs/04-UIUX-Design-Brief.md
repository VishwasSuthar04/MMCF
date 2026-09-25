# 04 — UI/UX Design Brief

**Product:** MMCS (MM Consultancy Solutions) Web Platform
**Version:** 1.0.0
**Last Updated:** 2026-07-14
**Design & Development:** Vishwas Suthar

---

## 1. Design System Overview

MMCS follows a **premium corporate consultancy** aesthetic built on Bootstrap 5.3.2 with a restrained, brand-consistent custom layer. The design language communicates professionalism, trustworthiness, and development-sector credibility.

### 1.1 Color System

#### Brand Colors (CSS Custom Properties)

| Token | Hex | Usage |
|-------|-----|-------|
| `--orange-primary` | `#FF5C00` | Primary accent — CTAs, active states, icons, highlights |
| `--orange-hover` | `#cc4a00` | Hover state for orange elements |
| `--navy-dark` | `#0f172a` | Navbar, hero backgrounds, dark sections, headings |
| `--navy-light` | `#1e293b` | Admin sidebar, secondary dark sections |
| `--text-dark` | `#0f172a` | Default body text |
| `--text-muted` | `#64748b` | Secondary/muted text |
| `--gray-bg` | `#f8fafc` | Alternate section backgrounds (`.bg-gray-soft`) |
| `--gray-border` | `#e2e8f0` | Card borders, dividers, form inputs |

#### Raw RGB Values (for transparency)

| Token | Value |
|-------|-------|
| `--navy-raw` | `15, 23, 42` |
| `--orange-raw` | `255, 92, 0` |

#### Derived Utility Classes

| Class | Color | Applied To |
|-------|-------|------------|
| `.text-orange` | `#FF5C00` | Accent text, section labels, icons |
| `.bg-navy` | `#0f172a` | Navbar, dark sections |
| `.bg-dark-navy` | `#0b0f19` | Footer background |
| `.bg-gray-soft` | `#f8fafc` | Alternating section backgrounds |
| `.text-gray` | `#cbd5e1` | Light text on dark backgrounds |
| `.text-gray-muted` | `#94a3b8` | De-emphasized text on dark backgrounds |

#### Admin Panel Color Tokens

| Token | Hex | Usage |
|-------|-----|-------|
| `--navy-sidebar` | `#1e293b` | Admin sidebar background |
| `--slate-gray` | `#64748b` | Table header text |
| `--light-bg` | `#f8fafc` | Admin body background |
| `--border-color` | `#e2e8f0` | Admin card/table borders |

#### Status Badge Colors (Admin)

| Status | Background | Text | Usage |
|--------|-----------|------|-------|
| `.status-new` | `#dbeafe` | `#1e40af` | New inquiry |
| `.status-read` | `#f1f5f9` | `#475569` | Read inquiry |
| `.status-replied` | `#dcfce7` | `#15803d` | Replied inquiry |
| `.status-pending` | `#fef9c3` | `#854d0e` | Pending quote |
| `.status-inprogress` | `#ffedd5` | `#c2410c` | In-progress quote |
| `.status-closed` | `#dcfce7` | `#15803d` | Closed quote |

---

### 1.2 Typography

| Role | Family | Weights | Fallback |
|------|--------|---------|----------|
| **Body** | Inter | 300, 400, 500, 600, 700, 800 | sans-serif |
| **Headings** | Outfit | 400, 500, 600, 700, 800 | sans-serif |

#### Type Scale (Public)

| Element | Size | Weight | Notes |
|---------|------|--------|-------|
| `.hero-title` | `48px` (mobile: `36px`) | 800 | `letter-spacing: -0.03em` |
| `.hero-subtitle` | `15px` | 700 | Uppercase, `letter-spacing: 2px`, orange |
| Section heading (h2) | Bootstrap `fs-2` | 700 | `.font-outfit .tracking-tight` |
| Card title (h4) | Bootstrap `fs-5` | 700 | `.font-outfit` |
| Body text | Bootstrap default | 400 | Inter, `line-height: 1.7` |
| `.hero-subtitle` / labels | `15px` | 700 | Uppercase, `letter-spacing: 2px` |

#### Typography Utility Classes

| Class | Effect |
|-------|--------|
| `.font-outfit` | Switches to Outfit family |
| `.tracking-tight` | `letter-spacing: -0.025em` |
| `.tracking-wide` | `letter-spacing: 0.05em` |

---

### 1.3 Spacing & Layout

| Context | Approach |
|---------|----------|
| Page sections | `py-5` / `py-5 bg-gray-soft` (alternating) |
| Container | Bootstrap `.container` (max-width breakpoints) |
| Section headings | Centered with `mb-5` below, `mb-3` below h2 |
| Card grid | `row g-4` with responsive column classes |
| Footer columns | `row g-4 mb-4` — 4-col layout (4/2/2/4 at lg) |

---

## 2. Component Library

### 2.1 Public Site Components

#### Buttons

| Component | Classes | Style |
|-----------|---------|-------|
| Primary CTA | `.btn .btn-orange .btn-lg .px-4 .py-3 .rounded-pill .fw-semibold .font-outfit .shadow` | Solid orange, pill-shaped, hover lift + glow |
| Outline CTA | `.btn .btn-outline-orange .px-4 .py-2 .rounded-pill .fw-semibold` | Transparent with orange border, fills on hover |
| Nav CTA | `.btn .btn-orange .px-4 .py-2 .rounded-pill .fw-semibold .text-white .tracking-wide .shadow-sm` | Compact orange pill in navbar |
| Admin link | `.btn .btn-outline-light .px-3 .py-2 .rounded-pill` | Ghost white pill (visible only when logged in) |
| Footer link | `.text-gray-muted .text-decoration-none .hover-orange` | Text-only link with orange hover |

**Button Interaction Pattern:**
- `transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1)`
- Hover: `translateY(-2px)` + `box-shadow: 0 8px 20px rgba(234, 88, 12, 0.3)`

#### Cards

**Premium Card** (`.premium-card`):
- White background, `1px solid var(--gray-border)`, `border-radius: 16px`, `padding: 30px`
- Hover: `translateY(-6px)`, enhanced shadow, orange-tinted border
- Icon: `.premium-card-icon` — 56×56px rounded square, orange-10% bg, transitions to solid orange on card hover
- Used for: Service cards on homepage and services page

**Testimonial Bubble** (`.testimonial-bubble`):
- White bg, `border-radius: 16px`, `padding: 30px`, bottom triangle pointer
- Contains italic quote text + author attribution below

**Stats Badge** (`.stats-badge-card`):
- Dark transparent bg (`rgba(255,255,255,0.02)`), `border-radius: 12px`
- Used in hero banner grid (2×2)
- Number: `.stats-number` — `38px`, weight 800, orange color

#### Navigation

**Public Navbar:**
- Sticky top, dark navy background, full-width
- Brand: logo (60px height) + "MM Consultancy Solutions" in Outfit bold
- Links: horizontal nav with active state highlight
- Mobile: Bootstrap collapse with hamburger toggler
- Scroll behavior: glassmorphism effect via JS — `backdrop-filter: blur(8px)`, shadow appears at `scrollY > 50`

**Admin Sidebar** (`.sidebar`):
- Fixed left, 260px width, dark navy (`#1e293b`)
- Brand: shield icon + "MMCS Console"
- Nav items: icon + label, 4px left border, orange highlight on active
- Scrollable nav list with overflow-y auto
- Mobile: transforms off-screen, `.active` class toggles visibility
- Toggle button in topbar

#### Forms

| Component | Classes | Style |
|-----------|---------|-------|
| Input fields | `.form-input-premium` | `border-radius: 10px`, focus: orange border + glow |
| Admin inputs | `.form-control-premium` | `border-radius: 8px`, focus: orange border + glow |
| Admin buttons | `.btn-premium-orange` | Solid orange, `border-radius: 8px`, hover lift |

#### Portfolio Filters

- `.portfolio-filters`: flex row with `gap: 8px`, centered
- `.filter-btn`: pill-shaped, gray border, navy text
- Active/hover: navy bg, white text, `translateY(-1px)`

#### Social Icons (`.social-icon`):
- 38×38px circle, semi-transparent white bg, gray text
- Hover: solid orange bg, white text, `translateY(-3px)`

#### Footer

- 4-column responsive grid (`.bg-dark-navy`)
- Col 1: Brand + description + social icons
- Col 2: Services quick links
- Col 3: Navigation links
- Col 4: Contact details (head office, field office, phone, email)
- Sub-footer: copyright + developer credit + staff gateway link

---

### 2.2 Admin Panel Components

#### Dashboard Stats Cards

- 4-column grid (`col-xl-3 col-md-6`)
- Each card: icon box (colored bg-opacity-10) + label + count + footer link
- Color-coded: blue (messages), yellow (quotes), green (projects), info (team)

#### Data Tables

- `.table-premium th`: light bg, slate text, 600 weight, bottom border
- `.table-premium td`: 14px padding, middle-aligned
- Rows: `table-hover` with status badges and action buttons
- Responsive wrapper: `.table-responsive`

#### Admin Card (`.admin-card`)

- White bg, `border-radius: 12px`, `padding: 24px`, subtle shadow
- Contains section headers with action buttons

---

## 3. Page Layouts

### 3.1 Public Page Structure

Every public page follows this DOM structure:

```
<body>
  <nav.navbar>                    <!-- Sticky top nav -->
  <main>                          <!-- Opened by header.php, closed by footer.php -->
    <section>                     <!-- Page-specific content sections -->
    ...
  </main>
  <footer.bg-dark-navy>           <!-- Site-wide footer -->
  <script>                        <!-- Bootstrap JS + main.js -->
</body>
```

### 3.2 Admin Page Structure

```
<body>
  <div.sidebar>                   <!-- Fixed left sidebar (260px) -->
  <div.main-content>              <!-- Offset by sidebar width -->
    <header.admin-navbar>         <!-- Top bar with toggle + user info -->
    <!-- Page content here -->
  </div>
  <script>                        <!-- Bootstrap JS + sidebar toggle -->
</body>
```

### 3.3 Homepage Section Order (`public/index.php`)

| # | Section | Background | Layout |
|---|---------|------------|--------|
| 1 | Hero Banner | `.hero-banner` — dark gradient with radial orange glow | 2-col: text (col-7) + stats grid (col-5, 2×2) |
| 2 | Core Services | `.bg-gray-soft` | Centered heading + 3-col card grid (top 6 services) |
| 3 | About Callout | White | 2-col: text + checklist (col-6) + mission card (col-6) |
| 4 | Who We Serve | White | Centered text-only section |
| 5 | Testimonials | `.bg-gray-soft` | Bootstrap carousel with testimonial bubbles |
| 6 | CTA Banner | `.bg-navy` | Centered heading + CTA button |

### 3.4 Standard CRUD Page Pattern (Admin)

Every admin CRUD page follows this pattern:

```
List View (default):
  ┌─ Admin Card ─────────────────────────┐
  │ Header: "Module Name" + "Add New" btn │
  ├───────────────────────────────────────┤
  │ Table: rows with Edit/Delete icons    │
  └───────────────────────────────────────┘

Form View (?action=add OR ?action=edit&id=N):
  ┌─ Admin Card ─────────────────────────┐
  │ Header: "Add/Edit Module"             │
  ├───────────────────────────────────────┤
  │ Form: fields + file upload + Save btn │
  └───────────────────────────────────────┘
```

---

## 4. Visual Patterns & Treatments

### 4.1 Hero Banner

- Background: `linear-gradient(135deg, #0f172a 0%, #0b0f19 100%)`
- Decorative radial glow: `radial-gradient(circle, rgba(234,88,12,0.08) 0%, transparent 70%)` positioned top-right
- Stats cards: glassmorphism style — `rgba(255,255,255,0.02)` bg + `rgba(255,255,255,0.05)` border
- Stats numbers: `38px` weight-800 orange text

### 4.2 Card Hover Interactions

- All cards use `cubic-bezier(0.4, 0, 0.2, 1)` easing
- Lift: `translateY(-6px)` on premium cards, `-2px` on buttons
- Shadow progression: subtle base → enhanced on hover
- Icon transition: bg container changes from orange-10% to solid orange

### 4.3 Navbar Scroll Effect (via `main.js`)

```
scrollY <= 50:  Solid navy, no shadow, padding 16px
scrollY > 50:   Semi-transparent navy, blur(8px), shadow, padding 12px
```

### 4.4 Portfolio Filter Animation

- Filter click: active button gets navy bg
- Cards: `opacity 0 → 1`, `scale(0.95 → 1)` with 50ms delay
- Hidden cards: `opacity 0`, `scale(0.95)`, then `display: none` after 300ms

### 4.5 Footer Link Hover

- Color transition to orange
- `padding-left: 3px` slide effect

---

## 5. Responsive Breakpoints

| Breakpoint | Behavior |
|------------|----------|
| `< 768px` (sm) | Hero title: 48px → 36px, padding reduced, hero stats 2-col |
| `< 992px` (md) | Navbar collapses to hamburger, footer stacks to 2-col, admin sidebar hidden (toggleable) |
| `≥ 992px` (lg) | Full navbar, 4-col footer, admin sidebar always visible |

### 5.1 Mobile-Specific

- Navbar: hamburger menu with Bootstrap collapse
- Hero: padding `140px 0` → `100px 0`
- Admin sidebar: `transform: translateX(-100%)` off-screen, `.active` toggles `translateX(0)`
- Admin main content: full-width, reduced padding

---

## 6. Iconography

| Library | Version | Source |
|---------|---------|--------|
| Bootstrap Icons | 1.11.2 | CDN |

**Common Icon Usage:**

| Context | Icons |
|---------|-------|
| Services | `bi-diagram-3`, `bi-file-earmark-text`, `bi-graph-up-arrow`, `bi-pencil-square`, `bi-people`, `bi-search`, `bi-droplet`, `bi-camera`, `bi-clipboard-data` |
| Navigation | `bi-arrow-right-short`, `bi-chevron-right` |
| Social | `bi-linkedin`, `bi-twitter-x`, `bi-facebook` |
| Contact | `bi-building`, `bi-geo-alt-fill`, `bi-telephone-fill`, `bi-envelope-fill` |
| Admin sidebar | `bi-speedometer2`, `bi-gear-fill`, `bi-image`, `bi-people-fill`, `bi-diagram-3-fill`, `bi-journal-text`, `bi-megaphone-fill`, `bi-envelope-fill`, `bi-file-earmark-pdf-fill`, `bi-chat-quote-fill`, `bi-building`, `bi-sliders`, `bi-box-arrow-right` |
| Actions | `bi-eye`, `bi-pencil`, `bi-trash`, `bi-download`, `bi-printer`, `bi-file-earmark-plus` |

---

## 7. Third-Party Visual Dependencies

| Dependency | Version | CDN URL | Purpose |
|------------|---------|---------|---------|
| Bootstrap CSS | 5.3.2 | `cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css` | Grid, components, utilities |
| Bootstrap JS | 5.3.2 | `cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js` | Carousel, collapse, modals, tooltips |
| Bootstrap Icons | 1.11.2 | `cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css` | All icons |
| Google Fonts | — | `fonts.googleapis.com` | Inter + Outfit |

---

## 8. Inline Styles & CSS-in-PHP

| File | Lines | Content |
|------|-------|---------|
| `admin/admin_header.php` | 45–241 | Full admin panel CSS (sidebar, tables, status badges, responsive) — ~200 lines |
| `public/about.php` | 178–197, 289–422, 529–546, 571–588 | Page-specific styles for org chart, expertise areas, geographic map |

> ⚠️ The admin panel has no separate CSS file; all styles are embedded in `<style>` in `admin_header.php`. The public site uses a single `style.css` (351 lines) plus page-specific inline styles in `about.php`.

---

## 9. Accessibility Notes

| Aspect | Status |
|--------|--------|
| Semantic HTML | Partial — `<nav>`, `<main>`, `<footer>`, `<section>` used |
| ARIA labels | Minimal — `aria-label="Toggle navigation"` on hamburger, `aria-hidden` on carousel icons |
| Color contrast | Orange on white meets AA; orange on dark bg exceeds AA |
| Keyboard navigation | Bootstrap provides basic tab order |
| Skip-to-content link | Not implemented |
| Focus indicators | Browser default only (no custom focus styles) |
| Alt text | Image tags present but alt content varies |

---

## 10. Design File References

| Asset | Location |
|-------|----------|
| Custom CSS | `assets/css/style.css` (351 lines) |
| Client JS | `assets/js/main.js` (72 lines) |
| Admin styles | Inline in `admin/admin_header.php:45-241` |
| Logo | `uploads/IMMCS_logo.png` |
| Favicon | `favicon.xml` + `favicon.ico` |
| Default avatar | `assets/images/default-avatar.png` |
| Placeholder project | `assets/images/placeholder-project.jpg` |
| Service images | `assets/images/service.png`, `assets/images/services-flow.jpg`, `assets/images/service approach.jpg` |
| About page image | `assets/images/about-workspace.jpg` |

---

*Document generated from codebase analysis. All design tokens, class names, and layout patterns are confirmed in source code.*
