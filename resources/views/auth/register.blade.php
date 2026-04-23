@extends('layouts.app')

@section('title', 'Register - E-Commerce-BFMSL')

@section('content')
<div class="auth-container fade-in">
    <div class="auth-header">
        <h1 class="auth-title">JOIN <span>CREW</span></h1>
        <p class="auth-subtitle">Start Your Collection</p>
    </div>

    @if($errors->any())
        <div class="auth-alert auth-alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('register.post') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">FULL NAME</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="John Doe" required>
        </div>
        <div class="form-group">
            <label for="email">EMAIL ADDRESS</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="collector@example.com" required>
        </div>
        <div class="form-group">
            <label for="password">PASSWORD</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
        </div>
        <div class="form-group">
            <label for="password_confirmation">CONFIRM PASSWORD</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 2rem; padding: 1rem;">GET STARTED</button>
    </form>
    
    <div style="text-align: center; margin-top: 2rem;">
        <p style="font-size: 0.9rem; color: #666;">Already a member? <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 800; text-decoration: none;">Login Here</a></p>
    </div>
</div>
@endsection
