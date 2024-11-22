<?php
require_once 'config.php';

try {
    // Create DSN (Data Source Name)
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

    // Options for PDO
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Enable exceptions for errors
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch associative arrays by default
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Disable emulation of prepared statements
    ];

    // Create PDO instance
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

    // Debug mode message
    if (DEBUG_MODE) {
        echo "Database connected successfully!";
    }
} catch (PDOException $e) {
    // Handle connection errors
    if (DEBUG_MODE) {
        echo "Database connection failed: " . $e->getMessage();
    } else {
        // Hide error details in production
        echo "An error occurred while connecting to the database.";
    }
    exit; // Stop script execution if the connection fails
}
