<?php
require 'db.php';

// Add room types
$rooms = [
    ['name' => 'Room 101', 'type' => 'Single', 'capacity' => 1],
    ['name' => 'Room 102', 'type' => 'Double', 'capacity' => 2],
    ['name' => 'Junior Suite', 'type' => 'Suite', 'capacity' => 4]
];

$stmt = $pdo->prepare('INSERT INTO rooms (name, type, capacity) VALUES (?, ?, ?)');
foreach ($rooms as $room) {
    $stmt->execute([$room['name'], $room['type'], $room['capacity']]);
}

echo "Database seeded successfully!";
