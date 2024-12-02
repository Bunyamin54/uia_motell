<?php

require_once '../config/config.php';

// Fetch all available rooms from the database

$stmt = $pdo->query("SELECT * FROM rooms WHERE status = 'available'");
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Available Rooms</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
      
   <!--  Display all available rooms in a grid layout -->
     
<div class="container mt-5">
    <h1>Available Rooms</h1>
    <div class="row">
        <?php foreach ($rooms as $room): ?>
            <div class="col-md-4">
                <div class="card mb-3">
                    <img src="../public/images/rooms/<?php echo htmlspecialchars($room['image']); ?>" class="card-img-top" alt="Room Image">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($room['name']); ?></h5>
                        <p class="card-text">Price: $<?php echo htmlspecialchars($room['price']); ?> per night</p>
                        <a href="booking.php?id=<?php echo htmlspecialchars($room['id']); ?>" class="btn btn-primary">Book Now</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>
