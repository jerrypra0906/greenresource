# Quick Fix: Switch from Port 8000 to Port 80

## Current Situation
- Port 8000 is still listening (old configuration)
- Need to update to port 80

## Quick Steps

### Step 1: Pull Latest Changes
```bash
cd /var/www/greenresource/frontend
git pull origin main
```

### Step 2: Verify docker-compose.yml is Updated
```bash
cat docker-compose.yml | grep -A 2 "ports:"
```

Should show:
```yaml
ports:
  - "80:80"    # HTTP - standard port
  - "443:443"  # HTTPS - for SSL certificates
```

### Step 3: Stop Current Containers
```bash
docker-compose down
```

### Step 4: Check if Port 80 is Available
```bash
sudo netstat -tuln | grep ":80 "
# Should show nothing (port 80 is free)
```

If something is using port 80:
```bash
# Find what's using port 80
sudo lsof -i :80
# OR
sudo netstat -tulpn | grep ":80 "

# If it's system nginx, stop it:
sudo systemctl stop nginx
sudo systemctl disable nginx
```

### Step 5: Start Containers with New Configuration
```bash
docker-compose up -d
```

### Step 6: Verify Port 80 is Now Listening
```bash
sudo netstat -tuln | grep 80
```

Should now show:
```
tcp        0      0 0.0.0.0:80            0.0.0.0:*               LISTEN
tcp6       0      0 :::80                  :::*                    LISTEN
```

**Important:** Must show `0.0.0.0:80` (all interfaces), NOT `127.0.0.1:80` (localhost only).

### Step 7: Test Access
```bash
# Test from server
curl http://localhost
# Should return HTML content

# Test from your local machine
curl http://172.28.80.101
# Should return HTML content
```

### Step 8: Verify Containers are Running
```bash
docker-compose ps
```

All containers should show "Up" status.

## Troubleshooting

### Port 80 Still Not Listening

**Check container logs:**
```bash
docker-compose logs nginx
```

**Check if containers started:**
```bash
docker-compose ps
```

**Check docker-compose.yml:**
```bash
cat docker-compose.yml | grep -A 3 "nginx:"
```

### Port 80 Already in Use Error

**Error message:** `Bind for 0.0.0.0:80 failed: port is already allocated`

**Solution:**
1. Find what's using port 80:
   ```bash
   sudo lsof -i :80
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
- Alibaba Cloud Security Group must allow port 80
- Check inbound rules for port 80

**Check Firewall:**
```bash
sudo ufw status
sudo ufw allow 80/tcp
sudo ufw reload
```

**Verify port is listening on all interfaces:**
```bash
sudo netstat -tuln | grep 80
# Must show 0.0.0.0:80, NOT 127.0.0.1:80
```

## After Port 80 is Working

1. **Configure DNS** - Point `greenresource.co` to `172.28.80.101`
2. **Set up SSL** - Get SSL certificate for HTTPS
3. **Update APP_URL** - Change `.env` to use `https://greenresource.co`

