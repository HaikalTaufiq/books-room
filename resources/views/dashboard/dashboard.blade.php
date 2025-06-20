@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div>
    <div class="dashboard-stats">
        <div class="stat-card">
            <a href="/list" style="text-decoration: none; display: block; color: inherit;">
                <h5>Total Rooms</h5>
                <div class="stat-number">{{ $totalRooms }}</div>
            </a>
        </div>

        <div class="stat-card">
            <h5>Today's Booking</h5>
            <div class="stat-number">{{ $bookings }}</div>
        </div>

        <div class="stat-card">
            <a href="/list" style="text-decoration: none; display: block; color: inherit;">
                <h5>Available Rooms</h5>
                <div class="stat-number">{{ $availableRooms }}</div>
            </a>
        </div>
    </div>


    <div id="calendar"></div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/dashboard/dashboard.js') }}"></script>
@endsection