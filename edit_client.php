<?php
/**
 * HIFIS Edit Client
 * Form to edit existing client information
 */
require_once 'includes/db_config.php';
require_once 'includes/header.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: clients.php');
    exit;
}

$conn = getDBConnection();
$client_id = intval($_GET['id']);

// Get client details
$stmt = $conn->prepare("SELECT * FROM clients WHERE client_id = ?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: clients.php');
    exit;
}

$client = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $conn->real_escape_string(trim($_POST['first_name']));
    $last_name = $conn->real_escape_string(trim($_POST['last_name']));
    $date_of_birth = !empty($_POST['date_of_birth']) ? $conn->real_escape_string($_POST['date_of_birth']) : null;
    $gender = $conn->real_escape_string($_POST['gender']);
    $phone = !empty($_POST['phone']) ? $conn->real_escape_string($_POST['phone']) : null;
    $email = !empty($_POST['email']) ? $conn->real_escape_string($_POST['email']) : null;
    $emergency_contact_name = !empty($_POST['emergency_contact_name']) ? $conn->real_escape_string($_POST['emergency_contact_name']) : null;
    $emergency_contact_phone = !empty($_POST['emergency_contact_phone']) ? $conn->real_escape_string($_POST['emergency_contact_phone']) : null;
    $veteran_status = $conn->real_escape_string($_POST['veteran_status']);
    $disability_status = $conn->real_escape_string($_POST['disability_status']);
    $household_type = $conn->real_escape_string($_POST['household_type']);
    $household_size = intval($_POST['household_size']);
    
    $update_stmt = $conn->prepare("UPDATE clients SET first_name=?, last_name=?, date_of_birth=?, gender=?, phone=?, email=?, emergency_contact_name=?, emergency_contact_phone=?, veteran_status=?, disability_status=?, household_type=?, household_size=? WHERE client_id=?");
    $update_stmt->bind_param("sssssssssssii", $first_name, $last_name, $date_of_birth, $gender, $phone, $email, $emergency_contact_name, $emergency_contact_phone, $veteran_status, $disability_status, $household_type, $household_size, $client_id);
    
    if ($update_stmt->execute()) {
        header('Location: client_details.php?id=' . $client_id);
        exit;
    }
    $update_stmt->close();
}
?>

<div class="container">
    <div class="page-header">
        <h2>Edit Client</h2>
        <a href="client_details.php?id=<?php echo $client_id; ?>" class="btn btn-secondary">Back to Details</a>
    </div>
    
    <div class="form-container">
        <form method="POST">
            <h3>Personal Information</h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="first_name">First Name *</label>
                    <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($client['first_name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="last_name">Last Name *</label>
                    <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($client['last_name']); ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="date_of_birth">Date of Birth</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo htmlspecialchars($client['date_of_birth'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender">
                        <option value="Prefer not to say" <?php echo ($client['gender'] == 'Prefer not to say') ? 'selected' : ''; ?>>Prefer not to say</option>
                        <option value="Male" <?php echo ($client['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($client['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo ($client['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
            </div>
            
            <h3>Contact Information</h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($client['phone'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($client['email'] ?? ''); ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="emergency_contact_name">Emergency Contact Name</label>
                    <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="<?php echo htmlspecialchars($client['emergency_contact_name'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="emergency_contact_phone">Emergency Contact Phone</label>
                    <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" value="<?php echo htmlspecialchars($client['emergency_contact_phone'] ?? ''); ?>">
                </div>
            </div>
            
            <h3>Additional Information</h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="veteran_status">Veteran Status</label>
                    <select id="veteran_status" name="veteran_status">
                        <option value="Unknown" <?php echo ($client['veteran_status'] == 'Unknown') ? 'selected' : ''; ?>>Unknown</option>
                        <option value="Yes" <?php echo ($client['veteran_status'] == 'Yes') ? 'selected' : ''; ?>>Yes</option>
                        <option value="No" <?php echo ($client['veteran_status'] == 'No') ? 'selected' : ''; ?>>No</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="disability_status">Disability Status</label>
                    <select id="disability_status" name="disability_status">
                        <option value="Unknown" <?php echo ($client['disability_status'] == 'Unknown') ? 'selected' : ''; ?>>Unknown</option>
                        <option value="Yes" <?php echo ($client['disability_status'] == 'Yes') ? 'selected' : ''; ?>>Yes</option>
                        <option value="No" <?php echo ($client['disability_status'] == 'No') ? 'selected' : ''; ?>>No</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="household_type">Household Type</label>
                    <select id="household_type" name="household_type">
                        <option value="Individual" <?php echo ($client['household_type'] == 'Individual') ? 'selected' : ''; ?>>Individual</option>
                        <option value="Family" <?php echo ($client['household_type'] == 'Family') ? 'selected' : ''; ?>>Family</option>
                        <option value="Youth" <?php echo ($client['household_type'] == 'Youth') ? 'selected' : ''; ?>>Youth</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="household_size">Household Size</label>
                    <input type="number" id="household_size" name="household_size" value="<?php echo htmlspecialchars($client['household_size']); ?>" min="1">
                </div>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-success">Update Client</button>
                <a href="client_details.php?id=<?php echo $client_id; ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php
$stmt->close();
closeDBConnection($conn);
require_once 'includes/footer.php';
?>
