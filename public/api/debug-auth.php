<?php
header('Content-Type: application/json');
require_once '../config/database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Check if users table exists
    $stmt = $conn->query("SHOW TABLES LIKE 'users'");
    $tableExists = $stmt->rowCount() > 0;
    
    // Get all users
    if ($tableExists) {
        $stmt = $conn->query("SELECT id, username, email FROM users");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $users = [];
    }
    
    echo json_encode([
        'table_exists' => $tableExists,
        'users' => $users,
        'database' => 'voice'
    ]);
    
} catch(Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>