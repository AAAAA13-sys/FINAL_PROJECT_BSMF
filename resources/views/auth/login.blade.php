@extends('layouts.app')

@section('title', 'Login - BSMF Garage')

@section('content')
<div class="auth-wrapper">
<div class="auth-container fade-in shadow-2xl">
    <div class="auth-header mb-4">
        <h1 class="auth-title" style="font-size: 1.8rem; font-weight: 900;">BSMF <span style="color: #fbbf24;">GARAGE</span></h1>
        <p class="auth-subtitle" style="color: rgba(255,255,255,0.6); letter-spacing: 2px; font-size: 0.75rem;">WELCOME BACK RACER</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger border-0 py-2 mb-3" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border-radius: 12px; font-size: 0.75rem;">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label small fw-bold text-uppercase mb-1" style="color: rgba(255,255,255,0.5); font-size: 0.65rem;">Email</label>
            <input type="email" name="email" class="form-control bg-dark border-secondary text-white px-3 py-2" placeholder="racer@example.com" value="{{ old('email') }}" required style="border-radius: 10px; font-size: 0.9rem; border: 1px solid rgba(255,255,255,0.1) !important;">
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold text-uppercase mb-1" style="color: rgba(255,255,255,0.5); font-size: 0.65rem;">Password</label>
            <div class="position-relative">
                <input type="password" name="password" class="form-control bg-dark border-secondary text-white px-3 py-2 pe-5" placeholder="••••••••" required style="border-radius: 10px; font-size: 0.9rem; border: 1px solid rgba(255,255,255,0.1) !important;">
                <button type="button" class="btn position-absolute top-50 end-0 translate-middle-y text-muted border-0 bg-transparent py-0 pe-3" onclick="togglePassword(this)" style="z-index: 10;">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <div class="text-end mt-1">
                <a href="#" class="small fw-bold text-decoration-none" style="font-size: 0.65rem;">Forgot Password?</a>
            </div>
        </div>

        <button type="submit" class="btn btn-warning w-100 py-2 fw-black text-uppercase mt-2 shadow-lg" style="border-radius: 10px; font-size: 0.95rem; background: #fbbf24; border: none; color: #000; letter-spacing: 1px;">
            IGNITE ENGINE
        </button>
    </form>
    
    <div class="text-center mt-4">
        <p class="small text-white-50 mb-0" style="font-size: 0.75rem;">Not a Member? <a href="{{ route('register') }}" class="fw-black text-decoration-underline ms-1">Join the Crew</a></p>
    </div>
</div>
</div>
@endsection
