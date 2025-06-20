 //chart
  (() => {
        const ctx = document.getElementById('roomUsageChart').getContext('2d');
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
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        async function fetchChartData(filter) {
            const res = await fetch(`/room-usage-data/${filter}`);
            const data = await res.json();
            const labels = data.map(item => item.room_name);
            const counts = data.map(item => item.total);

            chart.data.labels = labels;
            chart.data.datasets[0].data = counts;
            chart.update();
        }

        fetchChartData('weekly');

        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                fetchChartData(btn.dataset.filter);
            });
        });
    })();

//duration use
   document.querySelectorAll('tbody tr').forEach(row => {
        const startTime = row.querySelector('.start-time')?.textContent.trim();
        const endTime = row.querySelector('.end-time')?.textContent.trim();

        if (startTime && endTime) {
            const start = new Date(`1970-01-01T${startTime}`);
            const end = new Date(`1970-01-01T${endTime}`);

            let diff = (end - start) / 60000; // selisih menit
            if (diff < 0) diff += 1440; // crossing midnight

            const hours = Math.floor(diff / 60);
            const minutes = Math.floor(diff % 60);
            row.querySelector('.duration').textContent = `${hours > 0 ? hours + ' hour ' : ''}${minutes > 0 ? minutes + ' minute' : ''}` || '0 minute';
        }
    });

 //photo view
 
 document.querySelectorAll('.btn-view-photo').forEach(button => {
        button.addEventListener('click', function() {
            const url = this.getAttribute('data-photo');
            document.getElementById('modalImage').src = url;
            document.getElementById('photoModal').style.display = 'flex';
        });
    });

    function hidePhoto() {
        document.getElementById('photoModal').style.display = 'none';
    }

document.getElementById('photoModal').addEventListener('click', function (e) {
    const modalImage = document.getElementById('modalImage');
    if (!modalImage.contains(e.target)) {
        hidePhoto();
    }
});

      const input = document.getElementById('photo');
  const fileName = document.getElementById('file-name');

  input.addEventListener('change', () => {
    if (input.files.length > 0) {
      fileName.textContent = input.files[0].name;
    } else {
      fileName.textContent = 'No file chosen';
    }
  });

//status damage edit
    function confirmStatusChange(selectElement, reportId) {
        const newStatus = selectElement.value;

        if (confirm(`Are you sure to change this status "${newStatus}"?`)) {
            fetch(`/damage-reports/${reportId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ status: newStatus }),
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to update status');
                }
                return response.json();
            })
            .then(data => {
                alert('Status updated successfully');
            })
            .catch(error => {
                alert('Something Wrong: ' + error.message);
            });
        } else {
            // Reset kembali ke value sebelumnya jika user batal
            selectElement.value = selectElement.getAttribute('data-original');
        }
    }

