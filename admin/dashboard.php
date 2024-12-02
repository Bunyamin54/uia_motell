<?php
session_start();

// Take the room_id from the URL

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Redirect to login page if user is not an admin

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
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
       
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        
        @keyframes typing {
            from {
                width: 0; 
            }
            to {
                width: 100%; 
            }
        }

      
        .typing-effect {
            font-family: 'Courier New', Courier, monospace; 
            font-size: 3rem; 
            font-weight: bolder; 
            color: #C80F2F; 
            white-space: nowrap;
            overflow: hidden;
            width: 0; 
            animation: typing 4s steps(30, end); 
            animation-fill-mode: forwards; 
        }

       
        .center-title {
            text-align: center; 
            margin-top: 10px; 
        }
    </style>
</head>
<body>
      
      <!--  Display the admin dashboard -->

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
