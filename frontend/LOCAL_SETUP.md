# Local Development Setup Guide

## Quick Start

Your Laravel application is almost ready! You just need to configure the database connection.

## Database Configuration

### Option 1: PostgreSQL (Recommended - Already Configured)

1. **Update your `.env` file** in the `frontend` directory:
   - Open `frontend/.env`
   - Find the database section and update:
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=greenresource
   DB_USERNAME=postgres
   DB_PASSWORD=YOUR_POSTGRES_PASSWORD
   ```
   - Replace `YOUR_POSTGRES_PASSWORD` with your actual PostgreSQL password

2. **Create the database** (if it doesn't exist):
   ```sql
   CREATE DATABASE greenresource;
   ```

3. **Run migrations**:
   ```bash
   cd frontend
   php artisan migrate
   php artisan db:seed
   ```

### Option 2: SQLite (Simpler - No Server Required)

1. **Enable SQLite in PHP**:
   - Edit your `php.ini` file
   - Uncomment or add: `extension=pdo_sqlite`
   - Restart your web server/PHP

2. **Update your `.env` file**:
   ```env
   DB_CONNECTION=sqlite
   DB_DATABASE="D:\Project\Green energy\greenresource\frontend\database\database.sqlite"
   ```
   (Remove or comment out DB_HOST, DB_PORT, DB_USERNAME, DB_PASSWORD)

3. **Create SQLite database file**:
   ```bash
   cd frontend
   New-Item -ItemType File -Path "database\database.sqlite" -Force
   ```

4. **Run migrations**:
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

### Option 3: MySQL/MariaDB

1. **Update your `.env` file**:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=greenresource
   DB_USERNAME=root
   DB_PASSWORD=YOUR_MYSQL_PASSWORD
   ```

2. **Create the database**:
   ```sql
   CREATE DATABASE greenresource;
   ```

3. **Run migrations**:
   ```bash
   cd frontend
   php artisan migrate
   php artisan db:seed
   ```

## Complete Setup Steps

1. **Configure Database** (choose one option above)

2. **Run Migrations and Seeders**:
   ```bash
   cd frontend
   php artisan migrate
   php artisan db:seed
   ```

3. **Create Storage Link**:
   ```bash
   php artisan storage:link
   ```

4. **Start Development Server**:
   ```bash
   php artisan serve
   ```

5. **Access the Application**:
   - Public site: http://localhost:8000
   - Admin CMS: http://localhost:8000/admin/login
   - Default admin credentials:
     - Email: `admin@greenresources.com`
     - Password: `admin123`

## Troubleshooting

### "could not find driver" Error
- **For SQLite**: Enable `extension=pdo_sqlite` in `php.ini`
- **For PostgreSQL**: Enable `extension=pdo_pgsql` in `php.ini`
- **For MySQL**: Enable `extension=pdo_mysql` in `php.ini`

### Database Connection Failed
- Verify database server is running
- Check username and password in `.env`
- Ensure database exists
- Check firewall settings

### Permission Errors
- Ensure `storage/` and `bootstrap/cache/` directories are writable
- On Windows, you may need to run as administrator

## Current Status

✅ Composer dependencies installed
✅ Application key generated
✅ Environment file created
⏳ Database configuration needed
⏳ Migrations pending
⏳ Server not started

## Next Steps

1. Configure your database password in `.env`
2. Run `php artisan migrate`
3. Run `php artisan db:seed`
4. Run `php artisan storage:link`
5. Run `php artisan serve`

Then visit http://localhost:8000
