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

if ($action === 'register') {
    $username = trim($input['username'] ?? '');
    $email = trim($input['email'] ?? '');
    $password = $input['password'] ?? '';
    
    if (empty($username) || empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit;
    }
    
    if (strlen($password) < 6) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters']);
        exit;
    }
    
    // Check if user exists
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
    $stmt->execute([$email, $username]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'Email or username already exists']);
        exit;
    }
    
    // Create user
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    
    if ($stmt->execute([$username, $email, $hashedPassword])) {
        echo json_encode(['success' => true, 'message' => 'Account created successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Registration failed']);
    }
}

elseif ($action === 'login') {
    $email = trim($input['email'] ?? '');
    $password = $input['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Email and password are required']);
        exit;
    }
    
    $stmt = $db->prepare("SELECT id, username, email, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        unset($user['password']);
        echo json_encode(['success' => true, 'user' => $user]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
    }
}

elseif ($action === 'logout') {
    session_destroy();
    echo json_encode(['success' => true]);
}

else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>