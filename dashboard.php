<?php
$pageTitle = 'Dashboard';
require_once 'includes/header.php';

$db = getDB();
$userId = $_SESSION['user_id'];

// Get statistics
$totalClients = $db->query("SELECT COUNT(*) as count FROM clients")->fetch_assoc()['count'];
$activeClients = $db->query("SELECT COUNT(*) as count FROM clients WHERE status = 'active'")->fetch_assoc()['count'];
$servicesToday = $db->query("SELECT COUNT(*) as count FROM client_services WHERE service_date = CURDATE()")->fetch_assoc()['count'];

// Get shelter capacity
$shelters = $db->query("SELECT * FROM shelter_resources ORDER BY resource_name");

// Calculate total occupancy
$totalCapacity = $db->query("SELECT SUM(total_capacity) as total, SUM(occupied) as occupied FROM shelter_resources")->fetch_assoc();
$occupancyRate = $totalCapacity['total'] > 0 ? round(($totalCapacity['occupied'] / $totalCapacity['total']) * 100) : 0;

// Get recent clients
$recentClients = $db->query("SELECT * FROM clients ORDER BY created_at DESC LIMIT 5");

// Get recent activity
$recentActivity = $db->query("SELECT al.*, u.full_name FROM activity_log al LEFT JOIN users u ON al.user_id = u.user_id ORDER BY al.created_at DESC LIMIT 10");
?>

<h1>Dashboard</h1>

<div class="stats-grid" id="dashboard-stats">
    <div class="stat-card success">
        <div class="stat-number" id="total-clients"><?php echo $totalClients; ?></div>
        <div class="stat-label">Total Clients</div>
    </div>
    
    <div class="stat-card primary">
        <div class="stat-number" id="active-clients"><?php echo $activeClients; ?></div>
        <div class="stat-label">Active Clients</div>
    </div>
    
    <div class="stat-card warning">
        <div class="stat-number" id="services-today"><?php echo $servicesToday; ?></div>
        <div class="stat-label">Services Today</div>
    </div>
    
    <div class="stat-card <?php echo $occupancyRate > 90 ? 'danger' : ($occupancyRate > 70 ? 'warning' : 'success'); ?>">
        <div class="stat-number" id="shelter-occupancy"><?php echo $occupancyRate; ?>%</div>
        <div class="stat-label">Shelter Occupancy</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Shelter Resources</h2>
    </div>
    <div id="shelter-capacity">
        <div class="stats-grid">
            <?php while ($shelter = $shelters->fetch_assoc()): 
                $percentage = ($shelter['occupied'] / $shelter['total_capacity']) * 100;
                $statusClass = $percentage > 90 ? 'danger' : ($percentage > 70 ? 'warning' : 'success');
            ?>
                <div class="stat-card <?php echo $statusClass; ?>">
                    <h3><?php echo htmlspecialchars($shelter['resource_name']); ?></h3>
                    <div class="stat-number"><?php echo $shelter['available']; ?></div>
                    <div class="stat-label">
                        Available (<?php echo $shelter['occupied']; ?>/<?php echo $shelter['total_capacity']; ?>)
                    </div>
                    <small style="color: #7f8c8d;"><?php echo htmlspecialchars($shelter['location']); ?></small>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
    <div class="card">
        <div class="card-header">
            <h2>Recent Clients</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Entry Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($client = $recentClients->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($client['unique_identifier']); ?></td>
                    <td><?php echo htmlspecialchars($client['first_name'] . ' ' . $client['last_name']); ?></td>
                    <td><?php echo date('Y-m-d', strtotime($client['entry_date'])); ?></td>
                    <td>
                        <span class="badge badge-<?php echo $client['status'] === 'active' ? 'success' : 'secondary'; ?>">
                            <?php echo ucfirst($client['status']); ?>
                        </span>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div style="margin-top: 15px;">
            <a href="clients.php" class="btn btn-primary">View All Clients</a>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h2>Recent Activity</h2>
        </div>
        <div style="max-height: 400px; overflow-y: auto;">
            <?php while ($activity = $recentActivity->fetch_assoc()): ?>
                <div style="padding: 10px; border-bottom: 1px solid #eee;">
                    <strong><?php echo htmlspecialchars($activity['full_name'] ?? 'System'); ?></strong>
                    <p style="margin: 5px 0; color: #7f8c8d; font-size: 0.9rem;">
                        <?php echo htmlspecialchars($activity['action']); ?>
                        <?php if ($activity['details']): ?>
                            - <?php echo htmlspecialchars($activity['details']); ?>
                        <?php endif; ?>
                    </p>
                    <small style="color: #95a5a6;">
                        <?php echo date('M d, Y H:i', strtotime($activity['created_at'])); ?>
                    </small>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
