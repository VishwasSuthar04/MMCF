-- ====================================================================
-- MMCS DATABASE SCHEMA & SEED DATA
-- ====================================================================
-- Database: mmcs_db (MySQL / MariaDB)
-- 
-- Run this file ONCE to create the database, tables, and default data.
-- 
-- TABLES:
--   services     — 8 consultancy service cards
--   projects     — portfolio / project showcase
--   team_members — expert / staff directory
--   blog_posts   — articles and publications
--   inquiries    — contact form & quote request submissions
--   testimonials — client testimonials
--   admins       — administrator login credentials
--   settings     — global site configuration (key/value)
--   partners     — clients & partners logos
--   organogram   — organizational hierarchy (org chart)
-- 
-- USAGE:
--   1. Import into phpMyAdmin, or run:
--      mysql -u root -p < schema.sql
--   2. Then visit /admin/login.php and log in with the seeded admin.
-- ====================================================================

CREATE DATABASE IF NOT EXISTS `mmcs_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `mmcs_db`;

-- 1. Services Table
CREATE TABLE IF NOT EXISTS `services` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `icon_path` VARCHAR(255) DEFAULT 'bi-gear',
  `is_active` TINYINT(1) DEFAULT 1,
  `sort_order` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Projects Table (Portfolio)
CREATE TABLE IF NOT EXISTS `projects` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `client` VARCHAR(255) NOT NULL,
  `sector` VARCHAR(100) NOT NULL, -- e.g., WASH, Education, Climate, Gender
  `location` VARCHAR(255) NOT NULL,
  `year` INT NOT NULL,
  `description` TEXT NOT NULL,
  `images` TEXT NOT NULL, -- JSON or Comma-separated list of image paths
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Team Members Table
CREATE TABLE IF NOT EXISTS `team_members` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `role` VARCHAR(255) NOT NULL,
  `photo` VARCHAR(255) DEFAULT 'default-avatar.png',
  `bio` TEXT NOT NULL,
  `expertise_tags` VARCHAR(255) NOT NULL, -- Comma-separated (e.g. MEAL, Proposal)
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Blog Posts Table
CREATE TABLE IF NOT EXISTS `blog_posts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `content` LONGTEXT NOT NULL,
  `category` VARCHAR(100) NOT NULL,
  `file_path` VARCHAR(255) DEFAULT NULL, -- PDF/Resource attachment
  `published_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `status` VARCHAR(20) DEFAULT 'draft', -- draft, published
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Inquiries & Messages Table
CREATE TABLE IF NOT EXISTS `inquiries` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `org` VARCHAR(255) DEFAULT NULL,
  `email` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `type` VARCHAR(50) DEFAULT 'contact', -- contact, inquiry (quote)
  `status` VARCHAR(20) DEFAULT 'New', -- New, Read, Replied, Pending, In Progress, Closed
  `service_type` VARCHAR(100) DEFAULT NULL, -- only for inquiry
  `budget_range` VARCHAR(100) DEFAULT NULL, -- only for inquiry
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Testimonials Table
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `client_name` VARCHAR(255) NOT NULL,
  `org` VARCHAR(255) NOT NULL,
  `quote` TEXT NOT NULL,
  `photo` VARCHAR(255) DEFAULT 'default-avatar.png',
  `is_approved` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Admins Table
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(100) UNIQUE NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `last_login` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. Opportunities Table (Get Involved / Job Openings)
CREATE TABLE IF NOT EXISTS `opportunities` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `type` VARCHAR(50) NOT NULL COMMENT 'volunteer, intern, expert',
  `description` TEXT NOT NULL,
  `requirements` TEXT DEFAULT NULL,
  `location` VARCHAR(255) DEFAULT NULL,
  `deadline` DATE DEFAULT NULL,
  `status` VARCHAR(20) DEFAULT 'open' COMMENT 'open, closed',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 9. Applications Table
CREATE TABLE IF NOT EXISTS `applications` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `opportunity_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `cover_letter` TEXT DEFAULT NULL,
  `resume_path` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`opportunity_id`) REFERENCES `opportunities`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 10. Partners Table
CREATE TABLE IF NOT EXISTS `partners` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `logo` VARCHAR(255) DEFAULT NULL,
  `website` VARCHAR(255) DEFAULT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `sort_order` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 11. Settings Table
CREATE TABLE IF NOT EXISTS `settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `key` VARCHAR(100) UNIQUE NOT NULL,
  `value` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- 12. Organogram Table (Org Chart)
CREATE TABLE IF NOT EXISTS `organogram` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `designation` VARCHAR(255) NOT NULL,
  `photo` VARCHAR(255) DEFAULT NULL,
  `parent_id` INT DEFAULT NULL,
  `display_order` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`parent_id`) REFERENCES `organogram`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SEED DATA --

-- Seed Default Settings
INSERT INTO `settings` (`key`, `value`) VALUES
 ('site_logo', 'assets/images/mmcs-logo.png'),

('company_name', 'MM Consultancy Solutions (Private) Limited'),
('phone', '0334-2656314'),
('email', 'mmconsultancysolutions@gmail.com'),
('address', 'Suther Colony East, Mithi-PO Box-69230, Tharparkar, Sindh'),
('field_address', 'Al Rahim Villas, Qasimabad, Hyderabad, Sindh'),
('social_linkedin', 'https://www.linkedin.com/company/mm-consultancy-solutions-private-limited-mmcs/about/'),
('social_twitter', 'https://twitter.com/mmcs'),
('social_facebook', 'https://web.facebook.com/profile.php?id=61574248520929'),
('stats_years', '10+'),
('stats_projects', '150+'),
('stats_clients', '50+'),
('footer_text', '© 2026 MM Consultancy Solutions (Private) Limited. All rights reserved.'),
('consultation_fee', 'Free');

-- ---------------------------------------------------------------------------
--  ADMIN ACCOUNT (not seeded on purpose)
-- ---------------------------------------------------------------------------
--  No default admin row is shipped so that no shared or guessable credential
--  ever exists in this repository. Create the first admin after importing:
--
--    1. Generate a hash for your chosen password:
--         php -r "echo password_hash('YOUR_PASSWORD_HERE', PASSWORD_DEFAULT), PHP_EOL;"
--
--    2. Insert it (replace the hash and password with your own):
--         INSERT INTO `admins` (`username`, `password_hash`) VALUES
--         ('MahaDev', 'PASTE_HASH_FROM_STEP_1');
--
--  The intended admin username is `MahaDev`. Use a unique, strong password --
--  this account has full control over the site. You can add further admins
--  later from Admin Panel → Settings → Security Gate.
--
--  Note: bcrypt hashes remain crackable if the password is weak, so treat the
--  hash as a secret and never commit it.
-- ---------------------------------------------------------------------------

-- Seed Default Services (updated for 2026 company profile)
INSERT INTO `services` (`title`, `description`, `icon_path`, `sort_order`) VALUES
('End-to-End (E2E) Project Support (EEPS)', 'MMCS signature service framework functioning as the complete external technical arm of NGOs and development organizations. Covers proposal writing, implementation support, MEAL, reporting, capacity building, and impact documentation under one retainer and one team.', 'bi-diagram-3', 1),
('Monthly Reporting & E-newsletter', 'A retainer communications service delivering monthly progress reports, narrative reports, donor e-newsletters, field visit reports, presentations, social media content, and annual reports.', 'bi-file-earmark-text', 2),
('Monitoring, Evaluation, Accountability & Learning (MEAL)', 'Full MEAL function including framework and indicator development, monthly monitoring visits, baseline/midline/endline surveys, digital data tools (KoBoToolbox), and third-party verification.', 'bi-graph-up-arrow', 3),
('Proposal & Grant Writing', 'Competitive and compliant proposal writing from concept note to full submission. Budget development, EOI/RFP responses, log frame and Theory of Change design for major donor formats.', 'bi-pencil-square', 4),
('Training & Capacity Building', 'Practical trainings including inception trainings, Training of Trainers (ToT), MEAL training, report writing workshops, technical trainings in WASH/NFE/Gender, and community mobilization.', 'bi-people', 5),
('Research & Knowledge Products', 'Turning field data into credible knowledge — thematic research papers, sector studies, lessons learned documentation, knowledge briefs, data visualization, and impact assessments.', 'bi-search', 6),
('Water Quality Analysis (WQA)', 'On-site WASH consultancy and water quality testing per APHA Standard Methods. TDS, pH, Turbidity, EC, Total Coliform, E. Coli testing using portable meters from village to district scale.', 'bi-droplet', 7),
('Field Documentation & Visibility', 'End-to-end visual documentation including field photography and videography, success and human interest stories, documentary production, branding and communication materials.', 'bi-camera', 8),
('Independent Evaluations & Assessments', 'End-of-project evaluations and rapid assessments as standalone engagements or as part of EEPS retainer. Credible, donor-ready evidence products for any program scale.', 'bi-clipboard-data', 9);

-- Seed Placeholder Testimonials
-- These are PLACEHOLDERS with invented names and quotes, seeded unapproved
-- (is_approved = 0) so they never render on the public homepage carousel,
-- which only shows is_approved = 1. Replace them with real, attributable
-- client quotes and set is_approved = 1 from Admin -> Testimonials.
INSERT INTO `testimonials` (`client_name`, `org`, `quote`, `photo`, `is_approved`) VALUES
('John Doe', 'Global Development Initiative', 'MMCS delivered exceptional MEAL reporting for our climate resilience program. Highly recommended!', 'client1.jpg', 0),
('Sarah Jenkins', 'WASH Alliance', 'Their proposal writing team helped us secure a major funding grant. Very professional and timely.', 'client2.jpg', 0);

-- Seed Initial Projects
INSERT INTO `projects` (`title`, `client`, `sector`, `location`, `year`, `description`, `images`) VALUES
('Clean Water Initiative', 'WASH Alliance NGO', 'WASH', 'Sindh, Pakistan', 2025, 'Implemented comprehensive clean water supplies, sanitation infrastructure audits, and hygiene training programs reaching over 10,000 households.', 'project1.jpg'),
('Primary Education Quality Enhancement', 'EduTrust International', 'Education', 'Punjab, Pakistan', 2024, 'Conducted third-party monitoring and baseline assessments of teacher training outcomes across 50 regional primary schools.', 'project2.jpg'),
('Climate Resilient Agriculture', 'Climate Action Fund', 'Climate', 'KPK, Pakistan', 2025, 'Evaluated socio-economic impacts of climate-smart farming techniques and drought-resistant crops among smallholder farmers.', 'project3.jpg');

-- Seed Partners
INSERT INTO `partners` (`name`, `logo`, `website`, `is_active`, `sort_order`) VALUES
('SONAHRI HUMANITARIAN DEVELOPMENT SOCIETY (SHDS)', NULL, NULL, 1, 1),
('HELP TO UNDER-PRIVILEGED MASSES AND NON-DEVELOPED SOCIETIES (HUMANS)', NULL, NULL, 1, 2),
('BAANH BELI, THARPARKAR', NULL, NULL, 1, 3);

-- Seed Initial Blog Posts
INSERT INTO `blog_posts` (`title`, `content`, `category`, `status`) VALUES
('The Importance of MEAL in Development', 'Monitoring, Evaluation, Accountability, and Learning (MEAL) is essential for measuring the efficacy of humanitarian initiatives. Without proper indicators and continuous feedback loops, it is impossible to evaluate long-term impacts...', 'Research', 'published'),
('Writing a Winning Grant Proposal', 'Securing funding from major international donors requires a structured approach to problem definitions, logical frameworks, and detailed budgeting. This article outlines key strategies for success...', 'Guides', 'published');

-- Seed Opportunities
INSERT INTO `opportunities` (`title`, `type`, `description`, `location`, `status`) VALUES
('Community Volunteer — MEAL Support', 'volunteer', 'Assist the MEAL team in field data collection, community feedback sessions, and basic reporting. Ideal for fresh graduates.', 'Mithi, Tharparkar', 'open'),
('MEAL Intern — Data Analytics', 'intern', 'Support digital data collection tools, dashboards, and quantitative analysis for ongoing projects.', 'Hyderabad, Sindh', 'open'),
('WASH Technical Expert', 'expert', 'Lead water quality assessments, sanitation audits, and hygiene promotion strategies for rural development programs.', 'Sindh, Pakistan', 'open');

-- Seed Application (example)
INSERT INTO `applications` (`opportunity_id`, `name`, `email`, `phone`, `cover_letter`) VALUES
(1, 'Ali Khan', 'ali.khan@email.com', '0300-1234567', 'I am a recent graduate in Development Studies with a passion for MEAL systems. I am eager to contribute to MMCS field operations and learn from experienced professionals.');

-- Seed Organogram (multi-level hierarchy)
INSERT INTO `organogram` (`id`, `name`, `designation`, `photo`, `parent_id`, `display_order`) VALUES
(1, 'Maha Dev Makwano', 'Executive Director', NULL, NULL, 1),
(2, 'Dr. Amir Jalal', 'Head of Programs & Technical Services', NULL, 1, 1),
(3, 'Sarah Baloch', 'Head of Operations & Finance', NULL, 1, 2),
(4, 'Bhaskar Sodho', 'Head of Research & Knowledge', NULL, 1, 3),
(5, 'Ali Raza', 'Senior MEAL Specialist', NULL, 2, 1),
(6, 'Nadia Bajwa', 'WASH Technical Lead', NULL, 2, 2),
(7, 'Imran Memon', 'Proposal & Grants Manager', NULL, 2, 3),
(8, 'Fatima Junejo', 'Finance & Admin Officer', NULL, 3, 1),
(9, 'Usman Mirani', 'HR & Operations Coordinator', NULL, 3, 2),
(10, 'Dr. Kamla Devi', 'Senior Research Analyst', NULL, 4, 1),
(11, 'Asadullah Memon', 'Data & Documentation Specialist', NULL, 4, 2);
