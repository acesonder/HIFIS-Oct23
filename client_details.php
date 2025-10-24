<?php
$pageTitle = 'Client Details';
require_once 'includes/header.php';

$db = getDB();
$clientId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get client details
$stmt = $db->prepare("SELECT * FROM clients WHERE client_id = ?");
$stmt->bind_param("i", $clientId);
$stmt->execute();
$client = $stmt->get_result()->fetch_assoc();

if (!$client) {
    echo '<div class="alert alert-error">Client not found.</div>';
    require_once 'includes/footer.php';
    exit();
}

// Get client history
$history = $db->query("SELECT ch.*, u.full_name FROM client_history ch LEFT JOIN users u ON ch.recorded_by = u.user_id WHERE ch.client_id = $clientId ORDER BY ch.recorded_date DESC");

// Get client services
$services = $db->query("SELECT cs.*, s.service_name, s.service_type, u.full_name FROM client_services cs LEFT JOIN services s ON cs.service_id = s.service_id LEFT JOIN users u ON cs.provided_by = u.user_id WHERE cs.client_id = $clientId ORDER BY cs.service_date DESC");

// Get assessments
$assessments = $db->query("SELECT a.*, u.full_name FROM assessments a LEFT JOIN users u ON a.assessed_by = u.user_id WHERE a.client_id = $clientId ORDER BY a.assessment_date DESC");
?>

<div style="margin-bottom: 20px;">
    <a href="clients.php" class="btn btn-secondary">← Back to Clients</a>
</div>

<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Client Details</h2>
        <div>
            <a href="add_service.php?client_id=<?php echo $clientId; ?>" class="btn btn-success">Add Service</a>
            <a href="add_assessment.php?client_id=<?php echo $clientId; ?>" class="btn btn-primary">Add Assessment</a>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div>
            <h3>Personal Information</h3>
            <table style="width: 100%;">
                <tr>
                    <td style="font-weight: bold; width: 40%;">Client ID:</td>
                    <td><?php echo htmlspecialchars($client['unique_identifier']); ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Name:</td>
                    <td><?php echo htmlspecialchars($client['first_name'] . ' ' . $client['last_name']); ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Date of Birth:</td>
                    <td><?php echo $client['date_of_birth'] ? date('Y-m-d', strtotime($client['date_of_birth'])) : 'N/A'; ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Gender:</td>
                    <td><?php echo htmlspecialchars($client['gender']); ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Phone:</td>
                    <td><?php echo htmlspecialchars($client['phone'] ?? 'N/A'); ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Email:</td>
                    <td><?php echo htmlspecialchars($client['email'] ?? 'N/A'); ?></td>
                </tr>
            </table>
        </div>
        
        <div>
            <h3>Service Information</h3>
            <table style="width: 100%;">
                <tr>
                    <td style="font-weight: bold; width: 40%;">Entry Date:</td>
                    <td><?php echo date('Y-m-d', strtotime($client['entry_date'])); ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Exit Date:</td>
                    <td><?php echo $client['exit_date'] ? date('Y-m-d', strtotime($client['exit_date'])) : 'N/A'; ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Status:</td>
                    <td>
                        <span class="badge badge-<?php echo $client['status'] === 'active' ? 'success' : 'secondary'; ?>">
                            <?php echo ucfirst($client['status']); ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Veteran Status:</td>
                    <td><?php echo $client['veteran_status'] ? 'Yes' : 'No'; ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Chronic Homelessness:</td>
                    <td><?php echo $client['chronic_homelessness'] ? 'Yes' : 'No'; ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Created:</td>
                    <td><?php echo date('Y-m-d H:i', strtotime($client['created_at'])); ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
    <div class="card">
        <div class="card-header">
            <h2>Service History</h2>
        </div>
        <?php if ($services->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Service</th>
                        <th>Provided By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($service = $services->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo date('Y-m-d', strtotime($service['service_date'])); ?></td>
                        <td>
                            <?php echo htmlspecialchars($service['service_name']); ?>
                            <?php if ($service['notes']): ?>
                                <br><small style="color: #7f8c8d;"><?php echo htmlspecialchars($service['notes']); ?></small>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($service['full_name']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No service records found.</p>
        <?php endif; ?>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h2>Client History</h2>
        </div>
        <?php if ($history->num_rows > 0): ?>
            <div style="max-height: 400px; overflow-y: auto;">
                <?php while ($record = $history->fetch_assoc()): ?>
                    <div style="padding: 10px; border-bottom: 1px solid #eee;">
                        <strong><?php echo ucfirst($record['history_type']); ?></strong>
                        <p style="margin: 5px 0;"><?php echo htmlspecialchars($record['description']); ?></p>
                        <small style="color: #95a5a6;">
                            <?php echo date('M d, Y', strtotime($record['recorded_date'])); ?> 
                            by <?php echo htmlspecialchars($record['full_name']); ?>
                        </small>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No history records found.</p>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Assessments</h2>
    </div>
    <?php if ($assessments->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Vulnerability Score</th>
                    <th>Housing Priority</th>
                    <th>Notes</th>
                    <th>Assessed By</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($assessment = $assessments->fetch_assoc()): ?>
                <tr>
                    <td><?php echo date('Y-m-d', strtotime($assessment['assessment_date'])); ?></td>
                    <td><?php echo $assessment['vulnerability_score']; ?></td>
                    <td>
                        <span class="badge badge-<?php 
                            echo $assessment['housing_priority'] === 'critical' ? 'danger' : 
                                ($assessment['housing_priority'] === 'high' ? 'warning' : 'primary'); 
                        ?>">
                            <?php echo ucfirst($assessment['housing_priority']); ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($assessment['assessment_notes'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($assessment['full_name']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No assessments found.</p>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
