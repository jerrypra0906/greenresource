# Favicon Generator Script for Green Resources (PowerShell)
# Generates favicon files using ImageMagick
# Requires: ImageMagick installed and in PATH

$sourceImage = "public\assets\icon green resources.png"
$outputDir = "public"

# Check if ImageMagick is available
$magick = Get-Command magick -ErrorAction SilentlyContinue
if (-not $magick) {
    Write-Host "Error: ImageMagick not found. Please install ImageMagick first." -ForegroundColor Red
    Write-Host "Download from: https://imagemagick.org/script/download.php" -ForegroundColor Yellow
    exit 1
}

# Check if source image exists
if (-not (Test-Path $sourceImage)) {
    Write-Host "Error: Source image not found at $sourceImage" -ForegroundColor Red
    exit 1
}

Write-Host "Green Resources Favicon Generator" -ForegroundColor Green
Write-Host "=" * 40

# Generate PNG favicons
$sizes = @{
    "favicon-16x16.png" = 16
    "favicon-32x32.png" = 32
    "apple-touch-icon.png" = 180
    "android-chrome-192x192.png" = 192
    "android-chrome-512x512.png" = 512
    "mstile-150x150.png" = 150
}

Write-Host "`nGenerating favicon files..." -ForegroundColor Cyan

foreach ($file in $sizes.Keys) {
    $size = $sizes[$file]
    $outputPath = Join-Path $outputDir $file
    
    magick $sourceImage -resize "${size}x${size}" -background white -alpha remove $outputPath
    
    if (Test-Path $outputPath) {
        Write-Host "✓ Created $outputPath ($size x $size)" -ForegroundColor Green
    } else {
        Write-Host "✗ Failed to create $outputPath" -ForegroundColor Red
    }
}

# Generate favicon.ico (multi-resolution)
$icoPath = Join-Path $outputDir "favicon.ico"
magick $sourceImage -define icon:auto-resize=16,32,48 -background white -alpha remove $icoPath

if (Test-Path $icoPath) {
    Write-Host "✓ Created $icoPath" -ForegroundColor Green
} else {
    Write-Host "✗ Failed to create $icoPath" -ForegroundColor Red
}

Write-Host "`n✓ Favicon generation complete!" -ForegroundColor Green
Write-Host "`nNote: Clear your browser cache to see the new favicon." -ForegroundColor Yellow
