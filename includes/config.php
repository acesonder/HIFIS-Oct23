<?php
/**
 * HIFIS Database Configuration
 * Update these settings according to your database setup
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'hifis_db');

// Site configuration
define('SITE_NAME', 'HIFIS - Homeless Individuals and Families Information System');
define('SITE_URL', 'http://localhost/HIFIS-Oct23');

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS

// Timezone
date_default_timezone_set('America/Toronto');

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
