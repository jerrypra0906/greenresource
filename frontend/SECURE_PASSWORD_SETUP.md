# Secure Database Password Setup

## Generate a Secure Password

**IMPORTANT:** The password generator now creates passwords WITHOUT `$` characters, as Docker Compose interprets `$` as a variable.

### On Windows (PowerShell):
```powershell
.\generate-db-password.ps1
```

### On Linux/Mac (Bash):
```bash
chmod +x generate-db-password.sh
./generate-db-password.sh
```

### Manual Generation:
You can also use online tools or generate manually:
- Use at least 32 characters
- Include uppercase, lowercase, numbers, and special characters
- **DO NOT use `$` character** - Docker Compose will interpret it as a variable
- Avoid dictionary words or personal information

## Update Configuration Files

After generating a secure password, update it in these locations:

### 1. Create .env.docker file (Recommended)

Docker Compose automatically reads from `.env.docker` file:

```bash
cd ~/greenresource/frontend
cp env.docker.example .env.docker
nano .env.docker
```

Update the password:
```env
DB_PASSWORD=your_secure_password_here
```

**Important:** Make sure the password does NOT contain `$` character, as Docker Compose will try to interpret it as a variable.

### 2. .env file (for Laravel application)

```env
DB_PASSWORD=your_secure_password_here
```

### Alternative: Set Environment Variable

You can also set it as an environment variable before running docker-compose:

```bash
export DB_PASSWORD="your_secure_password_here"
docker-compose up -d
```

**Note:** If your password contains special characters, make sure to quote it properly.

## Security Best Practices

1. **Never commit passwords to Git**
   - `.env` file is already in `.gitignore`
   - Use `.env.example` or `env.example.template` for documentation

2. **Use different passwords for different environments**
   - Development, staging, and production should have different passwords

3. **Store passwords securely**
   - Use a password manager
   - Don't share passwords via email or chat
   - Use environment variables or secret management tools

4. **Rotate passwords regularly**
   - Change database passwords periodically
   - Update all configuration files when rotating

## Quick Setup for Production

1. Generate password:
   ```bash
   ./generate-db-password.sh
   ```

2. Copy the generated password

3. Update `.env` file:
   ```bash
   nano .env
   # Update DB_PASSWORD=your_generated_password
   ```

4. Update `docker-compose.yml`:
   ```bash
   nano docker-compose.yml
   # Update POSTGRES_PASSWORD and DB_PASSWORD
   ```

5. Restart containers:
   ```bash
   docker-compose down
   docker-compose up -d
   ```

## Example Secure Password Format

A good secure password should:
- Be at least 32 characters long
- Include: uppercase (A-Z), lowercase (a-z), numbers (0-9), special characters (!@#$%^&*)
- Be randomly generated
- Not contain dictionary words or personal information

Example format: `a$FqKybu'U!W3.+JSA6rIho-Clwi0BG)`

