<?php
// Database connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Retrieve homepage images

$stmt = $pdo->query("SELECT image_path FROM homepage_images");
$images = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Booking</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery UI CSS -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../public/styles.css">

    <style>
        .custom-bg {
            background-color: #007BFF;
            border: none;
        }

        .custom-bg:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>

    <!-- Swiper Section -->
    <div class="container-fluid px-lg-4 mt-4">
    <div class="swiper swiper-container">
    <div class="swiper-wrapper">
        <?php foreach ($images as $image): ?>
            <div class="swiper-slide">
                <img src="<?php echo htmlspecialchars($image['image_path']); ?>" class="w-100 d-block" style="height: 575px; object-fit: cover;">
            </div>
        <?php endforeach; ?>
    </div>
    <div class="swiper-pagination"></div>
</div>

    </div>

    <!-- Availability Form Section -->
    <div class="container availability-form mt-4">
        <div class="row">
            <div class="col-lg-12 bg-white shadow p-4 rounded">
                <h5 class="mb-4"style="color:#C80F2F;">Search for Available Rooms</h5>
                <form>
                    <div class="row align-items-end">
                        <div class="col-lg-3 mb-3">
                            <label for="checkInDatePicker" class="form-label" style="font-weight:500;">Check-In</label>
                            <input id="checkInDatePicker" type="text" class="form-control shadow-none" placeholder="Select Check-In Date">
                        </div>
                        <div class="col-lg-3 mb-3">
                            <label for="checkOutDatePicker" class="form-label" style="font-weight:500;">Check-Out</label>
                            <input id="checkOutDatePicker" type="text" class="form-control shadow-none" placeholder="Select Check-Out Date">
                        </div>
                        <div class="col-lg-3 mb-3">
                            <label class="form-label" style="font-weight:500;">Adults</label>
                            <select class="form-select shadow-none">
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                        <div class="col-lg-2 mb-3">
                            <label class="form-label" style="font-weight:500;">Children</label>
                            <select class="form-select shadow-none">
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                        <div class="col-12 col-lg-1 mb-lg-3 mt-2">
                            <button type="submit" class="btn text-success shadow-none custom-bg">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <!-- Swiper JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="../public/scripts.js"></script>

    <script>
        $(function () {
            $('#checkInDatePicker').datepicker({
                showButtonPanel: true,
                dateFormat: 'dd-mm-yy',
                minDate: 0,
                onSelect: function (selectedDate) {
                    const minCheckOutDate = new Date(selectedDate);
                    minCheckOutDate.setDate(minCheckOutDate.getDate() + 1);
                    $('#checkOutDatePicker').datepicker('option', 'minDate', minCheckOutDate);
                }
            });

            $('#checkOutDatePicker').datepicker({
                showButtonPanel: true,
                dateFormat: 'dd-mm-yy',
                minDate: 1
            });
        });
    </script>

 <script>
document.querySelector('.availability-form form').addEventListener('submit', function (e) {
    e.preventDefault(); // Form submission is prevented

    const roomContainer = document.getElementById('roomContainer');
    if (!roomContainer) {
        alert('Room container not found!');
        return;
    }

    const roomCards = roomContainer.querySelectorAll('.card');

    if (roomCards.length === 0) {
        alert('No rooms found to update!');
        return;
    }

    roomCards.forEach((card, index) => {
        const statuses = ['Available', 'Few Rooms Left', 'Fully Booked'];
        const colors = ['bg-success', 'bg-warning', 'bg-danger'];

        // available, few rooms left, fully booked
        
        const existingBadge = card.querySelector('.badge');
        if (existingBadge) {
            existingBadge.remove();
        }

        // You can use the index to cycle through the statuses array
        
        const badge = document.createElement('span');
        badge.className = `badge position-absolute top-0 start-0 m-2 ${colors[index % statuses.length]}`;
        badge.textContent = statuses[index % statuses.length];
        card.appendChild(badge);
    });
});
</script>



</body>

</html>
