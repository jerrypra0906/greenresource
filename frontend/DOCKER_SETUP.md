# Docker Setup Guide

This guide will help you set up and run the Green Resources website using Docker.

## Prerequisites

- Docker Desktop installed and running
- Docker Compose (included with Docker Desktop)

## Quick Start

1. **Build and start containers:**
   ```bash
   docker-compose up -d --build
   ```

2. **Run database migrations and seed:**
   ```bash
   docker-compose exec app php artisan migrate
   docker-compose exec app php artisan db:seed --class=AdminUserSeeder
   ```

3. **Create storage link:**
   ```bash
   docker-compose exec app php artisan storage:link
   ```

4. **Access the application:**
   - Website: http://localhost:8000
   - Admin Panel: http://localhost:8000/admin/login
   - Default Admin Credentials:
     - Email: `admin@greenresources.com`
     - Password: `admin123`

## Docker Services

The setup includes three services:

1. **app** - PHP-FPM 8.2 with Laravel application
2. **nginx** - Nginx web server (port 8000)
3. **postgres** - PostgreSQL 15 database (port 5433)

## Common Commands

### Start containers
```bash
docker-compose up -d
```

### Stop containers
```bash
docker-compose down
```

### View logs
```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f app
docker-compose logs -f nginx
docker-compose logs -f postgres
```

### Execute commands in app container
```bash
docker-compose exec app php artisan [command]
docker-compose exec app composer [command]
```

### Access container shell
```bash
docker-compose exec app sh
docker-compose exec postgres psql -U postgres -d greenresource
```

### Rebuild containers
```bash
docker-compose down
docker-compose up -d --build
```

### Clear cache
```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan view:clear
```

## Database Access

- **Host:** localhost
- **Port:** 5433
- **Database:** greenresource
- **Username:** postgres
- **Password:** postgres123

You can connect using any PostgreSQL client (pgAdmin, DBeaver, etc.) using the credentials above.

## Environment Variables

The `.env` file in the `frontend` directory is used for configuration. Key variables:

- `DB_HOST=postgres` (Docker service name)
- `DB_PORT=5432` (Internal Docker port)
- `DB_DATABASE=greenresource`
- `DB_USERNAME=postgres`
- `DB_PASSWORD=postgres123`

## Troubleshooting

### Port already in use
If port 8000 is already in use, change it in `docker-compose.yml`:
```yaml
ports:
  - "8080:80"  # Change 8000 to 8080
```

### Permission errors
If you encounter permission errors:
```bash
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

### Database connection errors
Ensure PostgreSQL container is healthy:
```bash
docker-compose ps
```

If postgres shows as unhealthy, check logs:
```bash
docker-compose logs postgres
```

### Clear everything and start fresh
```bash
docker-compose down -v  # Removes volumes too
docker-compose up -d --build
```

## Production Considerations

For production deployment:

1. Update `.env` with production values
2. Set `APP_DEBUG=false`
3. Set `APP_ENV=production`
4. Use proper secrets management
5. Configure SSL/TLS certificates
6. Set up proper backup strategy for PostgreSQL volume
7. Use environment-specific docker-compose files

## Volumes

- `postgres_data` - PostgreSQL database data (persistent)
- `./storage` - Laravel storage directory (mounted from host)
- `./` - Application code (mounted from host for development)

For production, consider using named volumes instead of bind mounts for better performance.

