<?php
// Database credentials
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'uia_motell');
define('DB_USER', 'root');
define('DB_PASS', '123');
define('DB_CHARSET', 'utf8mb4');

// Application settings
define('APP_NAME', 'UIA Motel');
define('BASE_URL', 'http://localhost/uia_motell/');

// login.php dosyasındaki yönlendirmeler
if ($user['role'] === 'admin') {
    header('Location: ' . BASE_URL . 'admin/dashboard.php');
    exit;
} elseif ($user['role'] === 'guest') {
    header('Location: ' . BASE_URL . 'inc/guest_dashboard.php');
    exit;
}


// Debug mode
define('DEBUG_MODE', true);

// Debug settings
if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

// Database connection
$dsn = sprintf(
    "mysql:host=%s;dbname=%s;charset=%s",
    DB_HOST,
    DB_NAME,
    DB_CHARSET
);

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
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
