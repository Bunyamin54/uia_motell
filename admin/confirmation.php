<?php
require_once '../config/config.php';

if (!isset($_GET['booking_id']) || !filter_var($_GET['booking_id'], FILTER_VALIDATE_INT)) {
    die("Booking ID is required and must be valid!");
}

$booking_id = intval($_GET['booking_id']);

// Fetch booking details
$stmt = $pdo->prepare("SELECT b.*, r.name as room_name FROM bookings b JOIN rooms r ON b.room_id = r.id WHERE b.id = ?");
$stmt->execute([$booking_id]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    die("Booking not found!");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Booking Confirmation</h1>
    <p>Thank you for your booking, <?php echo htmlspecialchars($booking['user_name']); ?>!</p>
    <p>Your booking ID is: <strong><?php echo $booking_id; ?></strong></p>
    <p>Room: <?php echo htmlspecialchars($booking['room_name']); ?></p>
    <p>Check-in: <?php echo htmlspecialchars($booking['check_in']); ?></p>
    <p>Check-out: <?php echo htmlspecialchars($booking['check_out']); ?></p>
    <p>Total Price: $<?php echo htmlspecialchars($booking['total_price']); ?></p>
    <a href="available.php" class="btn btn-primary">Back to Rooms</a>
</div>
</body>
</html>
