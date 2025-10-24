<?php
/**
 * API: Update client status
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
$status = isset($_POST['status']) ? $_POST['status'] : '';

if ($clientId <= 0 || !in_array($status, ['active', 'inactive', 'exited'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid parameters']);
    exit();
}

$db = getDB();
$stmt = $db->prepare("UPDATE clients SET status = ? WHERE client_id = ?");
$stmt->bind_param("si", $status, $clientId);

if ($stmt->execute()) {
    logActivity($_SESSION['user_id'], 'Update Client Status', 'clients', $clientId, "Status changed to: $status");
    echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to update status']);
}
?>
