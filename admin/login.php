<?php



session_start();
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email_mobile']);
    $password = trim($_POST['password']);



    try {
        $stmt = $pdo->prepare("
            SELECT id, name, email, password, role 
            FROM users 
            WHERE email = :email
            UNION 
            SELECT id, name, email, password, role 
            FROM guest_users 
            WHERE email = :email
        ");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Set session data
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role']
            ];

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header('Location: /uia_motell/admin/dashboard.php'); // Tam yol
                exit;
            } elseif ($user['role'] === 'guest') {
                header('Location: /uia_motell/inc/guest_dashboard.php'); // Tam yol
                exit;
            }
        } else {
            echo "<script>alert('Invalid email or password.'); window.history.back();</script>";
        }
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}
