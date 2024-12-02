<?php
session_start();
require_once '../config/config.php';

  // Check if the user is logged in

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $shutdown = isset($data['shutdown']) ? (int) $data['shutdown'] : 0;

     // Database connection

    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

          // Save settings to the database
        $stmt = $pdo->prepare("
            INSERT INTO settings (name, value) 
            VALUES ('shutdown', :shutdown) 
            ON DUPLICATE KEY UPDATE value = :shutdown
        ");

         // Execute the query
         
        $stmt->execute([':shutdown' => $shutdown]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
