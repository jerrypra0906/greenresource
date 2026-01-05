# Quick Deployment Guide - Alibaba Cloud

## Quick Start (5 Minutes)

### Step 1: Connect to Server
```bash
ssh root@172.28.80.101
```

### Step 2: Install Docker (if not installed)
```bash
curl -fsSL https://get.docker.com -o get-docker.sh
sh get-docker.sh
curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
chmod +x /usr/local/bin/docker-compose
```

### Step 3: Clone Repository
```bash
cd /var/www
git clone https://github.com/jerrypra0906/greenresource.git
cd greenresource/frontend
```

### Step 4: Configure Environment
```bash
cp .env.example .env
nano .env
```

**Important:** Update these values in `.env`:
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=http://172.28.80.101` (or your domain)
- Database password (change from default)

Generate key:
```bash
docker-compose run --rm app php artisan key:generate
```

### Step 5: Deploy
```bash
# Start services
docker-compose up -d --build

# Run migrations
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed --class=AdminUserSeeder

# Create storage link
docker-compose exec app php artisan storage:link

# Set permissions
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

### Step 6: Open Firewall
```bash
ufw allow 8000/tcp
ufw allow 80/tcp
ufw allow 443/tcp
```

### Step 7: Access Application
- **Website:** http://172.28.80.101:8000
- **Admin:** http://172.28.80.101:8000/admin/login
- **Default Login:**
  - Email: `admin@greenresources.com`
  - Password: `admin123` ⚠️ **Change immediately!**

## Using the Deployment Script

From your local machine:
```bash
# Make script executable (Linux/Mac)
chmod +x deploy.sh

# Run deployment
./deploy.sh 172.28.80.101 root

# Or with custom user
./deploy.sh 172.28.80.101 your_username
```

## Common Commands

### View Logs
```bash
docker-compose logs -f app
```

### Restart Services
```bash
docker-compose restart
```

### Update Application
```bash
cd /var/www/greenresource/frontend
git pull origin main
docker-compose exec app composer install --no-dev
docker-compose exec app php artisan migrate
docker-compose restart
```

### Backup Database
```bash
docker-compose exec postgres pg_dump -U postgres greenresource > backup_$(date +%Y%m%d).sql
```

## Troubleshooting

### Application not loading?
```bash
# Check container status
docker-compose ps

# Check logs
docker-compose logs app

# Restart
docker-compose restart
```

### Database connection error?
- Check `.env` file has correct database credentials
- Ensure PostgreSQL container is running: `docker-compose ps postgres`

### Permission errors?
```bash
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

## Security Checklist

- [ ] Changed default admin password
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Changed database password
- [ ] Set up SSL certificate (recommended)
- [ ] Configured firewall
- [ ] Set up backups

## Need Help?

See `DEPLOYMENT.md` for detailed instructions.

