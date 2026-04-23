@extends('layouts.app')

@section('title', 'Login - E-Commerce-BFMSL')

@section('content')
<div class="auth-container fade-in">
    <div class="auth-header">
        <h1 class="auth-title">DRIVE <span>IN</span></h1>
        <p class="auth-subtitle">Welcome Back Collector</p>
    </div>

    @if($errors->any())
        <div class="auth-alert auth-alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="email">EMAIL ADDRESS</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="collector@example.com" required>
        </div>
        <div class="form-group">
            <label for="password">PASSWORD</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 2rem; padding: 1rem;">SIGN IN</button>
    </form>
    
    <div style="text-align: center; margin-top: 2rem;">
        <p style="font-size: 0.9rem; color: #666;">New to the shop? <a href="{{ route('register') }}" style="color: var(--primary); font-weight: 800; text-decoration: none;">Register Now</a></p>
    </div>
</div>
@endsection
