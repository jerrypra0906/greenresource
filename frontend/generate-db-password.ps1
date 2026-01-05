# Generate Secure Database Password
# This script generates a secure random password for PostgreSQL

$password = -join ((48..57) + (65..90) + (97..122) + (33..47) | Get-Random -Count 32 | ForEach-Object {[char]$_})

Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "Generated Secure Database Password" -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Password: $password" -ForegroundColor Green
Write-Host ""
Write-Host "IMPORTANT: Save this password securely!" -ForegroundColor Yellow
Write-Host ""
Write-Host "Update these files with this password:" -ForegroundColor Yellow
Write-Host "  1. docker-compose.yml (POSTGRES_PASSWORD and DB_PASSWORD)" -ForegroundColor White
Write-Host "  2. .env file (DB_PASSWORD)" -ForegroundColor White
Write-Host ""

# Optionally save to a secure file
$save = Read-Host "Save password to secure-password.txt? (y/n)"
if ($save -eq 'y' -or $save -eq 'Y') {
    $password | Out-File -FilePath "secure-password.txt" -Encoding utf8
    Write-Host "Password saved to secure-password.txt" -ForegroundColor Green
    Write-Host "⚠ Remember to delete this file after use!" -ForegroundColor Yellow
}

