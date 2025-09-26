<?php
header('Content-Type: application/json');
require_once __DIR__ . '/config/database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    if ($conn) {
        echo json_encode(['status' => 'success', 'message' => 'Database connected successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>