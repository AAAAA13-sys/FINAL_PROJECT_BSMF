<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - BSMF Garage')</title>
    <!-- Bootstrap 5 for Grid & Utilities -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body style="padding-top: 0 !important;">
    <div class="admin-container">
        <aside class="admin-sidebar" style="padding: 3rem 0.75rem 1.5rem 0.75rem;">
            <div class="px-3 mb-5">
                <a href="{{ route('admin.dashboard') }}" class="logo d-block mb-0 text-decoration-none" style="font-size: 1.6rem; white-space: nowrap;">BSMF ADMIN</a>
                <div style="margin-top: 0.2rem;">
                    <span style="color: var(--secondary); font-weight: 900; letter-spacing: 2px; font-size: 0.8rem; text-transform: uppercase; font-family: 'Outfit';">Control Panel</span>
                </div>
            </div>

            <nav class="admin-nav px-2">
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
                <a href="{{ route('admin.products') }}" class="admin-nav-link {{ Route::is('admin.products') ? 'active' : '' }}">
                    <i class="fas fa-car"></i> Products
                </a>
                <a href="{{ route('admin.categories') }}" class="admin-nav-link {{ Route::is('admin.categories') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i> Categories
                </a>
                <a href="{{ route('admin.coupons') }}" class="admin-nav-link {{ Route::is('admin.coupons') ? 'active' : '' }}">
                    <i class="fas fa-ticket-alt"></i> Coupons
                </a>
                <a href="{{ route('admin.orders') }}" class="admin-nav-link {{ Route::is('admin.orders') ? 'active' : '' }}">
                    <i class="fas fa-shopping-bag"></i> Orders
                </a>
                <a href="{{ route('admin.disputes') }}" class="admin-nav-link {{ Route::is('admin.disputes') ? 'active' : '' }}">
                    <i class="fas fa-headset"></i> Support
                </a>
                <a href="{{ route('admin.users') }}" class="admin-nav-link {{ Route::is('admin.users') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Users
                </a>
                
                <div class="mt-auto pt-2 px-2">
                    <hr style="border: none; border-top: 1px solid var(--glass-border); margin-bottom: 1rem;">
                    <a href="{{ route('home') }}" class="admin-nav-link mb-2" style="opacity: 0.8; font-size: 0.8rem; font-weight: 800; padding-left: 0.75rem;">
                        <i class="fas fa-external-link-alt"></i> VISIT SITE
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn w-100" style="background: rgba(255, 77, 77, 0.15); color: #ff4d4d; border: 2px solid rgba(255, 77, 77, 0.4); padding: 0.6rem 1rem; font-weight: 900; text-transform: uppercase; border-radius: 12px; font-size: 0.8rem; letter-spacing: 1px; display: flex; align-items: center; justify-content: center; gap: 10px;">
                            <i class="fas fa-sign-out-alt"></i> LOGOUT
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <main class="admin-main fade-in">
            @if(session('success'))
                <div class="auth-alert auth-alert-info auto-hide-alert">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="auth-alert auth-alert-danger auto-hide-alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ session('error') }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>
