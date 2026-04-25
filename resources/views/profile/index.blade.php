@extends('layouts.app')

@section('title', 'My Profile - BFMSL')

@section('content')
<section class="section-padding">
    <div style="max-width: 800px; margin: 0 auto;">
        <h1 class="section-title">COLLECTOR <span>PROFILE</span></h1>
        <p style="color: var(--text-muted); margin-bottom: 4rem; text-transform: uppercase; font-style: italic;">Manage your account settings and security</p>

        <div class="auth-container fade-in" style="padding: 4rem;">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="text-muted small text-uppercase fw-bold mb-2">Full Name</label>
                            <input type="text" name="name" class="form-control bg-dark border-secondary text-white" value="{{ $user->name }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="text-muted small text-uppercase fw-bold mb-2">Email Address</label>
                            <input type="email" name="email" class="form-control bg-dark border-secondary text-white" value="{{ $user->email }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="text-muted small text-uppercase fw-bold mb-2">Phone Number</label>
                            <input type="text" name="phone" class="form-control bg-dark border-secondary text-white" value="{{ $user->phone }}" placeholder="+1 (555) 000-0000">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label class="text-muted small text-uppercase fw-bold mb-2">Default Shipping Address</label>
                            <textarea name="default_shipping_address" class="form-control bg-dark border-secondary text-white" rows="3" placeholder="Enter your full street address, city, state, and zip code...">{{ $user->default_shipping_address }}</textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="newsletter_subscribed" id="newsletter" {{ $user->newsletter_subscribed ? 'checked' : '' }}>
                            <label class="form-check-label text-white small" for="newsletter">
                                I want to receive restock alerts and new arrivals notifications via email.
                            </label>
                        </div>
                    </div>
                </div>

                <hr class="border-secondary my-5">

                <h3 class="h5 text-warning text-uppercase italic mb-4">SECURITY <span>PROTOCOL</span></h3>
                <p class="text-muted small mb-4">Update your credentials. Leave blank to keep current password.</p>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="text-muted small text-uppercase fw-bold mb-2">New Password</label>
                            <div class="position-relative">
                                <input type="password" name="password" class="form-control bg-dark border-secondary text-white pe-5" placeholder="••••••••">
                                <button type="button" class="btn position-absolute top-50 end-0 translate-middle-y text-muted border-0 bg-transparent py-0 pe-3" onclick="togglePassword(this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="text-muted small text-uppercase fw-bold mb-2">Confirm New Password</label>
                            <div class="position-relative">
                                <input type="password" name="password_confirmation" class="form-control bg-dark border-secondary text-white pe-5" placeholder="••••••••">
                                <button type="button" class="btn position-absolute top-50 end-0 translate-middle-y text-muted border-0 bg-transparent py-0 pe-3" onclick="togglePassword(this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5">
                    <button type="submit" class="btn btn-warning w-100 py-3 fw-black text-uppercase ls-2">UPDATE COLLECTOR STATUS</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
