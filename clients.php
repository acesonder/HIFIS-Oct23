<?php
/**
 * HIFIS Clients Management
 * List and search all clients
 */
require_once 'includes/db_config.php';
require_once 'includes/header.php';

$conn = getDBConnection();

// Get all clients
$sql = "SELECT * FROM clients ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<div class="container">
    <div class="page-header">
        <h2>Client Management</h2>
        <a href="add_client.php" class="btn btn-success">Add New Client</a>
    </div>
    
    <div class="search-container">
        <div class="search-box">
            <input 
                type="text" 
                id="searchInput" 
                placeholder="Search clients by name, phone, or email..." 
                autocomplete="off"
            >
            <button class="btn btn-primary" onclick="searchClients(document.getElementById('searchInput').value)">Search</button>
        </div>
    </div>
    
    <div class="table-container">
        <h3>All Clients</h3>
        <table id="clientsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Date of Birth</th>
                    <th>Gender</th>
                    <th>Phone</th>
                    <th>Household Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['client_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['date_of_birth'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($row['gender'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($row['phone'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($row['household_type'] ?? 'N/A'); ?></td>
                            <td>
                                <a href="client_details.php?id=<?php echo $row['client_id']; ?>" class="btn btn-primary">View</a>
                                <a href="edit_client.php?id=<?php echo $row['client_id']; ?>" class="btn btn-secondary">Edit</a>
                                <button onclick="deleteClient(<?php echo $row['client_id']; ?>)" class="btn btn-danger">Delete</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">No clients found. <a href="add_client.php">Add your first client</a></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
closeDBConnection($conn);
require_once 'includes/footer.php'; 
?>
