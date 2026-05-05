@extends('layouts.app')

@section('title', 'Collector Profile - BSMF GARAGE')

@section('content')
<div class="profile-hero">
    <div class="container">
        <div class="profile-header-content">
            <div class="profile-avatar">
                <div class="avatar-inner">
                    <i class="fas fa-user-secret"></i>
                </div>
                <div class="badge-collector">
                    <i class="fas fa-certificate"></i> ELITE COLLECTOR
                </div>
            </div>
            <div class="profile-identity">
                <h1>{{ strtoupper($user->name) }}</h1>
                <p>Member since {{ $user->created_at->format('M Y') }} • {{ $user->orders()->count() }} Acquisitions</p>
            </div>
        </div>
    </div>
</div>

<section class="profile-section">
    <div class="container">
        <form action="{{ route('profile.update') }}" method="POST" class="profile-form">
            @csrf
            @method('PUT')

            <div class="row g-5">
                <div class="col-lg-8">
                    <div class="glass-card mb-5">
                        <div class="card-header">
                            <i class="fas fa-id-card"></i>
                            <h3>IDENTITY DOSSIER</h3>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <input type="text" name="name" id="name" class="custom-input" value="{{ old('name', $user->name) }}" placeholder="Full Name" required>
                                        <label for="name">FULL NAME</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <input type="email" name="email" id="email" class="custom-input" value="{{ old('email', $user->email) }}" placeholder="Email Address" required>
                                        <label for="email">EMAIL ADDRESS</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <input type="text" name="phone" id="phone" class="custom-input" value="{{ old('phone', $user->phone) }}" placeholder="Phone Number">
                                        <label for="phone">PHONE NUMBER</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating-custom">
                                        <textarea name="default_shipping_address" id="address" class="custom-input" style="height: 120px" placeholder="Shipping Address">{{ old('default_shipping_address', $user->default_shipping_address) }}</textarea>
                                        <label for="address">DEFAULT SHIPPING ADDRESS</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="glass-card mb-5">
                        <div class="card-header">
                            <i class="fas fa-shield-alt"></i>
                            <h3>SECURITY PROTOCOL</h3>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-4 small">Leave blank if you do not wish to update your secure access credentials.</p>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <input type="password" name="password" id="password" class="custom-input" placeholder="New Password" style="padding-right: 45px;">
                                        <label for="password">NEW PASSWORD</label>
                                        <i class="fas fa-eye password-toggle" onclick="togglePassword(this)" style="position: absolute; right: 15px; top: 35px; cursor: pointer; color: var(--text-muted); font-size: 0.9rem;"></i>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating-custom">
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="custom-input" placeholder="Confirm Password" style="padding-right: 45px;">
                                        <label for="password_confirmation">CONFIRM NEW PASSWORD</label>
                                        <i class="fas fa-eye password-toggle" onclick="togglePassword(this)" style="position: absolute; right: 15px; top: 35px; cursor: pointer; color: var(--text-muted); font-size: 0.9rem;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="glass-card sticky-sidebar">
                        <div class="card-header">
                            <i class="fas fa-cog"></i>
                            <h3>PREFERENCES</h3>
                        </div>
                        <div class="card-body">
                            <div class="preference-item mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="pref-label">NEWSLETTER</span>
                                    <div class="form-check form-switch custom-switch">
                                        <input class="form-check-input" type="checkbox" name="newsletter_subscribed" id="newsletter" {{ $user->newsletter_subscribed ? 'checked' : '' }}>
                                    </div>
                                </div>
                                <p class="pref-desc">Receive exclusive alerts on new arrivals and rare treasures.</p>
                            </div>
                            
                            <div class="preference-item mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="pref-label">STOCK ALERTS</span>
                                    <div class="form-check form-switch custom-switch">
                                        <input class="form-check-input" type="checkbox" checked disabled>
                                    </div>
                                </div>
                                <p class="pref-desc">Automatic notifications when watched models are restocked.</p>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3 mt-4">
                                <i class="fas fa-save me-2"></i> UPDATE DOSSIER
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
    function togglePassword(inputId, icon) {
        const input = document.getElementById(inputId);
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

<style>
    :root {
        --profile-accent: #e63946;
        --profile-bg: #0a0a0a;
        --profile-card: rgba(20, 20, 20, 0.8);
        --profile-border: rgba(255, 255, 255, 0.08);
    }

    .profile-hero {
        background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1594731828614-83137d6ac4a8?q=80&w=2000') center/cover;
        padding: 8rem 0 4rem;
        border-bottom: 1px solid var(--profile-border);
    }

    .profile-header-content {
        display: flex;
        align-items: center;
        gap: 3rem;
    }

    .profile-avatar {
        position: relative;
    }

    .avatar-inner {
        width: 150px;
        height: 150px;
        background: var(--profile-card);
        border: 2px solid var(--profile-accent);
        border-radius: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        color: white;
        box-shadow: 0 0 30px rgba(230, 57, 70, 0.2);
    }

    .badge-collector {
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        background: var(--profile-accent);
        color: white;
        padding: 4px 15px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 900;
        letter-spacing: 1px;
        white-space: nowrap;
        box-shadow: 0 5px 15px rgba(230, 57, 70, 0.4);
    }

    .profile-identity h1 {
        font-family: 'Outfit', sans-serif;
        font-weight: 900;
        font-size: 3.5rem;
        letter-spacing: -2px;
        margin-bottom: 0.5rem;
        color: white;
    }

    .profile-identity p {
        color: var(--text-muted);
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-size: 0.9rem;
    }

    .profile-section {
        background: var(--profile-bg);
        padding: 6rem 0;
        min-height: 80vh;
    }

    .glass-card {
        background: var(--profile-card);
        backdrop-filter: blur(20px);
        border: 1px solid var(--profile-border);
        border-radius: 32px;
        overflow: hidden;
    }

    .card-header {
        padding: 2rem 2.5rem 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border-bottom: none;
        background: transparent;
    }

    .card-header i {
        color: var(--profile-accent);
        font-size: 1.2rem;
    }

    .card-header h3 {
        color: white;
        font-family: 'Outfit', sans-serif;
        font-weight: 800;
        font-size: 1.1rem;
        letter-spacing: 1px;
        margin: 0;
        text-transform: uppercase;
    }

    .card-body {
        padding: 2.5rem;
    }

    .form-floating-custom {
        position: relative;
    }

    .custom-input {
        width: 100%;
        background: rgba(255,255,255,0.03);
        border: 1px solid var(--profile-border);
        border-radius: 16px;
        padding: 1.8rem 1.2rem 0.6rem;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .custom-input:focus {
        background: rgba(255,255,255,0.06);
        border-color: var(--profile-accent);
        outline: none;
        box-shadow: 0 0 0 4px rgba(230, 57, 70, 0.1);
    }

    .form-floating-custom label {
        position: absolute;
        top: 0.6rem;
        left: 1.2rem;
        font-size: 0.65rem;
        font-weight: 800;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 1px;
        pointer-events: none;
    }

    .pref-label {
        color: white;
        font-weight: 800;
        font-size: 0.9rem;
        letter-spacing: 1px;
    }

    .pref-desc {
        color: var(--text-muted);
        font-size: 0.8rem;
        margin: 0;
    }

    .custom-switch .form-check-input {
        width: 3rem;
        height: 1.5rem;
        background-color: rgba(255,255,255,0.1);
        border-color: transparent;
        cursor: pointer;
    }

    .custom-switch .form-check-input:checked {
        background-color: var(--profile-accent);
    }

    .btn-primary {
        background: var(--profile-accent);
        border: none;
        border-radius: 16px;
        font-weight: 900;
        letter-spacing: 2px;
        text-transform: uppercase;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .btn-primary:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(230, 57, 70, 0.4);
        background: #f84d5a;
    }

    .sticky-sidebar {
        position: sticky;
        top: 6rem;
    }

    @media (max-width: 991px) {
        .profile-header-content {
            flex-direction: column;
            text-align: center;
            gap: 1.5rem;
        }
        .profile-identity h1 {
            font-size: 2.5rem;
        }
        .sticky-sidebar {
            position: static;
        }
    }
</style>
@endsection
