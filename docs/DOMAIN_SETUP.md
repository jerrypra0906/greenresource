# Domain Setup Guide - greenresource.co

This guide provides step-by-step instructions for setting up the domain `greenresource.co` for your Green Resources CMS deployment.

## Quick Start Checklist

- [ ] Configure DNS A records pointing to server IP (172.28.80.101)
- [ ] Verify DNS propagation
- [ ] Update APP_URL in .env file
- [ ] Update nginx server_name configuration
- [ ] Set up SSL/HTTPS certificate
- [ ] Configure Alibaba Cloud Security Group for ports 80 and 443
- [ ] Test domain access
- [ ] Set up SSL certificate auto-renewal

## Step-by-Step Instructions

### 1. DNS Configuration

#### At Your Domain Registrar

1. Log in to your domain registrar (where you purchased `greenresource.co`)
2. Navigate to DNS Management / DNS Settings
3. Add or update the following DNS records:

**A Record for Root Domain:**
- **Type:** A
- **Name:** @ (or leave blank, or use `greenresource.co`)
- **Value/Points to:** `172.28.80.101`
- **TTL:** 3600 (1 hour) or default

**A Record for www Subdomain (Optional):**
- **Type:** A
- **Name:** www
- **Value/Points to:** `172.28.80.101`
- **TTL:** 3600 (1 hour) or default

4. Save the changes

#### Verify DNS Propagation

Wait 5-30 minutes for DNS to propagate, then verify:

```bash
# From your local machine
nslookup greenresource.co
# OR
dig greenresource.co
# OR
ping greenresource.co

# Should return: 172.28.80.101
```

**Online DNS Checkers:**
- https://www.whatsmydns.net/#A/greenresource.co
- https://dnschecker.org/#A/greenresource.co

### 2. Update Application Configuration

#### For Docker Deployment

```bash
cd /var/www/greenresource/frontend

# Edit .env file
nano .env
```

Update:
```env
APP_URL=https://greenresource.co
```

If SSL is not set up yet, use:
```env
APP_URL=http://greenresource.co
```

Then:
```bash
# Restart application container
docker-compose restart app

# Clear config cache
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
```

#### For Traditional Deployment

```bash
cd /var/www/greenresource/frontend

# Edit .env file
nano .env
```

Update:
```env
APP_URL=https://greenresource.co
```

Then:
```bash
php artisan config:clear
php artisan config:cache
```

### 3. Update Nginx Configuration

The nginx configuration files have been updated to include `server_name greenresource.co www.greenresource.co`.

#### For Docker Deployment

The configuration is already updated in `frontend/docker/nginx/default.conf`. Just restart nginx:

```bash
docker-compose restart nginx
```

#### For Traditional Deployment

Edit `/etc/nginx/sites-available/greenresource`:

```nginx
server {
    listen 80;
    server_name greenresource.co www.greenresource.co;
    root /var/www/greenresource/frontend/public;
    # ... rest of configuration
}
```

Then:
```bash
sudo nginx -t
sudo systemctl reload nginx
```

### 4. Configure Alibaba Cloud Security Group

**IMPORTANT:** You must allow ports 80 and 443 in Alibaba Cloud Security Group.

1. Log in to Alibaba Cloud Console: https://ecs.console.aliyun.com
2. Navigate to **ECS** → **Instances**
3. Find your instance (IP: 172.28.80.101)
4. Click on the instance → **Security Groups** → Click security group name
5. Click **Inbound** tab
6. Add the following rules:

**Rule 1: HTTP (Port 80)**
- **Port Range:** `80/80`
- **Protocol:** `TCP`
- **Authorization Object:** `0.0.0.0/0`
- **Description:** `Allow HTTP for greenresource.co`

**Rule 2: HTTPS (Port 443)**
- **Port Range:** `443/443`
- **Protocol:** `TCP`
- **Authorization Object:** `0.0.0.0/0`
- **Description:** `Allow HTTPS for greenresource.co`

7. Click **Save**

### 5. Set Up SSL/HTTPS Certificate

#### Option A: Let's Encrypt (Free) - Recommended

**Prerequisites:**
- DNS must be pointing to your server
- Ports 80 and 443 must be open in security group
- Domain must be accessible via HTTP

**Install Certbot:**

```bash
sudo apt update
sudo apt install -y certbot python3-certbot-nginx
```

**For Docker Deployment:**

Since nginx is in a container, use standalone mode:

```bash
# Stop nginx container temporarily (or use a different method)
docker-compose stop nginx

# Obtain certificate
sudo certbot certonly --standalone -d greenresource.co -d www.greenresource.co

# Start nginx again
docker-compose start nginx
```

**Update docker-compose.yml to mount certificates:**

```yaml
nginx:
  volumes:
    - ./:/var/www/html
    - ./docker/nginx/default-ssl.conf:/etc/nginx/conf.d/default.conf
    - /etc/letsencrypt:/etc/letsencrypt:ro
```

**Switch to SSL configuration:**

```bash
cd /var/www/greenresource/frontend

# Backup current config
cp docker/nginx/default.conf docker/nginx/default.conf.backup

# Use SSL config
cp docker/nginx/default-ssl.conf docker/nginx/default.conf

# Restart nginx
docker-compose restart nginx
```

**For Traditional Deployment:**

```bash
# Obtain certificate
sudo certbot --nginx -d greenresource.co -d www.greenresource.co

# Certbot will automatically update nginx configuration
```

**Set Up Auto-Renewal:**

```bash
# Test renewal
sudo certbot renew --dry-run

# For Docker, add to crontab:
sudo crontab -e
# Add this line:
0 0 * * * certbot renew --quiet && docker-compose -f /var/www/greenresource/frontend/docker-compose.yml restart nginx

# For traditional deployment, certbot sets up auto-renewal automatically
```

#### Option B: Alibaba Cloud SSL Certificate

1. **Purchase/Upload SSL Certificate** in Alibaba Cloud Console
2. **Download certificate files** (certificate.pem and private.key)
3. **Upload to server:**

```bash
sudo mkdir -p /etc/ssl/greenresource.co
sudo nano /etc/ssl/greenresource.co/certificate.pem
# Paste certificate content

sudo nano /etc/ssl/greenresource.co/private.key
# Paste private key content

sudo chmod 600 /etc/ssl/greenresource.co/private.key
sudo chmod 644 /etc/ssl/greenresource.co/certificate.pem
```

4. **Update nginx configuration** to use these certificates (see default-ssl.conf)

5. **For Docker:** Mount SSL directory in docker-compose.yml:
```yaml
nginx:
  volumes:
    - /etc/ssl/greenresource.co:/etc/ssl/greenresource.co:ro
```

### 6. Verify Domain Setup

#### Test HTTP Access

```bash
# From your local machine
curl -I http://greenresource.co

# Should return HTTP 200 or 301 (redirect to HTTPS)
```

#### Test HTTPS Access

```bash
# From your local machine
curl -I https://greenresource.co

# Should return HTTP 200
```

#### Check SSL Certificate

```bash
# Check certificate details
openssl s_client -connect greenresource.co:443 -servername greenresource.co

# Online SSL checker
# Visit: https://www.ssllabs.com/ssltest/analyze.html?d=greenresource.co
```

#### Test Website Functionality

1. Visit `https://greenresource.co` in your browser
2. Verify all pages load correctly
3. Test admin panel: `https://greenresource.co/admin/login`
4. Test contact form submission

### 7. Update Application URLs

After SSL is set up, ensure all URLs in the application use HTTPS:

```bash
# Update .env
APP_URL=https://greenresource.co

# Clear caches
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
```

## Troubleshooting

### Domain Not Resolving

**Problem:** `nslookup greenresource.co` doesn't return your server IP

**Solutions:**
1. Check DNS records at your registrar
2. Wait longer for DNS propagation (can take up to 48 hours)
3. Clear DNS cache on your local machine:
   ```bash
   # Windows
   ipconfig /flushdns
   
   # Linux/Mac
   sudo systemd-resolve --flush-caches
   ```

### SSL Certificate Issues

**Problem:** Certificate not working or expired

**Solutions:**
1. Verify DNS is pointing correctly
2. Check certificate expiration: `sudo certbot certificates`
3. Renew certificate: `sudo certbot renew`
4. Verify nginx can access certificate files
5. Check nginx error logs: `docker-compose logs nginx`

### Connection Refused

**Problem:** Can't connect to domain

**Solutions:**
1. Verify Alibaba Cloud Security Group allows ports 80 and 443
2. Check server firewall: `sudo ufw status`
3. Verify nginx is running: `docker-compose ps` or `sudo systemctl status nginx`
4. Check nginx configuration: `docker-compose exec nginx nginx -t`

### Mixed Content Warnings

**Problem:** Browser shows mixed content warnings (HTTP resources on HTTPS page)

**Solutions:**
1. Ensure APP_URL uses HTTPS
2. Update all hardcoded URLs in code to use HTTPS
3. Use relative URLs where possible
4. Check browser console for specific resources causing issues

## Maintenance

### Renew SSL Certificate

Let's Encrypt certificates expire every 90 days. Auto-renewal should handle this, but you can manually renew:

```bash
sudo certbot renew
docker-compose restart nginx  # For Docker
sudo systemctl reload nginx   # For traditional
```

### Update DNS Records

If you change server IP, update DNS A records at your registrar and wait for propagation.

### Monitor Domain Health

- Set up uptime monitoring (e.g., UptimeRobot, Pingdom)
- Monitor SSL certificate expiration
- Check DNS resolution regularly

## Additional Resources

- [Let's Encrypt Documentation](https://letsencrypt.org/docs/)
- [Nginx SSL Configuration](https://nginx.org/en/docs/http/configuring_https_servers.html)
- [Alibaba Cloud Security Group Guide](https://www.alibabacloud.com/help/en/ecs/user-guide/configure-security-group-rules)

