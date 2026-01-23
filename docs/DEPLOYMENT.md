# Deployment Guide - Alibaba Cloud Server

This guide will help you deploy the Green Resources CMS to your Alibaba Cloud server.

## Server Information
- **IP Address:** 172.28.80.101
- **SSH Port:** 22
- **Server Type:** Alibaba Cloud ECS

## Prerequisites

1. **Local Machine:**
   - Git installed
   - SSH access to the server
   - Basic knowledge of Linux commands

2. **Server Requirements:**
   - Ubuntu 20.04+ or CentOS 7+ (recommended)
   - Docker and Docker Compose (for containerized deployment)
   - OR PHP 8.1+, Composer, Nginx, PostgreSQL/MySQL
   - Minimum 2GB RAM, 20GB disk space

## Deployment Options

### Option 1: Docker Deployment (Recommended)

This is the easiest method using Docker Compose.

#### Step 1: Connect to Server

```bash
ssh root@172.28.80.101
# Or if using a different user:
# ssh username@172.28.80.101
```

#### Step 2: Install Docker and Docker Compose

```bash
# For Ubuntu/Debian
curl -fsSL https://get.docker.com -o get-docker.sh
sh get-docker.sh
sudo usermod -aG docker $USER

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Verify installation
docker --version
docker-compose --version
```

#### Step 3: Clone Repository

```bash
# Create /var/www directory if it doesn't exist
sudo mkdir -p /var/www
sudo chown $USER:$USER /var/www

# Clone the repository
cd /var/www
git clone https://github.com/jerrypra0906/greenresource.git
cd greenresource/frontend
```

#### Step 4: Configure Environment

```bash
# Copy environment file template
cp env.example.template .env

# Create Docker environment file for password
cp env.docker.example .env.docker

# Generate secure password (optional - generates password without $ character)
chmod +x generate-db-password.sh
./generate-db-password.sh
# Copy the generated password

# Edit .env.docker file with your secure password
nano .env.docker
# Set: DB_PASSWORD=your_generated_secure_password

# Edit .env file with your production settings
nano .env
```

Update the following in `.env`:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=http://172.28.80.101

DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=greenresource
DB_USERNAME=postgres
DB_PASSWORD=your_secure_password_here
# Use the SAME password as in .env.docker

# Generate application key (will be done in container)
```

**IMPORTANT:** 
- The password in `.env.docker` and `.env` must match
- Do NOT use `$` character in password (Docker Compose interprets it as variable)
- Use the password generator script to create a safe password

#### Step 5: Build and Start Containers

```bash
# Build and start all services
docker-compose up -d --build

# Wait for containers to be ready
sleep 10

# Ensure all required directories exist and have proper permissions
docker-compose exec app mkdir -p bootstrap/cache
docker-compose exec app mkdir -p storage/framework/cache/data
docker-compose exec app mkdir -p storage/framework/sessions
docker-compose exec app mkdir -p storage/framework/views
docker-compose exec app mkdir -p storage/logs
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec app chmod -R 775 storage bootstrap/cache

# Generate application key (CRITICAL - must be done before migrations)
docker-compose exec app php artisan key:generate

# Clear any cached config that might have wrong paths
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear

# Run migrations
docker-compose exec app php artisan migrate

# Seed database with initial data (pages and admin user)
docker-compose exec app php artisan db:seed
# OR seed individually:
# docker-compose exec app php artisan db:seed --class=PageSeeder
# docker-compose exec app php artisan db:seed --class=AdminUserSeeder

# Create storage link
docker-compose exec app php artisan storage:link
```

#### Step 6: Configure Firewall

```bash
# Allow HTTP and HTTPS
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw allow 22/tcp
sudo ufw enable
```

#### Step 7: Access Application

- **Website:** http://greenresource.co:8000 (or https://greenresource.co after SSL setup)
- **Admin Panel:** http://greenresource.co:8000/admin/login (or https://greenresource.co/admin/login after SSL setup)
- **Default Admin:**
  - Email: `admin@greenresources.com`
  - Password: `admin123` (Change immediately!)

---

### Option 2: Traditional Deployment (Without Docker)

#### Step 1: Install Required Software

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.2 and extensions
sudo apt install -y php8.2-fpm php8.2-cli php8.2-common php8.2-mysql php8.2-pgsql \
    php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml php8.2-bcmath

# Install Nginx
sudo apt install -y nginx

# Install PostgreSQL
sudo apt install -y postgresql postgresql-contrib

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

#### Step 2: Clone and Setup Application

```bash
# Create /var/www directory if it doesn't exist
sudo mkdir -p /var/www
sudo chown $USER:$USER /var/www

# Clone the repository
cd /var/www
git clone https://github.com/jerrypra0906/greenresource.git
cd greenresource/frontend

# Install dependencies
composer install --no-dev --optimize-autoloader

# Set permissions
sudo chown -R www-data:www-data /var/www/greenresource/frontend
sudo chmod -R 755 /var/www/greenresource/frontend
sudo chmod -R 775 storage bootstrap/cache
```

#### Step 3: Configure Database

```bash
sudo -u postgres psql
```

```sql
CREATE DATABASE greenresource;
CREATE USER greenuser WITH PASSWORD 'your_secure_password';
GRANT ALL PRIVILEGES ON DATABASE greenresource TO greenuser;
\q
```

#### Step 4: Configure Environment

```bash
cp .env.example .env
nano .env
```

Update `.env`:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://greenresource.co
# Or use http://greenresource.co if SSL is not set up yet

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=greenresource
DB_USERNAME=greenuser
DB_PASSWORD=your_secure_password

php artisan key:generate
```

#### Step 5: Run Migrations

```bash
php artisan migrate
php artisan db:seed --class=AdminUserSeeder
php artisan storage:link
```

#### Step 6: Configure Nginx

```bash
sudo nano /etc/nginx/sites-available/greenresource
```

Add this configuration:
```nginx
server {
    listen 80;
    server_name greenresource.co www.greenresource.co;
    root /var/www/greenresource/frontend/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable site:
```bash
sudo ln -s /etc/nginx/sites-available/greenresource /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

---

## Post-Deployment Steps

### 1. Change Default Admin Password

Log in to admin panel and change the default password immediately.

### 2. Configure Domain (greenresource.co)

Before setting up SSL, you need to configure your domain to point to your server.

#### Step 1: DNS Configuration

1. **Log in to your domain registrar** (where you purchased greenresource.co)
2. **Add/Update DNS A Record:**
   - **Type:** A
   - **Name:** @ (or leave blank for root domain)
   - **Value:** 172.28.80.101
   - **TTL:** 3600 (or default)

3. **Add/Update DNS A Record for www (optional):**
   - **Type:** A
   - **Name:** www
   - **Value:** 172.28.80.101
   - **TTL:** 3600 (or default)

4. **Wait for DNS propagation** (can take 5 minutes to 48 hours, usually 15-30 minutes)

#### Step 2: Verify DNS Resolution

```bash
# Test DNS resolution from your local machine
nslookup greenresource.co
# OR
dig greenresource.co

# Should return: 172.28.80.101
```

#### Step 3: Update Application Configuration

Update your `.env` file to use the domain:

```bash
cd /var/www/greenresource/frontend
nano .env
```

Change:
```env
APP_URL=http://172.28.80.101
```

To:
```env
APP_URL=https://greenresource.co
```

**For Docker deployment:**
```bash
# After updating .env, restart containers
docker-compose restart app
docker-compose exec app php artisan config:clear
```

**For traditional deployment:**
```bash
php artisan config:clear
php artisan config:cache
```

#### Step 4: Update Nginx Configuration

The nginx configuration already includes `server_name greenresource.co www.greenresource.co` in `frontend/docker/nginx/default.conf`.

**For Docker deployment:**
- The configuration is already updated
- Restart nginx: `docker-compose restart nginx`

**For traditional deployment:**
Update `/etc/nginx/sites-available/greenresource`:
```nginx
server {
    listen 80;
    server_name greenresource.co www.greenresource.co;
    # ... rest of configuration
}
```

Then reload nginx:
```bash
sudo nginx -t
sudo systemctl reload nginx
```

### 3. Configure SSL/HTTPS (Recommended)

After DNS is configured and pointing to your server, set up SSL:

#### Option A: Using Certbot (Let's Encrypt) - Recommended

```bash
# Install Certbot
sudo apt update
sudo apt install -y certbot python3-certbot-nginx

# For Docker deployment - you need to run certbot on the host
# First, ensure port 80 and 443 are accessible
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp

# Get SSL certificate (interactive mode)
sudo certbot certonly --nginx -d greenresource.co -d www.greenresource.co

# OR use standalone mode (if nginx is in Docker)
sudo certbot certonly --standalone -d greenresource.co -d www.greenresource.co
```

**For Docker deployment with Certbot:**

After obtaining certificates, you need to:

1. **Update docker-compose.yml** to mount SSL certificates:
```yaml
nginx:
  volumes:
    - ./:/var/www/html
    - ./docker/nginx/default-ssl.conf:/etc/nginx/conf.d/default.conf
    - /etc/letsencrypt:/etc/letsencrypt:ro  # Mount SSL certificates
```

2. **Use the SSL configuration:**
```bash
# Copy SSL config
cp docker/nginx/default-ssl.conf docker/nginx/default.conf

# Restart nginx
docker-compose restart nginx
```

3. **Set up auto-renewal:**
```bash
# Test renewal
sudo certbot renew --dry-run

# Add to crontab for auto-renewal
sudo crontab -e
# Add: 0 0 * * * certbot renew --quiet && docker-compose -f /var/www/greenresource/frontend/docker-compose.yml restart nginx
```

#### Option B: Using Cloud Provider SSL (Alibaba Cloud)

If using Alibaba Cloud SSL certificates:

1. **Purchase/Upload SSL certificate** in Alibaba Cloud Console
2. **Download certificate files** (certificate.pem and private.key)
3. **Place them in a secure location** on your server:
```bash
sudo mkdir -p /etc/ssl/greenresource.co
sudo nano /etc/ssl/greenresource.co/certificate.pem
sudo nano /etc/ssl/greenresource.co/private.key
sudo chmod 600 /etc/ssl/greenresource.co/private.key
```

4. **Update nginx configuration** to use these certificates:
```nginx
ssl_certificate /etc/ssl/greenresource.co/certificate.pem;
ssl_certificate_key /etc/ssl/greenresource.co/private.key;
```

5. **For Docker:** Mount the SSL directory in docker-compose.yml:
```yaml
nginx:
  volumes:
    - /etc/ssl/greenresource.co:/etc/ssl/greenresource.co:ro
```

#### Verify SSL Setup

```bash
# Test SSL configuration
curl -I https://greenresource.co

# Check SSL certificate
openssl s_client -connect greenresource.co:443 -servername greenresource.co

# Online SSL checker
# Visit: https://www.ssllabs.com/ssltest/analyze.html?d=greenresource.co
```

### 4. Set Up Firewall

```bash
sudo ufw allow 'Nginx Full'
sudo ufw allow OpenSSH
sudo ufw enable
```

### 5. Configure Automatic Backups

Create a backup script:
```bash
sudo nano /usr/local/bin/backup-greenresource.sh
```

```bash
#!/bin/bash
BACKUP_DIR="/backups/greenresource"
DATE=$(date +%Y%m%d_%H%M%S)

mkdir -p $BACKUP_DIR

# Backup database
pg_dump -U greenuser greenresource > $BACKUP_DIR/db_$DATE.sql

# Backup files
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/greenresource/frontend/storage

# Keep only last 7 days
find $BACKUP_DIR -type f -mtime +7 -delete
```

Make executable:
```bash
sudo chmod +x /usr/local/bin/backup-greenresource.sh
```

Add to crontab:
```bash
sudo crontab -e
# Add: 0 2 * * * /usr/local/bin/backup-greenresource.sh
```

## Troubleshooting

### Site Can't Be Reached (Connection Refused)

If you can't access `http://172.28.80.101:8000` from your laptop/browser (but it works on the server), this is a network/firewall issue.

**Quick Diagnostic Commands (run on server):**

```bash
# 1. Check if port is listening on all interfaces (0.0.0.0)
ss -tuln | grep 8000
# Should show: tcp 0.0.0.0:8000 LISTEN

# 2. Check firewall status
sudo ufw status
# OR for CentOS/RHEL
sudo firewall-cmd --list-ports

# 3. Check if containers are running
docker-compose ps
```

**Most Common Issue: Alibaba Cloud Security Group**
- 90% of the time, this is the problem
- You MUST add a security group rule in Alibaba Cloud Console
- See Step 3 below for detailed instructions

If you can't access `http://172.28.80.101:8000`, follow these steps:

#### 1. Check if Containers are Running

```bash
cd /var/www/greenresource/frontend
docker-compose ps
```

All three containers (postgres, app, nginx) should show "Up" status. If any are down:

```bash
# Check logs for errors
docker-compose logs nginx
docker-compose logs app
docker-compose logs postgres

# Restart containers
docker-compose restart
```

#### 2. Check if Port 8000 is Listening

```bash
# Check if port 8000 is open and listening
netstat -tuln | grep 8000
# OR
ss -tuln | grep 8000

# Should show: tcp 0.0.0.0:8000 LISTEN (NOT 127.0.0.1:8000)
# If it shows 127.0.0.1:8000, the port is only accessible locally
```

**Important:** The port must be listening on `0.0.0.0:8000` (all interfaces), not `127.0.0.1:8000` (localhost only).

If it's only listening on 127.0.0.1, check docker-compose.yml port mapping:
```bash
# Verify port mapping in docker-compose.yml
grep -A 2 "ports:" docker-compose.yml
# Should show: - "8000:80" (not "127.0.0.1:8000:80")
```

If port 8000 is not listening, check nginx container:

```bash
docker-compose exec nginx nginx -t
docker-compose restart nginx
```

#### 3. Check Alibaba Cloud Security Group

**IMPORTANT:** Alibaba Cloud ECS requires security group rules to allow inbound traffic. This is the MOST COMMON cause of external access issues.

**Step-by-step instructions:**

1. **Log in to Alibaba Cloud Console:**
   - Go to: https://ecs.console.aliyun.com
   - Log in with your Alibaba Cloud account

2. **Navigate to your ECS instance:**
   - Click **ECS** → **Instances** in the left menu
   - Find your instance (IP: 172.28.80.101)
   - Click on the instance ID or name

3. **Access Security Group settings:**
   - In the instance details page, find the **Security Groups** section
   - Click on the security group name (it's a clickable link)
   - OR click **More** → **Network and Security Group** → **Security Group Rules**

4. **Check existing inbound rules:**
   - Click **Inbound** tab
   - Look for a rule allowing port 8000
   - If it doesn't exist, you need to add it

5. **Add Inbound Rules (if missing):**
   
   **For HTTP (port 80):**
   - Click **Add Rule** or **Create Rule**
   - Configure:
     - **Port Range:** `80/80` (or just `80`)
     - **Protocol:** `TCP`
     - **Authorization Object:** `0.0.0.0/0` (allows from anywhere) OR your specific IP for better security
     - **Description:** `Allow HTTP on port 80`
   - Click **Save** or **OK**
   
   **For HTTPS (port 443) - Required for SSL:**
   - Click **Add Rule** or **Create Rule**
   - Configure:
     - **Port Range:** `443/443` (or just `443`)
     - **Protocol:** `TCP`
     - **Authorization Object:** `0.0.0.0/0` (allows from anywhere) OR your specific IP for better security
     - **Description:** `Allow HTTPS on port 443`
   - Click **Save** or **OK**
   
   **For Docker deployment (port 8000) - Optional if using direct domain:**
   - Click **Add Rule** or **Create Rule**
   - Configure:
     - **Port Range:** `8000/8000` (or just `8000`)
     - **Protocol:** `TCP`
     - **Authorization Object:** `0.0.0.0/0` (allows from anywhere) OR your specific IP for better security
     - **Description:** `Allow HTTP on port 8000`
   - Click **Save** or **OK**

6. **Verify the rule is active:**
   - The rule should appear in the inbound rules list
   - Status should be **Enabled**

**Common issues:**
- Rule exists but is disabled → Enable it
- Rule exists but source is wrong → Edit to allow `0.0.0.0/0` (temporarily for testing)
- Multiple security groups → Make sure the rule is in the security group attached to your instance

**Alternative method (if above doesn't work):**
- Go to **ECS** → **Network & Security** → **Security Groups**
- Find the security group attached to your instance
- Click **Configure Rules** → **Inbound** → **Add Rule**

#### 4. Check Firewall on Server

```bash
# Check firewall status
sudo ufw status

# If firewall is active, allow port 8000
sudo ufw allow 8000/tcp
sudo ufw reload

# For CentOS/RHEL, check firewalld
sudo firewall-cmd --list-ports
sudo firewall-cmd --permanent --add-port=8000/tcp
sudo firewall-cmd --reload
```

#### 5. Test from Server Itself

```bash
# Test if nginx is responding locally
curl http://localhost:8000

# Test if app container is reachable from nginx
docker-compose exec nginx ping -c 2 app

# Check nginx error logs
docker-compose exec nginx cat /var/log/nginx/error.log
```

#### 6. Verify Nginx Configuration

```bash
# Test nginx configuration
docker-compose exec nginx nginx -t

# Check if nginx can access the app container
docker-compose exec nginx wget -O- http://app:9000
```

#### 7. Verify Port is Listening on All Interfaces

```bash
# Check if port 8000 is listening on 0.0.0.0 (all interfaces)
ss -tuln | grep 8000

# Should show: tcp 0.0.0.0:8000 LISTEN
# If it shows 127.0.0.1:8000, Docker is only binding to localhost

# If it's only on 127.0.0.1, check docker-compose.yml
cd /var/www/greenresource/frontend
grep -A 2 "ports:" docker-compose.yml
# Should show: - "8000:80" (NOT "127.0.0.1:8000:80")

# If wrong, fix it and restart:
docker-compose down
docker-compose up -d
```

#### 8. Test Connectivity from External Network

**From your laptop, test if the port is reachable:**

```bash
# Windows PowerShell
Test-NetConnection -ComputerName 172.28.80.101 -Port 8000

# Linux/Mac
telnet 172.28.80.101 8000
# OR
nc -zv 172.28.80.101 8000

# If connection times out or is refused, it's a security group issue
# If connection succeeds but site doesn't load, it's an application issue
```

### 500 Internal Server Error

If you see a 500 error when accessing the site, check the following:

#### 1. Check Application Logs

```bash
# Check Laravel application logs
docker-compose exec app tail -n 50 storage/logs/laravel.log

# Or view all app logs
docker-compose logs app --tail=100

# Check for specific errors
docker-compose exec app cat storage/logs/laravel.log | grep -i error
```

#### 2. Check if APP_KEY is Set

```bash
# Check if APP_KEY exists in .env
docker-compose exec app grep APP_KEY .env

# If missing or empty, generate it
docker-compose exec app php artisan key:generate

# After generating, clear config cache
docker-compose exec app php artisan config:clear
```

**Error: "No application encryption key has been specified"**
- This is a critical error that prevents Laravel from running
- Solution: Run `docker-compose exec app php artisan key:generate`
- Then clear config cache: `docker-compose exec app php artisan config:clear`

#### 3. Check Database Connection

```bash
# Test database connection
docker-compose exec app php artisan tinker
# Then run: DB::connection()->getPdo();

# Or check connection directly
docker-compose exec app php artisan migrate:status
```

#### 4. Check File Permissions

```bash
# Ensure storage and cache directories are writable
docker-compose exec app ls -la storage
docker-compose exec app ls -la bootstrap/cache

# Fix permissions if needed
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

**Error: "Please provide a valid cache path"**
- This means `bootstrap/cache` directory doesn't exist or isn't writable
- Solution:
  ```bash
  docker-compose exec app mkdir -p bootstrap/cache
  docker-compose exec app chown -R www-data:www-data bootstrap/cache
  docker-compose exec app chmod -R 775 bootstrap/cache
  docker-compose exec app php artisan config:clear
  ```

**Error: "Failed to open stream: No such file or directory" for sessions**
- This means `storage/framework/sessions` directory doesn't exist
- Solution:
  ```bash
  docker-compose exec app mkdir -p storage/framework/sessions
  docker-compose exec app mkdir -p storage/framework/cache/data
  docker-compose exec app mkdir -p storage/framework/views
  docker-compose exec app mkdir -p storage/logs
  docker-compose exec app chown -R www-data:www-data storage
  docker-compose exec app chmod -R 775 storage
  ```

#### 5. Clear Cache and Config

```bash
# Clear all caches
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear

# Rebuild cache
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
```

#### 6. Check Environment Configuration

```bash
# Verify .env file exists and has correct values
docker-compose exec app cat .env

# Check if .env.docker is loaded correctly
docker-compose exec app env | grep DB_
```

### Check Application Logs

```bash
# Docker
docker-compose logs app
docker-compose logs nginx
docker-compose logs postgres

# Follow logs in real-time
docker-compose logs -f app

# Traditional
tail -f storage/logs/laravel.log
```

### Check Nginx Logs

```bash
# Docker
docker-compose exec nginx tail -f /var/log/nginx/error.log
docker-compose exec nginx tail -f /var/log/nginx/access.log

# Traditional
sudo tail -f /var/log/nginx/error.log
```

### Restart Services

```bash
# Docker - Restart all services
docker-compose restart

# Docker - Restart specific service
docker-compose restart nginx
docker-compose restart app

# Docker - Rebuild and restart
docker-compose up -d --build

# Traditional
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
```

## Maintenance Commands

### Update Application

```bash
cd /var/www/greenresource/frontend
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Clear Cache

```bash
# Docker
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear

# Traditional
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Route Not Found (404 Errors)

If you get "404 Not Found" errors when accessing routes:

```bash
# 1. Clear route cache
docker-compose exec app php artisan route:clear

# 2. List all registered routes to verify they exist
docker-compose exec app php artisan route:list

# 3. Check if routes are cached (should not be in development)
docker-compose exec app php artisan route:cache

# 4. Clear all caches
docker-compose exec app php artisan optimize:clear

# 5. Verify nginx is passing requests to Laravel correctly
docker-compose exec nginx cat /var/log/nginx/error.log | tail -20
```

**Common issues:**
- Routes are cached with old configuration → Run `php artisan route:clear`
- Nginx not passing requests to PHP-FPM → Check nginx error logs
- APP_URL in .env doesn't match actual URL → Update APP_URL in .env file

### Page Not Found (CMS Pages Not Set Up)

If you see "Page not found" errors, the pages haven't been created in the database yet:

```bash
# Run the PageSeeder to create all required pages
docker-compose exec app php artisan db:seed --class=PageSeeder

# Verify pages were created
docker-compose exec app php artisan tinker
# Then run: \App\Models\Page::all(['slug', 'title', 'status']);

# Or check via SQL
docker-compose exec postgres psql -U postgres -d greenresource -c "SELECT slug, title, status FROM pages;"
```

**Pages that will be created:**
- `home` - Home page
- `company-about-us` - About Us page
- `company-location` - Location page
- `products-feedstocks` - Feedstocks page
- `products-methyl-ester` - Methyl Ester page
- `products-others` - Other Products page
- `news-and-event-news` - News page
- `news-and-event-event` - Events page
- `contact` - Contact page

**Note:** After running the seeder, you can edit these pages through the CMS admin panel at `/admin/pages`.

## Security Checklist

- [ ] Changed default admin password
- [ ] Set APP_DEBUG=false in production
- [ ] Configured strong database password
- [ ] Set up SSL/HTTPS certificate
- [ ] Configured firewall rules
- [ ] Set up regular backups
- [ ] Updated server packages
- [ ] Configured proper file permissions

## Support

For issues or questions, refer to:
- `frontend/DOCKER_SETUP.md` - Docker setup details
- `frontend/README.md` - Application documentation
- `docs/README.md` - Project documentation

