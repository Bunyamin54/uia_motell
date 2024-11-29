<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Rooms</title>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/styles.css">
</head>

<body class="bg-light">
    <header class="text-center py-5">
        <h2 class="fw-bold h-font text-danger">Our Rooms</h2>
    </header>
    <main class="container">
        <div class="row" id="roomContainer"></div>
        <div class="text-center mt-5">
            <a href="#" class="btn btn-outline-success btn-sm rounded-0 shadow-none">More iiiRooms >>></a>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        async function loadRooms() {
            const container = document.getElementById('roomContainer');
            try {
                const response = await fetch('../admin/ajax_rooms.php?action=list');
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const rooms = await response.json();
                container.innerHTML = ''; // Clear existing content

                if (rooms.length === 0) {
                    container.innerHTML = '<p class="text-center text-warning">No rooms available at the moment.</p>';
                    return;
                }

                rooms.forEach(room => {
                    // Fallbacks for missing data
                    const imagePath = room.image ? `../public/images/rooms/${room.image}` : '../public/images/default-placeholder.png';
                    const details = room.details
                        ? room.details.split(',').map(detail => `<span class="badge text-success">${detail}</span>`).join(' ')
                        : '<span class="badge text-secondary">No details available</span>';
                    const facilities = room.facilities
                        ? room.facilities.split(',').map(facility => `<span class="badge text-success">${facility}</span>`).join(' ')
                        : '<span class="badge text-secondary">No facilities available</span>';
                    const price = room.price ? `$${room.price}` : 'N/A';

                    // Room Card
                    container.innerHTML += `
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card border-0 shadow" style="max-width: 350px; margin:auto;">
                                <img src="${imagePath}" class="card-img-top" alt="Room Image">
                                <div class="card-body">
                                    <h5>${room.name}</h5>
                                    <h5 class="mb-4">From ${price} per night</h5>
                                    <div class="features mb-4">
                                        <h6>Room Details</h6>
                                        ${details}
                                    </div>
                                    <div class="facilities mb-4">
                                        <h6>Facilities</h6>
                                        ${facilities}
                                    </div>
                                    <div class="d-flex justify-content-evenly">
                                        <a href="../admin/booking.php?room_id=${room.id}" class="btn btn-primary">Book Now</a>
                                        <a href="#" class="btn btn-sm btn-success">More Info</a>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                });
            } catch (error) {
                console.error('Error loading rooms:', error);
                container.innerHTML = '<p class="text-center text-danger">Failed to load rooms. Please try again later.</p>';
            }
        }

        // Load rooms on page load
        document.addEventListener('DOMContentLoaded', loadRooms);
    </script>
</body>

</html>
