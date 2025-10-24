# HIFIS Installation Guide

This guide will walk you through the complete installation process for the HIFIS (Homeless Individuals and Families Information System) platform.

## System Requirements

### Server Requirements
- **Web Server**: Apache 2.4+ or Nginx 1.10+
- **PHP**: Version 7.4 or higher
  - Required extensions: mysqli, session, json
- **MySQL**: Version 5.7 or higher (or MariaDB 10.2+)
- **Storage**: Minimum 500MB free space

### Client Requirements
- Modern web browser (Chrome, Firefox, Safari, Edge)
- JavaScript enabled
- Minimum screen resolution: 1024x768

## Step-by-Step Installation

### Step 1: Download the Application

```bash
# Clone from GitHub
git clone https://github.com/acesonder/HIFIS-Oct23.git
cd HIFIS-Oct23
```

Or download and extract the ZIP file to your web server directory.

### Step 2: Set Up MySQL Database

1. **Log in to MySQL**:
```bash
mysql -u root -p
```

2. **Create the database**:
```sql
CREATE DATABASE hifis_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

3. **Create a database user** (optional but recommended):
```sql
CREATE USER 'hifis_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON hifis_db.* TO 'hifis_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

4. **Import the database schema**:
```bash
mysql -u root -p hifis_db < database.sql
```

Or if using the new user:
```bash
mysql -u hifis_user -p hifis_db < database.sql
```

### Step 3: Configure the Application

1. **Edit the configuration file**:
```bash
nano includes/config.php
```
or
```bash
vim includes/config.php
```

2. **Update database credentials**:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'hifis_user');        // Your MySQL username
define('DB_PASS', 'your_secure_password'); // Your MySQL password
define('DB_NAME', 'hifis_db');
```

3. **Update site URL** (if needed):
```php
define('SITE_URL', 'http://your-domain.com/HIFIS-Oct23');
```

### Step 4: Set File Permissions

```bash
# Set proper ownership (adjust 'www-data' to your web server user)
sudo chown -R www-data:www-data /path/to/HIFIS-Oct23

# Set directory permissions
sudo find /path/to/HIFIS-Oct23 -type d -exec chmod 755 {} \;

# Set file permissions
sudo find /path/to/HIFIS-Oct23 -type f -exec chmod 644 {} \;

# Make uploads directory writable
sudo chmod 775 /path/to/HIFIS-Oct23/uploads
```

### Step 5: Configure Web Server

#### For Apache:

Create or edit `.htaccess` in the project root:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /HIFIS-Oct23/
</IfModule>

# Security headers
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-XSS-Protection "1; mode=block"
</IfModule>
```

Enable necessary Apache modules:
```bash
sudo a2enmod rewrite
sudo a2enmod headers
sudo systemctl restart apache2
```

#### For Nginx:

Add to your server block configuration:
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/HIFIS-Oct23;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Security headers
    add_header X-Content-Type-Options "nosniff";
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
}
```

Restart Nginx:
```bash
sudo systemctl restart nginx
```

### Step 6: Test the Installation

1. **Access the application**:
   - Open your web browser
   - Navigate to `http://localhost/HIFIS-Oct23` or your configured URL

2. **Login with default credentials**:
   - Username: `admin`
   - Password: `admin123`

3. **Verify functionality**:
   - Check dashboard loads properly
   - Test adding a new client
   - Verify real-time updates work
   - Check all menu items are accessible

### Step 7: Post-Installation Security

1. **Change default password**:
   - Log in as admin
   - Navigate to user profile (when implemented) or update directly in database:
   ```sql
   UPDATE users SET password = '$2y$10$YOUR_NEW_HASHED_PASSWORD' WHERE username = 'admin';
   ```

2. **Enable production mode**:
   Edit `includes/config.php`:
   ```php
   error_reporting(0);
   ini_set('display_errors', 0);
   ```

3. **Enable HTTPS** (recommended):
   - Obtain SSL certificate (Let's Encrypt, commercial CA, etc.)
   - Configure web server for HTTPS
   - Update `includes/config.php`:
   ```php
   ini_set('session.cookie_secure', 1); // Enable for HTTPS
   define('SITE_URL', 'https://your-domain.com/HIFIS-Oct23');
   ```

4. **Set up backups**:
   ```bash
   # Create backup script
   #!/bin/bash
   DATE=$(date +%Y%m%d_%H%M%S)
   mysqldump -u hifis_user -p hifis_db > backup_$DATE.sql
   ```

## Troubleshooting

### Database Connection Error
- Verify MySQL is running: `sudo systemctl status mysql`
- Check credentials in `includes/config.php`
- Verify database exists: `mysql -u root -p -e "SHOW DATABASES;"`

### Permission Denied Errors
- Check file ownership and permissions
- Ensure web server user has read access
- Verify uploads directory is writable

### Blank Page or PHP Errors
- Check PHP error logs: `/var/log/apache2/error.log` or `/var/log/nginx/error.log`
- Verify PHP extensions are installed: `php -m`
- Check PHP version: `php -v`

### Ajax Not Working
- Check browser console for JavaScript errors
- Verify `api/` directory is accessible
- Check file permissions on API files

## Updating

To update the application:

1. Backup database:
```bash
mysqldump -u hifis_user -p hifis_db > backup_before_update.sql
```

2. Backup files:
```bash
tar -czf hifis_backup.tar.gz /path/to/HIFIS-Oct23
```

3. Pull latest changes or copy new files

4. Run any new database migrations if provided

## Support

For additional help:
- Check DOCUMENTATION.md for usage guide
- Review inline code comments
- Check system activity logs in the database

## Production Checklist

Before going live:
- [ ] Changed default admin password
- [ ] Configured HTTPS/SSL
- [ ] Set production error reporting
- [ ] Configured automated backups
- [ ] Reviewed and set appropriate file permissions
- [ ] Updated site URL in config
- [ ] Tested all functionality
- [ ] Reviewed security settings
- [ ] Set up monitoring/logging
- [ ] Created additional user accounts

---

**Installation complete! Your HIFIS system is ready to help manage homelessness services effectively.**
