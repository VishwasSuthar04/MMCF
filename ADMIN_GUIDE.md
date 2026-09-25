# MMCS Admin Panel — User Guide

**Design & Development:** Vishwas Suthar

---

## 1. Getting Started

### Login
1. Navigate to `https://yourdomain.com/admin/login.php`
2. Enter your username and password
3. You will be redirected to the Dashboard

### First Login
- **Change your password immediately** — go to **Site Settings** → **Security Gate**
- New password must contain: uppercase letter, lowercase letter, and a number (min 6 characters)

---

## 2. Dashboard

The Dashboard shows summary statistics:
- **Messages** — contact form submissions (click to view inbox)
- **Quotes (NGO)** — service quote requests (click to view)
- **Projects** — total portfolio entries
- **Team Experts** — active staff profiles

Click **View Site** in the top bar to open the public website in a new tab.

---

## 3. Managing Content

### Services
- **Add:** Click "Add New Service" → fill in title, description, icon, sort order → Save
- **Edit:** Click the pencil icon next to a service
- **Delete:** Click the trash icon (permanent)
- Services display in ascending sort order on the public site

### Portfolio / Projects
- **Add:** Click "Add New Project" → fill in title, client, sector, location, year, description, and upload a feature image → Save
- Supported image formats: JPG, PNG, WebP (max 5MB)

### Team / Experts
- **Add:** Click "Add Expert Profile" → fill in name, designation, bio, expertise tags, and upload a photo → Save
- Expertise tags are comma-separated (e.g. "MEAL, WASH, Research")

### Blog Posts
- **Add:** Click "Write New Post" → enter title, content, category, optionally attach a PDF resource → Save
- Status options: **Draft** (hidden from site) or **Published** (visible)

### Testimonials
- **Add:** Click "Add Testimonial" → enter client name, organization, quote text, optionally upload a photo
- **Approve:** Click the "Pending" button to toggle approval
- Only approved testimonials appear on the homepage

### Opportunities (Jobs / Volunteer / Intern)
- **Add:** Click "Add Opportunity" → enter title, type, description, requirements, location, deadline → Save
- **View Applications:** Click the people icon to see applicants and download their resumes
- Close expired opportunities by setting status to "Closed"

---

## 4. Managing Inquiries

### Contact Messages (Inbox)
- **View:** Click the eye icon to read a message
- **Change Status:** Use the dropdown to mark as New / Read / Replied
- **Delete:** Click the trash icon
- **Export:** Click "Export CSV" to download all messages

### Quote Requests
- Same workflow as contact messages
- **Print:** Click "Print / Save PDF" to generate a printable quote summary

---

## 5. Site Settings

### Global Configuration
Update company name, phone, email, addresses, social media links, stats counters, and footer text.

**Important:** After changing any setting, refresh the public site to see updates.

### Security Gate (Password Change)
1. Enter current password
2. Enter new password (must contain uppercase + lowercase + number)
3. Confirm new password
4. Click "Change Password"

---

## 6. Security Best Practices

| Practice | Why |
|----------|-----|
| Change password every 90 days | Prevents credential compromise |
| Log out when done | Prevents unauthorized access on shared computers |
| Keep admin URL private | Reduces attack surface |
| Use strong, unique passwords | Prevents brute-force and credential stuffing |
| Regularly review inquiries | Respond to clients promptly |

---

## 7. Troubleshooting

| Problem | Solution |
|---------|----------|
| Can't log in | Click "Forgot password?" — contact developer to reset |
| Uploaded images don't appear | Check file is under 5MB and JPG/PNG/WebP format |
| Changes not showing on site | Refresh the page (Ctrl+F5) — settings are cached per request |
| Email notifications not sending | Contact developer to verify SMTP configuration |
| "Session expired" message | Your session timed out after 30 minutes of inactivity — log in again |

---

## 8. Support

For technical issues or feature requests, contact the developer.

**Developer:** Vishwas Suthar
