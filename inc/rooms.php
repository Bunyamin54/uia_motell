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
    <h2 class="mt-5 pl-4 mb-4 text-center fw-bold h-font" style="color:#C80F2F;">Our Rooms</h2>
    <div class="container">
        <div class="row" id="roomContainer"></div>
        <div class="col-lg-12 text-center mt-5">
            <a href="#" class="btn btn-sm btn-outline-success rounded-0 fw-bold shadow-none">More Rooms >>></a>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function loadRooms() {
            fetch('../admin/ajax_rooms.php?action=list')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const container = document.getElementById('roomContainer');
                    container.innerHTML = '';
                    data.forEach(room => {
                        const imagePath = room.image ? `../public/images/rooms/${room.image}` : '../public/images/default-placeholder.png';
                        const details = room.details ? room.details.split(',').map(detail => `<span class="badge bg-success text-wrap">${detail}</span>`).join(' ') : 'No details available';
                        const facilities = room.facilities ? room.facilities.split(',').map(facility => `<span class="badge bg-success text-wrap">${facility}</span>`).join(' ') : 'No facilities available';

                        container.innerHTML += `
                            <div class="col-lg-4 col-md-6 my-3">
                                <div class="card border-0 shadow" style="max-width: 350px; margin:auto;">
                                    <img src="${imagePath}" class="card-img-top" alt="Room Image">
                                    <div class="card-body">
                                        <h5>${room.name}</h5>
                                        <h5 class="mb-4">From $${room.price || 'N/A'} per night</h5>
                                        <div class="features mb-4">
                                            <h6 class="mb-1">Room Details</h6>
                                            ${details}
                                        </div>
                                        <div class="facilities mb-4">
                                            <h6 class="mb-1">Facilities</h6>
                                            ${facilities}
                                        </div>
                                        <div class="d-flex justify-content-evenly mb-2">
                                            <a href="#" class="btn btn-sm btn-danger">Book Now</a>
                                            <a href="#" class="btn btn-sm btn-success">More Info</a>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                    });
                })
                .catch(error => {
                    console.error('Error loading rooms:', error);
                    const container = document.getElementById('roomContainer');
                    container.innerHTML = `<p class="text-center text-danger">Failed to load rooms. Please try again later.</p>`;
                });
        }
        document.addEventListener('DOMContentLoaded', loadRooms);
    </script>
</body>
</html>
