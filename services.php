<?php
/**
 * HIFIS Services Management
 * Manage services and client service assignments
 */
require_once 'includes/db_config.php';
require_once 'includes/header.php';

$conn = getDBConnection();

// Get all services
$services_sql = "SELECT * FROM services ORDER BY service_category, service_name";
$services_result = $conn->query($services_sql);

// Get client services with client names
$client_services_sql = "SELECT cs.*, c.first_name, c.last_name, s.service_name, s.service_category 
                        FROM client_services cs 
                        JOIN clients c ON cs.client_id = c.client_id 
                        JOIN services s ON cs.service_id = s.service_id 
                        ORDER BY cs.service_date DESC 
                        LIMIT 50";
$client_services_result = $conn->query($client_services_sql);
?>

<div class="container">
    <div class="page-header">
        <h2>Services Management</h2>
    </div>
    
    <div class="table-container">
        <h3>Available Services</h3>
        <table>
            <thead>
                <tr>
                    <th>Service ID</th>
                    <th>Service Name</th>
                    <th>Category</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($services_result && $services_result->num_rows > 0): ?>
                    <?php while ($service = $services_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($service['service_id']); ?></td>
                            <td><?php echo htmlspecialchars($service['service_name']); ?></td>
                            <td><?php echo htmlspecialchars($service['service_category']); ?></td>
                            <td><?php echo htmlspecialchars($service['service_description'] ?? 'N/A'); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No services found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <div class="table-container">
        <h3>Recent Client Services (Last 50)</h3>
        <table>
            <thead>
                <tr>
                    <th>Client Name</th>
                    <th>Service</th>
                    <th>Category</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($client_services_result && $client_services_result->num_rows > 0): ?>
                    <?php while ($cs = $client_services_result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <a href="client_details.php?id=<?php echo $cs['client_id']; ?>">
                                    <?php echo htmlspecialchars($cs['first_name'] . ' ' . $cs['last_name']); ?>
                                </a>
                            </td>
                            <td><?php echo htmlspecialchars($cs['service_name']); ?></td>
                            <td><?php echo htmlspecialchars($cs['service_category']); ?></td>
                            <td><?php echo htmlspecialchars($cs['service_date']); ?></td>
                            <td>
                                <span class="<?php echo strtolower($cs['service_status']); ?>">
                                    <?php echo htmlspecialchars($cs['service_status']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($cs['notes'] ?? 'N/A'); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No client services recorded</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <div class="form-container">
        <h3>Service Categories</h3>
        <p>HIFIS supports the following service categories:</p>
        <ul>
            <li><strong>Emergency Shelter</strong> - Immediate shelter for homeless individuals</li>
            <li><strong>Transitional Housing</strong> - Temporary housing with supportive services</li>
            <li><strong>Permanent Housing</strong> - Long-term housing solutions</li>
            <li><strong>Food Services</strong> - Food banks and meal programs</li>
            <li><strong>Health Services</strong> - Medical and mental health support</li>
            <li><strong>Employment Services</strong> - Job training and placement</li>
            <li><strong>Other</strong> - Additional support services</li>
        </ul>
    </div>
</div>

<?php
closeDBConnection($conn);
require_once 'includes/footer.php';
?>
