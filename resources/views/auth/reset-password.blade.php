@extends('layouts.app')

@section('title', 'Reset Password - BSMF Garage')

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
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group mb-4">
                <label for="otp">Security Code</label>
                <input type="text" id="otp" name="otp" class="form-control @error('otp') is-invalid @enderror" 
                       placeholder="6-digit code" required maxlength="6">
                @error('otp')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group mb-4">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                       placeholder="••••••••" required autofocus>
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group mb-4">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" 
                       placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">RESET PASSWORD</button>
        </form>
    </div>
</div>

<style>
    .auth-container {
        min-height: calc(100vh - 300px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 4rem 1rem;
        background: radial-gradient(circle at center, #1a1a1a 0%, #0a0a0a 100%);
    }

    .auth-card {
        width: 100%;
        max-width: 450px;
        background: rgba(20, 20, 20, 0.8);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        padding: 3rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }

    .auth-header h1 {
        font-family: 'Outfit', sans-serif;
        font-weight: 900;
        font-size: 2.2rem;
        letter-spacing: -1px;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, #fff 0%, #888 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .auth-header p {
        color: #888;
        font-size: 0.95rem;
        margin-bottom: 2rem;
    }

    .form-group label {
        display: block;
        color: #fff;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0.75rem;
    }

    .form-control {
        background: rgba(0, 0, 0, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        color: #ffffff !important;
        font-weight: 800;
        padding: 0.8rem 1.2rem;
        transition: all 0.3s ease;
    }

    .form-control[readonly] {
        background: rgba(255, 255, 255, 0.02);
        color: #666;
        cursor: not-allowed;
    }

    .form-control:focus:not([readonly]) {
        background: rgba(255, 255, 255, 0.1);
        border-color: var(--accent-color, #e63946);
        box-shadow: 0 0 0 4px rgba(230, 57, 70, 0.1);
        outline: none;
    }

    .btn-primary {
        background: var(--accent-color, #e63946);
        border: none;
        border-radius: 12px;
        padding: 1rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(230, 57, 70, 0.4);
        background: #f84d5a;
    }
</style>
@endsection
