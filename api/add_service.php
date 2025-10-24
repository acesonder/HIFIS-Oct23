<?php
/**
 * API: Add service record
 */
header('Content-Type: application/json');
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit();
}

$clientId = isset($_POST['client_id']) ? intval($_POST['client_id']) : 0;
$serviceId = isset($_POST['service_id']) ? intval($_POST['service_id']) : 0;
$notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';

if ($clientId <= 0 || $serviceId <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid parameters']);
    exit();
}

$db = getDB();
$serviceDate = date('Y-m-d');

$stmt = $db->prepare("INSERT INTO client_services (client_id, service_id, service_date, notes, provided_by) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iissi", $clientId, $serviceId, $serviceDate, $notes, $_SESSION['user_id']);

if ($stmt->execute()) {
    logActivity($_SESSION['user_id'], 'Add Service', 'client_services', $stmt->insert_id, "Added service for client $clientId");
    echo json_encode(['success' => true, 'message' => 'Service added successfully']);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to add service']);
}
?>
