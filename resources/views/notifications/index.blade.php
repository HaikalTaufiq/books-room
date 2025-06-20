@extends('layouts.app')

@section('content')
<div class="container">
    <h3>All Notification</h3>

    @foreach ($notifications as $notification)
    @php
    $data = $notification->data;
    $timeAgo = \Carbon\Carbon::parse($notification->created_at)->diffForHumans();
    $title = ucfirst($data['title'] ?? 'Status');
    $message = $data['message'] ?? '';
    @endphp

    <div style="border: 1px solid #ddd; padding: 12px 15px; margin-bottom: 12px; border-radius: 6px; background-color: #f9f9f9;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <strong style="font-size: 1.1rem; color: #2c3e50;">{{ $title }}</strong>
            <span style="color: #999; font-size: 0.85rem;">{{ $timeAgo }}</span>
        </div>
        <p style="margin-top: 6px; color: #555; line-height: 1.4;">{{ $title }}</p>

        <p style="margin-top: 6px; color: #555; line-height: 1.4;">{{ $message }}</p>
    </div>
    @endforeach


</div>
@endsection