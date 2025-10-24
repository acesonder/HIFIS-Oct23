<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HIFIS Installation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .install-container {
            background: white;
            border-radius: 10px;
            padding: 3rem;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        .subtitle {
            color: #7f8c8d;
            margin-bottom: 2rem;
        }
        .step {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border-left: 4px solid #667eea;
        }
        .step h3 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        .step code {
            background: #2c3e50;
            color: #fff;
            padding: 0.2rem 0.5rem;
            border-radius: 3px;
            font-size: 0.9rem;
        }
        .step ol {
            margin-left: 1.5rem;
        }
        .step li {
            margin-bottom: 0.5rem;
        }
        .alert {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1.5rem;
        }
        .success {
            background: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        .warning {
            background: #fff3cd;
            border-color: #ffeeba;
            color: #856404;
        }
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 1rem;
            transition: transform 0.3s;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="install-container">
        <h1>🏠 HIFIS Installation</h1>
        <p class="subtitle">Homeless Individuals and Families Information System</p>
        
        <?php
        $config_exists = file_exists('includes/db_config.php');
        $can_connect = false;
        
        if ($config_exists) {
            require_once 'includes/db_config.php';
            try {
                $conn = getDBConnection();
                $can_connect = true;
                closeDBConnection($conn);
            } catch (Exception $e) {
                $can_connect = false;
            }
        }
        
        if ($config_exists && $can_connect) {
            echo '<div class="alert success">';
            echo '<strong>✓ Installation Complete!</strong><br>';
            echo 'Your HIFIS system is ready to use.';
            echo '</div>';
            echo '<a href="index.php" class="btn">Go to Dashboard</a>';
        } else {
        ?>
        
        <div class="alert warning">
            <strong>⚠ Setup Required</strong><br>
            Please follow the steps below to install HIFIS.
        </div>
        
        <div class="step">
            <h3>Step 1: Create Database</h3>
            <ol>
                <li>Open MySQL (phpMyAdmin, MySQL Workbench, or command line)</li>
                <li>Create a new database named <code>hifis_db</code></li>
                <li>Import the schema file: <code>database/schema.sql</code></li>
            </ol>
            <p><strong>MySQL Command:</strong></p>
            <code style="display: block; padding: 1rem; margin-top: 0.5rem;">
                CREATE DATABASE hifis_db;<br>
                USE hifis_db;<br>
                SOURCE /path/to/database/schema.sql;
            </code>
        </div>
        
        <div class="step">
            <h3>Step 2: Configure Database Connection</h3>
            <ol>
                <li>Copy <code>includes/db_config.php.example</code> to <code>includes/db_config.php</code></li>
                <li>Edit <code>includes/db_config.php</code></li>
                <li>Update the database credentials:
                    <ul>
                        <li><code>DB_HOST</code> - usually 'localhost'</li>
                        <li><code>DB_USER</code> - your MySQL username</li>
                        <li><code>DB_PASS</code> - your MySQL password</li>
                        <li><code>DB_NAME</code> - 'hifis_db'</li>
                    </ul>
                </li>
            </ol>
        </div>
        
        <div class="step">
            <h3>Step 3: Verify Installation</h3>
            <ol>
                <li>Refresh this page</li>
                <li>If configuration is correct, you'll see a success message</li>
                <li>Click "Go to Dashboard" to start using HIFIS</li>
            </ol>
        </div>
        
        <div class="alert">
            <strong>ℹ Need Help?</strong><br>
            Check the <code>README.md</code> file for detailed installation instructions and troubleshooting.
        </div>
        
        <?php } ?>
    </div>
</body>
</html>
