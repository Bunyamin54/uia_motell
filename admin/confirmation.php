<?php
session_start();
require_once '../config/config.php';

// Ensure id is provided and valid
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    die("<div class='alert alert-danger'>Room ID is required and must be valid!</div>");
}

$id = intval($_GET['id']);

// Fetch room details from the database
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->execute([$id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    die("<div class='alert alert-danger'>Room not found!</div>");
}

// Generate CSRF token if not already set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle form submission
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("<div class='alert alert-danger'>Invalid CSRF token.</div>");
    }

    // Sanitize and validate inputs
    $user_name = trim($_POST['user_name']);
    $user_email = filter_var($_POST['user_email'], FILTER_SANITIZE_EMAIL);
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];

    if (empty($user_name) || empty($user_email)) {
        $error = "Name and email are required.";
    } elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } elseif (!strtotime($check_in) || !strtotime($check_out)) {
        $error = "Invalid date format for check-in or check-out.";
    } elseif (strtotime($check_out) <= strtotime($check_in)) {
        $error = "Check-out date must be later than check-in date.";
    } else {
        // Calculate total price
        $days = (strtotime($check_out) - strtotime($check_in)) / (60 * 60 * 24);
        $total_price = $room['price'] * $days;

        try {
            // Insert booking into the database
            $stmt = $pdo->prepare("
                INSERT INTO bookings (room_id, user_name, user_email, check_in, check_out, total_price, status, payment_status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $status = 'booked';
            $payment_status = 'pending';

            if ($stmt->execute([$id, $user_name, $user_email, $check_in, $check_out, $total_price, $status, $payment_status])) {
                $lastInsertId = $pdo->lastInsertId();

                // Regenerate CSRF token
                unset($_SESSION['csrf_token']);
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                // Send email confirmation (optional)
                $subject = "Booking Confirmation";
                $message = "Thank you for your booking, $user_name! Your booking ID is $lastInsertId.";
                mail($user_email, $subject, $message);

                // Redirect to confirmation page
                header("Location: confirmation.php?id={$id}&booking_id=$lastInsertId");

                exit;
            } else {
                $error = "Failed to process your booking. Please try again.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . htmlspecialchars($e->getMessage());
        }
    }
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
        <h1>Book <?php echo htmlspecialchars($room['name']); ?></h1>
        <p>Price Per Night: <strong>$<?php echo htmlspecialchars($room['price']); ?></strong></p>

        <!-- Display error messages -->
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Booking form -->
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
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <button type="submit" class="btn btn-success">Confirm Booking</button>
        </form>
    </div>
</body>

</html>
