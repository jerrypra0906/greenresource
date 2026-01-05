#!/bin/bash
# Generate Secure Database Password
# This script generates a secure random password for PostgreSQL

PASSWORD=$(openssl rand -base64 32 | tr -d "=+/" | cut -c1-32)

echo "========================================="
echo "Generated Secure Database Password"
echo "========================================="
echo ""
echo "Password: $PASSWORD"
echo ""
echo "IMPORTANT: Save this password securely!"
echo ""
echo "Update these files with this password:"
echo "  1. docker-compose.yml (POSTGRES_PASSWORD and DB_PASSWORD)"
echo "  2. .env file (DB_PASSWORD)"
echo ""

read -p "Save password to secure-password.txt? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "$PASSWORD" > secure-password.txt
    echo "Password saved to secure-password.txt"
    echo "⚠ Remember to delete this file after use!"
fi

