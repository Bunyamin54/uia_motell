<?php
// Database credentials
define('DB_HOST', '127.0.0.1'); // Database host
define('DB_NAME', 'uia_motell'); // Your database name
define('DB_USER', 'root'); // Your database username
define('DB_PASS', '123'); // Your database password
define('DB_CHARSET', 'utf8mb4'); // Character set

// Application settings
define('APP_NAME', 'UIA Motel');
define('BASE_URL', 'http://localhost/uia_motell/');

// login.php dosyasındaki yönlendirmeler
if (isset($user) && isset($user['role'])) {
    if ($user['role'] === 'admin') {
        header('Location: ' . BASE_URL . 'admin/dashboard.php');
        exit;
    } elseif ($user['role'] === 'guest') {
        header('Location: ' . BASE_URL . 'inc/guest_dashboard.php');
        exit;
    }
}



// Debug mode
define('DEBUG_MODE', true); // Set to false in production

// Create a database connection
try {
    $dsn = "mysql:host=127.0.0.1;dbname=uia_motell;charset=utf8mb4";
    $pdo = new PDO($dsn, 'root', '123', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    if (DEBUG_MODE) {
        error_log("Database connected successfully!");
    }
} catch (PDOException $e) {
    if (DEBUG_MODE) {
        error_log("Database connection failed: " . $e->getMessage());
    }
    die("Database connection failed. Please try again later.");
}
?>
