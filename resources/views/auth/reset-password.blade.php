@extends('layouts.app')

@section('title', 'Reset Password - BSMF Garage')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth-ui.css') }}">
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header text-center">
            <h1>RESET PASSWORD</h1>
            <p>Secure your account with a new password.</p>
        </div>

        <form action="{{ route('password.update') }}" method="POST" class="auth-form">
            @csrf
            
            <div class="form-group mb-4">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                       value="{{ $email ?? old('email') }}" required>
                @error('email')
                    <span class="invalid-feedback-auth">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group mb-4">
                <label for="otp">Security Code</label>
                <input type="text" id="otp" name="otp" class="form-control @error('otp') is-invalid @enderror" 
                       placeholder="6-digit code" required maxlength="6">
                @error('otp')
                    <span class="invalid-feedback-auth">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group mb-4">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                       placeholder="••••••••" required autofocus>
                @error('password')
                    <span class="invalid-feedback-auth">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group mb-4">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" 
                       placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary-auth w-100 mb-3">RESET PASSWORD</button>
        </form>
    </div>
</div>
@endsection
@endsection
