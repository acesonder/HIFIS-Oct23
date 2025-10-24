<?php
$pageTitle = 'Users';
require_once 'includes/header.php';

// Check if user is admin
if (!isAdmin()) {
    echo '<div class="alert alert-error">Access denied. Admin privileges required.</div>';
    require_once 'includes/footer.php';
    exit();
}

$db = getDB();

// Get all users
$users = $db->query("SELECT user_id, username, full_name, email, role, organization, is_active, last_login, created_at FROM users ORDER BY created_at DESC");
?>

<h1>User Management</h1>

<div class="card">
    <div class="card-header">
        <h2>All Users</h2>
    </div>
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Organization</th>
                <th>Last Login</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = $users->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td>
                    <span class="badge badge-<?php echo $user['role'] === 'admin' ? 'danger' : 'primary'; ?>">
                        <?php echo ucfirst(str_replace('_', ' ', $user['role'])); ?>
                    </span>
                </td>
                <td><?php echo htmlspecialchars($user['organization'] ?? 'N/A'); ?></td>
                <td><?php echo $user['last_login'] ? date('M d, Y H:i', strtotime($user['last_login'])) : 'Never'; ?></td>
                <td>
                    <span class="badge badge-<?php echo $user['is_active'] ? 'success' : 'secondary'; ?>">
                        <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
                    </span>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<div class="alert alert-info">
    <strong>Note:</strong> User management features allow administrators to oversee access to the HIFIS system. 
    All user activities are logged for security and audit purposes.
</div>

<?php require_once 'includes/footer.php'; ?>
