@extends('layouts.app')

@section('title', 'Verify Email - BSMF Garage')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>VERIFY EMAIL</h1>
            <p>Enter the 6-digit code sent to your email address.</p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="auth-alert auth-alert-info">
                A new verification code has been sent to the email address you provided during registration.
            </div>
        @endif

        <form action="{{ route('verification.verify') }}" method="POST" class="auth-form">
            @csrf
            <div class="form-group mb-4">
                <label for="otp">Verification Code</label>
                <input type="text" id="otp" name="otp" class="form-control @error('otp') is-invalid @enderror" 
                       placeholder="000000" maxlength="6" required autofocus>
                @error('otp')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">VERIFY CODE</button>
        </form>

        <div class="auth-footer text-center mt-4">
            <p>Didn't receive the code?</p>
            <form action="{{ route('verification.resend') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-link p-0" style="color: var(--accent-color); font-weight: 700; text-decoration: none;">RESEND CODE</button>
            </form>
        </div>
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
        font-size: 2.5rem;
        letter-spacing: -1px;
        margin-bottom: 0.5rem;
        background: linear-gradient(135deg, #fff 0%, #888 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .auth-header p {
        color: #888;
        font-size: 1rem;
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
        font-weight: 900;
        text-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
        padding: 1.2rem;
        font-size: 2rem;
        text-align: center;
        letter-spacing: 0.8rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
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

    .auth-footer p {
        color: #888;
        margin-bottom: 0.5rem;
    }
</style>
@endsection
