@extends('layouts.app')

@section('title', 'System Recovery - BSMF Garage')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth-ui.css') }}">
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <!-- Progress Bar -->
        <div class="recovery-steps mb-5">
            <div class="step active" id="step-dot-1">1</div>
            <div class="step-line"></div>
            <div class="step" id="step-dot-2">2</div>
            <div class="step-line"></div>
            <div class="step" id="step-dot-3">3</div>
        </div>

        <!-- STEP 1: Email Submission -->
        <div id="step-1" class="recovery-step-content">
            <div class="auth-header text-center">
                <h1>IDENTITY CHECK</h1>
                <p>Enter your registered email to begin the credential recovery sequence.</p>
            </div>
            <form id="form-email" class="auth-form">
                @csrf
                <div class="form-group mb-4">
                    <label for="email">Target Email</label>
                    <input type="email" id="email" name="email" class="form-control" 
                           placeholder="your@email.com" required autofocus>
                    <span class="invalid-feedback-auth" id="email-error"></span>
                </div>
                <button type="submit" class="btn btn-primary-auth w-100 mb-3" id="btn-email">DISPATCH CODE</button>
            </form>
        </div>

        <!-- STEP 2: OTP Verification -->
        <div id="step-2" class="recovery-step-content" style="display: none;">
            <div class="auth-header text-center">
                <h1>SECURITY VERIFICATION</h1>
                <p>A 6-digit access code has been dispatched. Enter it below to unlock recovery protocols.</p>
            </div>
            <form id="form-otp" class="auth-form">
                @csrf
                <div class="form-group mb-4">
                    <label for="otp">Access Code</label>
                    <input type="text" id="otp" name="otp" class="form-control otp-input-styled" 
                           placeholder="000000" maxlength="6" required>
                    <span class="invalid-feedback-auth" id="otp-error"></span>
                </div>
                <button type="submit" class="btn btn-primary-auth w-100 mb-3" id="btn-otp">AUTHENTICATE</button>
            </form>
        </div>

        <!-- STEP 3: Password Reset -->
        <div id="step-3" class="recovery-step-content" style="display: none;">
            <div class="auth-header text-center">
                <h1>CREDENTIAL UPDATE</h1>
                <p>Identity confirmed. Define your new access credentials below.</p>
            </div>
            <form id="form-password" class="auth-form">
                @csrf
                <div class="form-group mb-4">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" class="form-control" 
                           placeholder="••••••••" required>
                </div>
                <div class="form-group mb-4">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" 
                           placeholder="••••••••" required>
                    <span class="invalid-feedback-auth" id="password-error"></span>
                </div>
                <button type="submit" class="btn btn-primary-auth w-100 mb-3" id="btn-password">REINITIALIZE ENGINE</button>
            </form>
        </div>

        <div class="auth-footer text-center mt-4">
            <a href="{{ route('login') }}" style="color: var(--accent-color); font-weight: 700; text-decoration: none;">ABORT MISSION</a>
        </div>
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentEmail = '';
    let currentOtp = '';

    const step1 = document.getElementById('step-1');
    const step2 = document.getElementById('step-2');
    const step3 = document.getElementById('step-3');

    const dot1 = document.getElementById('step-dot-1');
    const dot2 = document.getElementById('step-dot-2');
    const dot3 = document.getElementById('step-dot-3');

    // STEP 1: Email
    document.getElementById('form-email').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('btn-email');
        const emailInput = document.getElementById('email');
        const errorMsg = document.getElementById('email-error');
        
        currentEmail = emailInput.value;
        btn.disabled = true;
        btn.innerHTML = 'DISPATCHING...';
        errorMsg.innerText = '';

        try {
            const response = await fetch("{{ route('password.email') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email: currentEmail })
            });
            const data = await response.json();

            if (data.success) {
                step1.style.display = 'none';
                step2.style.display = 'block';
                dot1.classList.add('completed');
                dot2.classList.add('active');
            } else {
                errorMsg.innerText = data.message || 'Identity not found in database.';
            }
        } catch (error) {
            errorMsg.innerText = 'Transmission error. Check your connection.';
        } finally {
            btn.disabled = false;
            btn.innerHTML = 'DISPATCH CODE';
        }
    });

    // STEP 2: OTP
    document.getElementById('form-otp').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('btn-otp');
        const otpInput = document.getElementById('otp');
        const errorMsg = document.getElementById('otp-error');
        
        currentOtp = otpInput.value;
        btn.disabled = true;
        btn.innerHTML = 'AUTHENTICATING...';
        errorMsg.innerText = '';

        try {
            const response = await fetch("{{ route('password.otp.verify') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ 
                    email: currentEmail,
                    otp: currentOtp
                })
            });
            const data = await response.json();

            if (data.success) {
                step2.style.display = 'none';
                step3.style.display = 'block';
                dot2.classList.add('completed');
                dot3.classList.add('active');
            } else {
                errorMsg.innerText = data.message || 'Invalid security code.';
            }
        } catch (error) {
            errorMsg.innerText = 'Authentication failed.';
        } finally {
            btn.disabled = false;
            btn.innerHTML = 'AUTHENTICATE';
        }
    });

    // STEP 3: Password
    document.getElementById('form-password').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('btn-password');
        const pass = document.getElementById('password').value;
        const passConfirm = document.getElementById('password_confirmation').value;
        const errorMsg = document.getElementById('password-error');
        
        btn.disabled = true;
        btn.innerHTML = 'REINITIALIZING...';
        errorMsg.innerText = '';

        try {
            const response = await fetch("{{ route('password.update') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ 
                    email: currentEmail,
                    otp: currentOtp,
                    password: pass,
                    password_confirmation: passConfirm
                })
            });
            const data = await response.json();

            if (data.success) {
                window.location.href = "{{ route('login') }}?reset=success";
            } else {
                errorMsg.innerText = data.message || 'Update failed. Check password requirements.';
            }
        } catch (error) {
            errorMsg.innerText = 'System error during reinitialization.';
        } finally {
            btn.disabled = false;
            btn.innerHTML = 'REINITIALIZE ENGINE';
        }
    });
});
</script>
@endsection
