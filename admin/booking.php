<?php
require_once '../config/config.php';

if (!isset($_GET['room_id']) || !filter_var($_GET['room_id'], FILTER_VALIDATE_INT)) {
    die("Room ID is required and must be valid!");
}

$room_id = intval($_GET['room_id']);

// Fetch room details
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->execute([$room_id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    die("Room not found!");
}

// Handle booking submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = trim($_POST['user_name']);
    $user_email = filter_var($_POST['user_email'], FILTER_SANITIZE_EMAIL);
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];

    if (strtotime($check_out) <= strtotime($check_in)) {
        $error = "Check-out date must be later than check-in date.";
    } else {
        $total_price = $room['price'] * (strtotime($check_out) - strtotime($check_in)) / (60 * 60 * 24);

        try {
            $stmt = $pdo->prepare("INSERT INTO bookings (room_id, user_name, user_email, check_in, check_out, total_price, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$room_id, $user_name, $user_email, $check_in, $check_out, $total_price, 'booked'])) {
                $lastInsertId = $pdo->lastInsertId();

                // Send email (optional)
                $subject = "Booking Confirmation";
                $message = "Thank you for your booking, $user_name! Your booking ID is $lastInsertId.";
                mail($user_email, $subject, $message);

                // Redirect to confirmation
                header("Location: confirmation.php?booking_id=$lastInsertId");
                exit;
            } else {
                $error = "Failed to process your booking. Please try again.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Booking</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1>Book <?php echo htmlspecialchars($room['name']); ?></h1>
        <p>Price Per Night: <strong>$<?php echo htmlspecialchars($room['price']); ?></strong></p>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="user_name" class="form-label">Your Name</label>
                <input type="text" name="user_name" id="user_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="user_email" class="form-label">Your Email</label>
                <input type="email" name="user_email" id="user_email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="check_in" class="form-label">Check-In Date</label>
                <input type="date" name="check_in" id="check_in" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="check_out" class="form-label">Check-Out Date</label>
                <input type="date" name="check_out" id="check_out" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Confirm Booking</button>
        </form>
    </div>
</body>

</html>