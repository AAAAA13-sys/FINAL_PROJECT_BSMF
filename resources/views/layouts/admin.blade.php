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
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/admin-ui.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/garage-main.css') }}?v={{ time() }}">
</head>
<body class="p-0">
    <div class="admin-container">
        <aside class="admin-sidebar admin-sidebar-nav">
            <div class="px-3 mb-2">
                <a href="{{ route('admin.dashboard') }}" class="logo d-block mb-0 text-decoration-none admin-logo-text">BSMF <span class="logo-accent">ADMIN</span></a>
                <div class="admin-panel-sublabel">
                    <span>Control Panel</span>
                </div>
            </div>

            <nav class="admin-nav px-2">
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="{{ route('admin.products') }}" class="admin-nav-link {{ Route::is('admin.products') ? 'active' : '' }}">
                    <i class="fas fa-car"></i> Products
                </a>
                <a href="{{ route('admin.coupons') }}" class="admin-nav-link {{ Route::is('admin.coupons') ? 'active' : '' }}">
                    <i class="fas fa-ticket-alt"></i> Coupons
                </a>
                <a href="{{ route('admin.orders') }}" class="admin-nav-link {{ Route::is('admin.orders') ? 'active' : '' }}">
                    <i class="fas fa-shopping-bag"></i> Orders
                </a>

                <a href="{{ route('admin.users') }}" class="admin-nav-link {{ Route::is('admin.users') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Users
                </a>

                @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.audit-logs') }}" class="admin-nav-link {{ Route::is('admin.audit-logs') ? 'active' : '' }}">
                    <i class="fas fa-terminal"></i> Logs
                </a>
                @endif
                
                <div class="mt-auto pt-2 px-2">
                    <hr class="hr-glass mb-2">
                    
                    <div class="px-3 mb-3">
                        <div class="text-white fw-black small text-uppercase admin-current-user-info">{{ Auth::user()->name }}</div>
                        <div class="text-danger fw-bold text-uppercase admin-current-user-role">ROLE: {{ Auth::user()->role }}</div>
                    </div>

                    <a href="{{ route('home') }}" class="admin-nav-link mb-2 btn-admin-visit-site">
                        <i class="fas fa-external-link-alt"></i> VISIT SITE
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn w-100 btn-admin-logout-sidebar">
                            <i class="fas fa-sign-out-alt"></i> LOGOUT
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <main class="admin-main fade-in d-flex flex-column">
            @if(session('success'))
                <div class="auth-alert auth-alert-info auto-hide-alert d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-check-circle me-2"></i> {{ session('success') }}</span>
                    <button type="button" class="btn-close btn-close-white btn-close-xs" onclick="this.parentElement.remove()"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="auth-alert auth-alert-danger auto-hide-alert d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}</span>
                    <button type="button" class="btn-close btn-close-white btn-close-xs" onclick="this.parentElement.remove()"></button>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const alerts = document.querySelectorAll('.auto-hide-alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.classList.add('alert-fade-out');
                    setTimeout(() => alert.remove(), 500);
                }, 3000);
            });
        });
        // Global Page Jump Handler
        window.addEventListener('page-jump', function(e) {
            // If the page has a custom handler (like Audit Logs AJAX), it should stop propagation
            // Otherwise, we just redirect
            if (!e.defaultPrevented) {
                window.location.href = e.detail.url;
            }
        });
    </script>
    @stack('modals')
</body>
</html>
