<?php
require 'db.php';

$queries = [
    "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
    )",
    "CREATE TABLE IF NOT EXISTS rooms (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        type VARCHAR(255) NOT NULL,
        capacity INT NOT NULL,
        status VARCHAR(50) DEFAULT 'available'
    )",
    "CREATE TABLE IF NOT EXISTS bookings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        room_id INT NOT NULL,
        check_in DATE NOT NULL,
        check_out DATE NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (room_id) REFERENCES rooms(id)
    )"
];

foreach ($queries as $query) {
    $pdo->exec($query);
}

echo "Migration completed successfully!";
