<?php
$pageTitle = 'Clients';
require_once 'includes/header.php';

$db = getDB();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add_client') {
        $uniqueId = generateClientIdentifier();
        $firstName = sanitize($_POST['first_name']);
        $lastName = sanitize($_POST['last_name']);
        $dob = $_POST['date_of_birth'];
        $gender = $_POST['gender'];
        $phone = sanitize($_POST['phone']);
        $email = sanitize($_POST['email']);
        $veteran = isset($_POST['veteran_status']) ? 1 : 0;
        $chronic = isset($_POST['chronic_homelessness']) ? 1 : 0;
        $entryDate = $_POST['entry_date'];
        
        $stmt = $db->prepare("INSERT INTO clients (unique_identifier, first_name, last_name, date_of_birth, gender, phone, email, veteran_status, chronic_homelessness, entry_date, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssi", $uniqueId, $firstName, $lastName, $dob, $gender, $phone, $email, $veteran, $chronic, $entryDate, $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            $clientId = $stmt->insert_id;
            logActivity($_SESSION['user_id'], 'Add Client', 'clients', $clientId, "Added new client: $firstName $lastName");
            
            // Add intake history
            $historyStmt = $db->prepare("INSERT INTO client_history (client_id, history_type, description, recorded_date, recorded_by) VALUES (?, 'intake', ?, ?, ?)");
            $description = "Initial intake completed";
            $historyStmt->bind_param("issi", $clientId, $description, $entryDate, $_SESSION['user_id']);
            $historyStmt->execute();
            
            $success = "Client added successfully! ID: $uniqueId";
        } else {
            $error = "Error adding client.";
        }
    }
}

// Get all clients
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';

$query = "SELECT * FROM clients WHERE 1=1";
if ($search) {
    $query .= " AND (first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR unique_identifier LIKE '%$search%')";
}
if ($status) {
    $query .= " AND status = '$status'";
}
$query .= " ORDER BY created_at DESC";

$clients = $db->query($query);
?>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-error"><?php echo $error; ?></div>
<?php endif; ?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1>Client Management</h1>
    <button onclick="openModal('addClientModal')" class="btn btn-success">+ Add New Client</button>
</div>

<div class="card">
    <div class="card-header">
        <h2>Search Clients</h2>
    </div>
    <form method="GET" action="clients.php" style="display: flex; gap: 10px; align-items: end;">
        <div class="form-group" style="flex: 1; margin-bottom: 0;">
            <label for="search">Search</label>
            <input type="text" id="search" name="search" placeholder="Name or ID..." value="<?php echo htmlspecialchars($search); ?>">
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="">All</option>
                <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>Active</option>
                <option value="inactive" <?php echo $status === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                <option value="exited" <?php echo $status === 'exited' ? 'selected' : ''; ?>>Exited</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h2>All Clients</h2>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Date of Birth</th>
                <th>Gender</th>
                <th>Entry Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($clients->num_rows > 0): ?>
                <?php while ($client = $clients->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($client['unique_identifier']); ?></td>
                    <td><?php echo htmlspecialchars($client['first_name'] . ' ' . $client['last_name']); ?></td>
                    <td><?php echo $client['date_of_birth'] ? date('Y-m-d', strtotime($client['date_of_birth'])) : 'N/A'; ?></td>
                    <td><?php echo htmlspecialchars($client['gender']); ?></td>
                    <td><?php echo date('Y-m-d', strtotime($client['entry_date'])); ?></td>
                    <td>
                        <span class="badge badge-<?php echo $client['status'] === 'active' ? 'success' : ($client['status'] === 'exited' ? 'secondary' : 'warning'); ?>">
                            <?php echo ucfirst($client['status']); ?>
                        </span>
                    </td>
                    <td class="table-actions">
                        <a href="client_details.php?id=<?php echo $client['client_id']; ?>" class="btn btn-primary">View</a>
                        <a href="add_service.php?client_id=<?php echo $client['client_id']; ?>" class="btn btn-success">Add Service</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center;">No clients found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Add Client Modal -->
<div id="addClientModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('addClientModal')">&times;</span>
        <h2>Add New Client</h2>
        <form method="POST" action="clients.php">
            <input type="hidden" name="action" value="add_client">
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label for="first_name">First Name *</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>
                
                <div class="form-group">
                    <label for="last_name">Last Name *</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>
                
                <div class="form-group">
                    <label for="date_of_birth">Date of Birth</label>
                    <input type="date" id="date_of_birth" name="date_of_birth">
                </div>
                
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                        <option value="Prefer not to say">Prefer not to say</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone">
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email">
                </div>
                
                <div class="form-group">
                    <label for="entry_date">Entry Date *</label>
                    <input type="date" id="entry_date" name="entry_date" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="veteran_status" value="1">
                        Veteran Status
                    </label>
                    <br>
                    <label>
                        <input type="checkbox" name="chronic_homelessness" value="1">
                        Chronic Homelessness
                    </label>
                </div>
            </div>
            
            <div style="margin-top: 20px; display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addClientModal')">Cancel</button>
                <button type="submit" class="btn btn-success">Add Client</button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
