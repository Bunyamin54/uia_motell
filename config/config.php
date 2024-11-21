<?php
// db.php
$host = 'localhost'; // Change as needed
$db = 'uiamotell';
$user = 'gruppe-2';
$pass = '12345';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

require 'db.php';

// Example: Fetch all rooms
$stmt = $pdo->query('SELECT * FROM rooms');
$rooms = $stmt->fetchAll();

foreach ($rooms as $room) {
    echo $room['name'] . '<br>';
}
?>

<?php
require 'db.php';

// Insert a new booking
$sql = 'INSERT INTO bookings (user_id, room_id, check_in, check_out) VALUES (?, ?, ?, ?)';
$stmt = $pdo->prepare($sql);
$stmt->execute([$userId, $roomId, $checkIn, $checkOut]);
