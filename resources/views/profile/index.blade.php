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

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    </div>
                </div>

                <hr style="border: none; border-top: 1px solid var(--glass-border); margin: 3rem 0;">

                <h3 style="margin-bottom: 2rem; color: var(--secondary); font-style: italic; text-transform: uppercase;">Change Password</h3>
                <p style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 2rem;">Leave blank if you don't want to change your password.</p>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>

                <div style="margin-top: 4rem;">
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1.5rem; font-size: 1.1rem;">Update Profile Settings</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
