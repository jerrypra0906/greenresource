# Setting Up Standard Port 80 Configuration

This guide explains how to configure your deployment to use standard port 80 (HTTP) instead of port 8000.

## What Changed

The `docker-compose.yml` has been updated to use standard ports:
- **Port 80** for HTTP (instead of 8000)
- **Port 443** for HTTPS (for SSL certificates)

This means you can now access your site as:
- `http://greenresource.co` (instead of `http://greenresource.co:8000`)
- `https://greenresource.co` (after SSL setup)

## Deployment Steps

### Step 1: Update Configuration on Server

**SSH into your server:**
```bash
ssh root@172.28.80.101
cd /var/www/greenresource/frontend
```

**Pull the latest changes:**
```bash
git pull origin main
```

### Step 2: Check if Port 80 is Available

**Check if anything is using port 80:**
```bash
sudo netstat -tuln | grep 80
# OR
sudo ss -tuln | grep 80
```

**If you see something using port 80:**
- If it's nginx (system nginx), you may need to stop it:
  ```bash
  sudo systemctl stop nginx
  sudo systemctl disable nginx  # Prevent it from starting on boot
  ```
- If it's Apache, stop it:
  ```bash
  sudo systemctl stop apache2
  sudo systemctl disable apache2
  ```

### Step 3: Update Docker Compose

The `docker-compose.yml` is already updated. Verify it:
```bash
cat docker-compose.yml | grep -A 2 "ports:"
```

Should show:
```yaml
ports:
  - "80:80"    # HTTP - standard port
  - "443:443"  # HTTPS - for SSL certificates
```

### Step 4: Restart Containers

**Stop current containers:**
```bash
docker-compose down
```

**Start with new configuration:**
```bash
docker-compose up -d
```

**Verify containers are running:**
```bash
docker-compose ps
```

All containers should show "Up" status.

### Step 5: Verify Port 80 is Listening

**Check if port 80 is listening:**
```bash
sudo netstat -tuln | grep 80
# OR
sudo ss -tuln | grep 80
```

Should show:
```
tcp  0  0  0.0.0.0:80  0.0.0.0:*  LISTEN
```

**Important:** Must show `0.0.0.0:80` (all interfaces), NOT `127.0.0.1:80` (localhost only).

### Step 6: Configure Alibaba Cloud Security Group

**IMPORTANT:** You must allow ports 80 and 443 in Alibaba Cloud Security Group.

1. Log in to Alibaba Cloud Console: https://ecs.console.aliyun.com
2. Navigate to **ECS** → **Instances** → Find your instance (172.28.80.101)
3. Click **Security Groups** → Click security group name
4. Click **Inbound** tab
5. Add/Verify these rules:

**Rule 1: HTTP (Port 80)**
- **Port Range:** `80/80`
- **Protocol:** `TCP`
- **Authorization Object:** `0.0.0.0/0`
- **Description:** `Allow HTTP on port 80`

**Rule 2: HTTPS (Port 443)**
- **Port Range:** `443/443`
- **Protocol:** `TCP`
- **Authorization Object:** `0.0.0.0/0`
- **Description:** `Allow HTTPS on port 443`

6. Click **Save**

### Step 7: Configure Server Firewall

**Ubuntu/Debian (ufw):**
```bash
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw reload
sudo ufw status
```

**CentOS/RHEL (firewalld):**
```bash
sudo firewall-cmd --permanent --add-port=80/tcp
sudo firewall-cmd --permanent --add-port=443/tcp
sudo firewall-cmd --reload
sudo firewall-cmd --list-ports
```

### Step 8: Test Access

**From the server:**
```bash
curl http://localhost
# Should return HTML content
```

**From your local machine:**
```bash
# Test via IP
curl http://172.28.80.101

# Test via domain (after DNS is configured)
curl http://greenresource.co
```

**In browser:**
- `http://172.28.80.101` (via IP)
- `http://greenresource.co` (via domain, after DNS setup)

## Troubleshooting

### Port 80 Already in Use

**Error:** `Bind for 0.0.0.0:80 failed: port is already allocated`

**Solution:**
1. Find what's using port 80:
   ```bash
   sudo lsof -i :80
   # OR
   sudo netstat -tulpn | grep 80
   ```

2. Stop the service:
   ```bash
   # If it's system nginx
   sudo systemctl stop nginx
   sudo systemctl disable nginx
   
   # If it's Apache
   sudo systemctl stop apache2
   sudo systemctl disable apache2
   ```

3. Restart Docker containers:
   ```bash
   docker-compose down
   docker-compose up -d
   ```

### Can't Access from Browser

**Check Security Group:**
- Verify ports 80 and 443 are allowed in Alibaba Cloud Security Group
- Check Authorization Object is `0.0.0.0/0` (allows from anywhere)

**Check Firewall:**
- Verify ufw/firewalld allows ports 80 and 443

**Check Port Binding:**
- Verify port is listening on `0.0.0.0:80`, not `127.0.0.1:80`

**Test from server:**
```bash
curl http://localhost
# If this works, the issue is network/firewall
```

### Container Won't Start

**Check logs:**
```bash
docker-compose logs nginx
```

**Common issues:**
- Port conflict (something else using port 80)
- Configuration error in docker-compose.yml
- Permission issues

## Verification Checklist

After setup, verify:

- [ ] Port 80 is listening on `0.0.0.0:80` (not `127.0.0.1:80`)
- [ ] Alibaba Cloud Security Group allows ports 80 and 443
- [ ] Server firewall allows ports 80 and 443
- [ ] Can access `http://172.28.80.101` from browser
- [ ] Can access `http://localhost` from server
- [ ] Docker containers are running (`docker-compose ps`)
- [ ] No other service is using port 80

## Next Steps

After port 80 is working:

1. **Configure DNS** - Point `greenresource.co` to `172.28.80.101`
2. **Set up SSL** - Get SSL certificate for HTTPS
3. **Update APP_URL** - Change `.env` to use `https://greenresource.co`

See `docs/DOMAIN_SETUP.md` for detailed instructions.

