<?php
session_start();

// Tarayıcı önbelleğini devre dışı bırak
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Oturum ve kullanıcı kontrolü
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php'); // Giriş sayfasına yönlendirme
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Logout butonu */
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        /* Yazı yazma animasyonu */
        @keyframes typing {
            from {
                width: 0; /* Yazı başlangıçta görünmez */
            }
            to {
                width: 100%; /* Yazının tamamı görünür */
            }
        }

        /* Yazı stil ve animasyon ayarları */
        .typing-effect {
            font-family: 'Courier New', Courier, monospace; /* Yazı tipi */
            font-size: 3rem; /* Yazı büyüklüğü */
            font-weight: bolder; /* Kalın yazı */
            color: #C80F2F; /* Kırmızı renk */
            white-space: nowrap; /* Satır taşmasını engeller */
            overflow: hidden; /* Taşan yazıları gizler */
            width: 0; /* Başlangıçta görünmez */
            animation: typing 6s steps(30, end); /* Animasyon */
            animation-fill-mode: forwards; 
        }

        /* Genel düzen */
        .center-title {
            text-align: center; /* Ortaya hizalama */
            margin-top: 10px; /* Edit Page başlığına yakın */
        }
    </style>
</head>
<body>
    <!-- Logout düğmesi -->
    <a href="logout.php" class="btn btn-danger logout-btn">Logout</a>

    <div class="container mt-5 text-center">
    <h1 class="typing-effect">Welcome to Admin Dashboard</h1>
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">Edit Home Page Images</div>
                    <div class="card-body">
                        <a href="home.php" class="btn btn-primary">Edit Images</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-success text-white">Manage Rooms</div>
                    <div class="card-body">
                        <a href="rooms.php" class="btn btn-success">Manage Rooms</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-warning text-white">Site Settings</div>
                    <div class="card-body">
                        <a href="settings.php" class="btn btn-warning">Edit Settings</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
