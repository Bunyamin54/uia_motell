<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../config/config.php';

// Admin control
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}


// Database connection
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$targetDir = "../public/images/home/";

// Create directory if missing
if (!is_dir($targetDir)) {
    if (!mkdir($targetDir, 0777, true)) {
        error_log("Failed to create target directory: " . $targetDir);
        die("Failed to create target directory. Please check permissions.");
    }
    error_log("Target directory created: " . $targetDir);
}

// Check if target directory is writable
if (!is_writable($targetDir)) {
    error_log("PHP cannot write to target directory: " . $targetDir);
    die("Target directory is not writable. Please check permissions.");
} else {
    error_log("PHP can write to target directory: " . $targetDir);
}



// Handle image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['home_image'])) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxSize = 2 * 1024 * 1024; // 2 MB
    $file = $_FILES['home_image'];

    error_log("File upload initiated.");

    if ($file['error'] !== UPLOAD_ERR_OK) {
        error_log("Upload error code: " . $file['error']);
        $_SESSION['message'] = "Upload error code: " . $file['error'];
        $_SESSION['message_type'] = 'danger';
    } elseif (!in_array(mime_content_type($file['tmp_name']), $allowedTypes)) {
        error_log("Invalid file type: " . mime_content_type($file['tmp_name']));
        $_SESSION['message'] = "Invalid file type. Only JPG, PNG, or GIF allowed.";
        $_SESSION['message_type'] = 'danger';
    } elseif ($file['size'] > $maxSize) {
        error_log("File size exceeds limit: " . $file['size']);
        $_SESSION['message'] = "File size exceeds 2 MB limit.";
        $_SESSION['message_type'] = 'danger';
    } else {
        $fileName = uniqid() . '_' . basename($file['name']);
        $targetFile = $targetDir . $fileName;

        error_log("Target file path: " . $targetFile);

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            error_log("File moved successfully.");
        
            // Database insertion

            $imagePath = "../public/images/home/" . $fileName;  // Relative path
            try {
                $stmt = $pdo->prepare("INSERT INTO homepage_images (image_path) VALUES (:image_path)");
                $stmt->execute([':image_path' => $imagePath]);
                error_log("Database insertion successful for file: " . $imagePath);
                $_SESSION['message'] = "Image uploaded successfully!";
                $_SESSION['message_type'] = 'success';
            } catch (PDOException $e) {
                error_log("Database error: " . $e->getMessage());
                $_SESSION['message'] = "Database error: " . $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }
        }
        else {
            error_log("Failed to move uploaded file.");
            $_SESSION['message'] = "Failed to upload image. Check directory permissions.";
            $_SESSION['message_type'] = 'danger';
        }
    }
    header('Location: home.php');
    exit;
}


// Handle image delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['image_id'])) {
    $imageId = $_POST['image_id'];

    // Fetch image path from database
    $stmt = $pdo->prepare("SELECT image_path FROM homepage_images WHERE id = :id");
    $stmt->execute([':id' => $imageId]);
    $image = $stmt->fetch();

    if ($image) {
        $filePath = $targetDir . $image['image_path'];

        // Remove image file from directory
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Delete record from database
        $deleteStmt = $pdo->prepare("DELETE FROM homepage_images WHERE id = :id");
        $deleteStmt->execute([':id' => $imageId]);

        $_SESSION['message'] = "Image deleted successfully!";
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = "Image not found.";
        $_SESSION['message_type'] = 'danger';
    }
    header('Location: home.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Home Page Images</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-success">Edit Home Page Images</h1>

        <!-- Toast for notifications -->
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


        <!-- Image upload form -->
        <form action="home.php" method="POST" enctype="multipart/form-data" class="mt-4">
            <div class="mb-3">
                <label for="home_image" class="form-label">Select Image</label>
                <input type="file" name="home_image" id="home_image" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload Image</button>
        </form>

        <h3 class="mt-5">Uploaded Images</h3>
        <div class="row">
            <?php
            $stmt = $pdo->query("SELECT * FROM homepage_images");
            while ($row = $stmt->fetch()) {
                echo '<div class="col-md-4 mt-3">';
                echo '<img src="' . htmlspecialchars($row['image_path']) . '" class="img-fluid" />';
                echo '<form action="home.php" method="POST" class="mt-2">';
                echo '<input type="hidden" name="image_id" value="' . $row['id'] . '">';
                echo '<button type="submit" class="btn btn-danger">Delete</button>';
                echo '</form>';
                echo '</div>';
            }
            ?>
        </div>
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