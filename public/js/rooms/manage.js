// Tombol + Ruangan 
   document.addEventListener('DOMContentLoaded', function() {
        const openModalBtn = document.getElementById('openModalBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const modal = document.getElementById('addRoomModal');

        function openModal() {
            modal.classList.remove('hidden');
            // Optional: focus first input for accessibility
            modal.querySelector('input, select, textarea').focus();
        }

        function closeModal() {
            modal.classList.add('hidden');
        }

        openModalBtn.addEventListener('click', openModal);
        closeModalBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);

        // Close modal on clicking outside modal-content
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Close modal with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === "Escape" && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });
    });

// Tombol Edit Ruangan 
  document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.edit-btn');
        const editDialog = document.getElementById('editDialog');
        const editOverlay = document.getElementById('editOverlay');
        const editRoomForm = document.getElementById('editRoomForm');
        const editCancelBtn = document.getElementById('editCancelBtn');

        editButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const location = this.getAttribute('data-location');
                const capacity = this.getAttribute('data-capacity');
                const available = this.getAttribute('data-available');

                // Isi form
                document.getElementById('editRoomId').value = id;
                document.getElementById('editName').value = name;
                document.getElementById('editLocation').value = location;
                document.getElementById('editCapacity').value = capacity;
                document.getElementById('editAvailable').value = available;

                // Set action form
                editRoomForm.action = `/rooms/${id}`;

                // Tampilkan dialog dan overlay
                editDialog.classList.add('show');
                editOverlay.classList.add('show');
            });
        });

        // Fungsi untuk sembunyikan dialog dan overlay
        function closeDialog() {
            editDialog.classList.remove('show');
            editOverlay.classList.remove('show');
        }

        editCancelBtn.addEventListener('click', function() {
            closeDialog();
        });

        editOverlay.addEventListener('click', function() {
            closeDialog();
        });
    });

    //delete btn

      document.addEventListener('DOMContentLoaded', function() {
        // Pasang event listener untuk semua tombol delete
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const roomId = this.dataset.id;

                if (confirm('Are you sure you want to delete this room?')) {
                    // Panggil fungsi hapus data ke backend
                    deleteRoom(roomId);
                }
            });
        });
    });

    function deleteRoom(id) {
        fetch(`/rooms/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    // Jika ada CSRF token bisa ditambahkan disini juga
                },
            })
            .then(response => {
                if (response.ok) {
                    alert('Data deleted successfully.');
                    // Reload halaman atau hapus baris tabel yang dihapus secara manual
                    location.reload();
                } else {
                    alert('failed to delete data.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('something went wrong while deleting the room.');
            });
    }