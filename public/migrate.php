<?php
require_once '../config/database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Create users table
    $sql1 = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) UNIQUE NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    // Create voice_history table
    $sql2 = "CREATE TABLE IF NOT EXISTS voice_history (
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
    )";
    
    $conn->exec($sql1);
    $conn->exec($sql2);
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Database tables created successfully',
        'tables' => ['users', 'voice_history']
    ]);
    
} catch(PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Migration failed: ' . $e->getMessage()
    ]);
}
?>