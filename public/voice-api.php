<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

if ($action === 'save_voice') {
    $user_id = $input['user_id'] ?? 0;
    $text_content = trim($input['text_content'] ?? '');
    $language = $input['language'] ?? 'en';
    
    if (empty($text_content) || !$user_id) {
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        exit;
    }
    
    $stmt = $db->prepare("INSERT INTO voice_history (user_id, text_content, language) VALUES (?, ?, ?)");
    
    if ($stmt->execute([$user_id, $text_content, $language])) {
        echo json_encode(['success' => true, 'message' => 'Voice saved successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save voice']);
    }
}

elseif ($action === 'get_history') {
    $user_id = $input['user_id'] ?? 0;
    
    if (!$user_id) {
        echo json_encode(['success' => false, 'message' => 'User ID required']);
        exit;
    }
    
    $stmt = $db->prepare("SELECT * FROM voice_history WHERE user_id = ? ORDER BY created_at DESC LIMIT 20");
    $stmt->execute([$user_id]);
    $history = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['success' => true, 'history' => $history]);
}

elseif ($action === 'delete_voice') {
    $voice_id = $input['voice_id'] ?? 0;
    $user_id = $input['user_id'] ?? 0;
    
    if (!$voice_id || !$user_id) {
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        exit;
    }
    
    $stmt = $db->prepare("DELETE FROM voice_history WHERE id = ? AND user_id = ?");
    
    if ($stmt->execute([$voice_id, $user_id])) {
        echo json_encode(['success' => true, 'message' => 'Voice deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete voice']);
    }
}

else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>