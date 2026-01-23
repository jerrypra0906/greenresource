#!/bin/bash
# Favicon Generator Script for Green Resources (Bash)
# Generates favicon files using ImageMagick
# Requires: ImageMagick installed

SOURCE_IMAGE="public/assets/icon green resources.png"
OUTPUT_DIR="public"

# Check if ImageMagick is available
if ! command -v convert &> /dev/null && ! command -v magick &> /dev/null; then
    echo "Error: ImageMagick not found. Please install ImageMagick first."
    echo "On Ubuntu/Debian: sudo apt-get install imagemagick"
    echo "On macOS: brew install imagemagick"
    exit 1
fi

# Use 'magick' if available, otherwise 'convert'
if command -v magick &> /dev/null; then
    MAGICK_CMD="magick"
else
    MAGICK_CMD="convert"
fi

# Check if source image exists
if [ ! -f "$SOURCE_IMAGE" ]; then
    echo "Error: Source image not found at $SOURCE_IMAGE"
    exit 1
fi

echo "Green Resources Favicon Generator"
echo "=================================="

# Generate PNG favicons
declare -A sizes=(
    ["favicon-16x16.png"]=16
    ["favicon-32x32.png"]=32
    ["apple-touch-icon.png"]=180
    ["android-chrome-192x192.png"]=192
    ["android-chrome-512x512.png"]=512
    ["mstile-150x150.png"]=150
)

echo ""
echo "Generating favicon files..."

for file in "${!sizes[@]}"; do
    size=${sizes[$file]}
    output_path="$OUTPUT_DIR/$file"
    
    if [ "$MAGICK_CMD" = "magick" ]; then
        $MAGICK_CMD "$SOURCE_IMAGE" -resize "${size}x${size}" -background white -alpha remove "$output_path"
    else
        $MAGICK_CMD "$SOURCE_IMAGE" -resize "${size}x${size}" -background white -alpha off "$output_path"
    fi
    
    if [ -f "$output_path" ]; then
        echo "✓ Created $output_path ($size x $size)"
    else
        echo "✗ Failed to create $output_path"
    fi
done

# Generate favicon.ico (multi-resolution)
ico_path="$OUTPUT_DIR/favicon.ico"
if [ "$MAGICK_CMD" = "magick" ]; then
    $MAGICK_CMD "$SOURCE_IMAGE" -define icon:auto-resize=16,32,48 -background white -alpha remove "$ico_path"
else
    $MAGICK_CMD "$SOURCE_IMAGE" -define icon:auto-resize=16,32,48 -background white -alpha off "$ico_path"
fi

if [ -f "$ico_path" ]; then
    echo "✓ Created $ico_path"
else
    echo "✗ Failed to create $ico_path"
fi

echo ""
echo "✓ Favicon generation complete!"
echo ""
echo "Note: Clear your browser cache to see the new favicon."
