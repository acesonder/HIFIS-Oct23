<?php
$pageTitle = 'Add Service';
require_once 'includes/header.php';

$db = getDB();
$clientId = isset($_GET['client_id']) ? intval($_GET['client_id']) : 0;

// Get client info
$stmt = $db->prepare("SELECT * FROM clients WHERE client_id = ?");
$stmt->bind_param("i", $clientId);
$stmt->execute();
$client = $stmt->get_result()->fetch_assoc();

if (!$client) {
    echo '<div class="alert alert-error">Client not found.</div>';
    require_once 'includes/footer.php';
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serviceId = intval($_POST['service_id']);
    $serviceDate = $_POST['service_date'];
    $notes = sanitize($_POST['notes']);
    
    $stmt = $db->prepare("INSERT INTO client_services (client_id, service_id, service_date, notes, provided_by) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iissi", $clientId, $serviceId, $serviceDate, $notes, $_SESSION['user_id']);
    
    if ($stmt->execute()) {
        logActivity($_SESSION['user_id'], 'Add Service', 'client_services', $stmt->insert_id, "Added service for client $clientId");
        header("Location: client_details.php?id=$clientId");
        exit();
    } else {
        $error = "Error adding service record.";
    }
}

// Get available services
$services = $db->query("SELECT * FROM services WHERE is_active = 1 ORDER BY service_name");
?>

<div style="margin-bottom: 20px;">
    <a href="client_details.php?id=<?php echo $clientId; ?>" class="btn btn-secondary">← Back to Client</a>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-error"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2>Add Service Record</h2>
    </div>
    
    <div style="background-color: #f8f9fa; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
        <strong>Client:</strong> <?php echo htmlspecialchars($client['first_name'] . ' ' . $client['last_name']); ?>
        (<?php echo htmlspecialchars($client['unique_identifier']); ?>)
    </div>
    
    <form method="POST" action="add_service.php?client_id=<?php echo $clientId; ?>">
        <div class="form-group">
            <label for="service_id">Service *</label>
            <select id="service_id" name="service_id" required>
                <option value="">Select a service...</option>
                <?php while ($service = $services->fetch_assoc()): ?>
                    <option value="<?php echo $service['service_id']; ?>">
                        <?php echo htmlspecialchars($service['service_name']); ?> 
                        (<?php echo ucfirst($service['service_type']); ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="service_date">Service Date *</label>
            <input type="date" id="service_date" name="service_date" value="<?php echo date('Y-m-d'); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea id="notes" name="notes" rows="4"></textarea>
        </div>
        
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-success">Add Service Record</button>
            <a href="client_details.php?id=<?php echo $clientId; ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
