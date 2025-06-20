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
                <button class="btn btn-primary bookingBtn">
                    <a href="/apply" class="btn btn-primary">
                        Book Now
                    </a>
                </button>
                <button class="btn btn-secondary">
                    @if($room->available)
                    <span class="badge bg-success">Available</span>
                    @else
                    <span class="badge bg-danger">Not Available</span>
                    @endif
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
            if (userRole === 'guest') {
                e.preventDefault();
                alert('Silakan login terlebih dahulu untuk mengajukan peminjaman ruangan.');
            } else {
                window.location.href = "/apply";
            }
        });
    });
</script>


@endsection