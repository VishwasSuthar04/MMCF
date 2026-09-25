# UI/UX Wireframe Specification

**Project:** MMCS Web Platform — MM Consultancy Solutions (Private) Limited
**Version:** 1.0
**Date:** January 2026
**Prepared by:** UI/UX Design Team
**Reference:** SRS v1.0, BRD v1.0

---

## 1. Design Philosophy

### 1.1 Brand Identity

The MMCS Web Platform shall convey a **premium corporate consultancy** aesthetic that communicates professionalism, trustworthiness, and development-sector credibility. The visual language must position MMCS as a capable, established partner for NGOs, INGOs, and government agencies.

### 1.2 Design Principles

| Principle | Description |
|-----------|-------------|
| **Professional** | Clean layouts, consistent typography, restrained use of color |
| **Trustworthy** | Clear presentation of credentials, registrations, and track record |
| **Accessible** | Readable fonts, sufficient contrast, responsive across devices |
| **Modern** | Contemporary design patterns (cards, gradients, subtle animations) without being trendy |

---

## 2. Design System

### 2.1 Color Palette

#### Primary Brand Colors

| Token | Hex | RGB | Usage |
|-------|-----|-----|-------|
| Orange Primary | `#FF5C00` | `255, 92, 0` | Primary accent, CTAs, active states, icons, highlights |
| Orange Hover | `#cc4a00` | `204, 74, 0` | Hover state for orange elements |
| Navy Dark | `#0f172a` | `15, 23, 42` | Navbar, hero backgrounds, dark sections, headings |
| Navy Light | `#1e293b` | `30, 41, 59` | Admin sidebar, secondary dark sections |

#### Neutral Colors

| Token | Hex | Usage |
|-------|-----|-------|
| Text Dark | `#0f172a` | Default body text |
| Text Muted | `#64748b` | Secondary/muted text |
| Gray Background | `#f8fafc` | Alternate section backgrounds |
| Gray Border | `#e2e8f0` | Card borders, dividers, form inputs |

#### Status Badge Colors (Admin Panel)

| Status | Background | Text |
|--------|-----------|------|
| New | `#dbeafe` | `#1e40af` |
| Read | `#f1f5f9` | `#475569` |
| Replied | `#dcfce7` | `#15803d` |
| Pending | `#fef9c3` | `#854d0e` |
| In Progress | `#ffedd5` | `#c2410c` |
| Closed | `#dcfce7` | `#15803d` |

### 2.2 Typography

| Role | Font Family | Weights | Fallback |
|------|-------------|---------|----------|
| Body Text | Inter | 300, 400, 500, 600, 700, 800 | sans-serif |
| Headings | Outfit | 400, 500, 600, 700, 800 | sans-serif |

#### Type Scale

| Element | Size | Weight | Notes |
|---------|------|--------|-------|
| Hero Title | 48px (mobile: 36px) | 800 | Tight letter-spacing (-0.03em) |
| Hero Subtitle | 15px | 700 | Uppercase, wide letter-spacing (2px), orange |
| Section Heading (h2) | Bootstrap fs-2 | 700 | Outfit font, tight tracking |
| Card Title (h4) | Bootstrap fs-5 | 700 | Outfit font |
| Body Text | Bootstrap default | 400 | Inter, line-height 1.7 |
| Labels / Tags | 15px | 700 | Uppercase, wide tracking |

### 2.3 Spacing System

| Context | Approach |
|---------|----------|
| Page Sections | `py-5` (48px vertical padding), alternating with `.bg-gray-soft` |
| Container | Bootstrap `.container` with standard breakpoints |
| Section Headings | Centered, `mb-5` (32px) below heading group |
| Card Grids | `row g-4` (16px gutters) |
| Card Internal | 30px padding |

---

## 3. Public Website Wireframes

### 3.1 Global Navigation Bar

```
┌──────────────────────────────────────────────────────────────────┐
│  [LOGO 60px]  MM Consultancy Solutions          [Nav Links]  [CTA]│
│                                                                  │
│  ┌──────┐ ┌──────────┐ ┌──────────┐ ┌──────┐ ┌────────┐        │
│  │ Home │ │ Services │ │ Portfolio│ │ Team │ │  Blog  │        │
│  └──────┘ └──────────┘ └──────────┘ └──────┘ └────────┘        │
│                                                      ┌────────┐ │
│                                                      │Get Inv.│ │
│                                                      └────────┘ │
│                                                      ┌────────┐ │
│                                                      │Contact │ │
│                                                      └────────┘ │
│                                                      ┌────────┐ │
│                                                      │Get Quote│ │
│                                                      │(Orange) │ │
│                                                      └────────┘ │
├──────────────────────────────────────────────────────────────────┤
│  Mobile: [Hamburger ☰] [Logo]                                   │
└──────────────────────────────────────────────────────────────────┘

Behavior:
- Sticky top, full-width
- Desktop: Solid navy background → Semi-transparent + blur on scroll (>50px)
- Mobile: Collapses to hamburger menu
- "Get Quote" button: Orange pill-shaped CTA
```

### 3.2 Homepage Wireframe

```
┌──────────────────────────────────────────────────────────────────┐
│                         HERO BANNER                              │
│  Background: Dark gradient + radial orange glow (top-right)      │
│                                                                  │
│  ┌─────────────────────────────┐  ┌────────────────────────────┐│
│  │                             │  │   ┌──────────┐ ┌─────────┐││
│  │   Building Resilience       │  │   │  10+     │ │ 150+    │││
│  │   Through Development       │  │   │  Years   │ │Projects │││
│  │   Excellence                │  │   └──────────┘ └─────────┘││
│  │                             │  │   ┌──────────┐ ┌─────────┐││
│  │   [Orange CTA Button]       │  │   │  50+     │ │ [Stat4] │││
│  │   Get Proposal Quote        │  │   │ Clients  │ │         │││
│  │                             │  │   └──────────┘ └─────────┘││
│  └─────────────────────────────┘  └────────────────────────────┘│
├──────────────────────────────────────────────────────────────────┤
│                    CORE SERVICES (bg-gray-soft)                  │
│                                                                  │
│              ── What We Deliver ──                               │
│              Our Core Services                                   │
│                                                                  │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐           │
│  │ [Icon]   │ │ [Icon]   │ │ [Icon]   │ │ [Icon]   │           │
│  │ Service  │ │ Service  │ │ Service  │ │ Service  │           │
│  │ Title    │ │ Title    │ │ Title    │ │ Title    │           │
│  │ Desc...  │ │ Desc...  │ │ Desc...  │ │ Desc...  │           │
│  │ [Read →] │ │ [Read →] │ │ [Read →] │ │ [Read →] │           │
│  └──────────┘ └──────────┘ └──────────┘ └──────────┘           │
│                                                                  │
│  ┌──────────┐ ┌──────────┐                                     │
│  │ [Icon]   │ │ [Icon]   │                                     │
│  │ Service  │ │ Service  │    [Explore All Services →]          │
│  └──────────┘ └──────────┘                                     │
├──────────────────────────────────────────────────────────────────┤
│                      ABOUT CALLOUT                               │
│                                                                  │
│  ┌─────────────────────────┐  ┌────────────────────────────────┐│
│  │  ── Who We Are ──       │  │  ┌────────────────────────────┐││
│  │  Empowering Communities │  │  │  ✓ Key Differentiator 1    │││
│  │  Since 2015             │  │  │  ✓ Key Differentiator 2    │││
│  │                         │  │  │  ✓ Key Differentiator 3    │││
│  │  Description text...    │  │  │  ✓ Key Differentiator 4    │││
│  │                         │  │  └────────────────────────────┘││
│  │  [Learn More About Us]  │  │                                ││
│  └─────────────────────────┘  └────────────────────────────────┘│
├──────────────────────────────────────────────────────────────────┤
│                    TESTIMONIALS (bg-gray-soft)                   │
│                                                                  │
│              ── What Our Clients Say ──                          │
│              Trusted by Leading Organizations                    │
│                                                                  │
│  ┌──────────────────────────────────────────────────────────────┐│
│  │  ┌────────────────────────────────────────────────────────┐  ││
│  │  │  "Testimonial quote text goes here. This is what      │  ││
│  │  │   a satisfied client says about MMCS..."              │  ││
│  │  │                                                        │  ││
│  │  │  ┌────┐  Client Name                                  │  ││
│  │  │  │ IMG│  Organization Name                             │  ││
│  │  │  └────┘                                                │  ││
│  │  └────────────────────────────────────────────────────────┘  ││
│  │                                                              ││
│  │  ○ ○ ● ○  (carousel indicators)                             ││
│  └──────────────────────────────────────────────────────────────┘│
├──────────────────────────────────────────────────────────────────┤
│                    CTA BANNER (navy background)                  │
│                                                                  │
│           Ready to Transform Your Development Project?           │
│                                                                  │
│                    [Get Proposal Quote]                          │
│                    (Orange pill button)                          │
├──────────────────────────────────────────────────────────────────┤
│                         FOOTER                                   │
│  ┌──────────────┐ ┌──────────┐ ┌──────────┐ ┌────────────────┐ │
│  │ MMCS Logo    │ │ Services │ │ Quick    │ │ Contact Us     │ │
│  │ Description  │ │ Links    │ │ Links    │ │ Head Office    │ │
│  │              │ │ • EEPS   │ │ • About  │ │ Field Office   │ │
│  │ [Social]     │ │ • MEAL   │ │ • Team   │ │ Phone          │ │
│  │ [Icons]      │ │ • More   │ │ • Blog   │ │ Email          │ │
│  └──────────────┘ └──────────┘ └──────────┘ └────────────────┘ │
│                                                                  │
│  ────────────────────────────────────────────────────────────────│
│  © 2026 MMCS. All rights reserved.  |  Designed by Vishwas      │
└──────────────────────────────────────────────────────────────────┘
```

### 3.3 About Page Wireframe

```
┌──────────────────────────────────────────────────────────────────┐
│  [Navbar]                                                        │
├──────────────────────────────────────────────────────────────────┤
│  ── About MMCS ──                                                │
│  Building Resilience Through Development Excellence              │
├──────────────────────────────────────────────────────────────────┤
│  VISION, MISSION & PILLARS                                       │
│                                                                  │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐                        │
│  │ Vision   │ │ Mission  │ │ Values   │                        │
│  │ Card     │ │ Card     │ │ Card     │                        │
│  └──────────┘ └──────────┘ └──────────┘                        │
├──────────────────────────────────────────────────────────────────┤
│  TECHNICAL EXPERTISE AREAS (12 items in grid)                    │
│  ┌────────┐ ┌────────┐ ┌────────┐ ┌────────┐                   │
│  │MEAL    │ │WASH    │ │Education│ │Climate │                   │
│  ├────────┤ ├────────┤ ├────────┤ ├────────┤                   │
│  │Research│ │Gender  │ │Livelihood│ │Policy │                   │
│  ├────────┤ ├────────┤ ├────────┤ ├────────┤                   │
│  │Training│ │M&E     │ │WQA     │ │Eval   │                   │
│  └────────┘ └────────┘ └────────┘ └────────┘                   │
├──────────────────────────────────────────────────────────────────┤
│  COMPANY PROFILE                                                 │
│  SECP Registration, NTN, Banking Details, Contact Information   │
├──────────────────────────────────────────────────────────────────┤
│  ORGANIZATIONAL CHART (Database-driven tree)                     │
│                                                                  │
│              ┌──────────────────┐                                │
│              │  Executive       │                                │
│              │  Director        │                                │
│              └────────┬─────────┘                                │
│         ┌─────────────┼─────────────┐                           │
│  ┌──────┴──────┐ ┌────┴─────┐ ┌────┴──────┐                   │
│  │ Head of     │ │ Head of  │ │ Head of   │                   │
│  │ Programs    │ │ Research │ │ Finance   │                   │
│  └──────┬──────┘ └──────────┘ └───────────┘                   │
│    ┌────┴────┐                                                  │
│  ┌─┴───┐ ┌──┴───┐                                              │
│  │Spec.│ │Spec. │  ( Specialists under each dept head)         │
│  └─────┘ └──────┘                                              │
├──────────────────────────────────────────────────────────────────┤
│  GEOGRAPHIC COVERAGE (Map or text description)                   │
├──────────────────────────────────────────────────────────────────┤
│  PARTNER LOGOS (Database-driven carousel/grid)                   │
│  ┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐                          │
│  │Logo 1│ │Logo 2│ │Logo 3│ │Logo N│                          │
│  └──────┘ └──────┘ └──────┘ └──────┘                          │
├──────────────────────────────────────────────────────────────────┤
│  WHY PARTNER WITH US                                             │
│  Key differentiators and value propositions                      │
├──────────────────────────────────────────────────────────────────┤
│  [Footer]                                                        │
└──────────────────────────────────────────────────────────────────┘
```

### 3.4 Services Page Wireframe

```
┌──────────────────────────────────────────────────────────────────┐
│  [Navbar]                                                        │
├──────────────────────────────────────────────────────────────────┤
│  ── Our Services ──                                              │
│  Comprehensive Development Consultancy Solutions                 │
├──────────────────────────────────────────────────────────────────┤
│  CORE PROGRAMS (3 highlighted services)                          │
│  ┌────────────────┐ ┌────────────────┐ ┌────────────────┐       │
│  │ Program 1      │ │ Program 2      │ │ Program 3      │       │
│  │ (EEPS)         │ │ (MEAL)         │ │ (Training)     │       │
│  └────────────────┘ └────────────────┘ └────────────────┘       │
├──────────────────────────────────────────────────────────────────┤
│  EEPS MODEL EXPLANATION                                          │
│  ┌──┐ ──→ ┌──┐ ──→ ┌──┐ ──→ ┌──┐ ──→ ┌──┐ ──→ ┌──┐           │
│  │01│     │02│     │03│     │04│     │05│     │06│           │
│  └──┘     └──┘     └──┘     └──┘     └──┘     └──┘           │
│  Stage1   Stage2   Stage3   Stage4   Stage5   Stage6           │
├──────────────────────────────────────────────────────────────────┤
│  ALL SERVICES (9 premium cards)                                   │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐                        │
│  │ [Icon]   │ │ [Icon]   │ │ [Icon]   │                        │
│  │ Service  │ │ Service  │ │ Service  │                        │
│  │ Title    │ │ Title    │ │ Title    │                        │
│  │ Desc     │ │ Desc     │ │ Desc     │                        │
│  └──────────┘ └──────────┘ └──────────┘                        │
│  (repeating 3x3 grid)                                            │
├──────────────────────────────────────────────────────────────────┤
│  TECHNICAL EXPERTISE AREAS (9 items)                              │
├──────────────────────────────────────────────────────────────────┤
│  [Footer]                                                        │
└──────────────────────────────────────────────────────────────────┘
```

### 3.5 Portfolio Page Wireframe

```
┌──────────────────────────────────────────────────────────────────┐
│  [Navbar]                                                        │
├──────────────────────────────────────────────────────────────────┤
│  ── Our Portfolio ──                                             │
│  Development Impact Across Sindh                                  │
├──────────────────────────────────────────────────────────────────┤
│  FILTER BUTTONS (horizontally centered)                          │
│  ┌──────┐ ┌────────┐ ┌────────┐ ┌────────┐ ┌──────┐ ┌────────┐│
│  │ All  │ │  WASH  │ │Education│ │Climate │ │Gender│ │Research││
│  └──────┘ └────────┘ └────────┘ └────────┘ └──────┘ └────────┘│
├──────────────────────────────────────────────────────────────────┤
│  PROJECT CARDS (filterable grid)                                  │
│  ┌────────────────────────────┐ ┌────────────────────────────┐  │
│  │  [Project Image]           │ │  [Project Image]           │  │
│  │                            │ │                            │  │
│  │  Project Title             │ │  Project Title             │  │
│  │  Client: UNICEF            │ │  Client: SEF               │  │
│  │  Sector: WASH              │ │  Sector: Education         │  │
│  │  Location: Tharparkar      │ │  Location: Rural Sindh     │  │
│  │  Year: 2024                │ │  Year: 2023                │  │
│  │  Description text...       │ │  Description text...       │  │
│  └────────────────────────────┘ └────────────────────────────┘  │
│  ┌────────────────────────────┐                                  │
│  │  [Project Image]           │                                  │
│  │  Project Title             │                                  │
│  │  ...                       │                                  │
│  └────────────────────────────┘                                  │
├──────────────────────────────────────────────────────────────────┤
│  [Footer]                                                        │
└──────────────────────────────────────────────────────────────────┘

Filter Animation:
- Clicking a filter button: active button → navy background
- Matching cards: opacity 0→1, scale 0.95→1 (50ms staggered delay)
- Non-matching cards: opacity 1→0, then display:none after 300ms
```

### 3.6 Team Page Wireframe

```
┌──────────────────────────────────────────────────────────────────┐
│  [Navbar]                                                        │
├──────────────────────────────────────────────────────────────────┤
│  ── Our Experts ──                                               │
│  Meet the Professionals Behind MMCS                               │
├──────────────────────────────────────────────────────────────────┤
│  TEAM CARDS (responsive grid)                                    │
│  ┌─────────────────────────┐ ┌─────────────────────────┐       │
│  │  [Photo 200×200]        │ │  [Photo 200×200]        │       │
│  │                         │ │                         │       │
│  │  Expert Name            │ │  Expert Name            │       │
│  │  Job Title / Role       │ │  Job Title / Role       │       │
│  │                         │ │                         │       │
│  │  Bio text goes here     │ │  Bio text goes here     │       │
│  │  with details about...  │ │  with details about...  │       │
│  │                         │ │                         │       │
│  │  [MEAL] [Training]     │ │  [WASH] [Research]      │       │
│  │  (expertise tags)       │ │  (expertise tags)       │       │
│  └─────────────────────────┘ └─────────────────────────┘       │
│  (repeating grid)                                                │
├──────────────────────────────────────────────────────────────────┤
│  [Footer]                                                        │
└──────────────────────────────────────────────────────────────────┘
```

### 3.7 Contact Page Wireframe

```
┌──────────────────────────────────────────────────────────────────┐
│  [Navbar]                                                        │
├──────────────────────────────────────────────────────────────────┤
│  ── Get In Touch ──                                              │
│  We'd Love to Hear From You                                      │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌────────────────────────────┐  ┌────────────────────────────┐ │
│  │                            │  │  CONTACT INFORMATION       │ │
│  │  CONTACT FORM              │  │                            │ │
│  │                            │  │  [🏢] Head Office          │ │
│  │  Name *                    │  │  Suther Colony East, Mithi │ │
│  │  ┌────────────────────┐    │  │                            │ │
│  │  │                    │    │  │  [🏢] Field Office         │ │
│  │  └────────────────────┘    │  │  Al Rahim Villas, Qdbad   │ │
│  │                            │  │                            │ │
│  │  Organization              │  │  [📞] 0334-2656314        │ │
│  │  ┌────────────────────┐    │  │                            │ │
│  │  │                    │    │  │  [✉️] email@mmcs.com      │ │
│  │  └────────────────────┘    │  │                            │ │
│  │                            │  │  [Google Maps Embed]       │ │
│  │  Email *                   │  │  ┌────────────────────┐   │ │
│  │  ┌────────────────────┐    │  │  │                    │   │ │
│  │  │                    │    │  │  │   Mithi,           │   │ │
│  │  └────────────────────┘    │  │  │   Tharparkar       │   │ │
│  │                            │  │  │                    │   │ │
│  │  Message *                 │  │  └────────────────────┘   │ │
│  │  ┌────────────────────┐    │  │                            │ │
│  │  │                    │    │  └────────────────────────────┘ │
│  │  │                    │    │                                 │
│  │  └────────────────────┘    │                                 │
│  │                            │                                 │
│  │  [Submit Message]          │                                 │
│  │  (Orange button)           │                                 │
│  └────────────────────────────┘                                 │
├──────────────────────────────────────────────────────────────────┤
│  [Footer]                                                        │
└──────────────────────────────────────────────────────────────────┘
```

### 3.8 Quote Request Page Wireframe

```
┌──────────────────────────────────────────────────────────────────┐
│  [Navbar]                                                        │
├──────────────────────────────────────────────────────────────────┤
│  ── Request a Quote ──                                           │
│  Tell Us About Your Project                                      │
│  Consultation Fee: [Free] (from settings)                        │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────────────────────────────────────────────────┐    │
│  │                                                          │    │
│  │  Name *          Organization *                          │    │
│  │  ┌──────────┐    ┌──────────────┐                        │    │
│  │  │          │    │              │                        │    │
│  │  └──────────┘    └──────────────┘                        │    │
│  │                                                          │    │
│  │  Email *         Service Type *                          │    │
│  │  ┌──────────┐    ┌──────────────┐                        │    │
│  │  │          │    │  [Dropdown]  │                        │    │
│  │  └──────────┘    └──────────────┘                        │    │
│  │                     (populated from DB)                   │    │
│  │                                                          │    │
│  │  Budget Range                                            │    │
│  │  ┌──────────────┐                                        │    │
│  │  │  [Dropdown]  │  Options: Not Specified, Under $5K,   │    │
│  │  └──────────────┘  $5K-$15K, $15K-$50K, Over $50K      │    │
│  │                                                          │    │
│  │  Project Scope / Message *                               │    │
│  │  ┌──────────────────────────────────────────────────┐    │    │
│  │  │                                                  │    │    │
│  │  │                                                  │    │    │
│  │  └──────────────────────────────────────────────────┘    │    │
│  │                                                          │    │
│  │  [Submit Inquiry Request]                                │    │
│  │  (Orange button)                                         │    │
│  └──────────────────────────────────────────────────────────┘    │
├──────────────────────────────────────────────────────────────────┤
│  [Footer]                                                        │
└──────────────────────────────────────────────────────────────────┘
```

### 3.9 Get Involved & Application Page Wireframes

```
GET INVOLVED PAGE:
┌──────────────────────────────────────────────────────────────────┐
│  [Navbar]                                                        │
├──────────────────────────────────────────────────────────────────┤
│  ── Get Involved ──                                              │
│  Join Our Mission                                                │
├──────────────────────────────────────────────────────────────────┤
│  EXPERT OPPORTUNITIES                                            │
│  ┌──────────────────────────────┐ ┌──────────────────────────┐  │
│  │  Title                       │ │  Title                   │  │
│  │  Location: Mithi             │ │  Location: Hyderabad     │  │
│  │  Deadline: 2026-03-15        │ │  Deadline: Open          │  │
│  │  Description...              │ │  Description...          │  │
│  │  Requirements...             │ │  Requirements...         │  │
│  │  [Apply Now →]               │ │  [Apply Now →]           │  │
│  └──────────────────────────────┘ └──────────────────────────┘  │
├──────────────────────────────────────────────────────────────────┤
│  INTERNSHIP OPPORTUNITIES                                        │
│  ┌──────────────────────────────┐                                │
│  │  Title                       │                                │
│  │  ...                         │                                │
│  │  [Apply Now →]               │                                │
│  └──────────────────────────────┘                                │
├──────────────────────────────────────────────────────────────────┤
│  VOLUNTEER OPPORTUNITIES                                         │
│  (similar card layout)                                           │
├──────────────────────────────────────────────────────────────────┤
│  [Footer]                                                        │
└──────────────────────────────────────────────────────────────────┘

APPLICATION FORM (apply.php?id=N):
┌──────────────────────────────────────────────────────────────────┐
│  [Navbar]                                                        │
├──────────────────────────────────────────────────────────────────┤
│  Opportunity Details Box                                         │
│  Title, Location, Deadline, Requirements                         │
├──────────────────────────────────────────────────────────────────┤
│  APPLICATION FORM                                                │
│  Name *  |  Email *  |  Phone                                    │
│  Cover Letter (textarea)                                         │
│  Resume Upload (PDF, max 5MB)                                    │
│  [Submit Application]                                            │
├──────────────────────────────────────────────────────────────────┤
│  [Footer]                                                        │
└──────────────────────────────────────────────────────────────────┘
```

---

## 4. Admin Panel Wireframes

### 4.1 Admin Layout Structure

```
┌──────────────────────────────────────────────────────────────────┐
│ ┌──────────┐ ┌──────────────────────────────────────────────────┐│
│ │ SIDEBAR  │ │ TOPBAR                                           ││
│ │ (260px)  │ │ [☰ Toggle]     MMCS Console    [View Site →]   ││
│ │          │ ├──────────────────────────────────────────────────┤│
│ │ [Logo]   │ │                                                  ││
│ │ MMCS     │ │              PAGE CONTENT                        ││
│ │ Console  │ │                                                  ││
│ │          │ │  ┌────────────────────────────────────────────┐  ││
│ │ ─────── │ │  │                                            │  ││
│ │ Dashboard│ │  │  (Page-specific content here)             │  ││
│ │ Services │ │  │                                            │  ││
│ │ Team     │ │  │                                            │  ││
│ │ Portfolio│ │  │                                            │  ││
│ │ Blog     │ │  │                                            │  ││
│ │ Opps.    │ │  │                                            │  ││
│ │ Inquiries│ │  │                                            │  ││
│ │ Quotes   │ │  └────────────────────────────────────────────┘  ││
│ │ Testim.  │ │                                                  ││
│ │ Partners │ │                                                  ││
│ │ Org Chart│ │                                                  ││
│ │ Settings │ │                                                  ││
│ │ ─────── │ │                                                  ││
│ │ [Logout] │ │                                                  ││
│ └──────────┘ └──────────────────────────────────────────────────┘│
└──────────────────────────────────────────────────────────────────┘

Mobile: Sidebar slides off-screen, toggle button in topbar
```

### 4.2 Admin Dashboard Wireframe

```
┌──────────────────────────────────────────────────────────────────┐
│  [Sidebar]              Dashboard                                │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────┐ ┌──────────────┐ ┌──────────────┐ ┌────────┐ │
│  │ 📧           │ │ 💰           │ │ 📁           │ │ 👥     │ │
│  │ Messages     │ │ Quotes       │ │ Projects     │ │ Team   │ │
│  │ [Count]      │ │ [Count]      │ │ [Count]      │ │[Count] │ │
│  │ View All →   │ │ View All →   │ │ View All →   │ │All →   │ │
│  └──────────────┘ └──────────────┘ └──────────────┘ └────────┘ │
│                                                                  │
│  ┌────────────────────────────────────────────────────────────┐  │
│  │ Recent Contact Messages                                    │  │
│  │ ┌────┬────────┬─────────┬──────────┬────────┬──────┐      │  │
│  │ │ ID │ Name   │ Org     │ Email    │ Status │ Date │      │  │
│  │ ├────┼────────┼─────────┼──────────┼────────┼──────┤      │  │
│  │ │ 1  │ Ahmed  │ NGO XYZ │ a@xyz... │ New    │ Jul  │      │  │
│  │ │ 2  │ Sara   │ UN Org  │ s@un...  │ Read   │ Jul  │      │  │
│  │ └────┴────────┴─────────┴──────────┴────────┴──────┘      │  │
│  └────────────────────────────────────────────────────────────┘  │
│                                                                  │
│  ┌────────────────────────────────────────────────────────────┐  │
│  │ Recent Quote Requests                                      │  │
│  │ (similar table layout)                                     │  │
│  └────────────────────────────────────────────────────────────┘  │
├──────────────────────────────────────────────────────────────────┤
│  [Logout]                                                        │
└──────────────────────────────────────────────────────────────────┘
```

### 4.3 Admin CRUD Page Pattern

```
LIST VIEW:
┌──────────────────────────────────────────────────────────────────┐
│  [Sidebar]              Module Name                              │
├──────────────────────────────────────────────────────────────────┤
│  ┌────────────────────────────────────────────────────────────┐  │
│  │ Module Name List              [+ Add New Module] (orange)  │  │
│  ├────────────────────────────────────────────────────────────┤  │
│  │ ┌────┬──────────┬────────┬──────────┬──────────────────┐   │  │
│  │ │ ID │ Title    │ Status │ Order    │ Actions          │   │  │
│  │ ├────┼──────────┼────────┼──────────┼──────────────────┤   │  │
│  │ │ 1  │ Service1 │ Active │ 1        │ [✏️] [🗑️]       │   │  │
│  │ │ 2  │ Service2 │ Active │ 2        │ [✏️] [🗑️]       │   │  │
│  │ │ 3  │ Service3 │ Inact. │ 3        │ [✏️] [🗑️]       │   │  │
│  │ └────┴──────────┴────────┴──────────┴──────────────────┘   │  │
│  └────────────────────────────────────────────────────────────┘  │
└──────────────────────────────────────────────────────────────────┘

FORM VIEW (Add/Edit):
┌──────────────────────────────────────────────────────────────────┐
│  [Sidebar]              Add / Edit Module                        │
├──────────────────────────────────────────────────────────────────┤
│  ┌────────────────────────────────────────────────────────────┐  │
│  │ Add New Module Name                                        │  │
│  ├────────────────────────────────────────────────────────────┤  │
│  │                                                            │  │
│  │  Title *                                                   │  │
│  │  ┌──────────────────────────────────────────────────┐     │  │
│  │  │                                                  │     │  │
│  │  └──────────────────────────────────────────────────┘     │  │
│  │                                                            │  │
│  │  Description *                                             │  │
│  │  ┌──────────────────────────────────────────────────┐     │  │
│  │  │                                                  │     │  │
│  │  │                                                  │     │  │
│  │  └──────────────────────────────────────────────────┘     │  │
│  │                                                            │  │
│  │  [File Upload: Choose File]                                │  │
│  │                                                            │  │
│  │  Sort Order: [___]    Active: [✓]                         │  │
│  │                                                            │  │
│  │  [Save Module] (orange button)    [Cancel] (gray)         │  │
│  └────────────────────────────────────────────────────────────┘  │
└──────────────────────────────────────────────────────────────────┘
```

---

## 5. Component Specifications

### 5.1 Premium Card (Public)

```
Default State:
  - Background: white
  - Border: 1px solid #e2e8f0
  - Border-radius: 16px
  - Padding: 30px
  - Shadow: subtle (0 2px 8px rgba(0,0,0,0.04))

Hover State:
  - Transform: translateY(-6px)
  - Border-color: rgba(255, 92, 0, 0.2) (orange tint)
  - Shadow: enhanced (0 12px 32px rgba(0,0,0,0.08))

Icon Container (56×56px):
  - Default: orange-10% background
  - Hover: solid orange background, white icon

Transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1)
```

### 5.2 CTA Buttons

```
Primary CTA:
  - Background: #FF5C00 (orange)
  - Text: white, font-weight 600, Outfit font
  - Shape: pill (border-radius: 50px)
  - Padding: 12px 32px
  - Shadow: 0 4px 12px rgba(255, 92, 0, 0.2)
  - Hover: translateY(-2px), enhanced shadow

Outline CTA:
  - Background: transparent
  - Border: 2px solid #FF5C00
  - Text: #FF5C00
  - Hover: fill orange, text white
```

### 5.3 Form Inputs

```
Public Site (.form-input-premium):
  - Border: 1px solid #e2e8f0
  - Border-radius: 10px
  - Padding: 12px 16px
  - Focus: orange border + subtle orange glow
  - Transition: border-color 0.3s, box-shadow 0.3s

Admin Panel (.form-control-premium):
  - Border: 1px solid #e2e8f0
  - Border-radius: 8px
  - Focus: orange border + glow
```

### 5.4 Portfolio Filters

```
Default:
  - Pill-shaped (border-radius: 50px)
  - Border: 1px solid #e2e8f0
  - Background: white
  - Text: #0f172a

Active/Hover:
  - Background: #0f172a (navy)
  - Text: white
  - Transform: translateY(-1px)

Container: flex row, gap 8px, centered
```

---

## 6. Responsive Breakpoints

| Breakpoint | Width | Behavior |
|------------|-------|----------|
| Mobile (sm) | < 768px | Hero title 48→36px, navbar collapses, footer stacks to 1-col, admin sidebar hidden |
| Tablet (md) | 768–991px | Partial grid, admin sidebar hidden |
| Desktop (lg) | ≥ 992px | Full navbar, 4-col footer, admin sidebar visible |
| Wide (xl) | ≥ 1200px | Max-width container, full multi-column grids |

### 6.1 Mobile-Specific Adjustments

- **Hero:** Padding 140px → 100px vertical; title scales down
- **Navbar:** Hamburger toggler with Bootstrap collapse
- **Cards:** Stack to single column
- **Footer:** 4-col → 2-col → 1-col
- **Admin sidebar:** `transform: translateX(-100%)` off-screen; toggled via `.active` class
- **Admin content:** Full width, reduced padding

---

## 7. Animation & Interaction Patterns

| Pattern | Trigger | Effect |
|---------|---------|--------|
| Card hover lift | Mouse enter | translateY(-6px), shadow enhancement |
| Button hover lift | Mouse enter | translateY(-2px), shadow enhancement |
| Navbar glassmorphism | scrollY > 50px | Semi-transparent bg, backdrop-filter blur(8px), shadow |
| Portfolio filter | Filter button click | Cards fade out (300ms), matching cards fade in with stagger (50ms each) |
| Testimonial carousel | Auto-rotate + manual | Bootstrap carousel with fade transition |
| Footer link hover | Mouse enter | Color transitions to orange, padding-left: 3px slide |
| Icon transition | Card hover | Icon bg changes from orange-10% to solid orange |

---

## 8. Accessibility Considerations

| Aspect | Current Approach | Recommendation for Future |
|--------|-----------------|--------------------------|
| Semantic HTML | `<nav>`, `<main>`, `<footer>`, `<section>` used | Continue for all new pages |
| ARIA labels | Minimal (hamburger toggle only) | Add to all interactive elements |
| Color contrast | Orange on white meets AA; orange on dark exceeds AA | Maintain AA compliance |
| Keyboard navigation | Bootstrap provides basic tab order | Add custom focus indicators |
| Skip-to-content | Not implemented | Add for keyboard users |
| Alt text | Images include alt attributes | Ensure descriptive alt text for all images |

---

## 9. Third-Party Visual Dependencies

| Dependency | Version | Delivery | Purpose |
|------------|---------|----------|---------|
| Bootstrap CSS | 5.3.2 | CDN (jsDelivr) | Grid system, components, utilities |
| Bootstrap JS | 5.3.2 | CDN (jsDelivr) | Carousel, collapse, tooltips |
| Bootstrap Icons | 1.11.2 | CDN (jsDelivr) | All iconography |
| Google Fonts (Inter) | — | Google Fonts CDN | Body typography |
| Google Fonts (Outfit) | — | Google Fonts CDN | Heading typography |

No npm packages. No build tools. No local copies of Bootstrap.

---

*This document specifies the visual design and interaction patterns for the MMCS Web Platform. All UI components and layouts herein shall guide frontend development and design review.*
