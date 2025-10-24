<?php
/**
 * API: Delete Client
 * Handles Ajax request to delete a client
 */
header('Content-Type: application/json');
require_once '../includes/db_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

if (empty($_POST['client_id'])) {
    echo json_encode(['success' => false, 'message' => 'Client ID is required']);
    exit;
}

$conn = getDBConnection();
$client_id = intval($_POST['client_id']);

// Use prepared statement for security
$stmt = $conn->prepare("DELETE FROM clients WHERE client_id = ?");
$stmt->bind_param("i", $client_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode([
            'success' => true, 
            'message' => 'Client deleted successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'Client not found'
        ]);
    }
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Failed to delete client: ' . $conn->error
    ]);
}

$stmt->close();
closeDBConnection($conn);
?>
