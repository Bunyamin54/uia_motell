<?php
session_start();
include('../config/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
   
   
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    try {
        // Insert new guest user into the database
        $stmt = $pdo->prepare("
            INSERT INTO guest_users (name, email, phone, address, password, role, loyalty_points, discount_level)
            VALUES (:name, :email, :phone, :address, :password, 'guest', 0, 0)
        ");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':address' => $address,
          
          
            ':password' => $password,
        ]);

        // Set session for the new user
        $_SESSION['user'] = [
            'id' => $pdo->lastInsertId(),
            'name' => $name,
            'email' => $email,
            'role' => 'guest',
            'loyalty_points' => 0,
            'discount_level' => 0
        ];

        // Redirect to the guest dashboard
        header('Location: ../inc/guest_dashboard.php');
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}


   // Redirect to booking page if applicable
if (isset($_GET['redirect']) && $_GET['redirect'] === 'booking' && isset($_GET['room_id'])) {
    header("Location: booking.php?room_id=" . $_GET['room_id']);
    exit;
}

?>
