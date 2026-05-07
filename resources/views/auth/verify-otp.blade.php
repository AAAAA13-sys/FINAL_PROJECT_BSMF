@extends('layouts.app')

@section('title', 'Verify Registration - BSMF GARAGE')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth-ui.css') }}">
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card-simple fade-in">
        <div class="text-center mb-4">
            <div class="auth-logo-spectrum">BSMF GARAGE</div>
            <p class="auth-subtitle-spaced mt-2">SECURE VERIFICATION</p>
        </div>

        <div class="text-center mb-4">
            <p class="auth-footer-text px-3">We've sent a 6-digit verification code to your email. Enter it below to ignite your account.</p>
        </div>

        @if(session('success'))
            <div class="auth-alert-box mb-4" style="background: rgba(34, 197, 94, 0.1); color: #22c55e; border-color: rgba(34, 197, 94, 0.3);">
                <i class="fas fa-check-circle me-2"></i> 
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="auth-alert-box mb-4">
                <i class="fas fa-exclamation-triangle me-2"></i> 
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('verification.verify') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="label-auth-sm mb-2 text-center d-block">6-DIGIT PASSCODE</label>
                <input 
                    type="text" 
                    name="otp" 
                    class="input-auth-rounded text-center fw-black" 
                    style="font-size: 2rem; letter-spacing: 0.8rem; padding-left: 1.8rem;"
                    placeholder="000000" 
                    maxlength="6"
                    required
                    autofocus
                >
            </div>

            <button type="submit" class="btn-auth-action">
                VERIFY ACCOUNT
            </button>
        </form>
        
        <div class="text-center mt-4">
            <p class="auth-footer-text">Didn't receive the code? 
                <form action="{{ route('verification.resend') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link p-0 auth-subtitle-spaced ms-2" style="text-decoration: underline !important;">RESEND CODE</button>
                </form>
            </p>
        </div>
    </div>
</div>
@endsection
