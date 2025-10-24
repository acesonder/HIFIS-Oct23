<?php
/**
 * API: Get dashboard statistics
 */
header('Content-Type: application/json');
require_once '../includes/db.php';

$db = getDB();

$totalClients = $db->query("SELECT COUNT(*) as count FROM clients")->fetch_assoc()['count'];
$activeClients = $db->query("SELECT COUNT(*) as count FROM clients WHERE status = 'active'")->fetch_assoc()['count'];
$servicesToday = $db->query("SELECT COUNT(*) as count FROM client_services WHERE service_date = CURDATE()")->fetch_assoc()['count'];

$totalCapacity = $db->query("SELECT SUM(total_capacity) as total, SUM(occupied) as occupied FROM shelter_resources")->fetch_assoc();
$occupancyRate = $totalCapacity['total'] > 0 ? round(($totalCapacity['occupied'] / $totalCapacity['total']) * 100) : 0;

echo json_encode([
    'success' => true,
    'data' => [
        'total_clients' => $totalClients,
        'active_clients' => $activeClients,
        'services_today' => $servicesToday,
        'shelter_occupancy' => $occupancyRate
    ]
]);
?>
