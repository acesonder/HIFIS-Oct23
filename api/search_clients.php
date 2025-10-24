<?php
/**
 * API: Search clients
 */
header('Content-Type: application/json');
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit();
}

$query = isset($_POST['query']) ? trim($_POST['query']) : '';

if (empty($query)) {
    echo json_encode(['success' => true, 'data' => []]);
    exit();
}

$db = getDB();
$searchTerm = $db->real_escape_string($query);

$result = $db->query("SELECT client_id, unique_identifier, first_name, last_name, date_of_birth, status FROM clients WHERE first_name LIKE '%$searchTerm%' OR last_name LIKE '%$searchTerm%' OR unique_identifier LIKE '%$searchTerm%' ORDER BY created_at DESC LIMIT 20");

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    'success' => true,
    'data' => $data
]);
?>
