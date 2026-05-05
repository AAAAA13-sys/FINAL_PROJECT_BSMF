@extends('layouts.app')

@section('title', 'System Recovery - BSMF Garage')

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
                    <span class="invalid-feedback" id="email-error"></span>
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-3" id="btn-email">DISPATCH CODE</button>
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
                    <span class="invalid-feedback" id="otp-error"></span>
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-3" id="btn-otp">AUTHENTICATE</button>
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
                    <span class="invalid-feedback" id="password-error"></span>
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-3" id="btn-password">REINITIALIZE ENGINE</button>
            </form>
        </div>

        <div class="auth-footer text-center mt-4">
            <a href="{{ route('login') }}" style="color: var(--accent-color); font-weight: 700; text-decoration: none;">ABORT MISSION</a>
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
        max-width: 480px;
        background: rgba(10, 10, 10, 0.9);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 32px;
        padding: 3.5rem;
        box-shadow: 0 40px 100px rgba(0, 0, 0, 0.8);
        position: relative;
    }

    .recovery-steps {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
    }

    .step {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: #222;
        color: #555;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-size: 0.9rem;
        border: 2px solid #333;
        transition: all 0.4s ease;
    }

    .step.active {
        background: var(--accent-color, #e63946);
        color: white;
        border-color: var(--accent-color, #e63946);
        box-shadow: 0 0 15px rgba(230, 57, 70, 0.4);
    }

    .step.completed {
        background: #2ecc71;
        color: white;
        border-color: #2ecc71;
    }

    .step-line {
        height: 2px;
        width: 40px;
        background: #333;
    }

    .auth-header h1 {
        font-family: 'Outfit', sans-serif;
        font-weight: 900;
        font-size: 2.2rem;
        letter-spacing: -1px;
        margin-bottom: 1rem;
        color: #fff;
    }

    .auth-header p {
        color: #888;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 2rem;
    }

    .form-group label {
        display: block;
        color: #fff;
        font-weight: 700;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 0.75rem;
    }

    .form-control {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        color: #fff;
        padding: 1rem 1.2rem;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }

    .otp-input-styled {
        text-align: center;
        font-size: 2.5rem;
        font-weight: 900;
        letter-spacing: 1rem;
        color: #ffffff !important;
        background: rgba(0, 0, 0, 0.5);
        border-color: rgba(255, 255, 255, 0.2);
    }

    .form-control:focus {
        background: rgba(255, 255, 255, 0.08);
        border-color: var(--accent-color, #e63946);
        box-shadow: 0 0 20px rgba(230, 57, 70, 0.1);
        outline: none;
    }

    .btn-primary {
        background: var(--accent-color, #e63946);
        border: none;
        border-radius: 14px;
        padding: 1.1rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 2px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(230, 57, 70, 0.4);
    }

    .btn-primary:disabled {
        background: #444;
        opacity: 0.7;
    }

    .invalid-feedback {
        display: block;
        color: #ff4757;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        font-weight: 600;
    }
</style>

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
