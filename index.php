<?php
/**
 * HIFIS Dashboard
 * Main landing page with system statistics
 */
require_once 'includes/db_config.php';
require_once 'includes/header.php';

$conn = getDBConnection();

// Get statistics
$total_clients = 0;
$active_services = 0;
$new_clients_month = 0;

// Total clients
$result = $conn->query("SELECT COUNT(*) as count FROM clients");
if ($result) {
    $row = $result->fetch_assoc();
    $total_clients = $row['count'];
}

// Active services
$result = $conn->query("SELECT COUNT(*) as count FROM client_services WHERE service_status = 'Active'");
if ($result) {
    $row = $result->fetch_assoc();
    $active_services = $row['count'];
}

// New clients this month
$result = $conn->query("SELECT COUNT(*) as count FROM clients WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())");
if ($result) {
    $row = $result->fetch_assoc();
    $new_clients_month = $row['count'];
}

closeDBConnection($conn);
?>

<div class="container">
    <div class="page-header">
        <h2>HIFIS Dashboard</h2>
        <p>Homeless Individuals and Families Information System</p>
    </div>
    
    <div class="alert alert-info">
        <strong>Welcome to HIFIS!</strong> This system helps manage client data and services for homeless individuals and families in your community.
    </div>
    
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h3>Total Clients</h3>
            <div class="stat-number" id="totalClients"><?php echo $total_clients; ?></div>
            <p>Registered in the system</p>
            <a href="clients.php" class="btn btn-primary">View Clients</a>
        </div>
        
        <div class="dashboard-card">
            <h3>Active Services</h3>
            <div class="stat-number" id="activeServices"><?php echo $active_services; ?></div>
            <p>Currently being provided</p>
            <a href="services.php" class="btn btn-primary">Manage Services</a>
        </div>
        
        <div class="dashboard-card">
            <h3>New This Month</h3>
            <div class="stat-number" id="newClients"><?php echo $new_clients_month; ?></div>
            <p>Clients registered this month</p>
            <a href="add_client.php" class="btn btn-success">Add New Client</a>
        </div>
        
        <div class="dashboard-card">
            <h3>Quick Actions</h3>
            <div style="margin-top: 1rem;">
                <a href="add_client.php" class="btn btn-success" style="width: 100%; margin-bottom: 0.5rem;">Add Client</a>
                <a href="reports.php" class="btn btn-secondary" style="width: 100%; margin-bottom: 0.5rem;">View Reports</a>
                <a href="services.php" class="btn btn-primary" style="width: 100%;">Manage Services</a>
            </div>
        </div>
    </div>
    
    <div class="form-container">
        <h3>About HIFIS</h3>
        <p>The Homeless Individuals and Families Information System (HIFIS) is a comprehensive data collection and case management tool designed to support homelessness service providers in Canada.</p>
        <br>
        <h4>Key Features:</h4>
        <ul>
            <li>Client information management</li>
            <li>Service tracking and coordination</li>
            <li>Real-time data updates with Ajax</li>
            <li>Case management and notes</li>
            <li>Reporting and data analysis</li>
            <li>Coordinated Access support</li>
        </ul>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
