<?php
require_once '../config/config.php';

// Determine if the user is logged in
$isLoggedIn = isset($_SESSION['user']);

//  Database connection
$stmt = $pdo->query("SELECT * FROM rooms");
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms & Suites</title>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/styles.css">
</head>

<body class="bg-light">
    <header class="text-center py-5">
        <h2 class="fw-bold h-font text-danger">Rooms & Suites</h2>
    </header>
    <main class="container">
        <div id="roomContainer" class="row">
            <?php if (count($rooms) === 0): ?>
                <p class="text-center text-warning">No rooms available at the moment.</p>
            <?php else: ?>
                <?php foreach ($rooms as $room): ?>
                    <?php
                    // Room image path

                    $imagePath = !empty($room['image']) ? "../public/images/rooms/{$room['image']}" : '../public/images/default-placeholder.png';
                    $details = !empty($room['details'])
                        ? implode(' ', array_map(fn($detail) => "<span class='badge text-success'>$detail</span>", explode(',', $room['details'])))
                        : '<span class="badge text-secondary">No details available</span>';
                    $facilities = !empty($room['facilities'])
                        ? implode(' ', array_map(fn($facility) => "<span class='badge text-success'>$facility</span>", explode(',', $room['facilities'])))
                        : '<span class="badge text-secondary">No facilities available</span>';
                    $price = !empty($room['price']) ? "$" . htmlspecialchars($room['price']) : 'N/A';
                    ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card border-0 shadow position-relative" style="max-width: 350px; margin:auto;">
                            <img src="<?= htmlspecialchars($imagePath) ?>" class="card-img-top" alt="Room Image">
                            <div class="card-body">
                                <h5><?= htmlspecialchars($room['name']) ?></h5>
                                <h5 class="mb-4">From <?= htmlspecialchars($price) ?> per night</h5>
                                <div class="features mb-4">
                                    <h6>Room Details</h6>
                                    <?= $details ?>
                                </div>
                                <div class="facilities mb-4">
                                    <h6>Facilities</h6>
                                    <?= $facilities ?>
                                </div>
                                <div class="d-flex justify-content-evenly">
                                    <a href="#" class="btn btn-primary book-now-button" data-room-id="<?= $room['id'] ?>">Book Now</a>

                                    <a href="#" class="btn btn-sm btn-success">More Info</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelector('.availability-form form').addEventListener('submit', function(e) {
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
                const statuses = ['Available', 'Few Rooms Left', 'Not Available for Your Dates'];
                const colors = ['bg-success', 'bg-warning', 'bg-danger'];

                // delete existing badge

                const existingBadge = card.querySelector('.badge');
                if (existingBadge) {
                    existingBadge.remove();
                }

                // create new badge
                const badge = document.createElement('span');
                badge.className = `badge position-absolute top-0 start-0 m-2 ${colors[index % statuses.length]}`;
                badge.textContent = statuses[index % statuses.length];
                card.appendChild(badge);
            });
        });
    </script>

    <script>
        const isLoggedIn = <?= json_encode($isLoggedIn) ?>; // Pass PHP variable to JavaScript

        document.addEventListener('DOMContentLoaded', function() {
            const bookNowButtons = document.querySelectorAll('.book-now-button');

            bookNowButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const roomId = this.dataset.roomId;

                    if (isLoggedIn) {
                        // Redirect to the booking page directly if the user is logged in
                        window.location.href = `../admin/booking.php?room_id=${roomId}`;
                    } else {
                        // Show the modal for non-logged-in users
                        const bookingModal = new bootstrap.Modal(document.getElementById('bookingChoiceModal'));
                        bookingModal.show();
                    }
                });
            });

            // Handle Register button click
            document.getElementById('registerButton')?.addEventListener('click', function() {
                const bookingModal = bootstrap.Modal.getInstance(document.getElementById('bookingChoiceModal'));
                bookingModal.hide();

                const registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
                registerModal.show();
            });

            // Handle Guest button click
            document.getElementById('guestButton')?.addEventListener('click', function() {
                window.location.href = `../admin/booking.php?room_id=${roomId}`;
            });
        });
    </script>


</body>

</html>