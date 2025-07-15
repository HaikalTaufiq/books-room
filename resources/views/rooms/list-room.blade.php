@extends('layouts.app')

@section('title', 'Room List')

@section('content')
<link rel="stylesheet" href="{{ asset('css/custom/list.css') }}">

<div class="container-ruangan">
    <h1 class="judul-halaman">Room List</h1>
    <div class="ruangan-list">
        @forelse ($rooms as $room)
        <div class="ruangan-card">
            <div class="ruangan-info">
                <h2>{{ $room->name }}</h2>
                <p><strong>Location:</strong> {{ $room->location }}</p>
                <p><strong>Capacity:</strong> {{ $room->capacity }} people</p>

            </div>
            <div class="ruangan-actions">
                <button
                    class="btn btn-primary bookingBtn"
                    data-available="{{ $room->available ? '1' : '0' }}"
                    data-room-name="{{ $room->name }}">
                    Book Now
                </button>

                <button class="btn {{ $room->available ? 'btn-available' : 'btn-unavailable' }}">
                    {{ $room->available ? 'Available' : 'Not Available' }}
                </button>

            </div>
        </div>
        @empty
        <p class="text-center">Tidak ada ruangan tersedia.</p>
        @endforelse
    </div>
</div>

<script>
    const userRole = "{{ Auth::check() ? Auth::user()->role : 'guest' }}";

    document.querySelectorAll('.bookingBtn').forEach(button => {
        button.addEventListener('click', function(e) {
            const isAvailable = this.dataset.available === '1';
            const roomName = this.dataset.roomName;

            if (userRole === 'guest') {
                alert('Please log in first to submit a room reservation request.');
                return;
            }

            if (!isAvailable) {
                alert(`The room "${roomName}" is currently unavailable due to maintenance or scheduling. Please choose another room.`);
                return;
            }

            // Redirect hanya jika room tersedia dan user bukan guest
            window.location.href = `/apply?room_name=${encodeURIComponent(roomName)}`;
        });
    });
</script>


@endsection