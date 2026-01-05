#!/bin/bash

# Quick start script for Docker setup

echo "🚀 Starting Green Resources Docker containers..."

# Build and start containers
docker-compose up -d --build

echo "⏳ Waiting for services to be ready..."
sleep 5

# Run migrations
echo "📦 Running database migrations..."
docker-compose exec -T app php artisan migrate --force

# Seed admin user
echo "👤 Seeding admin user..."
docker-compose exec -T app php artisan db:seed --class=AdminUserSeeder

# Create storage link
echo "🔗 Creating storage link..."
docker-compose exec -T app php artisan storage:link

echo "✅ Setup complete!"
echo ""
echo "🌐 Access the application at: http://localhost:8000"
echo "🔐 Admin panel: http://localhost:8000/admin/login"
echo "   Email: admin@greenresources.com"
echo "   Password: admin123"
echo ""
echo "📝 View logs: docker-compose logs -f"
echo "🛑 Stop containers: docker-compose down"

