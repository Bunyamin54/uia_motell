<?php
session_start();
require_once '../config/config.php';

// Ensure both `id` and `booking_id` are provided and valid
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT) ||
    !isset($_GET['booking_id']) || !filter_var($_GET['booking_id'], FILTER_VALIDATE_INT)) {
    die("<div class='alert alert-danger'>Invalid parameters. Room ID and Booking ID are required!</div>");
}

$id = intval($_GET['id']);
$booking_id = intval($_GET['booking_id']);

try {
    // Database connection
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Fetch booking details
    $stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ? AND room_id = ?");
    $stmt->execute([$booking_id, $id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$booking) {
        die("<div class='alert alert-danger'>Booking not found or mismatch in Room ID and Booking ID!</div>");
    }

    // Fetch room details
    $roomStmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
    $roomStmt->execute([$id]);
    $room = $roomStmt->fetch(PDO::FETCH_ASSOC);

    if (!$room) {
        die("<div class='alert alert-danger'>Room details not found!</div>");
    }

    // Update loyalty points
    $loyaltyUpdate = $pdo->prepare("
        UPDATE guest_users 
        SET loyalty_points = loyalty_points + 10 
        WHERE email = ?
    ");
    $loyaltyUpdate->execute([$booking['user_email']]);

    if ($loyaltyUpdate->rowCount() === 0) {
        die("<div class='alert alert-danger'>Failed to update loyalty points. No rows affected.</div>");
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
    die("<div class='alert alert-danger'>An unexpected error occurred. Please try again later.</div>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
       <!-- Display the booking confirmation details -->
    <div class="container mt-5">
        <h1 class="text-center text-success">Booking Confirmation</h1>
        <div class="card">
            <div class="card-header bg-primary text-white">Booking Details</div>
            <div class="card-body">
                <p><strong>Booking ID:</strong> <?php echo htmlspecialchars($booking_id); ?></p>
                <p><strong>Room:</strong> <?php echo htmlspecialchars($room['name']); ?></p>
                <p><strong>Check-In:</strong> <?php echo htmlspecialchars($booking['check_in']); ?></p>
                <p><strong>Check-Out:</strong> <?php echo htmlspecialchars($booking['check_out']); ?></p>
                <p><strong>Total Price:</strong> $<?php echo htmlspecialchars($booking['total_price']); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($booking['status']); ?></p>
            </div>
        </div>
    </div>
</body>
</html>
