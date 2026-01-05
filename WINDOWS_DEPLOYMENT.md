# Windows Deployment Guide

This guide is for deploying from a Windows machine to your Alibaba Cloud server.

## Prerequisites

You need one of the following SSH clients installed:

### Option 1: OpenSSH Client (Recommended for Windows 10/11)

1. Open **Settings** > **Apps** > **Optional Features**
2. Click **Add a feature**
3. Search for **OpenSSH Client** and install it
4. Restart PowerShell

### Option 2: Git for Windows

Download and install from: https://git-scm.com/download/win

This includes Git Bash which has SSH support.

### Option 3: Windows Subsystem for Linux (WSL)

```powershell
# Run in PowerShell as Administrator
wsl --install
```

## Deployment Methods

### Method 1: PowerShell Script (Easiest)

1. **Open PowerShell** in the project directory:
```powershell
cd "D:\Cursor\Company Profile"
```

2. **Run the PowerShell deployment script:**
```powershell
.\deploy.ps1 -ServerIP "172.28.80.101" -SSHUser "root"
```

Or with default values:
```powershell
.\deploy.ps1
```

3. **Enter your SSH password** when prompted.

### Method 2: Git Bash

1. **Open Git Bash** in the project directory

2. **Run the bash script:**
```bash
chmod +x deploy.sh
./deploy.sh 172.28.80.101 root
```

### Method 3: Manual Deployment via SSH

1. **Connect to your server:**
```powershell
ssh root@172.28.80.101
```

2. **Once connected, run these commands on the server:**
```bash
# Install Docker (if not installed)
curl -fsSL https://get.docker.com -o get-docker.sh
sh get-docker.sh
curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
chmod +x /usr/local/bin/docker-compose

# Clone repository
cd /var/www
git clone https://github.com/jerrypra0906/greenresource.git
cd greenresource/frontend

# Configure environment
cp .env.example .env
nano .env  # Edit with production settings

# Generate application key
docker-compose run --rm app php artisan key:generate

# Deploy
docker-compose up -d --build
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed --class=AdminUserSeeder
docker-compose exec app php artisan storage:link

# Set permissions
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

3. **Access your application:**
   - Website: http://172.28.80.101:8000
   - Admin: http://172.28.80.101:8000/admin/login

## Using SSH Keys (Passwordless Login)

To avoid entering password every time:

### 1. Generate SSH Key (if you don't have one)
```powershell
ssh-keygen -t rsa -b 4096
# Press Enter to accept default location
# Press Enter twice for no passphrase (or set one)
```

### 2. Copy Public Key to Server
```powershell
type $env:USERPROFILE\.ssh\id_rsa.pub | ssh root@172.28.80.101 "mkdir -p ~/.ssh && cat >> ~/.ssh/authorized_keys"
```

Or manually:
```powershell
# Display your public key
type $env:USERPROFILE\.ssh\id_rsa.pub

# Copy the output, then SSH to server and run:
ssh root@172.28.80.101
mkdir -p ~/.ssh
nano ~/.ssh/authorized_keys
# Paste your public key, save and exit
chmod 600 ~/.ssh/authorized_keys
chmod 700 ~/.ssh
```

### 3. Test Connection
```powershell
ssh root@172.28.80.101
# Should connect without password
```

## Troubleshooting

### "SSH not found" Error

Install OpenSSH Client:
```powershell
# Run PowerShell as Administrator
Add-WindowsCapability -Online -Name OpenSSH.Client~~~~0.0.1.0
```

### "Permission denied" Error

- Check if you're using the correct username
- Verify SSH key is properly set up
- Check server firewall allows SSH (port 22)

### Script Execution Policy Error

If you get an execution policy error:
```powershell
# Run PowerShell as Administrator
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
```

### Connection Timeout

- Verify server IP is correct: `172.28.80.101`
- Check if server is running
- Verify firewall allows SSH connections
- Try pinging the server: `ping 172.28.80.101`

## Quick Reference

### Connect to Server
```powershell
ssh root@172.28.80.101
```

### Run PowerShell Deployment Script
```powershell
.\deploy.ps1
```

### Run Bash Deployment Script (Git Bash)
```bash
chmod +x deploy.sh
./deploy.sh 172.28.80.101 root
```

### View Application Logs
```powershell
ssh root@172.28.80.101 "cd /var/www/greenresource/frontend && docker-compose logs -f app"
```

### Restart Application
```powershell
ssh root@172.28.80.101 "cd /var/www/greenresource/frontend && docker-compose restart"
```

## Next Steps After Deployment

1. **Configure .env file** with production settings
2. **Change default admin password** (admin@greenresources.com / admin123)
3. **Set up SSL certificate** for HTTPS
4. **Configure firewall** rules
5. **Set up backups**

See `DEPLOYMENT.md` for detailed post-deployment steps.

