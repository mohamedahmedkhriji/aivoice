<?php
header('Content-Type: application/json');
require_once '../config/database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();

    // Create test user
    $hashedPassword = password_hash('123456', PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE password = ?");
    $stmt->execute(['testuser', 'test@example.com', $hashedPassword, $hashedPassword]);
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Test user created',
        'credentials' => [
            'email' => 'test@example.com',
            'password' => '123456'
        ]
    ]);
    
} catch(Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>