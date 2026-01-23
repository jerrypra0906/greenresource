# Troubleshooting: "Site Can't Be Reached" for greenresource.co

## Problem: DNS Not Configured

If you're getting "site can't be reached" when accessing `http://www.greenresource.co/` or `http://greenresource.co/`, the most common cause is that **DNS records haven't been set up yet**.

### Quick Diagnosis

Run this command to check if DNS is configured:

```bash
nslookup greenresource.co
nslookup www.greenresource.co
```

**If you see "Non-existent domain" or no IP address returned**, DNS is not configured.

## Solution: Configure DNS Records

### Step 1: Access Your Domain Registrar

1. Log in to your domain registrar (where you purchased `greenresource.co`)
   - Common registrars: GoDaddy, Namecheap, Google Domains, Cloudflare, etc.
2. Navigate to **DNS Management** or **DNS Settings**

### Step 2: Add DNS A Records

You need to add **A records** that point your domain to your server IP: `172.28.80.101`

#### A Record for Root Domain (greenresource.co)

- **Type:** `A`
- **Name/Host:** `@` (or leave blank, or `greenresource.co`)
- **Value/Points to/Address:** `172.28.80.101`
- **TTL:** `3600` (1 hour) or default

#### A Record for www Subdomain (www.greenresource.co)

- **Type:** `A`
- **Name/Host:** `www`
- **Value/Points to/Address:** `172.28.80.101`
- **TTL:** `3600` (1 hour) or default

### Step 3: Save and Wait

1. **Save** the DNS records
2. **Wait 5-30 minutes** for DNS propagation (can take up to 48 hours, but usually 15-30 minutes)

### Step 4: Verify DNS Propagation

#### From Command Line:

```bash
# Windows PowerShell
nslookup greenresource.co
nslookup www.greenresource.co

# Linux/Mac
dig greenresource.co
dig www.greenresource.co

# Or ping (will show IP)
ping greenresource.co
```

**Expected Result:** Should return `172.28.80.101`

#### Online DNS Checkers:

- https://www.whatsmydns.net/#A/greenresource.co
- https://dnschecker.org/#A/greenresource.co
- https://mxtoolbox.com/DNSLookup.aspx

Enter `greenresource.co` and check if it resolves to `172.28.80.101` globally.

## Important: Port Configuration

**Your docker-compose.yml is configured to use standard port 80**, which means:
- Site is accessible on standard HTTP port 80
- Access: `http://greenresource.co` (no port number needed)
- HTTPS will use port 443

**Configuration:**
```yaml
nginx:
  ports:
    - "80:80"    # HTTP - standard port
    - "443:443"  # HTTPS - for SSL certificates
```

**Important:** Make sure:
- No other service is using port 80 on the server
- Alibaba Cloud Security Group allows ports 80 and 443
- Server firewall allows ports 80 and 443

See `docs/PORT_80_SETUP.md` for detailed setup instructions.

## Temporary Workaround: Access via IP Address

While waiting for DNS to propagate, you can access your site using the IP address:

**For Docker deployment:**
- `http://172.28.80.101` (standard port 80)

**For traditional deployment:**
- `http://172.28.80.101`

**Note:** Some features may not work correctly when accessing via IP instead of domain name.

## Other Potential Issues (After DNS is Configured)

### 1. Server Not Running

**Check if containers are running (Docker):**
```bash
ssh root@172.28.80.101
cd /var/www/greenresource/frontend
docker-compose ps
```

All containers should show "Up" status. If not:
```bash
docker-compose up -d
```

**Check if services are running (Traditional):**
```bash
ssh root@172.28.80.101
sudo systemctl status nginx
sudo systemctl status php8.2-fpm
```

### 2. Port 80 Not Accessible

**Check Alibaba Cloud Security Group:**

1. Log in to Alibaba Cloud Console: https://ecs.console.aliyun.com
2. Navigate to **ECS** → **Instances** → Find your instance (172.28.80.101)
3. Click **Security Groups** → Click security group name
4. Check **Inbound** rules:
   - **Port 80** should be allowed (HTTP)
   - **Port 443** should be allowed (HTTPS)
   - **Authorization Object:** `0.0.0.0/0` (allows from anywhere)

If missing, add the rules:
- **Port Range:** `80/80`, **Protocol:** `TCP`, **Source:** `0.0.0.0/0`
- **Port Range:** `443/443`, **Protocol:** `TCP`, **Source:** `0.0.0.0/0`

### 3. Nginx Not Listening on Port 80

**For Docker:**
```bash
ssh root@172.28.80.101
cd /var/www/greenresource/frontend

# Check if nginx is listening
docker-compose exec nginx netstat -tuln | grep 80

# Check nginx configuration
docker-compose exec nginx nginx -t

# Check nginx logs
docker-compose logs nginx
```

**For Traditional:**
```bash
ssh root@172.28.80.101

# Check if nginx is listening
sudo netstat -tuln | grep 80

# Check nginx configuration
sudo nginx -t

# Check nginx status
sudo systemctl status nginx
```

### 4. Nginx Configuration Issue

**Check server_name in nginx config:**

The nginx configuration should include:
```nginx
server_name greenresource.co www.greenresource.co;
```

**For Docker:**
```bash
ssh root@172.28.80.101
cd /var/www/greenresource/frontend
cat docker/nginx/default.conf | grep server_name
```

**For Traditional:**
```bash
ssh root@172.28.80.101
sudo cat /etc/nginx/sites-available/greenresource | grep server_name
```

If missing, update the configuration and restart nginx.

### 5. Firewall Blocking Port 80

**Check server firewall:**
```bash
ssh root@172.28.80.101

# Ubuntu/Debian
sudo ufw status
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp

# CentOS/RHEL
sudo firewall-cmd --list-ports
sudo firewall-cmd --permanent --add-port=80/tcp
sudo firewall-cmd --permanent --add-port=443/tcp
sudo firewall-cmd --reload
```

### 6. Port Mapping Configuration (Docker Only)

**Current Configuration:**
Your `docker-compose.yml` is configured to use standard port 80:
```yaml
nginx:
  ports:
    - "80:80"    # HTTP - standard port
    - "443:443"  # HTTPS - for SSL certificates
```

This means you can access: `http://greenresource.co` (no port number needed)

**If you need to verify the configuration:**

1. **Check docker-compose.yml:**
```bash
cd /var/www/greenresource/frontend
cat docker-compose.yml | grep -A 2 "ports:"
```

2. **Verify port 80 is listening:**
```bash
ssh root@172.28.80.101
sudo netstat -tuln | grep 80
# Should show: tcp  0  0  0.0.0.0:80  0.0.0.0:*  LISTEN
```

3. **Test access:**
```bash
curl http://localhost
# Should return HTML content
```

**If port 80 is not working, see `docs/PORT_80_SETUP.md` for troubleshooting.**

## Step-by-Step Verification Checklist

After configuring DNS, verify each step:

- [ ] DNS A records added at registrar
- [ ] DNS propagation verified (nslookup returns 172.28.80.101)
- [ ] Alibaba Cloud Security Group allows ports 80 and 443
- [ ] Server firewall allows ports 80 and 443
- [ ] Docker containers are running (if using Docker)
- [ ] Nginx service is running
- [ ] Nginx configuration includes `server_name greenresource.co www.greenresource.co`
- [ ] Nginx is listening on port 80
- [ ] Can access via IP address (http://172.28.80.101:8000 or http://172.28.80.101)
- [ ] Domain resolves correctly (nslookup greenresource.co)

## Testing Domain Access

Once DNS is configured and propagated:

1. **Test HTTP:**
   ```bash
   curl -I http://greenresource.co
   curl -I http://www.greenresource.co
   ```

2. **Test in Browser:**
   - Open: `http://greenresource.co`
   - Open: `http://www.greenresource.co`
   - Both should load your website

3. **Check for Redirects:**
   - If you see a redirect, that's normal (HTTP to HTTPS or www to non-www)

## Common DNS Registrar Examples

### GoDaddy
1. Log in → **My Products** → **DNS**
2. Click **Add** → Select **A** record
3. **Host:** `@` (for root) or `www`
4. **Points to:** `172.28.80.101`
5. **TTL:** `600 seconds` (10 minutes)
6. **Save**

### Namecheap
1. Log in → **Domain List** → Click **Manage** next to greenresource.co
2. Go to **Advanced DNS** tab
3. Click **Add New Record**
4. **Type:** `A Record`
5. **Host:** `@` (for root) or `www`
6. **Value:** `172.28.80.101`
7. **TTL:** `Automatic`
8. **Save**

### Cloudflare
1. Log in → Select domain
2. Go to **DNS** → **Records**
3. Click **Add record**
4. **Type:** `A`
5. **Name:** `@` (for root) or `www`
6. **IPv4 address:** `172.28.80.101`
7. **Proxy status:** `DNS only` (gray cloud) or `Proxied` (orange cloud)
8. **Save**

## Still Having Issues?

If DNS is configured correctly but site still can't be reached:

1. **Check server logs:**
   ```bash
   ssh root@172.28.80.101
   docker-compose logs nginx
   docker-compose logs app
   ```

2. **Test from server itself:**
   ```bash
   ssh root@172.28.80.101
   curl http://localhost:8000  # For Docker
   curl http://localhost      # For traditional
   ```

3. **Check if domain is actually resolving:**
   ```bash
   # From server
   nslookup greenresource.co
   ping greenresource.co
   ```

4. **Verify nginx is receiving requests:**
   ```bash
   ssh root@172.28.80.101
   tail -f /var/log/nginx/access.log  # Watch for incoming requests
   ```

## Next Steps After DNS is Working

Once your domain is accessible via HTTP:

1. **Set up SSL/HTTPS** - See `docs/DOMAIN_SETUP.md` section 5
2. **Update APP_URL** in `.env` to use `https://greenresource.co`
3. **Configure auto-renewal** for SSL certificates

