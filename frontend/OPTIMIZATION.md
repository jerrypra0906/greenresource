# Website & CMS Optimization Guide

This document outlines the optimizations implemented to improve website and CMS performance.

## Implemented Optimizations

### 1. Database Query Optimization
- **Eager Loading**: All page queries now use eager loading (`with()`) to prevent N+1 queries
- **Query Ordering**: Sections are ordered at the database level using `orderBy('order')`
- **Database Indexes**: Added indexes on frequently queried columns:
  - `pages.status` and `pages.slug, status` (composite index)
  - `sections.page_id, order` (composite index)
  - `sections.type`
  - `media.file_path`

### 2. Page Caching
- **Public Pages**: Pages are cached for 1 hour (3600 seconds)
- **Cache Keys**: Uses `page.{slug}` format for easy cache management
- **Cache Invalidation**: Cache is automatically cleared when:
  - Pages are created, updated, or deleted
  - Sections are created, updated, or deleted
- **Home Page**: Special cache key `page.home` for faster home page loading

### 3. Nginx Optimizations
- **Gzip Compression**: Enabled for text-based files (HTML, CSS, JS, JSON, XML, fonts, SVG)
- **Static Asset Caching**: Images, CSS, JS files cached for 1 year with immutable flag
- **HTML Caching**: HTML pages cached for 1 hour
- **Compression Level**: Set to level 6 (good balance between compression and CPU usage)
- **FastCGI Buffering**: Optimized buffer sizes (16x16k buffers, 32k buffer size)
- **FastCGI Timeouts**: Increased timeouts to 300 seconds for complex operations
- **Access Log Optimization**: Static assets excluded from access logging
- **Public Page Caching**: HTTP cache headers for public pages (not admin)

### 4. Image Optimization
- **Lazy Loading**: All images in sections use `loading="lazy"` attribute
- **Browser Caching**: Images cached for 1 year via Nginx headers

### 5. Laravel Optimizations
- **Route Caching**: Routes are cached for faster routing (production only)
- **Config Caching**: Configuration files are cached (production only)
- **View Caching**: Compiled views are cached (production only)
- **Event Caching**: Events are cached for better performance (production only)
- **Cache Driver**: Using file-based cache instead of database for better performance
- **Auto-Optimization**: Automatic optimization on container startup in production

### 6. PHP OPcache Optimization
- **OPcache Enabled**: PHP OPcache is enabled for bytecode caching
- **Memory Allocation**: 256MB allocated for OPcache
- **String Buffer**: 16MB interned strings buffer
- **Max Files**: 20,000 accelerated files supported
- **Optimization Level**: Maximum optimization enabled (0x7FFFBFFF)
- **File Cache**: OPcache file cache enabled for faster startup
- **Huge Code Pages**: Enabled for better memory efficiency

### 7. Admin Panel Optimizations
- **Eager Loading**: All admin controllers use eager loading to prevent N+1 queries
- **Media Pagination**: Using simplePaginate for better performance on large media libraries
- **Relationship Loading**: Sections and media relationships loaded efficiently

## Performance Improvements

### Expected Improvements:
- **Page Load Time**: 50-70% faster for cached pages
- **Database Queries**: Reduced by ~80% through eager loading and caching
- **Bandwidth**: Reduced by ~60-70% through gzip compression
- **Image Loading**: Improved perceived performance with lazy loading
- **PHP Execution**: 30-50% faster with OPcache enabled
- **Admin Panel**: 40-60% faster with optimized queries and eager loading
- **Static Assets**: Near-instant loading with 1-year browser cache

## Cache Management

### Clear All Caches
```bash
docker-compose exec app php artisan optimize:clear
```

### Clear Specific Caches
```bash
# Clear page cache
docker-compose exec app php artisan cache:forget page.home
docker-compose exec app php artisan cache:forget page.{slug}

# Clear config cache
docker-compose exec app php artisan config:clear

# Clear route cache
docker-compose exec app php artisan route:clear

# Clear view cache
docker-compose exec app php artisan view:clear
```

### Rebuild Caches
```bash
docker-compose exec app php artisan optimize
```

## Monitoring Performance

### Check Cache Status
```bash
# Check cache driver
docker-compose exec app php artisan tinker
>>> Cache::getStore()
```

### Database Query Monitoring
Enable query logging in `.env`:
```
DB_LOG_QUERIES=true
```

## Additional Optimizations Implemented

### Cache Driver Optimization
- **File Cache**: Switched from database cache to file-based cache for better performance
- **No Database Overhead**: File cache doesn't require database queries for cache operations
- **Faster Reads/Writes**: File system cache is faster than database cache for most use cases

### Production Auto-Optimization
- **Automatic Caching**: In production environment, Laravel automatically caches:
  - Configuration files
  - Routes
  - Views
  - Events
- **Container Startup**: Optimizations run automatically when container starts in production mode

## Future Optimization Opportunities

1. **Redis Caching**: Switch from file cache to Redis for even better performance in multi-server setups
2. **CDN Integration**: Use CDN for static assets (images, CSS, JS) for global performance
3. **Image Optimization**: Implement automatic image compression/resizing on upload
4. **HTTP/2**: Enable HTTP/2 support in Nginx (requires SSL/TLS)
5. **Database Connection Pooling**: Optimize database connections for high-traffic scenarios
6. **Query Result Caching**: Cache frequently accessed database queries
7. **Asset Minification**: Minify CSS and JavaScript files
8. **Lazy Loading Routes**: Implement route lazy loading for better initial load time

## Notes

- Cache is automatically cleared when content is updated via CMS
- Public pages are cached, admin pages are not cached
- Cache duration can be adjusted in `PageController.php` (currently 3600 seconds = 1 hour)
- OPcache is enabled by default and automatically manages PHP bytecode caching
- File cache is stored in `storage/framework/cache/data/` directory
- In production, ensure `APP_ENV=production` in `.env` to enable auto-optimization
- OPcache configuration can be adjusted in `docker/php/opcache.ini`
- Nginx optimizations are automatically applied when using the Docker setup

