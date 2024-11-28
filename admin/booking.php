<?php
session_start();
require_once '../config/config.php';

// id doğrulama
if (!isset($_GET['room_id']) || !filter_var($_GET['room_id'], FILTER_VALIDATE_INT)) {
    die("<div class='alert alert-danger'>Room ID is required and must be valid!</div>");
}

$id = intval($_GET['room_id']);

// Oda bilgilerini veritabanından çek

$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ? AND status = 'available'");
$stmt->execute([$id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    die("<div class='alert alert-danger'>Room not found!</div>");
}

// Kullanıcı formunu işle
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = trim($_POST['user_name']);
    $user_email = filter_var($_POST['user_email'], FILTER_SANITIZE_EMAIL);
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];

    if (empty($user_name) || empty($user_email)) {
        $error = "Name and email are required.";
    } elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } elseif (strtotime($check_out) <= strtotime($check_in)) {
        $error = "Check-out date must be later than check-in date.";
    } else {
        // Toplam ücreti hesapla
        $total_price = $room['price'] * (strtotime($check_out) - strtotime($check_in)) / (60 * 60 * 24);

        // $_SESSION['booking'] içine kaydet
        $_SESSION['booking'] = [
            'room_id' => $id,
            'user_name' => $user_name,
            'user_email' => $user_email,
            'check_in' => $check_in,
            'check_out' => $check_out,
            'total_price' => $total_price,
        ];

        // payment.php'ye yönlendir
        header("Location: payment.php");
        exit;
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

        <!-- Display error messages -->
        <?php if (isset($error)): ?>
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
            <button type="submit" class="btn btn-success">Proceed to Payment</button>
        </form>
    </div>
</body>

</html>
