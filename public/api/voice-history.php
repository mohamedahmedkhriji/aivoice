<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    $db = new Database();
    $conn = $db->getConnection();

    if ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $stmt = $conn->prepare("INSERT INTO voice_history (user_id, original_text, source_language, target_language, translated_text, voice_type, pitch, speed) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['user_id'],
            $data['original_text'],
            $data['source_language'] ?? 'en',
            $data['target_language'],
            $data['translated_text'],
            $data['voice_type'] ?? 'female',
            $data['pitch'] ?? 1.0,
            $data['speed'] ?? 1.0
        ]);
        
        echo json_encode(['status' => 'success', 'id' => $conn->lastInsertId()]);
    }
    
    if ($method === 'DELETE') {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'];
        
        $stmt = $conn->prepare("DELETE FROM voice_history WHERE id = ?");
        $stmt->execute([$id]);
        
        echo json_encode(['status' => 'success', 'message' => 'Item deleted successfully']);
    }
    
    if ($method === 'GET') {
        $userId = $_GET['user_id'] ?? null;
        if ($userId) {
            $stmt = $conn->prepare("SELECT * FROM voice_history WHERE user_id = ? ORDER BY created_at DESC LIMIT 20");
            $stmt->execute([$userId]);
        } else {
            $stmt = $conn->query("SELECT * FROM voice_history ORDER BY created_at DESC LIMIT 20");
        }
        $history = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($history);
    }
    
} catch(Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>