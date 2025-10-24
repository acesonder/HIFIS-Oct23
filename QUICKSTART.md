# HIFIS Quick Start Guide

Get your HIFIS system running in under 5 minutes!

## Prerequisites Checklist
- [ ] PHP 7.4 or higher installed
- [ ] MySQL 5.7 or higher installed
- [ ] Web server (Apache/Nginx) running
- [ ] MySQL credentials ready

## Installation Steps

### Step 1: Get the Code (30 seconds)
```bash
git clone https://github.com/acesonder/HIFIS-Oct23.git
cd HIFIS-Oct23
```

### Step 2: Create Database (1 minute)
```bash
# Login to MySQL
mysql -u root -p

# In MySQL prompt:
CREATE DATABASE hifis_db;
USE hifis_db;
SOURCE database/schema.sql;
SOURCE database/sample_data.sql;  # Optional: adds test data
EXIT;
```

### Step 3: Configure Connection (1 minute)
```bash
# Copy the example config
cp includes/db_config.php.example includes/db_config.php

# Edit the file (use nano, vim, or any editor)
nano includes/db_config.php
```

Update these lines:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_mysql_username');
define('DB_PASS', 'your_mysql_password');
define('DB_NAME', 'hifis_db');
```

Save and exit.

### Step 4: Start Using HIFIS! (30 seconds)
Open your browser and go to:
```
http://localhost/HIFIS-Oct23/install.php
```

If you see a green success message, click "Go to Dashboard"!

## Common URLs
- **Installation Check**: `http://localhost/HIFIS-Oct23/install.php`
- **Dashboard**: `http://localhost/HIFIS-Oct23/index.php`
- **Clients**: `http://localhost/HIFIS-Oct23/clients.php`
- **Add Client**: `http://localhost/HIFIS-Oct23/add_client.php`
- **Services**: `http://localhost/HIFIS-Oct23/services.php`
- **Reports**: `http://localhost/HIFIS-Oct23/reports.php`

## Quick Tasks

### Add Your First Client
1. Go to Dashboard → Click "Add New Client"
2. Fill in First Name and Last Name (required)
3. Add any other details you want
4. Click "Save Client"
5. You'll be redirected to the clients list

### Search for a Client
1. Go to "Clients" page
2. Type in the search box
3. Results appear automatically as you type!

### View Reports
1. Click "Reports" in the navigation
2. See demographics, services, and trends
3. Data updates as you add more clients

## Troubleshooting

### "Connection failed" error
- ✅ Check MySQL is running: `sudo service mysql status`
- ✅ Verify credentials in `includes/db_config.php`
- ✅ Make sure database exists: `SHOW DATABASES;` in MySQL

### Page shows PHP code instead of running
- ✅ Ensure PHP is enabled in your web server
- ✅ Check file extension is `.php` not `.html`
- ✅ Restart web server: `sudo service apache2 restart`

### Can't find the page
- ✅ Check web server document root points to project directory
- ✅ Verify URL matches your server configuration
- ✅ Check file permissions: `chmod 755` on directories

### Search not working
- ✅ Open browser console (F12) to check for JavaScript errors
- ✅ Verify `assets/js/main.js` is loaded
- ✅ Check that you have sample data or real clients in database

### CSS not loading (page looks plain)
- ✅ Check `assets/css/style.css` exists
- ✅ Verify path in `includes/header.php`
- ✅ Clear browser cache (Ctrl+Shift+R)

## Testing with Sample Data

The sample data includes:
- 10 diverse clients (various demographics)
- Active service assignments
- Case notes and histories

To explore features with sample data:
1. Go to Clients page - see the list
2. Click "View" on any client - see full details
3. Try searching for "Smith" or "Maria"
4. Check Reports page for populated statistics

## Next Steps

### Customize the System
- Edit `database/schema.sql` to add custom fields
- Modify `assets/css/style.css` for your branding
- Add your organization logo to `includes/header.php`

### Add Real Data
- Start adding actual clients through the web interface
- Import existing data (write custom SQL scripts)
- Train staff on the system

### Secure for Production
- [ ] Set strong MySQL passwords
- [ ] Restrict file permissions
- [ ] Enable HTTPS
- [ ] Add user authentication
- [ ] Regular backups
- [ ] Update PHP/MySQL to latest versions

## Getting Help

### Documentation
- Read `README.md` for comprehensive documentation
- Check `FEATURES.md` for technical details
- View inline comments in code files

### Common Questions

**Q: Can I delete sample data?**
A: Yes! Just run: `TRUNCATE TABLE case_notes; TRUNCATE TABLE client_services; TRUNCATE TABLE clients;`

**Q: How do I backup data?**
A: `mysqldump -u root -p hifis_db > backup.sql`

**Q: Can I change the database name?**
A: Yes, update both the database and `includes/db_config.php`

**Q: Is this production-ready?**
A: It's a solid foundation but needs authentication and enhanced security for production use.

## Success Checklist
- [ ] Database created and schema imported
- [ ] Configuration file updated with credentials
- [ ] Install.php shows green success message
- [ ] Dashboard loads and shows statistics
- [ ] Can add a new client
- [ ] Search functionality works
- [ ] Reports page displays data

## Congratulations! 🎉

Your HIFIS system is ready to help manage homelessness services in your community!

---

**Need more help?** Check the README.md or create an issue on GitHub.
