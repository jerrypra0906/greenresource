# Cloudflare SSL Quick Start Guide

This is a simplified guide to get SSL working with Cloudflare in 5 minutes.

## Prerequisites

- Domain `greenresource.co` registered
- Cloudflare account (free tier works)
- Server running on `172.28.80.101`

## Step 1: Add Domain to Cloudflare (2 minutes)

1. Go to https://dash.cloudflare.com
2. Click **"Add a Site"**
3. Enter: `greenresource.co`
4. Select **Free** plan
5. Cloudflare will scan your DNS - click **Continue**
6. **Update nameservers** at your domain registrar to Cloudflare's nameservers
   - Cloudflare will show you the nameservers (e.g., `alice.ns.cloudflare.com`)
   - Go to your domain registrar and update nameservers
   - Wait 5-30 minutes for DNS propagation

## Step 2: Configure DNS in Cloudflare (1 minute)

1. In Cloudflare, go to **DNS → Records**
2. **Delete any existing A records** for your domain
3. **Add new A record:**
   - **Type:** `A`
   - **Name:** `@`
   - **IPv4 address:** `172.28.80.101`
   - **Proxy status:** Click the cloud icon to make it **orange (Proxied)** ⚠️ **IMPORTANT**
   - Click **Save**

4. **Add A record for www:**
   - **Type:** `A`
   - **Name:** `www`
   - **IPv4 address:** `172.28.80.101`
   - **Proxy status:** **Orange (Proxied)** ⚠️ **IMPORTANT**
   - Click **Save**

## Step 3: Enable SSL (1 minute)

1. Go to **SSL/TLS → Overview**
2. Select **"Flexible"** mode
   - This means: HTTPS between visitor and Cloudflare, HTTP between Cloudflare and server
   - No server certificate needed - easiest option!

3. Go to **SSL/TLS → Edge Certificates**
4. Toggle **"Always Use HTTPS"** to **ON**
5. Toggle **"Automatic HTTPS Rewrites"** to **ON**

## Step 4: Update Server (1 minute)

**SSH into your server:**
```bash
ssh root@172.28.80.101
cd /var/www/greenresource/frontend
```

**Update APP_URL:**
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

**Optional: Use Cloudflare-optimized nginx config:**
```bash
cp docker/nginx/default-cloudflare.conf docker/nginx/default.conf
docker-compose restart nginx
```

## Step 5: Test (30 seconds)

1. Wait 5-10 minutes for DNS to propagate
2. Visit: `https://greenresource.co`
3. You should see the padlock icon 🔒
4. Certificate should be issued by Cloudflare

## That's It!

Your site is now:
- ✅ Accessible via HTTPS
- ✅ Automatically redirects HTTP to HTTPS
- ✅ Has valid SSL certificate (Cloudflare)
- ✅ Protected by Cloudflare's CDN and security features

## Troubleshooting

### Site Still Shows HTTP

**Check DNS:**
- Verify DNS records are **Proxied** (orange cloud) in Cloudflare
- Wait longer for DNS propagation (can take up to 48 hours)

**Check SSL Mode:**
- Go to SSL/TLS → Overview
- Ensure mode is set to "Flexible"

**Check Always Use HTTPS:**
- Go to SSL/TLS → Edge Certificates
- Ensure "Always Use HTTPS" is enabled

### 502/520/521 Errors

**Check server is running:**
```bash
ssh root@172.28.80.101
docker-compose ps
```

**Check port 80 is accessible:**
```bash
curl http://localhost
```

**Check Alibaba Cloud Security Group:**
- Ensure port 80 is allowed in inbound rules

### Certificate Errors

If using Flexible mode, you shouldn't see certificate errors. If you do:
- Clear browser cache
- Try incognito/private mode
- Check Cloudflare SSL settings

## Next Steps

- **Monitor traffic** in Cloudflare dashboard
- **Enable Cloudflare features** (caching, minification, etc.)
- **Set up Cloudflare page rules** for better performance
- **Consider Full SSL mode** for better security (requires server certificate)

## Need More Details?

See `docs/CLOUDFLARE_SSL_SETUP.md` for comprehensive guide with all options.

