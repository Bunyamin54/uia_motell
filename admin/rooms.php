<?php
session_start();

// Ensure only admin users can access
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php');
    exit;
} 

// Include database connection
include('../config/config.php');

// Handle adding or deleting rooms
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_room'])) {
        $stmt = $conn->prepare("INSERT INTO rooms (name, type, capacity, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $_POST['room_name'], $_POST['room_type'], $_POST['capacity'], $_POST['status']);
        $stmt->execute();

        // Set toast message
        $_SESSION['message'] = "Room added successfully!";
        $_SESSION['message_type'] = "success";
    } elseif (isset($_POST['delete_room'])) {
        $stmt = $conn->prepare("DELETE FROM rooms WHERE id = ?");
        $stmt->bind_param("i", $_POST['room_id']);
        $stmt->execute();

        // Set toast message
        $_SESSION['message'] = "Room deleted successfully!";
        $_SESSION['message_type'] = "danger";
    }
    header('Location: rooms.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rooms</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1>Manage Rooms</h1>

        <!-- Toast Notifications -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="toast-container position-fixed top-0 end-0 p-3">
                <div class="toast show text-bg-<?= $_SESSION['message_type']; ?>" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <?= $_SESSION['message']; ?>
                        </div>
                        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
        <?php endif; ?>

        <!-- Add Room Form -->
        <form action="rooms.php" method="POST" class="mt-4">
            <h3>Add New Room</h3>
            <div class="mb-3">
                <input type="text" name="room_name" placeholder="Room Name" class="form-control" required>
            </div>
            <div class="mb-3">
                <select name="room_type" class="form-select">
                    <option value="Single">Single</option>
                    <option value="Double">Double</option>
                    <option value="Suite">Suite</option>
                </select>
            </div>
            <div class="mb-3">
                <input type="number" name="capacity" placeholder="Capacity" class="form-control" required>
            </div>
            <div class="mb-3">
                <select name="status" class="form-select">
                    <option value="available">Available</option>
                    <option value="unavailable">Unavailable</option>
                </select>
            </div>
            <button type="submit" name="add_room" class="btn btn-success">Add Room</button>
        </form>

        <h3 class="mt-5">All Rooms</h3>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Capacity</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM rooms");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['name']}</td>
                        <td>{$row['type']}</td>
                        <td>{$row['capacity']}</td>
                        <td>{$row['status']}</td>
                        <td>
                            <form method='POST' style='display:inline-block;'>
                                <input type='hidden' name='room_id' value='{$row['id']}'>
                                <button type='submit' name='delete_room' class='btn btn-danger'>Delete</button>
                            </form>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Automatically hide toast after 3 seconds
        const toastEl = document.querySelector('.toast');
        if (toastEl) {
            setTimeout(() => {
                toastEl.classList.remove('show');
            }, 3000);
        }
    </script>
</body>

</html>