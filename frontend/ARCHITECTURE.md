# Green Resources Website & CMS – Architecture

This document describes the **system architecture** for the Green Resources corporate website and CMS.

---

## 1. High-Level Architecture

```text
Browser
  ↓
Nginx (docker/nginx/default.conf, port 8000)
  ↓
PHP-FPM (container: app, PHP 8.2, Laravel 10)
  ↓
Laravel Application
  ├── Routes (routes/web.php)
  ├── Middleware (security, auth, CSRF, headers)
  ├── Controllers (public + admin)
  ├── Models (Page, Section, Media, Inquiry, etc.)
  ├── Services (HtmlSanitizer)
  └── Blade Views (public site, CMS UI)
  ↓
PostgreSQL (container: postgres, database `greenresource`)
```

The system runs as **three Docker services**:

- `app` – PHP-FPM + Laravel application.
- `nginx` – Nginx reverse proxy and static asset server.
- `postgres` – PostgreSQL database.

---

## 2. Application Layers

### 2.1 Routing Layer (`routes/web.php`)

- **Public routes** – map friendly URLs to CMS-backed pages:
  - `/` → `PageController@home`
  - `/company/about-us` → `PageController@show('company-about-us')`
  - `/products/*`, `/news-and-event/*`, `/contact`, etc.
- **Contact** – `POST /contact` handled by `ContactController@submit`.
- **Admin** – all `/admin/*` routes:
  - `/admin/login` (GET/POST)
  - `/admin/dashboard`
  - `/admin/pages`, `/admin/pages/{page}/sections`, `/admin/media`, `/admin/inquiries`, `/admin/settings`
  - Protected by `auth` middleware.
  - Login POST additionally protected by `throttle:5,1` (rate limiting).

### 2.2 Middleware

Key middleware in `app/Http/Middleware` and `Kernel.php`:

- `EncryptCookies`, `StartSession`, `VerifyCsrfToken` – standard Laravel web security.
- `CheckRole` – for role-based access control (future expansion).
- `SecurityHeaders` – **custom**:
  - Adds `X-Content-Type-Options`, `X-Frame-Options`, `X-XSS-Protection`,
    `Referrer-Policy`, and `Permissions-Policy` on every response.
- `auth` – protects admin routes.
- `throttle` – rate limits login attempts.

### 2.3 Controllers

**Public:**

- `PageController`
  - `home()` – renders the home page using `Page` + `Section` models (with eager loading and caching).
  - `show($slug)` – resolves a page by slug (`pages.slug`) and renders via `resources/views/page.blade.php`.
- `ContactController`
  - Validates contact form input.
  - Saves `Inquiry` record.
  - Sends notification emails using `Mail` + `emails/inquiry-notification.blade.php`.

**Admin (CMS):**

- `Admin\AuthController` – login/logout, session handling, updates `last_login_at`.
- `Admin\PageController` – CRUD for `Page`:
  - Manages slug, meta fields, status, and banner JSON (title, subtitle, image).
  - Handles banner image upload (stored in `storage/app/public/media/banners`).
  - Invalidates page cache on create/update/delete.
- `Admin\SectionController` – CRUD for `Section`:
  - Types: `hero`, `content_block`, etc.
  - Uses `HtmlSanitizer` (HTMLPurifier) to clean `body` before saving.
  - Manages ordering and associated `Media` (optional).
  - Invalidates page cache after changes.
- `Admin\MediaController` – media library for uploads and deletions.
- `Admin\InquiryController` – lists inquiries and updates their status.
- `Admin\SettingsController` – site settings (e.g., contact email recipients).

### 2.4 Models & Data Relationships

Located in `app/Models`:

- `Page`
  - Fields: `slug`, `title`, `meta_title`, `meta_description`, `status`, `banner` (JSON), timestamps.
  - Relationships:
    - `hasMany(Section::class)` – `sections()` ordered by `order`.
  - Uses `casts` to treat `banner` as an array.

- `Section`
  - Fields: `page_id`, `type`, `title`, `body`, `media_id`, `order`.
  - Relationships:
    - `belongsTo(Page::class)`.
    - `belongsTo(Media::class)` – optional image/media.

- `Media`
  - Fields: `file_name`, `file_path`, `url`, `mime_type`, `file_size`, `alt_text`.
  - Accessor `getUrlAttribute()` builds a public URL from `storage/` when `url` is not set.

- `Inquiry`
  - Stores contact form submissions (`name`, `email`, `subject`, `message`, `status`, `submitted_at`).

- `SiteSetting`, `NavigationItem`, `User`
  - Provide configuration, navigation model, and admin accounts.

Database schema is defined via migrations in `database/migrations/*_create_*_table.php` and seeded using `DatabaseSeeder`.

---

## 3. View Layer (Blade Templates)

### 3.1 Public Layout

- `resources/views/layouts/app.blade.php`
  - Global `<head>` (meta tags, font, CSS).
  - Top navigation bar with company/products/news/contact menus.
  - `@yield('content')` for page-specific content.
  - Footer with quick links and company info.

### 3.2 Dynamic Pages

- `resources/views/page.blade.php`
  - Renders the page banner from `$banner` (image, title, subtitle).
  - Iterates `@foreach($page->sections as $section)` and includes section partials dynamically:
    - `sections.hero` – hero layout.
    - `sections.content_block` – generic content with optional image.
  - For unknown types, falls back to a simple section with title/body.

### 3.3 Admin Views

- `resources/views/layouts/admin.blade.php` – base layout for CMS.
- Dashboard: `admin/dashboard.blade.php` – includes a **Security Status** card summarizing protections.
- Pages: `admin/pages/*.blade.php` – list, create, edit, show, preview.
- Sections: `admin/sections/*.blade.php` – manage sections for each page.
- Media, Inquiries, Settings: respective `admin/*` views.

---

## 4. Caching & Performance Architecture

### 4.1 Application Caching

- `PageController@show` and `PageController@home` cache page data:
  - Cache key: `page.{slug}` and `page.home`.
  - Duration: 3600 seconds (1 hour) by default.
- Cache invalidation:
  - In `Admin\PageController` and `Admin\SectionController`, after create/update/delete:
    - `Cache::forget("page.{$page->slug}")`
    - `Cache::forget('page.home')`

### 4.2 Database Indexes

Additional indexes (migration `*_add_indexes_for_performance.php`):

- `pages`: indexes on `status` and composite `slug,status`.
- `sections`: composite index on `page_id,order` and index on `type`.
- `media`: index on `file_path`.

### 4.3 Nginx & Static Assets

- Nginx serves `/public` as the document root.
- Gzip compression enabled for HTML/CSS/JS and related types.
- Long-lived caching headers for images, CSS, JS.
- All application requests are rewritten to `index.php` (`try_files $uri $uri/ /index.php?$query_string;`).

---

## 5. Security Architecture

### 5.1 Input & Output Handling

- All standard Blade output uses `{{ }}` (escaped).
- Rich text (`sections.body`) is sanitized using `App\Services\HtmlSanitizer`:
  - Wraps **HTMLPurifier** with an allowlist configuration.
  - Removes scripts, event handlers, and unsafe protocols.
- CSRF protection via `VerifyCsrfToken` middleware and `@csrf` in all forms.

### 5.2 Authentication & Authorization

- Session-based admin authentication via `AuthController` and Laravel’s `auth` guard.
- `auth` middleware protects all `/admin/*` routes except login.
- Login POST route uses `throttle:5,1` to limit brute force attempts.
- Role-based access (via `CheckRole`) is available for future use.

### 5.3 HTTP Security Headers

- `SecurityHeaders` middleware adds:
  - `X-Content-Type-Options: nosniff`
  - `X-Frame-Options: DENY`
  - `X-XSS-Protection: 1; mode=block`
  - `Referrer-Policy: no-referrer-when-downgrade`
  - `Permissions-Policy: geolocation=(), microphone=(), camera=()`
- For production HTTPS deployment, `Strict-Transport-Security` and CSP can be added.

---

## 6. Deployment Topology

### 6.1 Docker Compose (Development / Single-Host)

- `docker-compose.yml` defines:
  - `app` (php-fpm, port 9000 internal)
  - `nginx` (port 8000 → 80 inside container)
  - `postgres` (port 5434 host → 5432 inside container)

### 6.2 Production Considerations

For production, you can:

- Run the same containers on a server (or Kubernetes), fronted by a load balancer / reverse proxy with TLS.
- Move cache / sessions / queues to Redis or other specialized services.
- Configure automated backups for the PostgreSQL volume.

---

## 7. How CMS Drives the Public Site

1. **Admin** configures pages and sections in the CMS.
2. `Page` records define slugs, meta data, and banner config.
3. `Section` records define structured content and link to `Media` when needed.
4. Public routes resolve slugs to `Page` models and render through `page.blade.php`.
5. Content editors do not need to touch Blade templates; all content changes happen via CMS.

This separation allows the design system (CSS + Blade layouts) to evolve independently from content, while keeping everything in a single Laravel codebase.







