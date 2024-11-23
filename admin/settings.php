  <!-- //* settings page for admin -->
  
  <?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

// Save settings to a configuration file
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings = [
        'site_name' => $_POST['site_name'],
        'admin_email' => $_POST['admin_email'],
    ];
    file_put_contents('../config/site_settings.json', json_encode($settings));
    echo "<script>alert('Settings updated successfully!');</script>";
}

// Load settings
$settings = json_decode(file_get_contents('../config/site_settings.json'), true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Site Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Site Settings</h1>
        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="site_name" class="form-label">Site Name</label>
                <input type="text" name="site_name" value="<?php echo $settings['site_name']; ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="admin_email" class="form-label">Admin Email</label>
                <input type="email" name="admin_email" value="<?php echo $settings['admin_email']; ?>" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-warning">Save Settings</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
