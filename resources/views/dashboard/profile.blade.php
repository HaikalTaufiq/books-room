@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard/profile.css') }}" />
<div class="profile-container">
    <h1>Edit Profile</h1>

    @if(session('success'))
    <div class="success-message">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf

        <div class="form-group">
            <label>First Name</label>
            <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}">
            @error('first_name') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}">
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}">
            @error('email') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>New Password (optional)</label>
            <input type="password" name="password">
        </div>

        <div class="form-group">
            <label>Confirm New Password</label>
            <input type="password" name="password_confirmation">
            @error('password') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <button class="submit-btn" type="submit">Update Profile</button>
    </form>
</div>
@endsection