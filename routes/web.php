<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return redirect('/auth');
});

Route::get('/auth', function () {
    return view('auth');
});

Route::get('/dbconn', function () {
    try {
        DB::connection()->getPdo();
        return response()->json([
            'status' => 'success',
            'message' => 'Database connection successful',
            'database' => config('database.connections.mysql.database'),
            'host' => config('database.connections.mysql.host'),
            'port' => config('database.connections.mysql.port')
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Database connection failed',
            'error' => $e->getMessage()
        ], 500);
    }
});

Route::get('/dbtest', function () {
    return view('dbconn');
});

Route::get('/migrate', function () {
    try {
        DB::statement('CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) UNIQUE NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )');
        
        DB::statement('CREATE TABLE IF NOT EXISTS voice_history (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            original_text TEXT NOT NULL,
            source_language VARCHAR(10) DEFAULT "en",
            target_language VARCHAR(10) NOT NULL,
            translated_text TEXT,
            voice_type VARCHAR(50) DEFAULT "female",
            pitch DECIMAL(3,1) DEFAULT 1.0,
            speed DECIMAL(3,1) DEFAULT 1.0,
            audio_file_path VARCHAR(500),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )');
        
        return response()->json([
            'status' => 'success',
            'message' => 'Database tables created successfully',
            'tables' => ['users', 'voice_history']
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Migration failed: ' . $e->getMessage()
        ], 500);
    }
});