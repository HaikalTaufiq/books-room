@extends('layouts.app')

@section('title', 'My Borrow Status')

@section('content')
<link href="{{ asset('css/custom/status.css') }}" rel="stylesheet">
<script>
    function confirmDelete() {
        return confirm('are you sure you want to cancel this booking?');
    }
</script>

<div class="status-page">
    <div class="header-actions">
        <h1 class="status-title">My Borrow Status</h1>
        <form action="{{ route('booking.myStatus') }}" method="GET">
            <input
                type="text"
                name="search"
                placeholder="Search..."
                value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>
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
                @php $filteredBookings = $bookings->where('user_id', auth()->id()); @endphp

                @forelse ($filteredBookings as $index => $booking)
                <tr>
                    <td>{{ $index + 1 }}</td>
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
                        <form action="{{ route('bookings.destroy2', $booking->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete();">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:none;border:none; color:red; margin-left: 15px" title="Batalkan Ajuan">
                                <i class="fas fa-trash text-danger fa-lg"></i>
                            </button>
                        </form>
                    </td>


                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align: center;">No Data Available</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1rem;">
        {{ $bookings->links() }}
    </div>
</div>
@endsection