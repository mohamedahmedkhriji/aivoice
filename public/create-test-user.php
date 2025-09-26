<?php
require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Create test user
$username = 'testuser';
$email = 'test@example.com';
$password = 'password123';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $result = $stmt->execute([$username, $email, $hashedPassword]);
    
    if ($result) {
        echo "Test user created successfully!<br>";
        echo "Email: test@example.com<br>";
        echo "Password: password123<br>";
        echo "Hashed password: " . $hashedPassword;
    } else {
        echo "Failed to create test user";
    }
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        echo "Test user already exists!<br>";
        echo "Email: test@example.com<br>";
        echo "Password: password123";
    } else {
        echo "Error: " . $e->getMessage();
    }
}
?>