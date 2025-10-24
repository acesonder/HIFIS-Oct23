<?php
$pageTitle = 'Assessments';
require_once 'includes/header.php';

$db = getDB();

// Get all assessments with client info
$assessments = $db->query("SELECT a.*, c.unique_identifier, c.first_name, c.last_name, u.full_name as assessor_name FROM assessments a LEFT JOIN clients c ON a.client_id = c.client_id LEFT JOIN users u ON a.assessed_by = u.user_id ORDER BY a.assessment_date DESC");
?>

<h1>Client Assessments</h1>
<p style="color: #7f8c8d; margin-bottom: 20px;">
    Coordinated Access assessments help prioritize clients based on vulnerability and need.
</p>

<div class="card">
    <div class="card-header">
        <h2>All Assessments</h2>
    </div>
    <table>
        <thead>
            <tr>
                <th>Client ID</th>
                <th>Client Name</th>
                <th>Assessment Date</th>
                <th>Vulnerability Score</th>
                <th>Housing Priority</th>
                <th>Assessed By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($assessments->num_rows > 0): ?>
                <?php while ($assessment = $assessments->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($assessment['unique_identifier']); ?></td>
                    <td><?php echo htmlspecialchars($assessment['first_name'] . ' ' . $assessment['last_name']); ?></td>
                    <td><?php echo date('Y-m-d', strtotime($assessment['assessment_date'])); ?></td>
                    <td><strong><?php echo $assessment['vulnerability_score']; ?></strong></td>
                    <td>
                        <span class="badge badge-<?php 
                            echo $assessment['housing_priority'] === 'critical' ? 'danger' : 
                                ($assessment['housing_priority'] === 'high' ? 'warning' : 
                                ($assessment['housing_priority'] === 'medium' ? 'primary' : 'secondary')); 
                        ?>">
                            <?php echo ucfirst($assessment['housing_priority']); ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($assessment['assessor_name']); ?></td>
                    <td>
                        <a href="client_details.php?id=<?php echo $assessment['client_id']; ?>" class="btn btn-primary">View Client</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center;">No assessments found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="card">
    <div class="card-header">
        <h2>Priority Distribution</h2>
    </div>
    <?php
    $priorityStats = $db->query("SELECT housing_priority, COUNT(*) as count FROM assessments GROUP BY housing_priority ORDER BY FIELD(housing_priority, 'critical', 'high', 'medium', 'low')");
    ?>
    <div class="stats-grid">
        <?php while ($stat = $priorityStats->fetch_assoc()): ?>
            <div class="stat-card <?php 
                echo $stat['housing_priority'] === 'critical' ? 'danger' : 
                    ($stat['housing_priority'] === 'high' ? 'warning' : 'primary'); 
            ?>">
                <div class="stat-number"><?php echo $stat['count']; ?></div>
                <div class="stat-label"><?php echo ucfirst($stat['housing_priority']); ?> Priority</div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
