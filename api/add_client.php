<?php
/**
 * API: Add Client
 * Handles Ajax request to add new client
 */
header('Content-Type: application/json');
require_once '../includes/db_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Validate required fields
if (empty($_POST['first_name']) || empty($_POST['last_name'])) {
    echo json_encode(['success' => false, 'message' => 'First name and last name are required']);
    exit;
}

$conn = getDBConnection();

// Prepare data
$first_name = $conn->real_escape_string(trim($_POST['first_name']));
$last_name = $conn->real_escape_string(trim($_POST['last_name']));
$date_of_birth = !empty($_POST['date_of_birth']) ? $conn->real_escape_string($_POST['date_of_birth']) : null;
$gender = !empty($_POST['gender']) ? $conn->real_escape_string($_POST['gender']) : 'Prefer not to say';
$phone = !empty($_POST['phone']) ? $conn->real_escape_string($_POST['phone']) : null;
$email = !empty($_POST['email']) ? $conn->real_escape_string($_POST['email']) : null;
$emergency_contact_name = !empty($_POST['emergency_contact_name']) ? $conn->real_escape_string($_POST['emergency_contact_name']) : null;
$emergency_contact_phone = !empty($_POST['emergency_contact_phone']) ? $conn->real_escape_string($_POST['emergency_contact_phone']) : null;
$veteran_status = !empty($_POST['veteran_status']) ? $conn->real_escape_string($_POST['veteran_status']) : 'Unknown';
$disability_status = !empty($_POST['disability_status']) ? $conn->real_escape_string($_POST['disability_status']) : 'Unknown';
$household_type = !empty($_POST['household_type']) ? $conn->real_escape_string($_POST['household_type']) : 'Individual';
$household_size = !empty($_POST['household_size']) ? intval($_POST['household_size']) : 1;

// Insert client using prepared statement
$stmt = $conn->prepare("INSERT INTO clients (first_name, last_name, date_of_birth, gender, phone, email, emergency_contact_name, emergency_contact_phone, veteran_status, disability_status, household_type, household_size) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("sssssssssssi", $first_name, $last_name, $date_of_birth, $gender, $phone, $email, $emergency_contact_name, $emergency_contact_phone, $veteran_status, $disability_status, $household_type, $household_size);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true, 
        'message' => 'Client added successfully',
        'client_id' => $conn->insert_id
    ]);
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Failed to add client: ' . $conn->error
    ]);
}

$stmt->close();
closeDBConnection($conn);
?>
