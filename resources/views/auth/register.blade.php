@extends('layouts.app')

@section('title', 'Join the Elite - BSMF Garage')

@section('content')
<div class="auth-wrapper">
<div class="auth-container fade-in shadow-2xl" style="width: 440px;">
    <div class="auth-header mb-3">
        <h1 class="auth-title" style="font-size: 1.8rem; font-weight: 900;">BSMF <span style="color: #fbbf24;">GARAGE</span></h1>
        <p class="auth-subtitle" style="color: white; opacity: 0.8; letter-spacing: 4px; font-size: 0.75rem;">JOIN THE ELITE</p>
    </div>

    <form action="{{ route('register.post') }}" method="POST">
        @csrf
        <div class="mb-2">
            <label class="form-label small fw-bold text-uppercase mb-1" style="color: var(--secondary); font-size: 0.65rem;">Full Name</label>
            <input type="text" name="name" class="form-control bg-dark border-secondary text-white px-3 py-1" placeholder="Racer Name" value="{{ old('name') }}" required style="border-radius: 8px; font-size: 0.85rem; border: 1px solid rgba(255,255,255,0.2) !important;">
        </div>

        <div class="mb-2">
            <label class="form-label small fw-bold text-uppercase mb-1" style="color: var(--secondary); font-size: 0.65rem;">Username</label>
            <input type="text" name="username" class="form-control bg-dark border-secondary text-white px-3 py-1" placeholder="racer_one" value="{{ old('username') }}" required style="border-radius: 8px; font-size: 0.85rem; border: 1px solid rgba(255,255,255,0.2) !important;">
        </div>

        <div class="mb-2">
            <label class="form-label small fw-bold text-uppercase mb-1" style="color: var(--secondary); font-size: 0.65rem;">Email Address</label>
            <input type="email" name="email" class="form-control bg-dark border-secondary text-white px-3 py-1" placeholder="racer@example.com" value="{{ old('email') }}" required style="border-radius: 8px; font-size: 0.85rem; border: 1px solid rgba(255,255,255,0.2) !important;">
        </div>

        <div class="row g-2 mb-3">
            <div class="col-md-6">
                <label class="form-label small fw-bold text-uppercase mb-1" style="color: var(--secondary); font-size: 0.65rem;">Password</label>
                <div class="position-relative">
                    <input type="password" name="password" class="form-control bg-dark border-secondary text-white px-3 py-1 pe-5" placeholder="••••••••" required style="border-radius: 8px; font-size: 0.85rem; border: 1px solid rgba(255,255,255,0.2) !important;">
                    <button type="button" class="btn position-absolute top-50 end-0 translate-middle-y text-white opacity-50 border-0 bg-transparent py-0 pe-2" onclick="togglePassword(this)" style="z-index: 10;">
                        <i class="fas fa-eye" style="font-size: 0.75rem;"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold text-uppercase mb-1" style="color: var(--secondary); font-size: 0.65rem;">Confirm</label>
                <div class="position-relative">
                    <input type="password" name="password_confirmation" class="form-control bg-dark border-secondary text-white px-3 py-1 pe-5" placeholder="••••••••" required style="border-radius: 8px; font-size: 0.85rem; border: 1px solid rgba(255,255,255,0.2) !important;">
                    <button type="button" class="btn position-absolute top-50 end-0 translate-middle-y text-white opacity-50 border-0 bg-transparent py-0 pe-2" onclick="togglePassword(this)" style="z-index: 10;">
                        <i class="fas fa-eye" style="font-size: 0.75rem;"></i>
                    </button>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-warning w-100 py-2 fw-black text-uppercase shadow-lg" style="border-radius: 8px; font-size: 0.95rem; background: #fbbf24; border: none; color: #000; letter-spacing: 1px;">
            START ENGINE
        </button>
    </form>
    
    <div class="text-center mt-3">
        <p class="small text-white-50 mb-0" style="font-size: 0.75rem;">Already a Member? <a href="{{ route('login') }}" class="fw-black text-decoration-underline ms-1">Login Here</a></p>
    </div>
</div>
</div>
@endsection
