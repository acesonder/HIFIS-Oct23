<?php
/**
 * HIFIS Reports
 * Data analysis and reporting
 */
require_once 'includes/db_config.php';
require_once 'includes/header.php';

$conn = getDBConnection();

// Client demographics by gender
$gender_sql = "SELECT gender, COUNT(*) as count FROM clients GROUP BY gender";
$gender_result = $conn->query($gender_sql);

// Household types
$household_sql = "SELECT household_type, COUNT(*) as count FROM clients GROUP BY household_type";
$household_result = $conn->query($household_sql);

// Veteran status
$veteran_sql = "SELECT veteran_status, COUNT(*) as count FROM clients WHERE veteran_status != 'Unknown' GROUP BY veteran_status";
$veteran_result = $conn->query($veteran_sql);

// Services by category
$service_category_sql = "SELECT s.service_category, COUNT(cs.cs_id) as count 
                         FROM client_services cs 
                         JOIN services s ON cs.service_id = s.service_id 
                         GROUP BY s.service_category 
                         ORDER BY count DESC";
$service_category_result = $conn->query($service_category_sql);

// Monthly enrollment trend (last 6 months)
$monthly_sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count 
                FROM clients 
                WHERE created_at >= DATE_SUB(CURRENT_DATE, INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m') 
                ORDER BY month DESC";
$monthly_result = $conn->query($monthly_sql);
?>

<div class="container">
    <div class="page-header">
        <h2>Reports & Analytics</h2>
        <p>Data analysis for informed decision-making</p>
    </div>
    
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h3>Client Demographics - Gender</h3>
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th>Gender</th>
                        <th>Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($gender_result && $gender_result->num_rows > 0): ?>
                        <?php while ($row = $gender_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['gender']); ?></td>
                                <td><?php echo htmlspecialchars($row['count']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="2">No data</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="dashboard-card">
            <h3>Household Types</h3>
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($household_result && $household_result->num_rows > 0): ?>
                        <?php while ($row = $household_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['household_type']); ?></td>
                                <td><?php echo htmlspecialchars($row['count']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="2">No data</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="dashboard-card">
            <h3>Veteran Status</h3>
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($veteran_result && $veteran_result->num_rows > 0): ?>
                        <?php while ($row = $veteran_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['veteran_status']); ?></td>
                                <td><?php echo htmlspecialchars($row['count']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="2">No data</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="dashboard-card">
            <h3>Services by Category</h3>
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($service_category_result && $service_category_result->num_rows > 0): ?>
                        <?php while ($row = $service_category_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['service_category']); ?></td>
                                <td><?php echo htmlspecialchars($row['count']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="2">No data</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="table-container">
        <h3>Monthly Enrollment Trend (Last 6 Months)</h3>
        <table>
            <thead>
                <tr>
                    <th>Month</th>
                    <th>New Clients</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($monthly_result && $monthly_result->num_rows > 0): ?>
                    <?php while ($row = $monthly_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['month']); ?></td>
                            <td><?php echo htmlspecialchars($row['count']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2" class="text-center">No enrollment data available</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <div class="form-container">
        <h3>Report Information</h3>
        <p>These reports provide insights into client demographics, service utilization, and enrollment trends. This data supports:</p>
        <ul>
            <li>Understanding community needs</li>
            <li>Resource allocation and planning</li>
            <li>Policy development and advocacy</li>
            <li>Compliance with reporting requirements</li>
            <li>National data contribution for homelessness research</li>
        </ul>
    </div>
</div>

<?php
closeDBConnection($conn);
require_once 'includes/footer.php';
?>
