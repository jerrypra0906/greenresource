# Database Setup Guide

## Overview

This document outlines the database setup and CMS implementation for the Green Resources website.

## Database Migrations

All migrations have been created for the following tables:

1. **pages** - Website pages with CMS support
2. **sections** - Content sections for each page
3. **media** - Media library for images and files
4. **navigation_items** - Navigation menu items
5. **site_settings** - Site-wide settings (key-value pairs)
6. **inquiries** - Contact form submissions
7. **users** - Admin users with role-based access

## Setup Steps

### 1. Configure Database

Update your `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=green_resources
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 2. Run Migrations

```bash
php artisan migrate
```

### 3. Seed Default Admin User

```bash
php artisan db:seed --class=AdminUserSeeder
```

This will create three default users:
- **Admin**: admin@greenresources.com / password123
- **Editor**: editor@greenresources.com / password123
- **Viewer**: viewer@greenresources.com / password123

**⚠️ IMPORTANT**: Change these passwords in production!

### 4. Create Storage Link

```bash
php artisan storage:link
```

This creates a symbolic link from `public/storage` to `storage/app/public` for media uploads.

## Authentication

Authentication is implemented using Laravel's built-in authentication system:

- Login: `/admin/login`
- Dashboard: `/admin/dashboard` (requires authentication)
- Logout: POST to `/admin/logout`

## CMS Features

### Pages Management
- Create, edit, and delete pages
- Manage page metadata (title, description, banner)
- Control publication status (draft/published)

### Sections Management
- Add content sections to pages
- Support for different section types (hero, content_block, etc.)
- Reorder sections

### Media Library
- Upload images and files
- Manage media files
- Use media in pages and sections

### Inquiries Management
- View contact form submissions
- Update inquiry status (new/handled/archived)
- Email notifications on new inquiries

### Site Settings
- Configure company information
- Set contact form email recipients
- Manage social media links

## Contact Form

The contact form (`/contact`) now:
- Saves all submissions to the `inquiries` table
- Sends email notifications to configured recipients
- Supports CC and BCC recipients
- Logs all submissions for audit trail

## Email Configuration

Configure email settings in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@greenresources.com
MAIL_FROM_NAME="Green Resources"
```

For production, use your actual SMTP server settings.

## Next Steps

1. **Configure Email**: Set up your SMTP server or use a service like Mailtrap for testing
2. **Create Initial Pages**: Use the CMS to create pages for your menu items
3. **Upload Media**: Add logos and images to the media library
4. **Configure Settings**: Set up company information and contact email recipients
5. **Test Contact Form**: Submit a test inquiry to verify email notifications

## Security Notes

- Change default admin passwords before deploying to production
- Use strong passwords for admin accounts
- Configure proper email settings for production
- Set up proper file permissions for storage directory
- Consider implementing rate limiting for contact form submissions

