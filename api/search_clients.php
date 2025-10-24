<?php
/**
 * API: Search Clients
 * Handles Ajax request to search clients
 */
header('Content-Type: application/json');
require_once '../includes/db_config.php';

$search_term = isset($_GET['q']) ? trim($_GET['q']) : '';

$conn = getDBConnection();

if (empty($search_term)) {
    // Return all clients if no search term
    $sql = "SELECT * FROM clients ORDER BY created_at DESC LIMIT 100";
    $result = $conn->query($sql);
} else {
    // Search in multiple fields using prepared statement
    $search_like = '%' . $conn->real_escape_string($search_term) . '%';
    $stmt = $conn->prepare("SELECT * FROM clients WHERE first_name LIKE ? OR last_name LIKE ? OR phone LIKE ? OR email LIKE ? ORDER BY created_at DESC LIMIT 100");
    $stmt->bind_param("ssss", $search_like, $search_like, $search_like, $search_like);
    $stmt->execute();
    $result = $stmt->get_result();
}

$clients = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $clients[] = $row;
    }
}

echo json_encode([
    'success' => true,
    'clients' => $clients,
    'count' => count($clients)
]);

if (isset($stmt)) {
    $stmt->close();
}
closeDBConnection($conn);
?>
