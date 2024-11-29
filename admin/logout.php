<?php
session_start();

// Oturumu sonlandır
session_unset();
session_destroy();

// Tarayıcı önbelleğini temizle
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Giriş sayfasına yönlendir
header('Location: ../public/index.php');
exit;
?>
