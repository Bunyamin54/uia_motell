<?php

//   This file handles AJAX requests for rooms 


require_once '../config/config.php';

header('Content-Type: application/json');

try {


    // GET Requests

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if ($_GET['action'] === 'list') {

            // List all rooms
            $stmt = $pdo->query("SELECT id, name, type, capacity, status, image, details, facilities, price FROM rooms");
            $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($rooms);
        } elseif ($_GET['action'] === 'get_room') {

            // Get a specific room's details
            $roomId = intval($_GET['id'] ?? 0);
            $stmt = $pdo->prepare("SELECT id, name, type, capacity, status, image , details, facilities, price FROM rooms WHERE id = ?");
            $stmt->execute([$roomId]);
            $room = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($room) {
                echo json_encode($room);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Room not found.']);
            }
        } else {
            throw new Exception('Invalid GET action.');
        }
    }

    // POST Requests and Form Submissions

    elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? null;

        // Add a new room

        if ($action === 'add_room') {
            $name = trim($_POST['room_name'] ?? '');
            $type = trim($_POST['room_type'] ?? '');
            $capacity = intval($_POST['capacity'] ?? 0);
            $status = trim($_POST['status'] ?? '');
            $details = $_POST['details'] ?? '';
            $facilities = $_POST['facilities'] ?? '';
            $price = intval($_POST['price'] ?? 0);

            if (!$name || !$type || !$capacity || !$status ||  $price <= 0) {
                throw new Exception('All fields are required and price must be a positive integer.');
            }

            if (!in_array($type, ['Single', 'Double', 'Suite']) || !in_array($status, ['available', 'unavailable'])) {
                throw new Exception('Invalid room type or status.');
            }

            $imageName = null;
            if (!empty($_FILES['room_image']['tmp_name'])) {
                $fileInfo = pathinfo($_FILES['room_image']['name']);
                $extension = strtolower($fileInfo['extension']);
                $allowedExtensions = ['jpg', 'jpeg', 'png'];

                if (!in_array($extension, $allowedExtensions)) {
                    throw new Exception('Invalid image type. Only JPG, JPEG, and PNG are allowed.');
                }

                $imageName = uniqid('room_') . '.' . $extension;
                $imagePath = realpath(dirname(__FILE__) . '/../public/images/rooms') . '/' . $imageName;

                if (!file_exists(dirname($imagePath))) {
                    mkdir(dirname($imagePath), 0755, true);
                }

                if (!move_uploaded_file($_FILES['room_image']['tmp_name'], $imagePath)) {
                    throw new Exception('Failed to upload image. Check directory permissions.');
                }
            }

            // Insert the room into the database

            $stmt = $pdo->prepare("INSERT INTO rooms (name, type, capacity, status, image, details, facilities, price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$name, $type, $capacity, $status, $imageName, $details, $facilities, $price])) {
                echo json_encode(['status' => 'success', 'message' => 'Room added successfully!']);
            } else {
                throw new Exception('Failed to add room.');
            }
        }

        // Update an existing room 

        elseif ($action === 'update_room') {
            $roomId = intval($_POST['room_id'] ?? 0);
            $name = trim($_POST['room_name'] ?? '');
            $type = trim($_POST['room_type'] ?? '');
            $capacity = intval($_POST['capacity'] ?? 0);
            $status = trim($_POST['status'] ?? '');
            $imageName = null;
            $details = $_POST['details'] ?? '';
            $facilities = $_POST['facilities'] ?? '';
            $price = intval($_POST['price'] ?? 0);

            if (!$roomId || !$name || !$type || !$capacity || !$status) {
                throw new Exception('All fields are required.');
            }

            if (!empty($_FILES['room_image']['tmp_name'])) {
                $fileInfo = pathinfo($_FILES['room_image']['name']);
                $extension = strtolower($fileInfo['extension']);
                $allowedExtensions = ['jpg', 'jpeg', 'png'];

                if (!in_array($extension, $allowedExtensions)) {
                    throw new Exception('Invalid image type.');
                }

                $imageName = uniqid('room_') . '.' . $extension;
                $imagePath = realpath(dirname(__FILE__) . '/../public/images/rooms') . '/' . $imageName;

                $stmt = $pdo->prepare("SELECT image FROM rooms WHERE id = ?");
                $stmt->execute([$roomId]);
                $oldImage = $stmt->fetchColumn();
                if ($oldImage) {
                    $oldImagePath = realpath(dirname(__FILE__) . '/../public/images/rooms') . '/' . $oldImage;
                    if (file_exists($oldImagePath)) unlink($oldImagePath);
                }

                if (!move_uploaded_file($_FILES['room_image']['tmp_name'], $imagePath)) {
                    throw new Exception('Failed to upload image.');
                }
            }

            // Update the room in the database

            $stmt = $pdo->prepare("UPDATE rooms SET name = ?, type = ?, capacity = ?, status = ?, image = COALESCE(?, image), details = ?, facilities = ?, price = ?  WHERE id = ?");
            if ($stmt->execute([$name, $type, $capacity, $status, $imageName,  $details, $facilities, $price, $roomId])) {
                echo json_encode(['status' => 'success', 'message' => 'Room updated successfully!']);
            } else {
                throw new Exception('Failed to update room.');
            }
        }

        // Delete a room if requested

        elseif ($action === 'delete_room') {
            $roomId = intval($_POST['room_id'] ?? 0);

            $stmt = $pdo->prepare("SELECT image FROM rooms WHERE id = ?");
            $stmt->execute([$roomId]);
            $imageName = $stmt->fetchColumn();
            if ($imageName) {
                $imagePath = realpath(dirname(__FILE__) . '/../public/images/rooms') . '/' . $imageName;
                if (file_exists($imagePath)) unlink($imagePath);
            }

            $stmt = $pdo->prepare("DELETE FROM rooms WHERE id = ?");
            if ($stmt->execute([$roomId])) {
                echo json_encode(['status' => 'success', 'message' => 'Room deleted successfully!']);
            } else {
                throw new Exception('Failed to delete room.');
            }
        }

        // Invalid action for POST requests

        else {
            throw new Exception('Invalid action.');
        }
    } else {
        throw new Exception('Invalid request method.');
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
