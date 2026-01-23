#!/usr/bin/env python3
"""
Favicon Generator Script for Green Resources
Generates all required favicon sizes from the source icon image.
Requires: pip install Pillow
"""

import os
from PIL import Image

# Source image path
SOURCE_IMAGE = "public/assets/icon green resources.png"
OUTPUT_DIR = "public"

# Required favicon sizes
FAVICON_SIZES = {
    "favicon-16x16.png": 16,
    "favicon-32x32.png": 32,
    "apple-touch-icon.png": 180,
    "android-chrome-192x192.png": 192,
    "android-chrome-512x512.png": 512,
    "mstile-150x150.png": 150,
}

def generate_favicons():
    """Generate all favicon sizes from the source image."""
    
    # Check if source image exists
    if not os.path.exists(SOURCE_IMAGE):
        print(f"Error: Source image not found at {SOURCE_IMAGE}")
        print("Please ensure the icon file exists in the assets directory.")
        return False
    
    # Open source image
    try:
        img = Image.open(SOURCE_IMAGE)
        print(f"Source image loaded: {img.size[0]}x{img.size[1]}")
    except Exception as e:
        print(f"Error opening source image: {e}")
        return False
    
    # Convert to RGB if necessary (for PNG with transparency)
    if img.mode in ('RGBA', 'LA', 'P'):
        # Create a white background
        background = Image.new('RGB', img.size, (255, 255, 255))
        if img.mode == 'P':
            img = img.convert('RGBA')
        background.paste(img, mask=img.split()[-1] if img.mode == 'RGBA' else None)
        img = background
    elif img.mode != 'RGB':
        img = img.convert('RGB')
    
    # Generate each favicon size
    print("\nGenerating favicon files...")
    for filename, size in FAVICON_SIZES.items():
        output_path = os.path.join(OUTPUT_DIR, filename)
        
        # Resize image with high-quality resampling
        resized = img.resize((size, size), Image.Resampling.LANCZOS)
        
        # Save as PNG
        resized.save(output_path, "PNG", optimize=True)
        print(f"✓ Created {output_path} ({size}x{size})")
    
    print("\n✓ All favicon files generated successfully!")
    print("\nNote: You still need to create favicon.ico manually.")
    print("You can use an online tool like https://icoconvert.com/")
    print("or use ImageMagick: convert favicon-32x32.png favicon.ico")
    
    return True

if __name__ == "__main__":
    print("Green Resources Favicon Generator")
    print("=" * 40)
    generate_favicons()
