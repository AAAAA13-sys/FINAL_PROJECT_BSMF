<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'FINAL_PROJECT_COLLECTOR') . ' - Premium Die-Cast')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

</head>
<body class="{{ Route::is('login') || Route::is('register') ? 'auth-page' : '' }}">
    <nav>
        <a href="{{ route('home') }}" class="logo">BSMF GARAGE</a>
        
        <button class="mobile-toggle" style="background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer;">
            <i class="fas fa-bars"></i>
        </button>

        <ul class="nav-links">
            <li><a href="{{ route('home') }}">HOME</a></li>
            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn">THE GARAGE <i class="fas fa-chevron-down" style="font-size: 0.7rem;"></i></a>
                <div class="dropdown-content">
                    <a href="{{ route('products.index', ['is_carded' => 1]) }}">CARDED COLLECTION</a>
                    <a href="{{ route('products.index', ['is_loose' => 1]) }}">LOOSE SELECTION</a>
                </div>
            </li>
            <li><a href="{{ route('products.index') }}">SHOP</a></li>
            @auth
                <li><a href="{{ route('cart.index') }}">CART</a></li>
                <li><a href="{{ route('orders.index') }}">ORDERS</a></li>
                <li><a href="{{ route('profile.index') }}">PROFILE</a></li>
                @if(Auth::user()->isAdmin())
                    <li><a href="{{ route('admin.dashboard') }}">DASHBOARD</a></li>
                @endif
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: white; font-weight: 800; cursor: pointer; text-transform: uppercase; font-size: 0.9rem;">LOGOUT</button>
                    </form>
                </li>
            @else
                <li><a href="{{ route('login') }}">LOGIN</a></li>
                <li><a href="{{ route('register') }}" class="btn btn-primary" style="padding: 0.5rem 1.5rem;">JOIN</a></li>
            @endauth
        </ul>
    </nav>



    <main class="page-transition">
        @if(session('success'))
            <div class="auth-alert auth-alert-info auto-hide-alert d-flex justify-content-between align-items-center">
                <span><i class="fas fa-check-circle me-2"></i> {{ session('success') }}</span>
                <button type="button" class="btn-close btn-close-white" onclick="this.parentElement.remove()" style="font-size: 0.6rem;"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="auth-alert auth-alert-danger auto-hide-alert d-flex justify-content-between align-items-center">
                <span><i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}</span>
                <button type="button" class="btn-close btn-close-white" onclick="this.parentElement.remove()" style="font-size: 0.6rem;"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <footer>
        <div class="footer-grid">
            <div>
                <div class="logo" style="font-size: 1.5rem;">BSMF GARAGE</div>
                <p class="footer-desc">Premium Die-Cast Collector Series. The ultimate destination for collectors.</p>
            </div>
            <div>
                <h4 class="footer-links-title">Quick Links</h4>
                <ul class="footer-links-list">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('products.index') }}">Shop Now</a></li>
                </ul>
            </div>
            <div>
                <h4 class="footer-links-title">Support</h4>
                <ul class="footer-links-list">
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">FAQ</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; {{ date('Y') }} BSMF GARAGE. All rights reserved.
        </div>
    </footer>



    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>
</body>
</html>
