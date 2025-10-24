<?php
$pageTitle = 'Services';
require_once 'includes/header.php';

$db = getDB();

// Get all services
$services = $db->query("SELECT s.*, COUNT(cs.client_service_id) as usage_count FROM services s LEFT JOIN client_services cs ON s.service_id = cs.service_id GROUP BY s.service_id ORDER BY s.service_name");
?>

<h1>Services Management</h1>

<div class="card">
    <div class="card-header">
        <h2>Available Services</h2>
    </div>
    <table>
        <thead>
            <tr>
                <th>Service Name</th>
                <th>Type</th>
                <th>Description</th>
                <th>Usage Count</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($service = $services->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($service['service_name']); ?></td>
                <td>
                    <span class="badge badge-primary">
                        <?php echo ucfirst($service['service_type']); ?>
                    </span>
                </td>
                <td><?php echo htmlspecialchars($service['description'] ?? ''); ?></td>
                <td><?php echo $service['usage_count']; ?></td>
                <td>
                    <span class="badge badge-<?php echo $service['is_active'] ? 'success' : 'secondary'; ?>">
                        <?php echo $service['is_active'] ? 'Active' : 'Inactive'; ?>
                    </span>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<div class="card">
    <div class="card-header">
        <h2>Service Statistics</h2>
    </div>
    <?php
    $serviceStats = $db->query("SELECT s.service_name, s.service_type, COUNT(cs.client_service_id) as count FROM services s LEFT JOIN client_services cs ON s.service_id = cs.service_id WHERE cs.service_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) GROUP BY s.service_id ORDER BY count DESC");
    ?>
    <h3>Services Provided (Last 30 Days)</h3>
    <table>
        <thead>
            <tr>
                <th>Service</th>
                <th>Type</th>
                <th>Count</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($stat = $serviceStats->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($stat['service_name']); ?></td>
                <td><?php echo ucfirst($stat['service_type']); ?></td>
                <td><strong><?php echo $stat['count']; ?></strong></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>
