@extends('layouts.app')

@section('title', 'Apply Room Reservation')

@section('content')
<link href="{{ asset('css/custom/apply.css') }}" rel="stylesheet">
@if(session('success'))
<div class="alert alert-success" style="margin-left: 500px; margin-top: 5px; margin-bottom: -10px">
    {{ session('success') }}
</div>
@endif


<div class="apply-page">
    <h1 class="apply-title">Apply Room Reservation</h1>

    <form class="apply-form" method="POST" action="{{ route('booking.store') }}">
        @csrf

        <div class="form-group">
            <label>Applicant Name</label>
            <input type="text" name="applicant_name" value="{{ old('applicant_name', Auth::user()->first_name) }}" required>
        </div>

        <div class="form-group">
            <label>Room Name</label>
            <select name="room_name" required>
                <option value="">-- Choose Room --</option>
                @foreach($rooms as $room)
                <option value="{{ $room->name }}">
                    {{ $room->name }} || {{ $room->location }}
                </option>
                @endforeach
            </select>

        </div>


        <div class="form-group">
            <label>Activity</label>
            <input type="text" name="activity_type" required>
        </div>

        <div class="form-group">
            <label>Capacity</label>
            <input type="number" name="usage_capacity" min="1" required>
        </div>

        <div class="form-group">
            <label>Date</label>
            <input type="date" name="borrow_date" required pattern="\d{2}-\d{2}-\d{4}">
        </div>

        <div class="form-row">
            <div class="form-group-half">
                <label>Start</label>
                <input type="time" name="start_time" required>
            </div>

            <div class="form-group-half">
                <label>Finish</label>
                <input type="time" name="end_time" required>
            </div>
        </div>

        <button type="submit" class="apply-btn" style="font-family: 'Poppins', sans-serif;">Apply</button>
    </form>
</div>


<script>
    document.querySelector('.apply-form').addEventListener('submit', function(e) {
        const startTime = document.querySelector('input[name="start_time"]').value;
        const endTime = document.querySelector('input[name="end_time"]').value;

        const start = parseInt(startTime.replace(':', ''), 10);
        const end = parseInt(endTime.replace(':', ''), 10);

        // 08:00 = 800, 17:00 = 1700
        if (start < 800 || end > 1700) {
            e.preventDefault(); // Stop form submit
            alert("Room usage time is limited to 08:00 - 17:00 only.");
        }
    });
</script>

@endsection