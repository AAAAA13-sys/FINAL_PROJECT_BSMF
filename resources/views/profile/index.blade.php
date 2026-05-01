@extends('layouts.app')

@section('title', 'My Profile - BSMF GARAGE')

@section('content')
<section class="section-padding" style="background: var(--bg-darker); min-height: 100vh; padding-top: 4rem;">
    <div style="max-width: 1000px; margin: 0 auto;">
        <h1 class="section-title">BSMF <span>PROFILE</span></h1>
        <p style="color: var(--text-muted); margin-bottom: 4rem; text-transform: uppercase; font-style: italic; font-weight: 800; letter-spacing: 2px; font-size: 0.8rem;">Manage your account settings and security</p>

        <div style="background: var(--bg-dark); border: 1px solid var(--glass-border); border-radius: 32px; padding: 4rem; box-shadow: var(--surface-raised);">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label style="color: var(--text-muted); font-size: 0.65rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.75rem;">Full Name</label>
                            <input type="text" name="name" class="filter-input" value="{{ $user->name }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label style="color: var(--text-muted); font-size: 0.65rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.75rem;">Email Address</label>
                            <input type="email" name="email" class="filter-input" value="{{ $user->email }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label style="color: var(--text-muted); font-size: 0.65rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.75rem;">Phone Number</label>
                            <input type="text" name="phone" class="filter-input" value="{{ $user->phone }}" placeholder="+1 (555) 000-0000">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label style="color: var(--text-muted); font-size: 0.65rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.75rem;">Default Shipping Address</label>
                            <textarea name="default_shipping_address" class="filter-input" rows="3" placeholder="Enter your full street address, city, state, and zip code..." style="border-radius: 16px;">{{ $user->default_shipping_address }}</textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="custom-checkbox">
                            <input type="checkbox" name="newsletter_subscribed" id="newsletter" {{ $user->newsletter_subscribed ? 'checked' : '' }}>
                            <span class="checkbox-box"></span>
                            <span style="font-size: 0.85rem; font-weight: 600; color: white;">I want to receive restock alerts and new arrivals notifications via email.</span>
                        </label>
                    </div>
                </div>

                <hr style="border: none; border-top: 1px solid var(--glass-border); margin: 4rem 0;">

                <h3 class="h5 text-uppercase italic mb-4" style="color: var(--primary); font-weight: 900; letter-spacing: 1px;">SECURITY <span>PROTOCOL</span></h3>
                <p style="color: var(--text-muted); font-size: 0.85rem; font-weight: 600; margin-bottom: 2.5rem;">Update your credentials. Leave blank to keep current password.</p>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label style="color: var(--text-muted); font-size: 0.65rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.75rem;">New Password</label>
                            <input type="password" name="password" class="filter-input" placeholder="••••••••">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label style="color: var(--text-muted); font-size: 0.65rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.75rem;">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="filter-input" placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div class="mt-5">
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-black text-uppercase" style="border-radius: 16px; font-weight: 900; letter-spacing: 2px; font-size: 0.9rem;">UPDATE COLLECTOR STATUS</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
