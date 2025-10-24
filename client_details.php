<?php
/**
 * HIFIS Client Details
 * View detailed information about a specific client
 */
require_once 'includes/db_config.php';
require_once 'includes/header.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: clients.php');
    exit;
}

$conn = getDBConnection();
$client_id = intval($_GET['id']);

// Get client details using prepared statement
$stmt = $conn->prepare("SELECT * FROM clients WHERE client_id = ?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: clients.php');
    exit;
}

$client = $result->fetch_assoc();

// Get client services
$stmt2 = $conn->prepare("SELECT cs.*, s.service_name, s.service_category FROM client_services cs JOIN services s ON cs.service_id = s.service_id WHERE cs.client_id = ? ORDER BY cs.service_date DESC");
$stmt2->bind_param("i", $client_id);
$stmt2->execute();
$services_result = $stmt2->get_result();

// Get case notes
$stmt3 = $conn->prepare("SELECT * FROM case_notes WHERE client_id = ? ORDER BY note_date DESC");
$stmt3->bind_param("i", $client_id);
$stmt3->execute();
$notes_result = $stmt3->get_result();
?>

<div class="container">
    <div class="page-header">
        <h2>Client Details</h2>
        <div>
            <a href="edit_client.php?id=<?php echo $client_id; ?>" class="btn btn-primary">Edit Client</a>
            <a href="clients.php" class="btn btn-secondary">Back to Clients</a>
        </div>
    </div>
    
    <div class="form-container">
        <h3>Personal Information</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Client ID:</label>
                <p><?php echo htmlspecialchars($client['client_id']); ?></p>
            </div>
            <div class="form-group">
                <label>Full Name:</label>
                <p><?php echo htmlspecialchars($client['first_name'] . ' ' . $client['last_name']); ?></p>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>Date of Birth:</label>
                <p><?php echo htmlspecialchars($client['date_of_birth'] ?? 'Not provided'); ?></p>
            </div>
            <div class="form-group">
                <label>Gender:</label>
                <p><?php echo htmlspecialchars($client['gender'] ?? 'Not provided'); ?></p>
            </div>
        </div>
        
        <h3>Contact Information</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Phone:</label>
                <p><?php echo htmlspecialchars($client['phone'] ?? 'Not provided'); ?></p>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <p><?php echo htmlspecialchars($client['email'] ?? 'Not provided'); ?></p>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>Emergency Contact:</label>
                <p><?php echo htmlspecialchars($client['emergency_contact_name'] ?? 'Not provided'); ?></p>
            </div>
            <div class="form-group">
                <label>Emergency Phone:</label>
                <p><?php echo htmlspecialchars($client['emergency_contact_phone'] ?? 'Not provided'); ?></p>
            </div>
        </div>
        
        <h3>Additional Information</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Veteran Status:</label>
                <p><?php echo htmlspecialchars($client['veteran_status'] ?? 'Unknown'); ?></p>
            </div>
            <div class="form-group">
                <label>Disability Status:</label>
                <p><?php echo htmlspecialchars($client['disability_status'] ?? 'Unknown'); ?></p>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>Household Type:</label>
                <p><?php echo htmlspecialchars($client['household_type'] ?? 'Individual'); ?></p>
            </div>
            <div class="form-group">
                <label>Household Size:</label>
                <p><?php echo htmlspecialchars($client['household_size'] ?? '1'); ?></p>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>Created:</label>
                <p><?php echo htmlspecialchars($client['created_at']); ?></p>
            </div>
            <div class="form-group">
                <label>Last Updated:</label>
                <p><?php echo htmlspecialchars($client['updated_at']); ?></p>
            </div>
        </div>
    </div>
    
    <div class="table-container">
        <h3>Services Received</h3>
        <?php if ($services_result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Service Name</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($service = $services_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($service['service_name']); ?></td>
                            <td><?php echo htmlspecialchars($service['service_category']); ?></td>
                            <td><?php echo htmlspecialchars($service['service_date']); ?></td>
                            <td><?php echo htmlspecialchars($service['service_status']); ?></td>
                            <td><?php echo htmlspecialchars($service['notes'] ?? 'N/A'); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No services recorded for this client.</p>
        <?php endif; ?>
    </div>
    
    <div class="table-container">
        <h3>Case Notes</h3>
        <?php if ($notes_result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Content</th>
                        <th>Created By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($note = $notes_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($note['note_date']); ?></td>
                            <td><?php echo htmlspecialchars($note['note_type']); ?></td>
                            <td><?php echo htmlspecialchars($note['note_content']); ?></td>
                            <td><?php echo htmlspecialchars($note['created_by'] ?? 'N/A'); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No case notes for this client.</p>
        <?php endif; ?>
    </div>
</div>

<?php
$stmt->close();
$stmt2->close();
$stmt3->close();
closeDBConnection($conn);
require_once 'includes/footer.php';
?>
