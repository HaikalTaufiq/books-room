    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('customModal');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const editButtons = document.querySelectorAll('.edit-btn');

        editButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();

                const booking = JSON.parse(this.dataset.booking);

                document.getElementById('room_name').value = booking.room_name ?? '';
                document.getElementById('applicant_name').value = booking.applicant_name ?? '';
                document.getElementById('activity_type').value = booking.activity_type ?? '';
                document.getElementById('capacity').value = booking.capacity ?? '';
                document.getElementById('booking_date').value = booking.booking_date;
                document.getElementById('start_time').value = booking.start_time;
                document.getElementById('end_time').value = booking.end_time;
                document.getElementById('status').value = booking.status;

                const form = document.getElementById('editForm');
                form.action = `/bookings/${booking.id}`;

                modal.style.display = 'flex';
            });
        });

        closeModalBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        // Optional: klik luar modal untuk close
        window.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    });


    function confirmDelete() {
        return confirm("Are you sure you want to delete this booking?");
    }

    function updateStatus(id, selectElement) {
        const status = selectElement.value;

        fetch(`/booking/update-status/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    status
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log(data.message);
            })
            .catch(error => {
                console.error('Update error:', error);
            });
    }
    