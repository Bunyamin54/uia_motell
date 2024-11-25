<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Rooms</title>
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
                <input type="number" step="0.01" name="price" placeholder="Price (per night)" class="form-control" required>
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
                <textarea name="details" placeholder="Room Details" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <textarea name="facilities" placeholder="Facilities" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <input type="file" name="room_image" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Add Room</button>
        </form>

        <h3 class="mt-5">All Rooms</h3>
        <div id="roomContainer" class="mt-3"></div>
    </div>

    <!-- Edit Room Modal -->
    <div class="modal fade" id="editRoomModal" tabindex="-1" aria-labelledby="editRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editRoomForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editRoomModalLabel">Edit Room</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="room_id" id="edit_room_id">
                        <div class="mb-3">
                            <label for="edit_room_name" class="form-label">Room Name</label>
                            <input type="text" name="room_name" id="edit_room_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_room_type" class="form-label">Room Type</label>
                            <select name="room_type" id="edit_room_type" class="form-select">
                                <option value="Single">Single</option>
                                <option value="Double">Double</option>
                                <option value="Suite">Suite</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_price" class="form-label">Price (per night)</label>
                            <input type="number" step="0.01" name="price" id="edit_price" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_capacity" class="form-label">Capacity</label>
                            <input type="number" name="capacity" id="edit_capacity" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_status" class="form-label">Status</label>
                            <select name="status" id="edit_status" class="form-select">
                                <option value="available">Available</option>
                                <option value="unavailable">Unavailable</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="details" class="form-label">Room Details</label>
                            <textarea name="details" id="details" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="facilities" class="form-label">Facilities</label>
                            <textarea name="facilities" id="facilities" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_room_image" class="form-label">Room Image</label>
                            <input type="file" name="room_image" id="edit_room_image" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
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
                    const imagePath = room.image ? `../public/images/rooms/${room.image}` : '../public/images/default-placeholder.png';
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
        document.getElementById('addRoomForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'add_room');

            try {
                const response = await fetch('ajax_rooms.php', {
                    method: 'POST',
                    body: formData
                });
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

        async function editRoom(id) {
            try {
                const response = await fetch(`ajax_rooms.php?action=get_room&id=${id}`);
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

                const room = await response.json();
                if (!room) throw new Error('Room not found.');

                document.getElementById('edit_room_id').value = room.id;
                document.getElementById('edit_room_name').value = room.name;
                document.getElementById('edit_room_type').value = room.type;
                document.getElementById('edit_capacity').value = room.capacity;
                document.getElementById('edit_status').value = room.status;
                document.getElementById('edit_price').value = room.price || 0;
                document.getElementById('details').value = room.details || '';
                document.getElementById('facilities').value = room.facilities || '';

                const editModal = new bootstrap.Modal(document.getElementById('editRoomModal'));
                editModal.show();
            } catch (error) {
                console.error('Error loading room details:', error);
                showToast('Failed to load room details.', 'danger');
            }
        }

        // Update Room
        document.getElementById('editRoomForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'update_room');

            try {
                const response = await fetch('ajax_rooms.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                showToast(data.message, data.status === 'success' ? 'success' : 'danger');
                if (data.status === 'success') {
                    loadRooms();
                    const editModal = bootstrap.Modal.getInstance(document.getElementById('editRoomModal'));
                    editModal.hide();
                }
            } catch (error) {
                console.error('Error updating room:', error);
                showToast('Failed to update room.', 'danger');
            }
        });

        // Delete room
        async function deleteRoom(id) {
            const formData = new FormData();
            formData.append('action', 'delete_room');
            formData.append('room_id', id);

            try {
                const response = await fetch('ajax_rooms.php', {
                    method: 'POST',
                    body: formData
                });
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