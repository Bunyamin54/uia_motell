<?php
session_start();
require_once '../config/config.php';

// Check if the user is logged in
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'guest') {
    header('Location: ../admin/login.php');
    exit;
}

// Validate form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user's input
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    // Validate required fields
    if (empty($name) || empty($email)) {
        echo "<script>alert('Name and email are required fields.'); window.history.back();</script>";
        exit;
    }

    // Get the current user's email
    $current_email = $_SESSION['user']['email'];

    try {
        // Update the user's profile in the database
        $stmt = $pdo->prepare("
            UPDATE guest_users 
            SET name = :name, email = :email, phone = :phone 
            WHERE email = :current_email
        ");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':current_email' => $current_email,
        ]);

        // Update the session with the new data
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['phone'] = $phone;

        // Redirect back to the guest dashboard with a success message
        header('Location: ../inc/guest_dashboard.php?update=success');
        exit;

    } catch (PDOException $e) {
        echo "<script>alert('An error occurred while updating your profile. Please try again.'); window.history.back();</script>";
        exit;
    }
} else {
    // Redirect to the dashboard if accessed directly
    header('Location: ../inc/guest_dashboard.php');
    exit;
}
