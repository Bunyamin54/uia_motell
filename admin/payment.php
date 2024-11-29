<?php
session_start();
require_once '../config/config.php';

// Ensure CSRF token is available
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Ensure booking details are available in the session
if (!isset($_SESSION['booking'])) {
    header("Location: available_rooms.php?error=missing_booking");
    exit;
}

$booking = $_SESSION['booking'];

// Validate booking details
if (empty($booking['room_id']) || !filter_var($booking['room_id'], FILTER_VALIDATE_INT)) {
    die("<div class='alert alert-danger'>Booking information is missing or invalid. Please restart the booking process.</div>");
}

// Process payment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("<div class='alert alert-danger'>Invalid CSRF token.</div>");
    }

    // Sanitize and validate payment inputs
    $card_number = preg_replace('/[^0-9]/', '', trim($_POST['card_number']));
    $expiry_date = trim($_POST['expiry_date']);
    $cvv = preg_replace('/[^0-9]/', '', trim($_POST['cvv']));

    if (empty($card_number) || empty($expiry_date) || empty($cvv)) {
        $error = "All payment fields are required.";
    } elseif (!is_numeric($card_number) || strlen($card_number) < 13 || strlen($card_number) > 16) {
        $error = "Invalid card number. Please check your input.";
    } elseif (!preg_match('/^(0[1-9]|1[0-2])\/([0-9]{2})$/', $expiry_date)) {
        $error = "Invalid expiry date format. Use MM/YY.";
    } elseif (strlen($cvv) !== 3) {
        $error = "CVV must be 3 digits.";
    } else {
        // Simulate payment success
        if (mockPayment($card_number, $expiry_date, $cvv)) {
            try {
                // Insert booking details into the database
                $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);

                $stmt = $pdo->prepare("
                    INSERT INTO bookings (room_id, user_name, user_email, check_in, check_out, total_price, status, payment_status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");

                if ($stmt->execute([
                    $booking['room_id'],
                    $booking['user_name'],
                    $booking['user_email'],
                    $booking['check_in'],
                    $booking['check_out'],
                    $booking['total_price'],
                    'booked',
                    'paid'
                ])) {
                    $lastInsertId = $pdo->lastInsertId();

                    // Send confirmation email
                    $subject = "Booking and Payment Confirmation";
                    $message = "Thank you for your booking, {$booking['user_name']}! Your booking ID is $lastInsertId.";
                    mail($booking['user_email'], $subject, $message);

                    // Clear session and redirect to confirmation
                    unset($_SESSION['booking']);
                    header("Location: confirmation.php?id={$booking['room_id']}&booking_id=$lastInsertId");

                    exit;
                } else {
                    $error = "Failed to process your booking. Please try again.";
                }
            } catch (PDOException $e) {
                $error = "Database error: " . $e->getMessage();
            }
        } else {
            $error = "Payment failed. Please check your card details and try again.";
        }
    }
}

// Mock payment function
function mockPayment($card_number, $expiry_date, $cvv)
{
    return true; // Simulate successful payment
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Payment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        .error {
            color: red;
            font-size: 0.9em;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1>Payment for Booking</h1>

        <!-- Display server-side error -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <p><strong>Room ID:</strong> <?php echo htmlspecialchars($booking['room_id']); ?></p>
        <p><strong>Total Price:</strong> $<?php echo htmlspecialchars($booking['total_price']); ?></p>

        <form method="POST" id="paymentForm" novalidate>
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

            <div class="mb-3">
                <label for="card_number" class="form-label">Card Number</label>
                <input type="text" name="card_number" id="card_number" class="form-control" required>
                <div class="error" id="card_number_error"></div>
            </div>
            <div class="mb-3">
                <label for="expiry_date" class="form-label">Expiry Date (MM/YY)</label>
                <input type="text" name="expiry_date" id="expiry_date" class="form-control" required>
                <div class="error" id="expiry_date_error"></div>
            </div>
            <div class="mb-3">
                <label for="cvv" class="form-label">CVV</label>
                <input type="text" name="cvv" id="cvv" class="form-control" required>
                <div class="error" id="cvv_error"></div>
            </div>
            <button type="submit" class="btn btn-success">Pay Now</button>
        </form>
    </div>

    <script>
        document.getElementById('paymentForm').addEventListener('submit', function (e) {
            let isValid = true;

            const cardNumber = document.getElementById('card_number').value;
            const expiryDate = document.getElementById('expiry_date').value;
            const cvv = document.getElementById('cvv').value;

            if (!/^\d{13,16}$/.test(cardNumber)) {
                document.getElementById('card_number_error').textContent = 'Invalid card number. Must be 13-16 digits.';
                isValid = false;
            }

            if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(expiryDate)) {
                document.getElementById('expiry_date_error').textContent = 'Invalid expiry date. Use MM/YY format.';
                isValid = false;
            }

            if (!/^\d{3}$/.test(cvv)) {
                document.getElementById('cvv_error').textContent = 'Invalid CVV. Must be 3 digits.';
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    </script>
</body>

</html>