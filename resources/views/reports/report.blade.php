@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<link rel="stylesheet" href="{{ asset('css/custom/report.css') }}">

@if(session('success'))
<div class="alert alert-success" style="margin-left: 500px; margin-top: 5px; margin-bottom: -10px">
    {{ session('success') }}
</div>
@endif

<div class="container">
    @if(Auth::user()->role === 'admin')

    <h1>Room Utilization Report</h1>

    {{-- Filter Mode --}}
    <div class="filter-toggle">
        <button class="filter-btn active" data-filter="weekly">Weekly</button>
        <button class="filter-btn" data-filter="monthly">Monthly</button>
        <button class="filter-btn" data-filter="yearly">Yearly</button>
    </div>

    {{-- Chart Area --}}
    <div class="chart-container" style="height: 400px;">
        <canvas id="roomUsageChart"></canvas>
    </div>
    <hr class="section-divider">

    {{-- Table Area --}}
    <h2>Room Usage Table</h2>
    <div class="table-wrapper">
        <table border="1" cellpadding="5" cellspacing="0" class="status-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Applicant</th>
                    <th>Room</th>
                    <th>Date</th>
                    <th>Duration</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usages as $usage)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $usage->applicant_name }}</td>
                    <td>{{ $usage->room_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($usage->booking_date)->format('d-m-Y') }}</td>
                    <td style="display:none" class="start-time">{{ $usage->start_time }}</td>
                    <td style="display:none" class="end-time">{{ $usage->end_time }}</td>
                    <td class="duration"></td>
                    <td>{{ ucfirst($usage->status) }}</td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <hr class="section-divider">
    {{-- Damage Reports --}}
    <h2>Damage Report</h2>

    <div class="table-wrapper">
        <table class="status-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Date</th>
                    <th>Room</th>
                    <th>Damage Type</th>
                    <th>Reporter</th>
                    <th>Photo</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($damageReports as $report)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ \Carbon\Carbon::parse($report->found_date)->format('d-m-Y') }}</td>
                    <td>{{ $report->room }}</td>
                    <td>{{ $report->damage_type }}</td>
                    <td>{{ $report->reporter->first_name ?? 'Unknown' }}</td>
                    <td>
                        @if ($report->photo_path)
                        <button class="btn-view-photo" data-photo="{{ asset('storage/' . $report->photo_path) }}">
                            View
                        </button>
                        @else
                        No photo available
                        @endif
                    </td>
                    <td>
                        <select onchange="confirmStatusChange(this, '{{ $report->id }}')" class="status-dropdown">
                            <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="on progress" {{ $report->status == 'on progress' ? 'selected' : '' }}>On Progress</option>
                            <option value="done" {{ $report->status == 'done' ? 'selected' : '' }}>Done</option>
                        </select>
                    </td>
                    <td>
                        <form action="{{ route('damageReports.destroy', $report->id) }}" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin menghapus laporan ini?');">
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
                    <td colspan="6">No Report Data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{-- Modal untuk Foto --}}
    <div id="photoModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); justify-content:center; align-items:center;">
        <img id="modalImage" src="" alt="Foto laporan" style="max-width:90%; max-height:90%;" />
        <button onclick="hidePhoto()" class="close-photo"></button>
    </div>
    <hr class="section-divider">

    @endif

    <h1>Damage Report</h1>

    {{-- Form Lapor Kerusakan --}}
    <form class="damage-form" action="{{ route('report.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label>Room</label>
        <select name="room" required>
            <option value="">-- Choose Room --</option>
            @foreach($rooms as $room)
            <option value="{{ $room->name }}">
                {{ $room->name }} || {{ $room->location }}
            </option>
            @endforeach
        </select>

        <label>Damage Type</label>
        <input type="text" name="damage_type" placeholder="Example: AC not working" required>

        <label>Date</label>
        <input type="date" name="found_date" required>

        <label>Description</label>
        <textarea rows="3" name="description"></textarea>

        <label>Proof Photo</label>
        <div class="file-upload-wrapper">
            <label for="photo" class="file-upload-label">Choose File</label>
            <input type="file" id="photo" name="photo" />
            <span class="file-name" id="file-name" title="No file chosen">No file chosen</span>
        </div>

        <button type="submit" style="font-family: 'Poppins', sans-serif;">Submit</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/rooms/report.js') }}"></script>


@endsection