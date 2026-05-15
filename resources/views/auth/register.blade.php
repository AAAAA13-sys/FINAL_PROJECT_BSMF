 @extends('layouts.app')

@section('title', 'Join the Elite - BSMF GARAGE')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth-ui.css') }}">
@endpush

@section('content')
<div class="auth-container">
        <div class="auth-card-simple fade-in">
            <div class="text-center mb-4">
                <div class="auth-logo-spectrum">BSMF GARAGE</div>
                <p class="auth-subtitle-spaced mt-3">JOIN THE ELITE</p>
            </div>

            @if($errors->any())
                <div class="auth-alert-box mb-4">
                    <i class="fas fa-exclamation-triangle me-2"></i> 
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST" autocomplete="off">
                @csrf
                
                <div class="mb-2">
                    <label class="label-auth-sm mb-1">FULL NAME</label>
                    <input type="text" name="name" class="input-auth-rounded" placeholder="Collector Name" value="{{ old('name') }}" required autocomplete="off">
                </div>

                <div class="mb-2">
                    <label class="label-auth-sm mb-1">USERNAME</label>
                    <input type="text" name="username" class="input-auth-rounded" placeholder="collector_one" value="{{ old('username') }}" required autocomplete="off">
                </div>

                <div class="mb-2">
                    <label class="label-auth-sm mb-2">EMAIL ADDRESS</label>
                    <input type="email" name="email" class="input-auth-rounded" placeholder="collector@example.com" value="{{ old('email') }}" required autocomplete="off">
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="label-auth-sm mb-1">PASSWORD</label>
                        <div class="position-relative">
                            <input type="password" name="password" class="input-auth-rounded input-auth-password" placeholder="••••••••" required autocomplete="new-password">
                            <i class="fas fa-eye password-toggle-auth" onclick="togglePassword(this)"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="label-auth-sm mb-1">CONFIRM</label>
                        <div class="position-relative">
                            <input type="password" name="password_confirmation" class="input-auth-rounded input-auth-password" placeholder="••••••••" required autocomplete="new-password">
                            <i class="fas fa-eye password-toggle-auth" onclick="togglePassword(this)"></i>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-auth-action">
                    INITIATE ACCOUNT
                </button>
            </form>
            
            <div class="text-center mt-3">
                <p class="auth-footer-text">Already a member?   
                    <a href="{{ route('login') }}" class="auth-subtitle-spaced ms-2">LOGIN HERE</a>
                </p>
            </div>
        </div>
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
</script>
@endpush
