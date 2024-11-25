<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Rooms</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/styles.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-success">Manage Rooms</h1>
        <div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3"></div>

        <!-- Add Room Form -->
        <form id="addRoomForm" enctype="multipart/form-data" class="mt-4">
            <h3 class="text-warning">Add New Room</h3>
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
            <div class="mb-3">
                <input type="file" name="room_image" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Add Room</button>
        </form>

        <h3 class="mt-5">All Rooms</h3>
        <div id="roomContainer" class="mt-3"></div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toast notifications
        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast text-bg-${type} show mb-2`;
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>`;
            toastContainer.appendChild(toast);
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        // Load rooms
        async function loadRooms() {
            try {
                const response = await fetch('ajax_rooms.php?action=list');
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

                const data = await response.json();
                if (!Array.isArray(data)) throw new Error(data.message || 'Unexpected response format.');

                const container = document.getElementById('roomContainer');
                container.innerHTML = '';

                data.forEach(room => {
                    const imagePath = room.image ? `../public/images/rooms/${room.image}` : 'https://via.placeholder.com/500';
                    container.innerHTML += `
                        <div class="card mb-3">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img src="${imagePath}" class="img-fluid rounded-start" alt="Room Image">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title">${room.name}</h5>
                                        <p class="card-text">${room.type} - ${room.capacity} beds</p>
                                        <p class="card-text">Status: 
                                            <span class="badge bg-${room.status === 'available' ? 'success' : 'danger'}">
                                                ${room.status}
                                            </span>
                                        </p>
                                        <button class="btn btn-primary btn-sm" onclick="editRoom(${room.id})">Edit</button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteRoom(${room.id})">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                });
            } catch (error) {
                console.error('Error loading rooms:', error);
                showToast(`Error loading rooms: ${error.message}`, 'danger');
            }
        }

        // Add room
        document.getElementById('addRoomForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'add_room');

            try {
                const response = await fetch('ajax_rooms.php', { method: 'POST', body: formData });
                const data = await response.json();
                showToast(data.message, data.status === 'success' ? 'success' : 'danger');
                if (data.status === 'success') {
                    this.reset();
                    loadRooms();
                }
            } catch (error) {
                console.error('Error adding room:', error);
                showToast('Failed to add room.', 'danger');
            }
        });

        // Delete room
        async function deleteRoom(id) {
            const formData = new FormData();
            formData.append('action', 'delete_room');
            formData.append('room_id', id);

            try {
                const response = await fetch('ajax_rooms.php', { method: 'POST', body: formData });
                const data = await response.json();
                showToast(data.message, data.status === 'success' ? 'success' : 'danger');
                if (data.status === 'success') loadRooms();
            } catch (error) {
                console.error('Error deleting room:', error);
                showToast('Failed to delete room.', 'danger');
            }
        }

        document.addEventListener('DOMContentLoaded', loadRooms);
    </script>
</body>

</html>
