# Cloudflare SSL Setup Guide

This guide explains how to configure SSL/HTTPS using Cloudflare for your greenresource.co domain.

## How Cloudflare SSL Works

Cloudflare acts as a proxy between your visitors and your server:
1. **Visitor → Cloudflare:** Connection is encrypted (HTTPS)
2. **Cloudflare → Your Server:** Can be HTTP or HTTPS depending on SSL mode

**SSL Modes:**
- **Flexible:** HTTPS between visitor and Cloudflare, HTTP between Cloudflare and server (easiest)
- **Full:** HTTPS both ways, but Cloudflare doesn't verify your server certificate
- **Full (Strict):** HTTPS both ways, Cloudflare verifies your server certificate (most secure)

## Prerequisites

1. Domain `greenresource.co` registered
2. Cloudflare account (free tier works)
3. Domain added to Cloudflare
4. DNS records pointing to your server IP (172.28.80.101)

## Step-by-Step Setup

### Step 1: Add Domain to Cloudflare

1. **Log in to Cloudflare:** https://dash.cloudflare.com
2. **Click "Add a Site"**
3. **Enter your domain:** `greenresource.co`
4. **Select plan:** Free plan is sufficient
5. **Cloudflare will scan your DNS records** - verify they're correct
6. **Update nameservers** at your domain registrar to Cloudflare's nameservers

### Step 2: Configure DNS Records in Cloudflare

1. **Go to DNS → Records**
2. **Add/Update A Records:**

**A Record for Root Domain:**
- **Type:** `A`
- **Name:** `@` (or `greenresource.co`)
- **IPv4 address:** `172.28.80.101`
- **Proxy status:** `Proxied` (orange cloud) - **IMPORTANT for SSL**
- **TTL:** `Auto`

**A Record for www:**
- **Type:** `A`
- **Name:** `www`
- **IPv4 address:** `172.28.80.101`
- **Proxy status:** `Proxied` (orange cloud) - **IMPORTANT for SSL**
- **TTL:** `Auto`

**Important:** The orange cloud (Proxied) must be enabled for SSL to work.

### Step 3: Configure SSL/TLS Settings

1. **Go to SSL/TLS → Overview**
2. **Select SSL/TLS encryption mode:**

**For Quick Setup (Recommended):**
- Select **"Flexible"** mode
- This allows HTTP between Cloudflare and your server
- No server certificate needed
- Easiest to set up

**For Better Security:**
- Select **"Full"** or **"Full (strict)"** mode
- Requires SSL certificate on your server
- See "Full SSL Setup" section below

### Step 4: Configure Automatic HTTPS Rewrites

1. **Go to SSL/TLS → Edge Certificates**
2. **Enable "Always Use HTTPS":**
   - Toggle **"Always Use HTTPS"** to ON
   - This automatically redirects HTTP to HTTPS

3. **Enable "Automatic HTTPS Rewrites":**
   - Toggle **"Automatic HTTPS Rewrites"** to ON
   - This rewrites HTTP links to HTTPS in your pages

### Step 5: Update Server Configuration

#### Option A: Flexible SSL Mode (Easiest - Recommended for Start)

**No changes needed on server!** Your server can continue using HTTP on port 80.

**Verify nginx is listening on port 80:**
```bash
ssh root@172.28.80.101
cd /var/www/greenresource/frontend
sudo netstat -tuln | grep 80
# Should show: tcp  0  0  0.0.0.0:80  0.0.0.0:*  LISTEN
```

**Update APP_URL in .env:**
```bash
nano .env
```

Change:
```env
APP_URL=https://greenresource.co
```

**Clear cache:**
```bash
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
```

#### Option B: Full SSL Mode (More Secure)

If you want to use "Full" or "Full (Strict)" mode, you need SSL on your server.

**Option B1: Use Cloudflare Origin Certificate (Recommended for Full SSL)**

1. **Generate Origin Certificate in Cloudflare:**
   - Go to **SSL/TLS → Origin Server**
   - Click **"Create Certificate"**
   - Select **"Let Cloudflare generate a private key and a CSR"**
   - Select validity: **15 years** (recommended)
   - Click **"Create"**
   - Copy the **Origin Certificate** and **Private Key**

2. **Install Certificate on Server:**
   ```bash
   ssh root@172.28.80.101
   
   # Create directory for certificates
   sudo mkdir -p /etc/ssl/greenresource.co
   
   # Create certificate file
   sudo nano /etc/ssl/greenresource.co/origin.crt
   # Paste the Origin Certificate content
   
   # Create private key file
   sudo nano /etc/ssl/greenresource.co/origin.key
   # Paste the Private Key content
   
   # Set proper permissions
   sudo chmod 600 /etc/ssl/greenresource.co/origin.key
   sudo chmod 644 /etc/ssl/greenresource.co/origin.crt
   ```

3. **Update docker-compose.yml:**
   ```yaml
   nginx:
     volumes:
       - ./:/var/www/html
       - ./docker/nginx/default-ssl.conf:/etc/nginx/conf.d/default.conf
       - /etc/ssl/greenresource.co:/etc/ssl/greenresource.co:ro
   ```

4. **Update nginx SSL configuration:**
   Edit `frontend/docker/nginx/default-ssl.conf`:
   ```nginx
   ssl_certificate /etc/ssl/greenresource.co/origin.crt;
   ssl_certificate_key /etc/ssl/greenresource.co/origin.key;
   ```

5. **Use SSL configuration:**
   ```bash
   cd /var/www/greenresource/frontend
   cp docker/nginx/default-ssl.conf docker/nginx/default.conf
   docker-compose restart nginx
   ```

**Option B2: Use Let's Encrypt Certificate**

If you prefer Let's Encrypt instead of Cloudflare Origin Certificate:

```bash
# Install Certbot
sudo apt update
sudo apt install -y certbot

# Get certificate (Cloudflare must be proxying for this to work)
sudo certbot certonly --standalone -d greenresource.co -d www.greenresource.co

# Update nginx to use Let's Encrypt certificates
# (certificates will be in /etc/letsencrypt/live/greenresource.co/)
```

### Step 6: Configure Cloudflare Page Rules (Optional)

1. **Go to Rules → Page Rules**
2. **Create rule to force HTTPS:**
   - **URL:** `http://*greenresource.co/*`
   - **Setting:** Always Use HTTPS
   - **Status:** ON

### Step 7: Verify SSL is Working

1. **Test HTTPS access:**
   ```bash
   curl -I https://greenresource.co
   # Should return HTTP 200 or 301
   ```

2. **Check SSL certificate:**
   ```bash
   openssl s_client -connect greenresource.co:443 -servername greenresource.co
   ```

3. **Online SSL checker:**
   - Visit: https://www.ssllabs.com/ssltest/analyze.html?d=greenresource.co
   - Should show Cloudflare certificate

4. **Test in browser:**
   - Visit: `https://greenresource.co`
   - Should show padlock icon
   - Certificate should be issued by Cloudflare

## Cloudflare SSL Modes Explained

### Flexible SSL (Easiest)
- ✅ **Visitor → Cloudflare:** HTTPS (encrypted)
- ❌ **Cloudflare → Server:** HTTP (not encrypted)
- ✅ **No server certificate needed**
- ⚠️ **Less secure** (traffic between Cloudflare and server is unencrypted)

**Best for:** Quick setup, development, or when you can't install certificates on server

### Full SSL
- ✅ **Visitor → Cloudflare:** HTTPS (encrypted)
- ✅ **Cloudflare → Server:** HTTPS (encrypted)
- ⚠️ **Server certificate not verified** by Cloudflare
- ✅ **More secure** than Flexible

**Best for:** Production when you have a self-signed or unverified certificate

### Full (Strict) SSL (Most Secure)
- ✅ **Visitor → Cloudflare:** HTTPS (encrypted)
- ✅ **Cloudflare → Server:** HTTPS (encrypted)
- ✅ **Server certificate verified** by Cloudflare
- ✅ **Most secure** option

**Best for:** Production with valid SSL certificate (Cloudflare Origin Certificate or Let's Encrypt)

## Recommended Configuration

**For Quick Setup:**
- SSL Mode: **Flexible**
- Always Use HTTPS: **ON**
- Automatic HTTPS Rewrites: **ON**
- Server: HTTP on port 80 (no changes needed)

**For Production:**
- SSL Mode: **Full (Strict)**
- Always Use HTTPS: **ON**
- Automatic HTTPS Rewrites: **ON**
- Server: HTTPS with Cloudflare Origin Certificate

## Troubleshooting

### SSL Not Working

**Check DNS:**
- Verify DNS records are **Proxied** (orange cloud) in Cloudflare
- DNS must point to your server IP (172.28.80.101)

**Check SSL Mode:**
- Go to SSL/TLS → Overview
- Ensure mode is set (Flexible, Full, or Full Strict)

**Check Always Use HTTPS:**
- Go to SSL/TLS → Edge Certificates
- Ensure "Always Use HTTPS" is enabled

### Mixed Content Warnings

**Problem:** Browser shows "Mixed Content" warnings

**Solution:**
1. Enable "Automatic HTTPS Rewrites" in Cloudflare
2. Update APP_URL in .env to use HTTPS
3. Ensure all hardcoded URLs in code use HTTPS
4. Use relative URLs where possible

### Certificate Errors

**Problem:** "Your connection is not private" error

**For Flexible Mode:**
- This shouldn't happen - check Cloudflare SSL settings

**For Full/Full Strict Mode:**
- Verify certificate is installed correctly on server
- Check certificate paths in nginx configuration
- Verify certificate hasn't expired
- Check nginx error logs: `docker-compose logs nginx`

### Cloudflare 502/520/521 Errors

**Problem:** Cloudflare shows 502, 520, or 521 errors

**Solutions:**
1. **Check server is running:**
   ```bash
   ssh root@172.28.80.101
   docker-compose ps
   ```

2. **Check port 80 is accessible:**
   ```bash
   curl http://localhost
   ```

3. **Check firewall:**
   - Ensure Alibaba Cloud Security Group allows port 80
   - Ensure server firewall allows port 80

4. **Check Cloudflare SSL mode:**
   - If using Full/Full Strict, ensure server has valid certificate
   - Try switching to Flexible mode temporarily to test

## Cloudflare Features to Enable

### Performance
- **Auto Minify:** CSS, JavaScript, HTML
- **Brotli:** Compression
- **Rocket Loader:** JavaScript optimization

### Security
- **Security Level:** Medium or High
- **Challenge Passage:** 30 minutes
- **Browser Integrity Check:** ON

### Caching
- **Caching Level:** Standard
- **Browser Cache TTL:** Respect Existing Headers

## Maintenance

### Renew Cloudflare Origin Certificate

Cloudflare Origin Certificates are valid for 15 years, but if you need to renew:

1. Go to SSL/TLS → Origin Server
2. Generate new certificate
3. Update certificate files on server
4. Restart nginx: `docker-compose restart nginx`

### Monitor SSL Status

- Check Cloudflare dashboard for SSL errors
- Monitor SSL Labs rating: https://www.ssllabs.com/ssltest/
- Set up Cloudflare alerts for SSL issues

## Next Steps

After SSL is configured:

1. **Update APP_URL** in `.env` to `https://greenresource.co`
2. **Test all pages** to ensure HTTPS works
3. **Check for mixed content** warnings
4. **Monitor Cloudflare analytics** for traffic and performance

## Additional Resources

- [Cloudflare SSL Documentation](https://developers.cloudflare.com/ssl/)
- [Cloudflare Origin Certificates](https://developers.cloudflare.com/ssl/origin-configuration/origin-ca/)
- [Cloudflare SSL Modes](https://developers.cloudflare.com/ssl/origin-configuration/ssl-modes/)

