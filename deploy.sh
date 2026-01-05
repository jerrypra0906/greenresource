#!/bin/bash

# Green Resources CMS Deployment Script
# Usage: ./deploy.sh [server_ip] [ssh_user]

set -e

SERVER_IP=${1:-"172.28.80.101"}
SSH_USER=${2:-"root"}
DEPLOY_PATH="/var/www/greenresource"

echo "========================================="
echo "Green Resources CMS Deployment Script"
echo "========================================="
echo "Server: $SSH_USER@$SERVER_IP"
echo ""

# Check if git is installed
if ! command -v git &> /dev/null; then
    echo "Error: Git is not installed. Please install Git first."
    exit 1
fi

# Check SSH connection
echo "Checking SSH connection..."
if ! ssh -o ConnectTimeout=5 "$SSH_USER@$SERVER_IP" "echo 'Connection successful'" &> /dev/null; then
    echo "Error: Cannot connect to server. Please check:"
    echo "  1. Server IP and credentials"
    echo "  2. SSH key is set up"
    echo "  3. Firewall allows SSH (port 22)"
    exit 1
fi

echo "✓ SSH connection successful"
echo ""

# Check if Docker is installed on server
echo "Checking Docker installation on server..."
if ssh "$SSH_USER@$SERVER_IP" "command -v docker" &> /dev/null; then
    echo "✓ Docker is installed"
    USE_DOCKER=true
else
    echo "⚠ Docker is not installed. Will use traditional deployment."
    USE_DOCKER=false
fi
echo ""

# Create deployment directory
echo "Creating deployment directory..."
ssh "$SSH_USER@$SERVER_IP" "mkdir -p $DEPLOY_PATH"
echo "✓ Directory created"
echo ""

# Clone or update repository
echo "Cloning/updating repository..."
if ssh "$SSH_USER@$SERVER_IP" "[ -d $DEPLOY_PATH/.git ]"; then
    echo "Repository exists, pulling latest changes..."
    ssh "$SSH_USER@$SERVER_IP" "cd $DEPLOY_PATH && git pull origin main"
else
    echo "Cloning repository..."
    ssh "$SSH_USER@$SERVER_IP" "cd /var/www && git clone https://github.com/jerrypra0906/greenresource.git"
fi
echo "✓ Repository updated"
echo ""

# Deploy based on method
if [ "$USE_DOCKER" = true ]; then
    echo "========================================="
    echo "Deploying with Docker..."
    echo "========================================="
    
    # Check if .env exists
    if ! ssh "$SSH_USER@$SERVER_IP" "[ -f $DEPLOY_PATH/frontend/.env ]"; then
        echo "Creating .env file..."
        ssh "$SSH_USER@$SERVER_IP" "cd $DEPLOY_PATH/frontend && cp .env.example .env"
        echo "⚠ Please edit .env file on server with production settings!"
        echo "   Run: ssh $SSH_USER@$SERVER_IP 'nano $DEPLOY_PATH/frontend/.env'"
    fi
    
    # Build and start containers
    echo "Building Docker containers..."
    ssh "$SSH_USER@$SERVER_IP" "cd $DEPLOY_PATH/frontend && docker-compose up -d --build"
    
    # Run migrations
    echo "Running database migrations..."
    ssh "$SSH_USER@$SERVER_IP" "cd $DEPLOY_PATH/frontend && docker-compose exec -T app php artisan migrate --force"
    
    # Create storage link
    echo "Creating storage link..."
    ssh "$SSH_USER@$SERVER_IP" "cd $DEPLOY_PATH/frontend && docker-compose exec -T app php artisan storage:link"
    
    # Set permissions
    echo "Setting permissions..."
    ssh "$SSH_USER@$SERVER_IP" "cd $DEPLOY_PATH/frontend && docker-compose exec -T app chown -R www-data:www-data storage bootstrap/cache"
    ssh "$SSH_USER@$SERVER_IP" "cd $DEPLOY_PATH/frontend && docker-compose exec -T app chmod -R 775 storage bootstrap/cache"
    
    echo ""
    echo "========================================="
    echo "✓ Docker deployment completed!"
    echo "========================================="
    echo "Application should be available at:"
    echo "  http://$SERVER_IP:8000"
    echo "  Admin: http://$SERVER_IP:8000/admin/login"
    echo ""
    echo "Next steps:"
    echo "  1. Configure .env file with production settings"
    echo "  2. Change default admin password"
    echo "  3. Set up SSL certificate (recommended)"
    
else
    echo "========================================="
    echo "Traditional deployment requires manual setup"
    echo "========================================="
    echo "Please follow the steps in DEPLOYMENT.md"
    echo "for traditional (non-Docker) deployment."
fi

echo ""
echo "Deployment script completed!"

