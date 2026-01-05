# Green Resources CMS Deployment Script for Windows PowerShell
# Usage: .\deploy.ps1 -ServerIP "172.28.80.101" -SSHUser "root"

param(
    [string]$ServerIP = "172.28.80.101",
    [string]$SSHUser = "root",
    [string]$DeployPath = "/var/www/greenresource"
)

Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "Green Resources CMS Deployment Script" -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "Server: $SSHUser@$ServerIP" -ForegroundColor Yellow
Write-Host ""

# Check if SSH is available (requires OpenSSH client or Git Bash)
$sshAvailable = $false
if (Get-Command ssh -ErrorAction SilentlyContinue) {
    $sshAvailable = $true
    Write-Host "✓ SSH command found" -ForegroundColor Green
} else {
    Write-Host "⚠ SSH not found. Please install one of the following:" -ForegroundColor Yellow
    Write-Host "  1. OpenSSH Client (Windows 10/11): Settings > Apps > Optional Features > OpenSSH Client" -ForegroundColor Yellow
    Write-Host "  2. Git for Windows (includes Git Bash)" -ForegroundColor Yellow
    Write-Host "  3. Use WSL (Windows Subsystem for Linux)" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "For now, here are the manual deployment commands:" -ForegroundColor Cyan
    Write-Host ""
    Show-ManualCommands
    exit 1
}

# Test SSH connection
Write-Host "Checking SSH connection..." -ForegroundColor Yellow
try {
    $testResult = ssh -o ConnectTimeout=5 -o BatchMode=yes "$SSHUser@$ServerIP" "echo 'Connection successful'" 2>&1
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✓ SSH connection successful" -ForegroundColor Green
    } else {
        Write-Host "⚠ SSH connection test failed. You may need to enter password manually." -ForegroundColor Yellow
        Write-Host "  Make sure you have SSH access configured." -ForegroundColor Yellow
    }
} catch {
    Write-Host "⚠ Could not test SSH connection automatically." -ForegroundColor Yellow
    Write-Host "  You may need to enter password when prompted." -ForegroundColor Yellow
}
Write-Host ""

# Check if Docker is installed on server
Write-Host "Checking Docker installation on server..." -ForegroundColor Yellow
$dockerCheck = ssh "$SSHUser@$ServerIP" "command -v docker" 2>&1
if ($dockerCheck -match "docker") {
    Write-Host "✓ Docker is installed on server" -ForegroundColor Green
    $UseDocker = $true
} else {
    Write-Host "⚠ Docker is not installed. Will use traditional deployment." -ForegroundColor Yellow
    $UseDocker = $false
}
Write-Host ""

# Create deployment directory
Write-Host "Creating deployment directory..." -ForegroundColor Yellow
ssh "$SSHUser@$ServerIP" "mkdir -p $DeployPath" 2>&1 | Out-Null
Write-Host "✓ Directory created" -ForegroundColor Green
Write-Host ""

# Clone or update repository
Write-Host "Cloning/updating repository..." -ForegroundColor Yellow
$repoExists = ssh "$SSHUser@$ServerIP" "test -d $DeployPath/.git && echo 'exists' || echo 'not exists'" 2>&1
if ($repoExists -match "exists") {
    Write-Host "Repository exists, pulling latest changes..." -ForegroundColor Yellow
    ssh "$SSHUser@$ServerIP" "cd $DeployPath && git pull origin main" 2>&1
} else {
    Write-Host "Cloning repository..." -ForegroundColor Yellow
    ssh "$SSHUser@$ServerIP" "cd /var/www && git clone https://github.com/jerrypra0906/greenresource.git" 2>&1
}
Write-Host "✓ Repository updated" -ForegroundColor Green
Write-Host ""

# Deploy based on method
if ($UseDocker) {
    Write-Host "=========================================" -ForegroundColor Cyan
    Write-Host "Deploying with Docker..." -ForegroundColor Cyan
    Write-Host "=========================================" -ForegroundColor Cyan
    Write-Host ""
    
    # Check if .env exists
    $envExists = ssh "$SSHUser@$ServerIP" "test -f $DeployPath/frontend/.env && echo 'exists' || echo 'not exists'" 2>&1
    if ($envExists -notmatch "exists") {
        Write-Host "Creating .env file..." -ForegroundColor Yellow
        ssh "$SSHUser@$ServerIP" "cd $DeployPath/frontend && cp .env.example .env" 2>&1
        Write-Host "⚠ IMPORTANT: Please edit .env file on server with production settings!" -ForegroundColor Red
        Write-Host "   Run: ssh $SSHUser@$ServerIP 'nano $DeployPath/frontend/.env'" -ForegroundColor Yellow
        Write-Host ""
    }
    
    # Build and start containers
    Write-Host "Building Docker containers..." -ForegroundColor Yellow
    ssh "$SSHUser@$ServerIP" "cd $DeployPath/frontend && docker-compose up -d --build" 2>&1
    
    # Run migrations
    Write-Host "Running database migrations..." -ForegroundColor Yellow
    ssh "$SSHUser@$ServerIP" "cd $DeployPath/frontend && docker-compose exec -T app php artisan migrate --force" 2>&1
    
    # Create storage link
    Write-Host "Creating storage link..." -ForegroundColor Yellow
    ssh "$SSHUser@$ServerIP" "cd $DeployPath/frontend && docker-compose exec -T app php artisan storage:link" 2>&1
    
    # Set permissions
    Write-Host "Setting permissions..." -ForegroundColor Yellow
    ssh "$SSHUser@$ServerIP" "cd $DeployPath/frontend && docker-compose exec -T app chown -R www-data:www-data storage bootstrap/cache" 2>&1
    ssh "$SSHUser@$ServerIP" "cd $DeployPath/frontend && docker-compose exec -T app chmod -R 775 storage bootstrap/cache" 2>&1
    
    Write-Host ""
    Write-Host "=========================================" -ForegroundColor Green
    Write-Host "✓ Docker deployment completed!" -ForegroundColor Green
    Write-Host "=========================================" -ForegroundColor Green
    Write-Host "Application should be available at:" -ForegroundColor Cyan
    Write-Host "  http://$ServerIP:8000" -ForegroundColor White
    Write-Host "  Admin: http://$ServerIP:8000/admin/login" -ForegroundColor White
    Write-Host ""
    Write-Host "Next steps:" -ForegroundColor Yellow
    Write-Host "  1. Configure .env file with production settings" -ForegroundColor White
    Write-Host "  2. Change default admin password" -ForegroundColor White
    Write-Host "  3. Set up SSL certificate (recommended)" -ForegroundColor White
    
} else {
    Write-Host "=========================================" -ForegroundColor Cyan
    Write-Host "Traditional deployment requires manual setup" -ForegroundColor Yellow
    Write-Host "=========================================" -ForegroundColor Cyan
    Write-Host "Please follow the steps in DEPLOYMENT.md" -ForegroundColor Yellow
    Write-Host "for traditional (non-Docker) deployment." -ForegroundColor Yellow
}

Write-Host ""
Write-Host "Deployment script completed!" -ForegroundColor Green

function Show-ManualCommands {
    Write-Host "=========================================" -ForegroundColor Cyan
    Write-Host "Manual Deployment Commands" -ForegroundColor Cyan
    Write-Host "=========================================" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "1. Connect to server:" -ForegroundColor Yellow
    Write-Host "   ssh $SSHUser@$ServerIP" -ForegroundColor White
    Write-Host ""
    Write-Host "2. Install Docker (if not installed):" -ForegroundColor Yellow
    Write-Host "   curl -fsSL https://get.docker.com -o get-docker.sh" -ForegroundColor White
    Write-Host "   sh get-docker.sh" -ForegroundColor White
    Write-Host ""
    Write-Host "3. Clone repository:" -ForegroundColor Yellow
    Write-Host "   cd /var/www" -ForegroundColor White
    Write-Host "   git clone https://github.com/jerrypra0906/greenresource.git" -ForegroundColor White
    Write-Host "   cd greenresource/frontend" -ForegroundColor White
    Write-Host ""
    Write-Host "4. Configure and deploy:" -ForegroundColor Yellow
    Write-Host "   cp .env.example .env" -ForegroundColor White
    Write-Host "   nano .env  # Edit with production settings" -ForegroundColor White
    Write-Host "   docker-compose up -d --build" -ForegroundColor White
    Write-Host "   docker-compose exec app php artisan migrate" -ForegroundColor White
    Write-Host "   docker-compose exec app php artisan db:seed --class=AdminUserSeeder" -ForegroundColor White
    Write-Host "   docker-compose exec app php artisan storage:link" -ForegroundColor White
    Write-Host ""
}

