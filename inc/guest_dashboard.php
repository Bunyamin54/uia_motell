<?php
session_start();
require_once '../config/config.php';

// Check if the user is logged in

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'guest') {
    header('Location: ../admin/login.php');
    exit;
}

$user = $_SESSION['user'];

// user information from the database

try {
    $userInfoStmt = $pdo->prepare("
        SELECT loyalty_points, discount_level 
        FROM guest_users 
        WHERE email = ?
    ");
    $userInfoStmt->execute([$user['email']]);
    $userInfo = $userInfoStmt->fetch(PDO::FETCH_ASSOC);

    $loyalty_points = $userInfo['loyalty_points'] ?? 0;
    $discount = $userInfo['discount_level'] ?? 0;
} catch (PDOException $e) {
    die("<div class='alert alert-danger'>Failed to fetch user information: " . $e->getMessage() . "</div>");
}

try {
    // users booking details

    $loyaltyUpdate = $pdo->prepare("
        UPDATE guest_users 
        SET loyalty_points = loyalty_points + 10 
        WHERE email = ?
    ");
    $loyaltyUpdate->execute([$booking['user_email']]);

    // users loyalty points

    $loyaltyCheck = $pdo->prepare("
        SELECT loyalty_points 
        FROM guest_users 
        WHERE email = ?
    ");
    $loyaltyCheck->execute([$booking['user_email']]);
    $loyaltyPoints = $loyaltyCheck->fetchColumn();

    // if loyalty points reach 20, update discount level

    if ($loyaltyPoints >= 20) {
        $updateDiscount = $pdo->prepare("
            UPDATE guest_users 
            SET discount_level = 15, loyalty_points = 0 
            WHERE email = ?
        ");
        $updateDiscount->execute([$booking['user_email']]);
    }
} catch (PDOException $e) {
    die("<div class='alert alert-danger'>Failed to update loyalty points or discount level: " . $e->getMessage());
}


// users booking history

$stmt = $pdo->prepare("
    SELECT b.id, r.name AS room_name, b.check_in, b.check_out, b.total_price, b.status 
    FROM bookings b
    JOIN rooms r ON b.room_id = r.id
    WHERE b.user_email = :user_email
    ORDER BY b.check_in DESC
");
$stmt->execute([':user_email' => $user['email']]);
$bookings = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

   <!-- Navbar -->
   <nav class="navbar navbar-light py-2 bg-light justify-content-end">
        <div class="container">
            <a class="navbar-brand" href="#">Guest Dashboard</a>
            <a href="../public/index.php" class="btn btn-primary">Home</a>
        </div>
    </nav>
<div class="container mt-5">
    <h1>Welcome, <?= htmlspecialchars($user['name']) ?>!</h1>
    <p>Email: <?= htmlspecialchars($user['email']) ?></p>
    <p>Role: <?= htmlspecialchars($user['role']) ?></p>
    <p>Loyalty Points: <?= htmlspecialchars($loyalty_points) ?></p>
    <p>Discount Level: <?= htmlspecialchars($discount) ?>%</p>

    <?php if ($discount > 0): ?>
        <div class="alert alert-success mt-3">
            <h5>Special Offer!</h5>
            <p>You qualify for a <?= htmlspecialchars($discount) ?>% discount on your next booking!</p>
        </div>
    <?php endif; ?>


        <!-- Edit Profile -->
        <h2>Edit Your Profile</h2>
        <form action="../admin/update_profile.php" method="POST" class="mb-5">
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>

        <!-- Booking History -->
        <h2>Your Booking History</h2>
        <?php if (!empty($bookings)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Room</th>
                        <th>Check-In</th>
                        <th>Check-Out</th>
                        <th>Total Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?= htmlspecialchars($booking['id']) ?></td>
                            <td><?= htmlspecialchars($booking['room_name']) ?></td>
                            <td><?= htmlspecialchars($booking['check_in']) ?></td>
                            <td><?= htmlspecialchars($booking['check_out']) ?></td>
                            <td>$<?= htmlspecialchars($booking['total_price']) ?></td>
                            <td><?= htmlspecialchars($booking['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No bookings found.</p>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>