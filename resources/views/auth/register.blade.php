@extends('layouts.app')

@section('title', 'Register')

@section('content')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}" />

<div class="register-container">
    <h2>Create Account</h2>

    <form action="{{ route('register') }}" method="POST">
        @csrf

        <div class="input-row">
            <div class="input-group">
                <label for="first-name">First Name</label>
                <input type="text" name="first_name" id="first-name" placeholder="First Name" value="{{ old('first_name') }}" required />
            </div>
            <div class="input-group">
                <label for="last-name">Last Name</label>
                <input type="text" name="last_name" id="last-name" placeholder="Last Name" value="{{ old('last_name') }}" />
            </div>
        </div>

        <div class="input-row">
            <div class="input-group">
                <label for="phone">Phone</label>
                <input type="tel" name="phone" id="phone" placeholder="+62 XXX XXXX XXXX" value="{{ old('phone') }}" required />
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter your email" value="{{ old('email') }}" required />
            </div>
        </div>

        <div class="input-row">
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" required />
            </div>
            <div class="input-group">
                <label for="confirm-password">Confirm Password</label>
                <input type="password" name="password_confirmation" id="confirm-password" placeholder="Confirm Password" required />
            </div>
        </div>

        <button class="btn1" type="submit">Register</button>

    </form>

    {{-- Pesan sukses --}}
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Error validasi --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
@endsection