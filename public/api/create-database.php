<?php
header('Content-Type: application/json');

try {
    $conn = new PDO("mysql:host=127.0.0.1;port=3306", "root", "moha");
    $conn->exec("set names utf8");
    
    // Create database
    $conn->exec("CREATE DATABASE IF NOT EXISTS voice");
    
    // Use the database
    $conn->exec("USE voice");
    
    // Create users table
    $conn->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) UNIQUE NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    
    // Create voice_history table
    $conn->exec("CREATE TABLE IF NOT EXISTS voice_history (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        original_text TEXT NOT NULL,
        source_language VARCHAR(10) DEFAULT 'en',
        target_language VARCHAR(10) NOT NULL,
        translated_text TEXT,
        voice_type VARCHAR(50) DEFAULT 'female',
        pitch DECIMAL(3,1) DEFAULT 1.0,
        speed DECIMAL(3,1) DEFAULT 1.0,
        audio_file_path VARCHAR(500),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");
    
    // Create test user
    $hashedPassword = password_hash('123456', PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE password = ?");
    $stmt->execute(['testuser', 'test@example.com', $hashedPassword, $hashedPassword]);
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Database and tables created successfully',
        'test_user' => [
            'email' => 'test@example.com',
            'password' => '123456'
        ]
    ]);
    
} catch(Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>