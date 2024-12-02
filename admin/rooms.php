<?php
// Veritabanı bağlantısı
require_once '../config/config.php';

// İşlem kontrolü
$action = $_GET['action'] ?? null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add') {
    // Oda ekleme işlemi
    $room_name = $_POST['room_name'];
    $room_type = $_POST['room_type'];
    $price = $_POST['price'];
    $capacity = $_POST['capacity'];
    $status = $_POST['status'];
    $details = $_POST['details'];
    $facilities = $_POST['facilities'];

    $image = null;
    if (!empty($_FILES['room_image']['name'])) {
        $image = uniqid() . '_' . $_FILES['room_image']['name'];
        move_uploaded_file($_FILES['room_image']['tmp_name'], "../public/images/rooms/$image");
    }

    $stmt = $pdo->prepare("INSERT INTO rooms (name, type, price, capacity, status, details, facilities, image) 
                           VALUES (:name, :type, :price, :capacity, :status, :details, :facilities, :image)");
    $stmt->execute([
        ':name' => $room_name,
        ':type' => $room_type,
        ':price' => $price,
        ':capacity' => $capacity,
        ':status' => $status,
        ':details' => $details,
        ':facilities' => $facilities,
        ':image' => $image
    ]);
    header("Location: ../public/index.php");
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'edit') {
    // Oda düzenleme işlemi
    $id = $_POST['id'];
    $room_name = $_POST['room_name'];
    $room_type = $_POST['room_type'];
    $price = $_POST['price'];
    $capacity = $_POST['capacity'];
    $status = $_POST['status'];
    $details = $_POST['details'];
    $facilities = $_POST['facilities'];

    $image = $_POST['current_image'];
    if (!empty($_FILES['room_image']['name'])) {
        $image = uniqid() . '_' . $_FILES['room_image']['name'];
        move_uploaded_file($_FILES['room_image']['tmp_name'], "../public/images/rooms/$image");
    }

    $stmt = $pdo->prepare("UPDATE rooms 
                           SET name = :name, type = :type, price = :price, capacity = :capacity, 
                               status = :status, details = :details, facilities = :facilities, image = :image 
                           WHERE id = :id");
    $stmt->execute([
        ':name' => $room_name,
        ':type' => $room_type,
        ':price' => $price,
        ':capacity' => $capacity,
        ':status' => $status,
        ':details' => $details,
        ':facilities' => $facilities,
        ':image' => $image,
        ':id' => $id
    ]);
    header("Location: ../public/index.php");
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'delete') {
    // Oda silme işlemi
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM rooms WHERE id = :id");
    $stmt->execute([':id' => $id]);
    header("Location: ../public/index.php");
    exit;
}

// Odaları listele
$stmt = $pdo->query("SELECT * FROM rooms");
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Düzenlenecek oda bilgileri
$editRoom = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $editRoom = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Rooms</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-success">Manage Rooms</h1>

        <!-- Add or Edit Room Form -->
        <form action="?action=<?= $editRoom ? 'edit' : 'add' ?>" method="POST" enctype="multipart/form-data" class="mt-4">
            <h3 class="text-warning"><?= $editRoom ? 'Edit Room' : 'Add New Room' ?></h3>
            <?php if ($editRoom): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($editRoom['id']) ?>">
                <input type="hidden" name="current_image" value="<?= htmlspecialchars($editRoom['image']) ?>">
            <?php endif; ?>
            <div class="mb-3">
                <input type="text" name="room_name" placeholder="Room Name" class="form-control" value="<?= $editRoom['name'] ?? '' ?>" required>
            </div>
            <div class="mb-3">
                <select name="room_type" class="form-select">
                    <option value="Single" <?= isset($editRoom) && $editRoom['type'] === 'Single' ? 'selected' : '' ?>>Single</option>
                    <option value="Double" <?= isset($editRoom) && $editRoom['type'] === 'Double' ? 'selected' : '' ?>>Double</option>
                    <option value="Suite" <?= isset($editRoom) && $editRoom['type'] === 'Suite' ? 'selected' : '' ?>>Suite</option>
                </select>
            </div>
            <div class="mb-3">
                <input type="number" name="price" placeholder="Price (per night)" class="form-control" value="<?= $editRoom['price'] ?? '' ?>" required>
            </div>
            <div class="mb-3">
                <input type="number" name="capacity" placeholder="Capacity" class="form-control" value="<?= $editRoom['capacity'] ?? '' ?>" required>
            </div>
            <div class="mb-3">
                <select name="status" class="form-select">
                    <option value="available" <?= isset($editRoom) && $editRoom['status'] === 'available' ? 'selected' : '' ?>>Available</option>
                    <option value="unavailable" <?= isset($editRoom) && $editRoom['status'] === 'unavailable' ? 'selected' : '' ?>>Unavailable</option>
                </select>
            </div>
            <div class="mb-3">
                <textarea name="details" placeholder="Room Details" class="form-control"><?= $editRoom['details'] ?? '' ?></textarea>
            </div>
            <div class="mb-3">
                <textarea name="facilities" placeholder="Facilities" class="form-control"><?= $editRoom['facilities'] ?? '' ?></textarea>
            </div>
            <div class="mb-3">
                <input type="file" name="room_image" class="form-control">
                <?php if ($editRoom && $editRoom['image']): ?>
                    <img src="../public/images/rooms/<?= htmlspecialchars($editRoom['image']) ?>" alt="Current Image" class="img-thumbnail mt-3" style="width: 200px;">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-success"><?= $editRoom ? 'Update Room' : 'Add Room' ?></button>
        </form>

        <h3 class="mt-5">All Rooms</h3>
        <div id="roomContainer" class="mt-3">
            <?php foreach ($rooms as $room): ?>
                <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="<?= !empty($room['image']) ? "../public/images/rooms/{$room['image']}" : '../public/images/default-placeholder.png' ?>" class="img-fluid rounded-start" alt="Room Image">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($room['name']) ?></h5>
                                <h6 class="mb-4">From $<?= htmlspecialchars($room['price']) ?> per night</h6>
                                <div class="features mb-4">
                                    <h6 class="mb-1">Room Details</h6>
                                    <?= htmlspecialchars($room['details']) ?>
                                </div>
                                <div class="facilities mb-4">
                                    <h6 class="mb-1">Facilities</h6>
                                    <?= htmlspecialchars($room['facilities']) ?>
                                </div>
                                <div class="d-flex justify-content-evenly mb-2">
                                    <a href="?action=edit&id=<?= $room['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="?action=delete&id=<?= $room['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
