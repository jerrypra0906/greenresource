# Database Troubleshooting Guide

## Common Issues and Solutions

### Issue: "Access denied for user 'root'@'localhost'"

This error occurs when MySQL cannot authenticate the connection. Here are solutions:

#### Solution 1: Check if MySQL is Running

1. Open **XAMPP Control Panel**
2. Make sure **MySQL** service is **Started** (green)
3. If not started, click **Start** button

#### Solution 2: Create Database Manually

1. Open **XAMPP Control Panel**
2. Start **Apache** and **MySQL**
3. Open browser and go to: `http://localhost/phpmyadmin`
4. Click **New** in the left sidebar
5. Enter database name: `green_resources`
6. Choose collation: `utf8mb4_unicode_ci`
7. Click **Create**

#### Solution 3: Set MySQL Root Password

If MySQL requires a password:

1. Open **XAMPP Control Panel**
2. Click **Shell** button
3. Run: `mysql -u root`
4. If that works, run: `CREATE DATABASE green_resources CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;`
5. Exit MySQL: `exit`

Or update `.env` file:

```env
DB_PASSWORD=your_mysql_password
```

#### Solution 4: Use Different MySQL Credentials

If you have a different MySQL user:

1. Edit `frontend/.env` file
2. Update these lines:
   ```env
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

### After Database is Created

Once the database exists, run migrations:

```powershell
cd frontend
C:\xampp\php\php.exe artisan migrate
C:\xampp\php\php.exe artisan db:seed --class=AdminUserSeeder
C:\xampp\php\php.exe artisan storage:link
```

### Verify Database Connection

Test the connection:

```powershell
cd frontend
C:\xampp\php\php.exe artisan tinker
```

Then in tinker:
```php
DB::connection()->getPdo();
```

If successful, you'll see connection info. Type `exit` to quit.

