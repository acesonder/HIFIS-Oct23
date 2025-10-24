<?php
$pageTitle = 'Add Assessment';
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
    $assessmentDate = $_POST['assessment_date'];
    $vulnerabilityScore = intval($_POST['vulnerability_score']);
    $housingPriority = $_POST['housing_priority'];
    $notes = sanitize($_POST['assessment_notes']);
    
    $stmt = $db->prepare("INSERT INTO assessments (client_id, assessment_date, vulnerability_score, housing_priority, assessment_notes, assessed_by) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isissi", $clientId, $assessmentDate, $vulnerabilityScore, $housingPriority, $notes, $_SESSION['user_id']);
    
    if ($stmt->execute()) {
        logActivity($_SESSION['user_id'], 'Add Assessment', 'assessments', $stmt->insert_id, "Added assessment for client $clientId");
        header("Location: client_details.php?id=$clientId");
        exit();
    } else {
        $error = "Error adding assessment.";
    }
}
?>

<div style="margin-bottom: 20px;">
    <a href="client_details.php?id=<?php echo $clientId; ?>" class="btn btn-secondary">← Back to Client</a>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-error"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2>Add Assessment</h2>
    </div>
    
    <div style="background-color: #f8f9fa; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
        <strong>Client:</strong> <?php echo htmlspecialchars($client['first_name'] . ' ' . $client['last_name']); ?>
        (<?php echo htmlspecialchars($client['unique_identifier']); ?>)
    </div>
    
    <form method="POST" action="add_assessment.php?client_id=<?php echo $clientId; ?>">
        <div class="form-group">
            <label for="assessment_date">Assessment Date *</label>
            <input type="date" id="assessment_date" name="assessment_date" value="<?php echo date('Y-m-d'); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="vulnerability_score">Vulnerability Score (0-100) *</label>
            <input type="number" id="vulnerability_score" name="vulnerability_score" min="0" max="100" required>
            <small style="color: #7f8c8d;">Higher scores indicate greater vulnerability</small>
        </div>
        
        <div class="form-group">
            <label for="housing_priority">Housing Priority *</label>
            <select id="housing_priority" name="housing_priority" required>
                <option value="">Select priority...</option>
                <option value="critical">Critical</option>
                <option value="high">High</option>
                <option value="medium">Medium</option>
                <option value="low">Low</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="assessment_notes">Assessment Notes</label>
            <textarea id="assessment_notes" name="assessment_notes" rows="5"></textarea>
        </div>
        
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-success">Add Assessment</button>
            <a href="client_details.php?id=<?php echo $clientId; ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
