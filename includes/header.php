<?php
require_once __DIR__ . '/functions.php';
requireLogin();
$currentUser = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?><?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1>HIFIS</h1>
                    <p style="font-size: 0.9rem; margin-top: 5px;">Homeless Individuals and Families Information System</p>
                </div>
                <nav>
                    <ul>
                        <li><a href="dashboard.php">Dashboard</a></li>
                        <li><a href="clients.php">Clients</a></li>
                        <li><a href="services.php">Services</a></li>
                        <li><a href="resources.php">Resources</a></li>
                        <li><a href="assessments.php">Assessments</a></li>
                        <li><a href="reports.php">Reports</a></li>
                        <?php if (isAdmin()): ?>
                        <li><a href="users.php">Users</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <div class="user-info">
                    <span>Welcome, <?php echo htmlspecialchars($currentUser['full_name']); ?></span>
                    <a href="logout.php" class="btn btn-secondary" style="padding: 5px 15px;">Logout</a>
                </div>
            </div>
        </div>
    </header>
    <main>
        <div class="container">
