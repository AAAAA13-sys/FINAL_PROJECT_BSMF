<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - BFMSL')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="admin-container">
        <aside class="admin-sidebar">
            <h2 class="logo" style="margin-bottom: 4rem; background: none; -webkit-text-fill-color: var(--secondary); color: var(--secondary);">SPEED <span>ADMIN</span></h2>
            <nav class="admin-nav" style="flex-grow: 1;">
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line" style="margin-right: 10px;"></i> Dashboard
                </a>
                <a href="{{ route('admin.products') }}" class="admin-nav-link {{ Route::is('admin.products') ? 'active' : '' }}">
                    <i class="fas fa-car" style="margin-right: 10px;"></i> Products
                </a>
                <a href="{{ route('admin.categories') }}" class="admin-nav-link {{ Route::is('admin.categories') ? 'active' : '' }}">
                    <i class="fas fa-tags" style="margin-right: 10px;"></i> Categories
                </a>
                <a href="{{ route('admin.coupons') }}" class="admin-nav-link {{ Route::is('admin.coupons') ? 'active' : '' }}">
                    <i class="fas fa-ticket-alt" style="margin-right: 10px;"></i> Coupons
                </a>
                <a href="{{ route('admin.orders') }}" class="admin-nav-link {{ Route::is('admin.orders') ? 'active' : '' }}">
                    <i class="fas fa-shopping-bag" style="margin-right: 10px;"></i> Orders
                </a>
                <a href="{{ route('admin.disputes') }}" class="admin-nav-link {{ Route::is('admin.disputes') ? 'active' : '' }}">
                    <i class="fas fa-headset" style="margin-right: 10px;"></i> Support
                </a>
                <a href="{{ route('admin.users') }}" class="admin-nav-link {{ Route::is('admin.users') ? 'active' : '' }}">
                    <i class="fas fa-users" style="margin-right: 10px;"></i> Users
                </a>
                <hr style="border: none; border-top: 1px solid var(--glass-border); margin: 2.5rem 0;">
                <a href="{{ route('home') }}" class="admin-nav-link" style="font-size: 0.8rem; color: var(--text-muted); opacity: 0.6;">
                    <i class="fas fa-external-link-alt" style="margin-right: 10px;"></i> View Site
                </a>
            </nav>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn" style="background: rgba(239, 68, 68, 0.1); color: var(--danger); border: 1px solid var(--danger); width: 100%; padding: 1rem; font-weight: 800; text-transform: uppercase; cursor: pointer; border-radius: 12px; letter-spacing: 1px;">
                    <i class="fas fa-sign-out-alt" style="margin-right: 10px;"></i> Logout
                </button>
            </form>
        </aside>

        <main class="admin-main fade-in">
            @if(session('success'))
                <div class="auth-alert auth-alert-info" style="margin-bottom: 3rem; background: rgba(251, 191, 36, 0.1); border: 1px solid var(--secondary); color: var(--secondary); font-weight: 800;">
                    <i class="fas fa-check-circle" style="margin-right: 10px;"></i> {{ session('success') }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>
