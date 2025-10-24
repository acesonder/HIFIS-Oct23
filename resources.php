<?php
$pageTitle = 'Shelter Resources';
require_once 'includes/header.php';

$db = getDB();

// Get all shelter resources
$resources = $db->query("SELECT * FROM shelter_resources ORDER BY resource_name");
?>

<h1>Shelter Resources</h1>

<div class="card">
    <div class="card-header">
        <h2>Real-Time Capacity Overview</h2>
    </div>
    <div id="shelter-capacity">
        <div class="stats-grid">
            <?php while ($resource = $resources->fetch_assoc()): 
                $percentage = ($resource['occupied'] / $resource['total_capacity']) * 100;
                $statusClass = $percentage > 90 ? 'danger' : ($percentage > 70 ? 'warning' : 'success');
            ?>
                <div class="stat-card <?php echo $statusClass; ?>">
                    <h3><?php echo htmlspecialchars($resource['resource_name']); ?></h3>
                    <div style="margin: 10px 0;">
                        <span class="badge badge-primary"><?php echo ucfirst($resource['resource_type']); ?></span>
                    </div>
                    <div class="stat-number"><?php echo $resource['available']; ?></div>
                    <div class="stat-label">
                        Available of <?php echo $resource['total_capacity']; ?> total
                    </div>
                    <div style="margin-top: 10px; color: #7f8c8d;">
                        <strong>Occupied:</strong> <?php echo $resource['occupied']; ?> 
                        (<?php echo round($percentage); ?>%)
                    </div>
                    <small style="color: #7f8c8d; display: block; margin-top: 10px;">
                        📍 <?php echo htmlspecialchars($resource['location']); ?>
                    </small>
                    <div style="margin-top: 10px; font-size: 0.85rem; color: #95a5a6;">
                        Last updated: <?php echo date('M d, Y H:i', strtotime($resource['updated_at'])); ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Capacity Details</h2>
    </div>
    <?php
    $resources->data_seek(0); // Reset pointer
    ?>
    <table>
        <thead>
            <tr>
                <th>Resource Name</th>
                <th>Type</th>
                <th>Location</th>
                <th>Total Capacity</th>
                <th>Occupied</th>
                <th>Available</th>
                <th>Occupancy Rate</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($resource = $resources->fetch_assoc()): 
                $percentage = ($resource['occupied'] / $resource['total_capacity']) * 100;
            ?>
            <tr>
                <td><?php echo htmlspecialchars($resource['resource_name']); ?></td>
                <td><?php echo ucfirst($resource['resource_type']); ?></td>
                <td><?php echo htmlspecialchars($resource['location']); ?></td>
                <td><?php echo $resource['total_capacity']; ?></td>
                <td><?php echo $resource['occupied']; ?></td>
                <td><strong><?php echo $resource['available']; ?></strong></td>
                <td>
                    <span class="badge badge-<?php echo $percentage > 90 ? 'danger' : ($percentage > 70 ? 'warning' : 'success'); ?>">
                        <?php echo round($percentage); ?>%
                    </span>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<div class="alert alert-info">
    <strong>Note:</strong> Shelter capacity data is updated in real-time and refreshes automatically every 30 seconds.
</div>

<?php require_once 'includes/footer.php'; ?>
