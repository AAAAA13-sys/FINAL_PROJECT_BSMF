@extends('layouts.app')

@section('title', 'Login - BSMF GARAGE')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth-ui.css') }}">
@endpush

@section('content')
<div class="auth-container">
        <div class="auth-card-simple fade-in">
            <div class="text-center mb-4">
                <div class="auth-logo-spectrum">BSMF GARAGE</div>
                <p class="auth-subtitle-spaced mt-2">WELCOME BACK COLLECTOR</p>
            </div>

            @if($errors->any())
                <div class="auth-alert-box mb-4">
                    <i class="fas fa-exclamation-triangle me-2"></i> 
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="label-auth-sm mb-2">USERNAME</label>
                    <input 
                        type="text" 
                        name="email" 
                        class="input-auth-rounded" 
                        placeholder="Collector Name" 
                        value="{{ old('email') }}" 
                        required
                    >
                </div>

                <div class="mb-3">
                    <label class="label-auth-sm mb-2">PASSWORD</label>
                    <div class="position-relative">
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="input-auth-rounded input-auth-password" 
                            placeholder="••••••••" 
                            required
                        >
                        <i class="fas fa-eye password-toggle-auth" onclick="togglePassword(this)"></i>
                    </div>
                    <div class="text-end mt-2">
                        <a href="{{ route('password.request') }}" class="forgot-link-muted">FORGOT PASSWORD?</a>
                    </div>
                </div>

                <button type="submit" class="btn-auth-action mt-3">
                    IGNITE ENGINE
                </button>
            </form>
            
            <div class="text-center mt-4">
                <p class="auth-footer-text">Not a member yet? 
                    <a href="{{ route('register') }}" class="forgot-link-muted ms-2">Register Here</a>
                </p>
            </div>
        </div>
    </div>

<!-- Success Notification Pop-up -->
<div id="reset-success-alert" class="auth-alert-info">
    <i class="fas fa-check-circle"></i>
    <span>REINITIALIZATION SUCCESSFUL. LOG IN WITH YOUR NEW CREDENTIALS.</span>
</div>
@endsection

@push('scripts')
<script>
function togglePassword(icon) {
    const input = icon.parentElement.querySelector('input');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('reset') === 'success') {
        const alertEl = document.getElementById('reset-success-alert');
        alertEl.style.display = 'flex';
        
        // Clean URL without page reload
        window.history.replaceState({}, document.title, window.location.pathname);
        
        setTimeout(() => {
            alertEl.style.opacity = '0';
            alertEl.style.transition = 'opacity 1s ease';
            setTimeout(() => {
                alertEl.style.display = 'none';
            }, 1000);
        }, 5000);
    }
});
</script>
@endpush