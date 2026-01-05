# Green Resources Corporate Website & CMS (Laravel + Docker)

This is the **Laravel 10** application that powers the **Green Resources** public website and **content management system (CMS)**.  
It runs behind **Nginx** with **PHP-FPM** and uses **PostgreSQL** as the primary database.

---

## Features

- **Public Website**
  - Home, Company, Products, News & Event, Contact pages
  - Dynamic page content managed via CMS (Pages + Sections)
  - Per-page banners (upload from CMS)
  - Montserrat typography and Green Resources brand colors

- **CMS / Admin**
  - Admin authentication (`/admin/login`)
  - Pages CRUD with banner management and preview
  - Sections CRUD (hero + content blocks) with media support
  - Media library (image uploads, reuse across sections)
  - Contact inquiries listing + status updates
  - Site settings (e.g. email recipients)

- **Security & Hardening**
  - CSRF protection on all forms
  - HTMLPurifier-based sanitization for rich text (prevents stored XSS)
  - Login rate limiting (`throttle:5,1`) on admin login
  - Global security headers middleware (X-Frame-Options, X-Content-Type-Options, etc.)
  - Eloquent ORM for all DB access (SQL injection resistant)

- **Performance**
  - Page-level caching for public pages (1 hour, with automatic invalidation)
  - DB indexes on `pages`, `sections`, and `media`
  - Lazy-loading images and Nginx gzip + asset caching

---

## Getting Started (Docker)

The easiest way to run the project is with Docker. See `DOCKER_SETUP.md` for full details.

### Quick start

```bash
cd frontend
docker-compose up -d --build

# (first-time only)
docker-compose exec app php artisan migrate --seed
docker-compose exec app php artisan storage:link
```

Then visit:

- Public site: `http://localhost:8000`
- Admin CMS: `http://localhost:8000/admin/login`  
  - Default admin: `admin@greenresources.com` / `admin123`

---

## Key Documentation

- `DOCKER_SETUP.md` – Docker services, ports, and common commands
- `BACKEND_EXPLANATION.md` – Overview of Laravel as the backend
- `OPTIMIZATION.md` – Performance & caching strategy
- `TEST_SCENARIOS.md` – Manual test checklist
- `TEST_RESULTS.md` – Summary of executed tests
- `SECURITY_REVIEW.md` – Security review (with mitigation updates)
- `PENETRATION_TEST_RESULTS.md` – Pen-test results (with mitigation updates)
- `ARCHITECTURE.md` – High-level system & application architecture

---

## Local (Non-Docker) Setup (Optional)

If you prefer running Laravel directly on your machine (XAMPP/Laragon/WAMP), follow `docs/SETUP_GUIDE.md`, then:

```bash
cd frontend
composer install
cp .env.example .env   # or copy docs/.env template
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

---

## Project Layout

```text
frontend/
├── app/
│   ├── Http/
│   │   ├── Controllers/       # Public + Admin controllers
│   │   ├── Middleware/        # Includes SecurityHeaders
│   │   └── Kernel.php
│   ├── Models/                # Page, Section, Media, Inquiry, etc.
│   └── Services/              # HtmlSanitizer (HTMLPurifier wrapper)
├── bootstrap/
├── config/
├── database/
│   ├── migrations/            # Tables for pages, sections, media, etc.
│   └── seeders/               # Admin user + initial pages
├── docker/                    # Nginx config
├── public/                    # index.php, assets, compiled files
├── resources/
│   └── views/                 # Blade templates (public + admin + sections)
├── routes/
│   └── web.php                # All public + admin routes
└── composer.json
```

---

## Environment & Credentials

Key `.env` settings (Docker defaults):

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=greenresource
DB_USERNAME=postgres
DB_PASSWORD=postgres123
```

For production:

- Set `APP_ENV=production` and `APP_DEBUG=false`
- Configure real DB, mail, and APP_URL values
- Add HTTPS / TLS termination in Nginx or your load balancer

---

## Security Highlights

- CSRF protection on all state-changing routes
- HTMLPurifier sanitization for section bodies
- Login throttling on `/admin/login`
- Global security headers middleware
- Eloquent ORM only, no raw SQL

For detailed security analysis and pen-test notes, see `SECURITY_REVIEW.md` and `PENETRATION_TEST_RESULTS.md`.







