@extends('layouts.app')

@section('title', 'Login - BSMF GARAGE')

@section('content')
<div class="auth-wrapper" style="padding-top: 6rem;">
<div class="auth-container fade-in">
    <div class="auth-header mb-4">
        <h1 class="auth-title" style="font-size: 1.8rem; font-weight: 900;">BSMF <span style="color: var(--primary);">GARAGE</span></h1>
        <p class="auth-subtitle" style="color: var(--text-muted); letter-spacing: 2px; font-size: 0.75rem;">WELCOME BACK RACER</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger border-0 py-2 mb-3" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border-radius: 12px; font-size: 0.75rem;">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label small fw-bold text-uppercase mb-1" style="color: var(--text-muted); font-size: 0.65rem;">Username</label>
            <input type="text" name="email" class="filter-input" placeholder="racer_name" value="{{ old('email') }}" required style="border-radius: 10px; font-size: 0.9rem;">
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold text-uppercase mb-1" style="color: var(--text-muted); font-size: 0.65rem;">Password</label>
            <div class="position-relative">
                <input type="password" name="password" id="password" class="filter-input" placeholder="••••••••" required style="border-radius: 10px; font-size: 0.9rem; padding-right: 40px;">
                <i class="fas fa-eye password-toggle" onclick="togglePassword(this)" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--text-muted); font-size: 0.8rem;"></i>
            </div>
            <div class="text-end mt-1">
                <a href="{{ route('password.request') }}" class="small fw-bold text-decoration-none" style="font-size: 0.65rem; color: var(--secondary) !important;">Forgot Password?</a>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-black text-uppercase mt-2" style="border-radius: 10px; font-size: 0.95rem; letter-spacing: 1px;">
            IGNITE ENGINE
        </button>
    </form>
    
    <div class="text-center mt-4">
        <p class="small mb-0" style="font-size: 0.75rem; color: var(--text-muted);">Not a member yet? <a href="{{ route('register') }}" class="fw-black text-decoration-underline ms-1" style="color: var(--secondary) !important;">JOIN CREW</a></p>
    </div>
</div>
</div>

<!-- Success Notification Pop-up -->
<div id="reset-success-alert" class="auth-alert auth-alert-info" style="display: none; position: fixed; top: 20px; right: 20px; z-index: 9999;">
    <i class="fas fa-check-circle"></i>
    <span>REINITIALIZATION SUCCESSFUL. LOG IN WITH YOUR NEW CREDENTIALS.</span>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('reset') === 'success') {
        const alert = document.getElementById('reset-success-alert');
        alert.style.display = 'flex';
        
        // Remove the ?reset=success from the URL without reloading
        window.history.replaceState({}, document.title, window.location.pathname);
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 1s ease';
            setTimeout(() => alert.style.display = 'none', 1000);
        }, 5000);
    }
});
</script>
@endsection
