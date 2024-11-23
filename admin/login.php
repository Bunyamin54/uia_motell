<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('../config/config.php'); // Veritabanı bağlantısı


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email_mobile'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        if ($user['role'] === 'admin') {
            header('Location: /uia_motell/admin/dashboard.php'); // Admin dashboard'a yönlendirme
        } else {
            header('Location: /uia_motell/index.php'); // Normal kullanıcı ana sayfaya
        }
        exit;
    } else {
        echo "<script>alert('Invalid email or password.');</script>";
    }
}

  echo "hei";   
?>
