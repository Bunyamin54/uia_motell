<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('../config/config.php'); // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email_mobile'];
    $password = $_POST['password'];

    try {
        // Use $pdo for the database connection
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            if ($user['role'] === 'admin') {
                header('Location: /uia_motell/admin/dashboard.php'); // admin page after login
            } else {
                header('Location: /uia_motell/index.php'); // user page after login
            }
            exit;
        } else {
            echo "<script>alert('Invalid email or password.');</script>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

echo "hei";
?>
