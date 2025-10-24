<?php
/**
 * Authentication and Session Management Functions
 */

require_once 'db.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['username']);
}

/**
 * Require login - redirect to login page if not logged in
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

/**
 * Get current user information
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    $db = getDB();
    $userId = $_SESSION['user_id'];
    
    $stmt = $db->prepare("SELECT user_id, username, full_name, email, role, organization FROM users WHERE user_id = ? AND is_active = 1");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
}

/**
 * Check if user has specific role
 */
function hasRole($role) {
    $user = getCurrentUser();
    return $user && $user['role'] === $role;
}

/**
 * Check if user is admin
 */
function isAdmin() {
    return hasRole('admin');
}

/**
 * Login user
 */
function loginUser($username, $password) {
    $db = getDB();
    
    $stmt = $db->prepare("SELECT user_id, username, password, full_name, email, role FROM users WHERE username = ? AND is_active = 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];
            
            // Update last login
            $updateStmt = $db->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
            $updateStmt->bind_param("i", $user['user_id']);
            $updateStmt->execute();
            
            // Log activity
            logActivity($user['user_id'], 'Login', 'users', $user['user_id'], 'User logged in');
            
            return true;
        }
    }
    
    return false;
}

/**
 * Logout user
 */
function logoutUser() {
    if (isLoggedIn()) {
        logActivity($_SESSION['user_id'], 'Logout', 'users', $_SESSION['user_id'], 'User logged out');
    }
    
    session_destroy();
    header('Location: login.php');
    exit();
}

/**
 * Log activity
 */
function logActivity($userId, $action, $tableName = null, $recordId = null, $details = null) {
    $db = getDB();
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
    
    $stmt = $db->prepare("INSERT INTO activity_log (user_id, action, table_name, record_id, details, ip_address) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ississ", $userId, $action, $tableName, $recordId, $details, $ipAddress);
    $stmt->execute();
}

/**
 * Sanitize input
 */
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

/**
 * Generate unique client identifier
 */
function generateClientIdentifier() {
    return 'CL' . date('Y') . '-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
}
?>
