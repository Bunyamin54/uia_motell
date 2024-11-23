<?php
require_once '../config/config.php';

// Database connection
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Shutdown kontrolü
$stmt = $pdo->prepare("SELECT value FROM settings WHERE name = 'shutdown'");
$stmt->execute();
$shutdown = (int) $stmt->fetchColumn();

if ($shutdown === 1) {
    echo "Bookings are currently disabled. Please try again later.";
    exit;
}

// Rezervasyon işlemleri devam ediyor...
