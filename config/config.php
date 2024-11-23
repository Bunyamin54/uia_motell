<?php
// Database credentials
define('DB_HOST', '127.0.0.1'); // Database host
define('DB_NAME', 'uia_motell'); // Your database name
define('DB_USER', 'root'); // Your database username
define('DB_PASS', '123'); // Your database password
define('DB_CHARSET', 'utf8mb4'); // Character set

// Application settings
define('APP_NAME', 'UIA Motel'); // Application name
define('BASE_URL', 'http://localhost/UIA_MOTELL/'); // Change this to your base URL

// Debug mode
define('DEBUG_MODE', true); // Set to false in production

// Create a database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check the connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
