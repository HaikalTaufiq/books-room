@extends('layouts.app')

@section('title', 'Booking Status')

@section('content')
<link href="{{ asset('css/custom/status.css') }}" rel="stylesheet">
@section('script')
<script src="{{ asset('js/rooms/status.js') }}"></script>
@endsection

<div class="status-page">
    <div class="header-actions">
        <h1 class="status-title">Reservation Status</h1>
        <form method="GET" action="{{ route('bookings.index') }}">
            <input
                type="text"
                name="search"
                placeholder="Search..."
                value="{{ request('search') }}">
            <button type="submit" class="btn-primary">Search</button>
        </form>
    </div>

    <div class="table-wrapper">

        <table class="status-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Room</th>
                    <th>Applicant</th>
                    <th>Activity</th>
                    <th>Capacity</th>
                    <th>Date</th>
                    <th>Start</th>
                    <th>Finish</th>
                    <th>Status</th>
                    <th>Action</th>

                </tr>
            </thead>
            <tbody>
                @forelse ($bookings as $index => $booking)
                <tr>
                    <td>{{ $bookings->firstItem() + $index }}</td>
                    <td>{{ $booking->room_name ?? '-' }}</td>
                    <td>{{ $booking->applicant_name }}</td>
                    <td>{{ $booking->activity_type }}</td>
                    <td>{{ $booking->capacity }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</td>
                    <td>
                        <span class="status {{ strtolower($booking->status) }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="#" class="edit-btn" data-booking='@json($booking)'>
                            <i class="fas fa-edit text-primary"></i>
                        </a>

                        <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete();">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:none;border:none; color:red; margin-left:5px;">
                                <i class="fas fa-trash fa-lg"></i>
                            </button>
                        </form>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center;">No data available</td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>

    <div style="margin-top: 1rem;">
        {{ $bookings->withQueryString()->links() }}
    </div>
</div>
<!-- Modal Edit Booking -->
<div id="customModal" class="modal-overlay" style="display: none;">
    <div class="modal-container">
        <form method="POST" id="editForm">
            @csrf
            @method('PUT')
            <h2>Edit Peminjaman</h2>
            <select name="room_name" id="room_name" class="modal-input">
                @foreach ($rooms as $room)
                <option value="{{ $room->name }}"
                    {{ (old('room_name', $booking->room_name ?? '') == $room->name) ? 'selected' : '' }}>
                    {{ $room->name }}
                </option>
                @endforeach
            </select>

            <input type="text" name="applicant_name" id="applicant_name" placeholder="Nama Pengaju" class="modal-input">
            <input type="text" name="activity_type" id="activity_type" placeholder="Jenis Kegiatan" class="modal-input">
            <input type="number" name="capacity" id="capacity" placeholder="Kapasitas" class="modal-input">
            <input type="date" name="booking_date" id="booking_date" class="modal-input">
            <input type="time" name="start_time" id="start_time" class="modal-input">
            <input type="time" name="end_time" id="end_time" class="modal-input">

            <select name="status" id="status" class="modal-input">
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="decline">Decline</option>
            </select>

            <div class="modal-buttons">
                <button type="submit" class="modal-save">Simpan</button>
                <button type="button" id="closeModalBtn" class="modal-close">Batal</button>
            </div>
        </form>
    </div>
</div>


@endsection