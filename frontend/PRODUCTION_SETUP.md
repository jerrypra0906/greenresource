# Production Setup Checklist

This document provides step-by-step instructions for deploying the Green Resources CMS to production.

## Prerequisites

- PHP 8.1+ with required extensions (pdo_pgsql, mbstring, etc.)
- PostgreSQL database
- Composer installed
- Node.js (if using frontend assets compilation)

## Setup Steps

### 1. Environment Configuration

Copy the environment template and configure:

```bash
cp env.example.template .env
```

Edit `.env` and set:
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://your-domain.com` (your actual domain)
- Database credentials (`DB_*` settings)
- Mail configuration (if needed)

### 2. Install Dependencies

```bash
composer install --optimize-autoloader --no-dev
```

### 3. Generate Application Key

```bash
php artisan key:generate
```

### 4. Run Migrations

```bash
php artisan migrate --force
```

**Note:** The `--force` flag is required in production to skip confirmation prompts.

### 5. Seed Database

Run the seeders to create initial data:

```bash
php artisan db:seed --class=NavigationSeeder
php artisan db:seed --class=PageSeeder
php artisan db:seed --class=AdminUserSeeder
```

Or run all seeders at once:

```bash
php artisan db:seed --force
```

This will create:
- Navigation menu structure (Home, Company, Product, Contact Us with submenus)
- All required pages (Home, About Us, Location, Sustainability, Commercial Partner, Feedstocks, Methyl Ester, Other, Fulfill Form, Contacts)
- Default admin user (`admin@greenresources.com` / `admin123`)

**⚠️ IMPORTANT:** Change the default admin password immediately after first login!

### 6. Create Storage Link

```bash
php artisan storage:link
```

This creates a symbolic link from `public/storage` to `storage/app/public` for uploaded media files.

### 7. Set Permissions

Ensure proper permissions on storage and cache directories:

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

(Adjust user/group based on your server setup)

### 8. Optimize Application

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 9. Verify Setup

1. Visit your domain and verify the homepage loads
2. Check that navigation menu appears correctly
3. Test admin login at `/admin/login`
4. Verify pages are accessible

## Post-Deployment

### Security Checklist

- [ ] Change default admin password
- [ ] Review and update `.env` security settings
- [ ] Enable HTTPS/SSL
- [ ] Configure firewall rules
- [ ] Set up regular database backups
- [ ] Review file upload limits
- [ ] Configure proper CORS headers (if needed)

### Content Management

After deployment, log into the admin panel (`/admin/login`) to:

1. **Update Navigation** (`/admin/navigation`):
   - Review menu items
   - Add/edit/remove as needed
   - Reorder items using the "Display Order" field

2. **Manage Pages** (`/admin/pages`):
   - Edit page content
   - Add sections to pages
   - Upload banner images
   - Update meta titles/descriptions for SEO

3. **Upload Media** (`/admin/media`):
   - Upload images for use in pages/sections
   - Organize media library

## Database Structure

The CMS uses the following main tables:

- `pages` - Page content and metadata
- `sections` - Page sections/blocks (hero, content_block, etc.)
- `navigation_items` - Menu structure (supports nested submenus)
- `media` - Uploaded images and files
- `users` - Admin users
- `inquiries` - Contact form submissions
- `site_settings` - Site-wide settings

## Seeder Details

### NavigationSeeder

Creates the following menu structure:
- **Home** (top-level)
- **Company** (with submenu: About Us, Location, Sustainability, Commercial Partner)
- **Product** (with submenu: Feedstocks, Methyl Ester, Other)
- **Contact Us** (with submenu: Fulfill Form, Contacts)

### PageSeeder

Creates the following pages:
- `home` - Homepage
- `company-about-us` - About Us page
- `company-location` - Location page
- `company-sustainability` - Sustainability page
- `company-commercial-partner` - Commercial Partner page
- `product-feedstocks` - Feedstocks page
- `product-methyl-ester` - Methyl Ester page
- `product-other` - Other Products page
- `contact-us-fulfill-form` - Contact Form page
- `contact-us-contacts` - Contacts page

All pages are created with:
- Status: `published`
- Basic banner configuration
- SEO meta titles and descriptions
- Placeholder content (can be edited in CMS)

## Troubleshooting

### Menu Not Appearing

- Check that `NavigationSeeder` ran successfully
- Verify `navigation_items` table has data
- Check that items have `visible = true`
- Clear view cache: `php artisan view:clear`

### Pages Not Loading

- Verify `PageSeeder` ran successfully
- Check that pages have `status = 'published'`
- Verify routes match page slugs
- Check application logs: `storage/logs/laravel.log`

### Images Not Displaying

- Ensure `storage:link` was run
- Check file permissions on `storage/app/public`
- Verify image paths in database are correct
- Check that images exist in `public/assets/` or `storage/app/public/`

### Database Connection Issues

- Verify `.env` database credentials
- Check PostgreSQL is running
- Test connection: `php artisan tinker` then `DB::connection()->getPdo();`
- Check firewall allows database connections

## Quick Reference Commands

```bash
# Full fresh setup (WARNING: Drops all data)
php artisan migrate:fresh --seed

# Run specific seeder
php artisan db:seed --class=NavigationSeeder

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Re-optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Support

For issues or questions:
1. Check application logs: `storage/logs/laravel.log`
2. Review database for data integrity
3. Verify all migrations have run: `php artisan migrate:status`
