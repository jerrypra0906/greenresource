# Laravel Setup Guide for Windows

## Step 1: Verify PHP Installation

After installing your local development environment (Laragon/XAMPP/WAMP), you need to ensure PHP and Composer are accessible.

### For Laragon:
1. Open Laragon
2. Right-click the Laragon icon in the system tray
3. Go to **Menu** → **Tools** → **Add to PATH** (or similar)
4. Restart your terminal/PowerShell

### For XAMPP:
1. Add to PATH manually:
   - Open System Properties → Environment Variables
   - Add `C:\xampp\php` to your PATH
   - Restart terminal

### For WAMP:
1. Add to PATH manually:
   - Open System Properties → Environment Variables
   - Add `C:\wamp64\bin\php\php8.x.x` to your PATH (replace x.x with your version)
   - Restart terminal

## Step 2: Install Composer

If Composer isn't installed with your environment:

1. Download Composer from: https://getcomposer.org/download/
2. Run the installer (it will detect PHP automatically)
3. Or download `composer.phar` and place it in your project root

## Step 3: Verify Installation

Open a **new** PowerShell/Command Prompt window and run:

```bash
php --version
composer --version
```

Both commands should work. If they don't, restart your terminal after adding to PATH.

## Step 4: Install Laravel Dependencies

Once PHP and Composer are working:

```bash
cd frontend
composer install
```

## Alternative: Using Full Paths

If you can't add to PATH, you can use full paths:

### For Laragon:
```bash
C:\laragon\bin\php\php-8.x.x\php.exe C:\laragon\bin\composer\composer.bat install
```

### For XAMPP:
```bash
C:\xampp\php\php.exe -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
C:\xampp\php\php.exe composer-setup.php
C:\xampp\php\php.exe composer.phar install
```

## Troubleshooting

- **"php is not recognized"**: PHP is not in PATH. Add it to system PATH and restart terminal.
- **"composer is not recognized"**: Composer is not installed or not in PATH.
- **Permission errors**: Run PowerShell as Administrator.

