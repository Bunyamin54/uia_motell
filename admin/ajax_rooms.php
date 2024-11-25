<?php
require_once '../config/config.php';

header('Content-Type: application/json');
ob_clean(); // Clear previous output

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'list') {
        $stmt = $pdo->query("SELECT id, name, type, capacity, status, image FROM rooms");
        $rooms = $stmt->fetchAll();
        echo json_encode($rooms);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? null;

        if ($action === 'add_room') {
            $name = trim($_POST['room_name'] ?? '');
            $type = trim($_POST['room_type'] ?? '');
            $capacity = intval($_POST['capacity'] ?? 0);
            $status = trim($_POST['status'] ?? '');

            if (!$name || !$type || !$capacity || !$status) {
                echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
                exit;
            }

            $imageName = '';
            if (!empty($_FILES['room_image']['tmp_name'])) {
                $fileInfo = pathinfo($_FILES['room_image']['name']);
                $extension = strtolower($fileInfo['extension']);
                $allowedExtensions = ['jpg', 'jpeg', 'png'];
                $fileInfo = pathinfo($_FILES['room_image']['name']);
                $extension = strtolower($fileInfo['extension']);
                
                if (!in_array($extension, $allowedExtensions)) {
                    echo json_encode(['status' => 'error', 'message' => 'Invalid image type. Only JPG, JPEG, and PNG are allowed.']);
                    exit;
                }
                

                $imageName = uniqid('room_') . '.' . $extension;
                $imagePath = realpath(dirname(__FILE__) . '/../public/images/rooms') . '/' . $imageName;

                if (!file_exists(dirname($imagePath))) {
                    mkdir(dirname($imagePath), 0755, true);
                }
                
                if (!move_uploaded_file($_FILES['room_image']['tmp_name'], $imagePath)) {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to upload image. Check directory permissions.']);
                    exit;
                }
                
            }

            $stmt = $pdo->prepare("INSERT INTO rooms (name, type, capacity, status, image) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$name, $type, $capacity, $status, $imageName])) {
                echo json_encode(['status' => 'success', 'message' => 'Room added successfully!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to add room.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Unexpected error: ' . $e->getMessage()]);
}
