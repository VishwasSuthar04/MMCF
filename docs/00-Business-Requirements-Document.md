# Business Requirements Document (BRD)

**Project:** MMCS Web Platform — MM Consultancy Solutions (Private) Limited
**Version:** 1.0
**Date:** January 2026
**Prepared by:** Project Management Team
**Client:** MM Consultancy Solutions (Private) Limited
**Executive Director:** Maha Dev Makwano

---

## 1. Executive Summary

MM Consultancy Solutions (Private) Limited (hereinafter "MMCS") is a Tharparkar-based SECP-registered development consultancy firm (CUI: 330906 | NTN: 1757080) serving NGOs, INGOs, government programs, and corporate CSR initiatives in Sindh, Pakistan. The organization requires a modern web platform to establish its digital presence, showcase its consultancy expertise, and streamline client acquisition through online channels.

This document outlines the business requirements for the development of the MMCS Web Platform — a content-managed website and administrative backend that will serve as the organization's primary digital storefront and client engagement tool.

---

## 2. Business Background

### 2.1 Organization Profile

| Attribute | Details |
|-----------|---------|
| Legal Name | MM Consultancy Solutions (Private) Limited |
| Registration | SECP (CUI: 330906), NTN: 1757080 |
| Executive Director | Maha Dev Makwano |
| Headquarters | Suther Colony East, Mithi, Tharparkar, Sindh, Pakistan |
| Field Office | Al Rahim Villas, Qasimabad, Hyderabad, Sindh |
| Sector | Development Consultancy (NGO/INGO/Government/CSR) |
| Core Services | MEAL, Proposal Writing, Training, Research, WQA, Field Documentation, Evaluations |

### 2.2 Business Problem

MMCS currently operates without a dedicated web presence. This creates the following business challenges:

1. **Limited Visibility:** Prospective clients (NGOs, INGOs, government agencies) have no centralized online resource to discover MMCS's services, expertise, or past project work.
2. **Manual Client Acquisition:** All inquiries and quote requests are handled through word-of-mouth, phone calls, or email — with no structured intake or tracking system.
3. **No Digital Portfolio:** Past project work and impact cannot be showcased to prospective clients in a compelling, organized manner.
4. **Talent Recruitment Gaps:** Recruiting field experts, interns, and volunteers relies on informal channels without a structured opportunity posting and application system.
5. **Content Distribution:** Research publications, policy briefs, and knowledge products have no online repository for sharing with stakeholders.

### 2.3 Business Objectives

| # | Objective | Success Metric |
|---|-----------|----------------|
| BO-1 | Establish a professional online presence for MMCS | Website live with all core pages within 6 weeks |
| BO-2 | Enable prospective clients to discover and evaluate MMCS services | Services, portfolio, and team pages showcase expertise |
| BO-3 | Streamline inquiry and quote request intake | All inquiries captured in a structured database with admin tracking |
| BO-4 | Showcase project portfolio and impact | Minimum 3 project case studies displayed with sector tagging |
| BO-5 | Facilitate talent recruitment | Opportunities posted online with application submission capability |
| BO-6 | Provide a knowledge-sharing platform for publications | Blog/resource library with downloadable PDF attachments |
| BO-7 | Enable non-technical staff to manage all content | Full CMS admin panel requiring no coding knowledge |

---

## 3. Stakeholders

### 3.1 Primary Stakeholders

| Role | Name / Group | Interest |
|------|-------------|----------|
| Project Sponsor | Maha Dev Makwano (Executive Director) | Final approval, business alignment |
| End Users (Admin) | MMCS administrative staff | Day-to-day content management |
| End Users (Public) | Prospective NGO/INGO clients | Service discovery, quote requests |
| End Users (Public) | Job/volunteer seekers | Opportunity browsing, application submission |
| Development Team | Vishwas Suthar (Designer/Developer) | Design and implementation |

### 3.2 Secondary Stakeholders

| Role | Interest |
|------|----------|
| MMCS field staff / experts | Team profile representation |
| Partner organizations | Logo/brand visibility on the platform |
| Existing clients | Testimonial representation |

---

## 4. Business Requirements

### 4.1 Content Management System (CMS)

The platform shall provide a web-based administrative interface that allows non-technical staff to manage all website content without requiring code changes or developer intervention.

| ID | Requirement | Priority |
|----|-------------|----------|
| BR-01 | Admin shall be able to create, read, update, and delete all content types through a browser-based interface | Must Have |
| BR-02 | Admin shall be able to manage the following content modules: services, team members, portfolio projects, blog posts, testimonials, partners, organizational chart, and job opportunities | Must Have |
| BR-03 | Admin shall be able to update global site settings (company name, contact info, social links, footer text) without developer assistance | Must Have |
| BR-04 | All content changes shall reflect on the public website immediately upon saving | Must Have |
| BR-05 | The system shall support role-based access control for administration (minimum: administrator role) | Must Have |

### 4.2 Public Website

The platform shall serve as a professional, informative public website that communicates MMCS's identity, services, expertise, and impact to prospective clients and partners.

| ID | Requirement | Priority |
|----|-------------|----------|
| BR-06 | The website shall present a professional homepage with company overview, key statistics, featured services, and client testimonials | Must Have |
| BR-07 | The website shall include dedicated pages for: About Us, Services, Portfolio, Team, Get Involved, Blog, and Contact | Must Have |
| BR-08 | All pages shall be responsive and accessible on desktop, tablet, and mobile devices | Must Have |
| BR-09 | The website shall load within 3 seconds on standard internet connections | Should Have |
| BR-10 | The website shall include SEO-friendly markup and structured content | Should Have |

### 4.3 Client Engagement & Inquiry Management

The platform shall capture and manage client inquiries and service quote requests through structured online forms.

| ID | Requirement | Priority |
|----|-------------|----------|
| BR-11 | The website shall provide a contact form for general inquiries | Must Have |
| BR-12 | The website shall provide a detailed quote request form allowing prospective clients to specify service type, budget range, and project scope | Must Have |
| BR-13 | All submitted inquiries and quote requests shall be stored in a database and accessible through the admin panel | Must Have |
| BR-14 | Admin shall be able to track inquiry status through a workflow (e.g., New → Read → Replied) | Must Have |
| BR-15 | Admin shall be able to export inquiry data for external reporting | Should Have |

### 4.4 Portfolio & Expertise Showcase

The platform shall showcase MMCS's project portfolio and expert team to establish credibility with prospective clients.

| ID | Requirement | Priority |
|----|-------------|----------|
| BR-16 | The portfolio page shall display project cards with client name, sector, location, year, and description | Must Have |
| BR-17 | Portfolio projects shall be filterable by sector (WASH, Education, Climate, Gender, Research, Livelihoods) | Must Have |
| BR-18 | The team page shall display expert profiles with photo, role, bio, and expertise tags | Must Have |
| BR-19 | The About page shall display the organizational hierarchy (org chart) | Must Have |
| BR-20 | The About page shall display partner/client logos with links | Must Have |

### 4.5 Career & Opportunity Portal

The platform shall enable MMCS to post job, volunteer, and expert opportunities and accept applications online.

| ID | Requirement | Priority |
|----|-------------|----------|
| BR-21 | The website shall display open opportunities categorized by type (expert, intern, volunteer) | Must Have |
| BR-22 | Prospective applicants shall be able to submit applications with a cover letter and PDF resume | Must Have |
| BR-23 | Admin shall be able to view, manage, and download applicant resumes | Must Have |
| BR-24 | Opportunities shall have configurable deadlines and status (open/closed) | Must Have |

### 4.6 Knowledge Sharing & Publications

The platform shall serve as a repository for MMCS's research publications, policy briefs, and knowledge products.

| ID | Requirement | Priority |
|----|-------------|----------|
| BR-25 | The blog page shall display published articles with category tagging | Must Have |
| BR-26 | Blog posts shall support PDF attachment uploads for downloadable publications | Must Have |
| BR-27 | Admin shall be able to publish/draft blog posts | Must Have |

### 4.7 Testimonials & Social Proof

The platform shall display client testimonials to build trust with prospective clients.

| ID | Requirement | Priority |
|----|-------------|----------|
| BR-28 | The homepage shall display a carousel of approved client testimonials | Must Have |
| BR-29 | Admin shall be able to approve/reject testimonials before they appear publicly | Must Have |

### 4.8 Security & Data Protection

The platform shall implement industry-standard security measures to protect user data and prevent unauthorized access.

| ID | Requirement | Priority |
|----|-------------|----------|
| BR-30 | All admin access shall require authentication with encrypted password storage | Must Have |
| BR-31 | The system shall implement protection against common web vulnerabilities (SQL injection, XSS, CSRF) | Must Have |
| BR-32 | Admin sessions shall expire after a configurable period of inactivity | Must Have |
| BR-33 | The system shall implement brute-force login protection | Must Have |
| BR-34 | File uploads shall be validated for type, size, and executed in a sandboxed environment | Must Have |

---

## 5. Out-of-Scope

The following features are explicitly excluded from the initial release:

| # | Item | Rationale |
|---|------|-----------|
| 1 | Multi-language support (Urdu/Sindhi) | English-only for initial target audience |
| 2 | E-commerce / payment processing | Not applicable to consultancy business model |
| 3 | User registration / public accounts | No consumer-facing user accounts needed |
| 4 | Mobile native application | Web platform only for initial release |
| 5 | Third-party CRM integration | Standalone admin panel sufficient for current scale |
| 6 | Multi-admin role-based permissions | Single administrator sufficient for small team |
| 7 | Real-time chat or messaging | Email-based communication preferred by clients |

---

## 6. Assumptions

1. MMCS will provide a hosting environment with Apache, PHP 8.x, and MySQL/MariaDB support.
2. MMCS will provide accurate content (text, images, project details) for initial population of the website.
3. A single administrator will manage the platform on a day-to-day basis.
4. The platform will be accessed primarily from desktop and mobile browsers in Pakistan.
5. English will be the sole language for the initial release.
6. Email notifications will use a Gmail SMTP account or equivalent service.

---

## 7. Constraints

| # | Constraint |
|---|-----------|
| 1 | Budget constraint requires a lightweight, framework-free PHP solution with no recurring license fees |
| 2 | Hosting environment is shared (XAMPP/Apache) — no Docker, Node.js, or containerized deployment |
| 3 | The solution must be maintainable by a non-developer after handover |
| 4 | All content management must be achievable through the admin panel without code changes |
| 5 | The platform must be operational within 6 weeks from project kickoff |

---

## 8. Acceptance Criteria Summary

The project shall be considered complete when:

- [ ] All public pages (Homepage, About, Services, Portfolio, Team, Get Involved, Blog, Contact) are functional and responsive
- [ ] Admin panel provides full CRUD for all 8 content modules
- [ ] Contact form and quote request form capture data and store it in the database
- [ ] Opportunity portal allows online applications with resume upload
- [ ] Admin can manage site settings (company info, social links, stats) without code changes
- [ ] Security measures (authentication, CSRF, XSS prevention, file upload validation) are implemented
- [ ] All seed data is populated and the platform is ready for production use
- [ ] Admin documentation (user guide) is delivered

---

## 9. Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Project Sponsor | Maha Dev Makwano | _____________ | ____/____/2026 |
| Project Manager | _____________ | _____________ | ____/____/2026 |
| Lead Developer | Vishwas Suthar | _____________ | ____/____/2026 |

---

*This document defines the business requirements for the MMCS Web Platform. All subsequent technical and design decisions shall trace back to these requirements.*
