# Green Resources Corporate Website & CMS - Laravel Frontend

This is the Laravel-based frontend for the Green Resources corporate website and CMS.

## Requirements

- PHP >= 8.1
- Composer
- Node.js & npm (for asset compilation if needed)
- MySQL/PostgreSQL (or SQLite for development)

## Installation

1. **Install dependencies:**

```bash
composer install
```

2. **Set up environment:**

```bash
cp .env.example .env
php artisan key:generate
```

3. **Configure database in `.env`:**

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=green_resources
DB_USERNAME=root
DB_PASSWORD=
```

4. **Run migrations (when database schema is ready):**

```bash
php artisan migrate
```

5. **Start the development server:**

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Project Structure

```
frontend/
├── app/
│   └── Http/
│       └── Controllers/
│           ├── ContactController.php
│           └── Admin/
│               └── AuthController.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php      # Main layout for public pages
│       │   └── admin.blade.php     # Layout for admin pages
│       ├── admin/
│       │   ├── login.blade.php
│       │   └── dashboard.blade.php
│       ├── home.blade.php
│       ├── about.blade.php
│       ├── business.blade.php
│       ├── sustainability.blade.php
│       ├── news.blade.php
│       ├── careers.blade.php
│       └── contact.blade.php
├── routes/
│   └── web.php                     # All web routes
├── public/
│   ├── css/
│   │   └── styles.css             # Main stylesheet
│   └── js/
│       └── main.js                # Main JavaScript
└── composer.json
```

## Routes

### Public Routes

- `/` - Home page
- `/about` - About Us
- `/business` - Business / Products / Services
- `/sustainability` - Sustainability / ESG
- `/news` - News & Updates
- `/careers` - Careers
- `/contact` - Contact Us (GET & POST)

### Admin Routes

- `/admin/login` - Admin login page (GET & POST)
- `/admin/dashboard` - Admin dashboard (requires authentication)
- `/admin/logout` - Logout (POST)

## Features Implemented

✅ **Public Website**
- Responsive design with modern UI
- All public pages (Home, About, Business, Sustainability, News, Careers, Contact)
- Contact form with validation
- SEO-friendly structure

✅ **Admin/CMS**
- Login page structure
- Dashboard layout
- Authentication controllers (placeholder - needs database implementation)

## Next Steps

1. **Database Setup:**
   - Create migrations for Pages, Sections, Media, Inquiries, Users, etc.
   - Set up models and relationships

2. **Authentication:**
   - Implement Laravel's authentication system
   - Add role-based access control (RBAC)
   - Secure admin routes

3. **CMS Functionality:**
   - Page management (CRUD)
   - Section management
   - Media library
   - WYSIWYG editor integration
   - Site settings management

4. **Contact Form:**
   - Store inquiries in database
   - Email notification system
   - Inquiry management in admin panel

5. **Backend Integration:**
   - Connect to backend API (if separate backend exists)
   - Or continue building within Laravel

## Development Notes

- The contact form currently shows success messages but doesn't persist data yet
- Admin authentication is a placeholder - needs database implementation
- All Blade templates use Laravel's asset helper for CSS/JS
- The design follows the PRD requirements for modern, clean, sustainability-focused UI

## License

Proprietary - Green Resources

