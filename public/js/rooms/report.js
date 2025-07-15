document.addEventListener('DOMContentLoaded', function () {
    // ==========================
    // 1. Chart Room Usage
    // ==========================
    const chartCanvas = document.getElementById('roomUsageChart');
    if (chartCanvas) {
        const ctx = chartCanvas.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, '#4e73df');
        gradient.addColorStop(1, '#1cc88a');

        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Number of room usages (Approved)',
                    data: [],
                    backgroundColor: gradient,
                    borderRadius: 10,
                    hoverBackgroundColor: '#2e59d9',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } },
                    x: { grid: { display: false } }
                }
            }
        });

        async function fetchChartData(filter) {
            try {
                const res = await fetch(`/room-usage-data/${filter}`);
                const data = await res.json();
                chart.data.labels = data.map(item => item.room_name);
                chart.data.datasets[0].data = data.map(item => item.total);
                chart.update();
            } catch (err) {
                console.error('Error fetching chart data:', err);
            }
        }

        fetchChartData('weekly');

        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                fetchChartData(btn.dataset.filter);
            });
        });
    }

    // ==========================
    // 2. Duration Calculation
    // ==========================
    document.querySelectorAll('tbody tr').forEach(row => {
        const startTime = row.querySelector('.start-time')?.textContent.trim();
        const endTime = row.querySelector('.end-time')?.textContent.trim();

        if (startTime && endTime) {
            const start = new Date(`1970-01-01T${startTime}`);
            const end = new Date(`1970-01-01T${endTime}`);

            let diff = (end - start) / 60000; // in minutes
            if (diff < 0) diff += 1440; // if crosses midnight

            const hours = Math.floor(diff / 60);
            const minutes = diff % 60;
            row.querySelector('.duration').textContent =
                `${hours > 0 ? hours + ' hour ' : ''}${minutes > 0 ? minutes + ' minute' : '0 minute'}`;
        }
    });

    // ==========================
    // 3. Photo Modal View
    // ==========================
    const modal = document.getElementById('photoModal');
    const modalImage = document.getElementById('modalImage');

    if (modal && modalImage) {
        document.querySelectorAll('.btn-view-photo').forEach(button => {
            button.addEventListener('click', function () {
                const url = this.getAttribute('data-photo');
                modalImage.src = url;
                modal.style.display = 'flex';
            });
        });

        modal.addEventListener('click', function (e) {
            // Close if clicked outside image
            if (!modalImage.contains(e.target)) {
                modal.style.display = 'none';
                modalImage.src = '';
            }
        });

        const closeButton = document.querySelector('.close-photo');
        if (closeButton) {
            closeButton.addEventListener('click', function () {
                modal.style.display = 'none';
                modalImage.src = '';
            });
        }
    }

    // ==========================
    // 4. File Input Name Display
    // ==========================
    const inputFile = document.getElementById('photo');
    const fileNameDisplay = document.getElementById('file-name');

    if (inputFile && fileNameDisplay) {
        inputFile.addEventListener('change', () => {
            fileNameDisplay.textContent = inputFile.files.length > 0
                ? inputFile.files[0].name
                : 'No file chosen';
        });
    }

    // ==========================
    // 5. Update Damage Report Status
    // ==========================
    window.confirmStatusChange = function (selectElement, reportId) {
        const newStatus = selectElement.value;

        if (confirm(`Are you sure to change this status to "${newStatus}"?`)) {
            fetch(`/damage-reports/${reportId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ status: newStatus }),
            })
            .then(response => {
                if (!response.ok) throw new Error('Failed to update status');
                return response.json();
            })
            .then(() => alert('Status updated successfully'))
            .catch(error => alert('Something went wrong: ' + error.message));
        } else {
            selectElement.value = selectElement.getAttribute('data-original');
        }
    };
});
