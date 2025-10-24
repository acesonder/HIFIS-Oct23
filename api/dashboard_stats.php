<?php
/**
 * API: Dashboard Statistics
 * Provides real-time statistics for the dashboard using Ajax
 */
header('Content-Type: application/json');
require_once '../includes/db_config.php';

$conn = getDBConnection();

$stats = [
    'total_clients' => 0,
    'active_services' => 0,
    'new_clients_month' => 0
];

// Total clients
$result = $conn->query("SELECT COUNT(*) as count FROM clients");
if ($result) {
    $row = $result->fetch_assoc();
    $stats['total_clients'] = $row['count'];
}

// Active services
$result = $conn->query("SELECT COUNT(*) as count FROM client_services WHERE service_status = 'Active'");
if ($result) {
    $row = $result->fetch_assoc();
    $stats['active_services'] = $row['count'];
}

// New clients this month
$result = $conn->query("SELECT COUNT(*) as count FROM clients WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())");
if ($result) {
    $row = $result->fetch_assoc();
    $stats['new_clients_month'] = $row['count'];
}

echo json_encode([
    'success' => true,
    'stats' => $stats
]);

closeDBConnection($conn);
?>
