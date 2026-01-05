## Technical Documentation – Green Resources Corporate Website & CMS

> This document summarizes the technical design and implementation of the Green Resources corporate website and CMS.  
> It is aligned with the Product Requirement Document (PRD) and should be updated if the implementation diverges.

### 1. System Overview

The system consists of:

- **Public Website** – read-only corporate site for external visitors.
- **CMS / Admin Panel** – authenticated backend for internal content managers.
- **Backend Services & Data Store** – APIs and persistence for content and inquiries.

At a high level:

- Public users access a responsive website over HTTPS.
- Content is served from a data store managed via the CMS.
- Contact forms submit to backend endpoints that send emails and log inquiries.

### 2. Architecture

> Replace placeholders in this section with the actual stack used in your project.

- **Frontend (Public Site & Admin UI)**
  - Framework: `<React / Next.js / Vue / Nuxt / Other>`
  - Rendering model: `<SPA / SSR / SSG / Hybrid>`
  - Styling: `<Tailwind CSS / CSS Modules / SCSS / Design System>`
  - Routing:
    - Public routes: `/`, `/about`, `/business`, `/sustainability`, `/news`, `/careers`, `/contact`
    - Admin routes: `/admin/login`, `/admin/dashboard`, `/admin/pages`, `/admin/media`, `/admin/settings`, `/admin/inquiries`

- **Backend**
  - Runtime / Framework: `<Node.js (Express/Nest) / Laravel / Django / Other>`
  - Responsibilities:
    - Authentication and authorization for CMS users
    - CRUD for content entities (pages, sections, media, navigation, settings)
    - Contact form endpoint(s) and email dispatch
    - Persistence of inquiries and content
    - Validation, logging, and error handling

- **Data Store**
  - Database: `<PostgreSQL / MySQL / SQL Server / Other>`
  - Optional: Object storage for media (e.g. `<S3-compatible storage>`)

- **Deployment**
  - Environments: `development`, `staging`, `production`
  - Hosting: `<Cloud provider / On-premise>`
  - Reverse proxy / load balancer: `<Nginx / Cloud Load Balancer / Other>`
  - TLS termination at the edge (HTTPS)

### 3. Data Model

> Adjust field names and relationships to match your implementation.

- **Page**
  - `id`
  - `slug` (e.g. `home`, `about`, `business`, `sustainability`, `news`, `careers`, `contact`)
  - `title`
  - `meta_title`
  - `meta_description`
  - `status` (draft/published)
  - `created_at`, `updated_at`

- **Section**
  - `id`
  - `page_id` (FK → Page)
  - `type` (hero, content_block, highlight_list, timeline, etc.)
  - `title` (optional)
  - `body` (rich text / JSON)
  - `media_id` (optional, FK → Media)
  - `order`

- **Media**
  - `id`
  - `file_name`
  - `file_path` / `url`
  - `mime_type`
  - `alt_text`
  - `created_at`

- **NavigationItem**
  - `id`
  - `label`
  - `target_url` (path or external)
  - `order`
  - `visible` (boolean)

- **SiteSettings**
  - Singleton (or key/value pairs) containing:
    - Company name, address, phone, email
    - Social links
    - Contact email recipients / CC / BCC
    - Optional: default SEO metadata

- **Inquiry**
  - `id`
  - `name`
  - `company`
  - `email`
  - `phone` (optional)
  - `subject`
  - `message`
  - `submitted_at`
  - `status` (e.g. new, handled)

- **User (Admin)**
  - `id`
  - `name`
  - `email`
  - `password_hash`
  - `role` (e.g. admin, editor, viewer)
  - `created_at`, `last_login_at`

### 4. Key Flows

#### 4.1 Public Page Rendering

1. User requests a URL (e.g. `/about`).
2. Router resolves slug and loads:
   - Page metadata (title, meta, status)
   - Ordered sections and related media
3. Frontend renders layout and sections following design system.
4. Browser receives fully responsive, SEO-friendly page.

#### 4.2 CMS Content Editing

1. Admin logs into `/admin/login` using email and password.
2. System:
   - Validates credentials
   - Issues a session/cookie or JWT (depending on implementation)
   - Applies role-based access rules for subsequent actions
3. Admin navigates to `Pages`:
   - Selects a page or creates a new one
   - Edits sections via WYSIWYG editor (text, images, CTAs)
4. On save:
   - Backend validates payload
   - Writes changes to `Page` + `Section` + `Media` records
   - Returns updated data to the client.

#### 4.3 Contact Form Submission

1. User submits the contact form on `/contact` with:
   - Name, Company, Email, optional Phone, Subject, Message.
2. Frontend performs basic validation and sends request to `/api/contact` (or equivalent).
3. Backend:
   - Validates required fields and email format
   - Persists the inquiry record
   - Resolves recipients from `SiteSettings`
   - Sends one or more emails (to recipients, CC/BCC)
   - Optionally sends an auto-reply to the submitter
4. Response:
   - On success: return confirmation; frontend shows success message.
   - On failure: log error and return safe error message; frontend shows retry/alternative contact options.

### 5. Security & Compliance

- **Transport Security**
  - All traffic served over HTTPS with valid TLS certificates.

- **Authentication & Authorization**
  - CMS restricted to authenticated users only.
  - Role-based access control (RBAC) for admin/editor/viewer roles.

- **Input Validation & Sanitization**
  - Server-side validation for all user and admin inputs.
  - Protection against:
    - SQL Injection (parameterized queries/ORM)
    - XSS (output escaping, HTML sanitization for rich text)
    - CSRF (tokens or same-site cookies for state-changing requests)

- **Secrets & Configuration**
  - Secrets (DB credentials, SMTP credentials, JWT/crypto keys) stored in environment variables or a secure secret store.

- **Logging & Monitoring**
  - Application logs for errors and key events (log-ins, content changes, failed email sends).
  - Inquiry logging per PRD requirements.

### 6. Performance & Scalability

- **Performance Targets**
  - Page load \< 3 seconds on typical broadband/mobile connections.
  - Asset optimization (image compression, lazy loading, caching).

- **Caching**
  - HTTP caching headers for static assets.
  - Optional: application-level caching for frequently accessed content.

- **CDN**
  - Static assets (and potentially HTML) served via CDN for global performance.

- **Scalability**
  - Stateless backend where possible, enabling horizontal scaling.
  - Database tuned and ready for moderate traffic with room to grow.

### 7. Deployment & Environments

> Update this section to match your actual CI/CD and hosting setup.

- **Environments**
  - `development`: local developer machines.
  - `staging`: QA and UAT; mirrors production configuration as much as possible.
  - `production`: live environment for end-users.

- **CI/CD**
  - On push to main or release branches:
    - Run tests and linters.
    - Build frontend and backend artifacts.
    - Deploy to target environment (container, server, or PaaS).

- **Configuration per Environment**
  - Base URLs (public site, CMS, APIs)
  - Database connection strings
  - SMTP configuration for emails
  - Logging and monitoring endpoints

### 8. Operations & Maintenance

- **Backups**
  - Regular automated database backups.
  - Recovery procedure tested and documented.

- **Monitoring**
  - Uptime and performance monitoring (e.g. health checks, APM).
  - Error tracking (e.g. centralized logging or error monitoring tool).

- **Content Governance**
  - Recommended content workflows:
    - Draft → Review → Publish
    - Role-based controls for who can publish.

### 9. Future Enhancements (Technical Considerations)

Per the PRD, these features are **out of scope for initial release** but should be considered in the architecture:

- **Multi-language Support**
  - I18n-ready content model (per-locale fields or separate records).
  - Localized routing and SEO (e.g. `/en`, `/id`, etc.).

- **Analytics Dashboard**
  - Capture key metrics (page views, sessions, conversions).
  - Expose them in an admin dashboard or integrate with an external analytics provider.

- **CRM Integration**
  - Sync inquiries into CRM via API/webhooks.

- **Sustainability Reporting Portal**
  - Secure, possibly role-based area to publish sustainability data and reports.

- **Investor Relations Section**
  - Structured data for financial reports, press releases, and filings.

Update this document as the implementation details (stack, services, and infrastructure) are finalized or evolve over time.


