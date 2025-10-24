<?php
/**
 * API: Get shelter capacity data
 */
header('Content-Type: application/json');
require_once '../includes/db.php';

$db = getDB();
$result = $db->query("SELECT * FROM shelter_resources ORDER BY resource_name");

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    'success' => true,
    'data' => $data
]);
?>
