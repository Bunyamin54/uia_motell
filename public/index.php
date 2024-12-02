<!doctype html>
<html lang="no">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Motel Booking System</title>

    <!-- CSS -->
    <link rel="stylesheet" href="styles.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <?php
    require_once '../config/config.php';

    // Database connection
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }

    // Shutdown  condition
    
    $stmt = $pdo->prepare("SELECT value FROM settings WHERE name = 'shutdown'");
    $stmt->execute();
    $shutdown = (int) $stmt->fetchColumn();

    if ($shutdown === 1): ?>
        <div class="alert alert-warning text-center" style="position: fixed; top: 0; width: 100%; z-index: 9999;">
            The website is currently under maintenance. Please try again later.
        </div>
    <?php endif; ?>



    <!-- Navbar -->
    <?php include '../inc/navbar.php'; ?>

    <!-- Home Section -->
    <div id="home">
        <?php include '../inc/home.php'; ?>
    </div>

    <!-- Rooms Section -->
    <div id="rooms">
        <?php include '../inc/rooms.php'; ?>
    </div>
 
    <!-- Reviews Section -->
    <div id="reviews">
        <?php include '../inc/reviews.php'; ?>
    </div>



    <!-- Contact Section -->
    <div id="contact">
        <?php include '../inc/contact.php'; ?>
    </div>

    <!-- Footer -->
    <?php include '../inc/footer.php'; ?>




    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">

</body>

</html>