<?php
$pageTitle = 'Reports';
require_once 'includes/header.php';

$db = getDB();

// Get date range from query params or default to last 30 days
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('-30 days'));
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

// Client statistics
$totalClients = $db->query("SELECT COUNT(*) as count FROM clients WHERE entry_date BETWEEN '$startDate' AND '$endDate'")->fetch_assoc()['count'];
$activeClients = $db->query("SELECT COUNT(*) as count FROM clients WHERE status = 'active'")->fetch_assoc()['count'];
$exitedClients = $db->query("SELECT COUNT(*) as count FROM clients WHERE exit_date BETWEEN '$startDate' AND '$endDate'")->fetch_assoc()['count'];

// Service statistics
$totalServices = $db->query("SELECT COUNT(*) as count FROM client_services WHERE service_date BETWEEN '$startDate' AND '$endDate'")->fetch_assoc()['count'];

// Demographics
$genderStats = $db->query("SELECT gender, COUNT(*) as count FROM clients GROUP BY gender");
$veteranStats = $db->query("SELECT veteran_status, COUNT(*) as count FROM clients GROUP BY veteran_status");
$chronicStats = $db->query("SELECT chronic_homelessness, COUNT(*) as count FROM clients GROUP BY chronic_homelessness");

// Service type breakdown
$serviceTypeStats = $db->query("SELECT s.service_type, COUNT(cs.client_service_id) as count FROM client_services cs LEFT JOIN services s ON cs.service_id = s.service_id WHERE cs.service_date BETWEEN '$startDate' AND '$endDate' GROUP BY s.service_type");
?>

<h1>Reports & Analytics</h1>

<div class="card">
    <div class="card-header">
        <h2>Select Date Range</h2>
    </div>
    <form method="GET" action="reports.php" style="display: flex; gap: 15px; align-items: end;">
        <div class="form-group" style="margin-bottom: 0;">
            <label for="start_date">Start Date</label>
            <input type="date" id="start_date" name="start_date" value="<?php echo $startDate; ?>">
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label for="end_date">End Date</label>
            <input type="date" id="end_date" name="end_date" value="<?php echo $endDate; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Generate Report</button>
    </form>
</div>

<div class="stats-grid">
    <div class="stat-card success">
        <div class="stat-number"><?php echo $totalClients; ?></div>
        <div class="stat-label">New Clients</div>
        <small style="color: #7f8c8d;">in selected period</small>
    </div>
    
    <div class="stat-card primary">
        <div class="stat-number"><?php echo $activeClients; ?></div>
        <div class="stat-label">Active Clients</div>
        <small style="color: #7f8c8d;">currently in system</small>
    </div>
    
    <div class="stat-card warning">
        <div class="stat-number"><?php echo $exitedClients; ?></div>
        <div class="stat-label">Client Exits</div>
        <small style="color: #7f8c8d;">in selected period</small>
    </div>
    
    <div class="stat-card">
        <div class="stat-number"><?php echo $totalServices; ?></div>
        <div class="stat-label">Services Provided</div>
        <small style="color: #7f8c8d;">in selected period</small>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
    <div class="card">
        <div class="card-header">
            <h2>Demographics</h2>
        </div>
        
        <h3>Gender Distribution</h3>
        <table>
            <thead>
                <tr>
                    <th>Gender</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($stat = $genderStats->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $stat['gender'] ?: 'Not specified'; ?></td>
                    <td><strong><?php echo $stat['count']; ?></strong></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <h3 style="margin-top: 20px;">Special Populations</h3>
        <table>
            <tbody>
                <?php while ($stat = $veteranStats->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $stat['veteran_status'] ? 'Veterans' : 'Non-veterans'; ?></td>
                    <td><strong><?php echo $stat['count']; ?></strong></td>
                </tr>
                <?php endwhile; ?>
                <?php while ($stat = $chronicStats->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $stat['chronic_homelessness'] ? 'Chronically Homeless' : 'Not Chronically Homeless'; ?></td>
                    <td><strong><?php echo $stat['count']; ?></strong></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h2>Service Breakdown</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Service Type</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($serviceTypeStats->num_rows > 0): ?>
                    <?php while ($stat = $serviceTypeStats->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo ucfirst($stat['service_type'] ?: 'Other'); ?></td>
                        <td><strong><?php echo $stat['count']; ?></strong></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2" style="text-align: center;">No services in selected period</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="alert alert-info">
    <strong>Data Privacy:</strong> All data shown in reports is anonymized before being exported to national systems. 
    Client personal information is protected and only aggregate statistics are shared.
</div>

<?php require_once 'includes/footer.php'; ?>
