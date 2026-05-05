@extends('layouts.app')

@section('title', 'Join the Elite - BSMF GARAGE')

@section('content')
<div class="auth-wrapper">
<div class="auth-container fade-in" style="width: 440px;">
    <div class="auth-header mb-3">
        <h1 class="auth-title" style="font-size: 1.8rem; font-weight: 900;">BSMF <span style="color: var(--primary);">GARAGE</span></h1>
        <p class="auth-subtitle" style="color: var(--text-muted); opacity: 0.8; letter-spacing: 4px; font-size: 0.75rem;">JOIN THE ELITE</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger border-0 py-2 mb-3" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border-radius: 12px; font-size: 0.75rem;">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('register.post') }}" method="POST">
        @csrf
        <div class="mb-2">
            <label class="form-label small fw-bold text-uppercase mb-1" style="color: var(--secondary); font-size: 0.65rem;">Full Name</label>
            <input type="text" name="name" class="filter-input" placeholder="Racer Name" value="{{ old('name') }}" required style="border-radius: 8px; font-size: 0.85rem;">
        </div>

        <div class="mb-2">
            <label class="form-label small fw-bold text-uppercase mb-1" style="color: var(--secondary); font-size: 0.65rem;">Username</label>
            <input type="text" name="username" class="filter-input" placeholder="racer_one" value="{{ old('username') }}" required style="border-radius: 8px; font-size: 0.85rem;">
        </div>

        <div class="mb-2">
            <label class="form-label small fw-bold text-uppercase mb-1" style="color: var(--secondary); font-size: 0.65rem;">Email Address</label>
            <input type="email" name="email" class="filter-input" placeholder="racer@example.com" value="{{ old('email') }}" required style="border-radius: 8px; font-size: 0.85rem;">
        </div>

        <div class="row g-2 mb-3">
            <div class="col-md-6">
                <label class="form-label small fw-bold text-uppercase mb-1" style="color: var(--secondary); font-size: 0.65rem;">Password</label>
                <div class="position-relative">
                    <input type="password" name="password" id="password" class="filter-input" placeholder="••••••••" required style="border-radius: 8px; font-size: 0.85rem; padding-right: 40px;">
                    <i class="fas fa-eye password-toggle" onclick="togglePassword(this)" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--text-muted); font-size: 0.8rem;"></i>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold text-uppercase mb-1" style="color: var(--secondary); font-size: 0.65rem;">Confirm</label>
                <div class="position-relative">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="filter-input" placeholder="••••••••" required style="border-radius: 8px; font-size: 0.85rem; padding-right: 40px;">
                    <i class="fas fa-eye password-toggle" onclick="togglePassword(this)" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--text-muted); font-size: 0.8rem;"></i>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-black text-uppercase shadow-lg" style="border-radius: 8px; font-size: 0.95rem; letter-spacing: 1px;">
            START ENGINE
        </button>
    </form>
    
    <div class="text-center mt-3">
        <p class="small mb-0" style="font-size: 0.75rem; color: var(--text-muted);">Already a Member? <a href="{{ route('login') }}" class="fw-black text-decoration-underline ms-1" style="color: var(--secondary) !important;">Login Here</a></p>
    </div>
</div>
</div>
@endsection
