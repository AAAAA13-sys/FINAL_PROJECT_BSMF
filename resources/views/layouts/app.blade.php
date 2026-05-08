<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'FINAL_PROJECT_COLLECTOR') . ' - Premium Die-Cast')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/garage-main.css') }}?v={{ time() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @stack('styles')
</head>

<body class="{{ Route::is('login') || Route::is('register') ? 'auth-page' : '' }}">
    <style>
        /* Minimal Header Styling */
        nav.minimal-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgba(10, 11, 14, 0.98);
            background-image: conic-gradient(
                rgba(18, 20, 25, 0.98) 90deg, 
                transparent 90deg 180deg, 
                rgba(18, 20, 25, 0.98) 180deg 270deg, 
                transparent 270deg
            );
            background-size: 40px 40px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--color-surface-border);
            z-index: 1000;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        nav.minimal-header.scrolled {
            padding: 0.6rem 5%;
            background-color: rgba(10, 11, 14, 1);
            background-image: conic-gradient(
                rgba(18, 20, 25, 1) 90deg, 
                transparent 90deg 180deg, 
                rgba(18, 20, 25, 1) 180deg 270deg, 
                transparent 270deg
            );
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .logo-minimal {
            font-size: 1.4rem;
            font-weight: 900;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-transform: uppercase;
            font-style: italic;
            letter-spacing: -0.5px;
        }

        .logo-minimal i {
            color: var(--color-brand-red);
            font-size: 1.4rem;
        }

        .nav-center {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .nav-links-minimal {
            display: flex;
            gap: 2rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-links-minimal a {
            color: var(--color-text-muted);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            transition: color 0.3s ease;
            position: relative;
            padding-bottom: 6px;
        }

        .nav-links-minimal a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--color-brand-red);
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 2px;
        }

        .nav-links-minimal a:hover::after,
        .nav-links-minimal a.active::after {
            width: 100%;
            box-shadow: 0 0 10px rgba(230, 57, 70, 0.6);
        }

        .nav-links-minimal a:hover,
        .nav-links-minimal a.active {
            color: white;
            text-shadow: 0 0 8px rgba(255, 255, 255, 0.2);
        }

        .nav-auth-minimal {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .btn-sign-in {
            color: var(--color-text-muted);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            transition: color 0.3s ease;
        }

        .btn-sign-in:hover {
            color: white;
        }

        .btn-get-started {
            background: linear-gradient(135deg, #800C1F 0%, #e63946 100%);
            color: white !important;
            padding: 0.6rem 1.5rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(230, 57, 70, 0.3);
        }

        .btn-get-started:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(230, 57, 70, 0.4);
        }

        .mobile-toggle-minimal {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: white;
            cursor: pointer;
        }

        @media (max-width: 991px) {

            .nav-center,
            .nav-auth-minimal {
                display: none;
            }

            .mobile-toggle-minimal {
                display: block;
            }

            nav.minimal-header {
                border-radius: 0;
                top: 0;
                width: 100%;
                padding: 1rem 5%;
                flex-wrap: wrap;
            }

            .nav-links-minimal {
                display: none;
                flex-direction: column;
                width: 100%;
                padding: 1.5rem 0 0.5rem 0;
                gap: 1.5rem;
                text-align: center;
                border-top: 1px solid rgba(255, 255, 255, 0.05);
                margin-top: 1rem;
            }

            .nav-links-minimal.active {
                display: flex;
            }

            /* Add Auth links to mobile menu since they are hidden */
            .nav-auth-mobile {
                display: flex;
                flex-direction: column;
                gap: 1rem;
                width: 100%;
                align-items: center;
            }

            .nav-links-minimal.active .nav-auth-mobile {
                display: flex;
            }
        }
    </style>

    <nav class="minimal-header">
        <a href="{{ route('home') }}" class="logo-minimal">
            <i class="fas fa-layer-group"></i> BSMF Garage
        </a>

        <button class="mobile-toggle-minimal">
            <i class="fas fa-bars"></i>
        </button>

        <ul class="nav-links-minimal">
            <li><a href="{{ route('home') }}" class="{{ Route::is('home') ? 'active' : '' }}">Home</a></li>
            <li><a href="{{ route('products.index') }}" class="{{ Route::is('products.*') ? 'active' : '' }}">Shop Collection</a></li>
            @auth
                <li><a href="{{ route('cart.index') }}" class="{{ Route::is('cart.*') ? 'active' : '' }}">Cart</a></li>
                <li><a href="{{ route('orders.index') }}" class="{{ Route::is('orders.*') ? 'active' : '' }}">Orders</a></li>
                @if(Auth::user()->isAdmin())
                    <li><a href="{{ route('admin.dashboard') }}" class="{{ Route::is('admin.*') ? 'active' : '' }}">Dashboard</a></li>
                @endif
                <li class="d-lg-none"><a href="{{ route('profile.index') }}">Profile</a></li>
                <li class="d-lg-none">
                    <form action="{{ route('logout') }}" method="POST" class="d-inline m-0">
                        @csrf
                        <button type="submit" class="btn btn-link text-danger text-decoration-none p-0 fw-bold">Logout</button>
                    </form>
                </li>
            @else
                <li class="d-lg-none"><a href="{{ route('login') }}">Sign in</a></li>
                <li class="d-lg-none"><a href="{{ route('register') }}" class="btn-get-started d-inline-block">Get Started</a></li>
            @endauth
        </ul>

        <div class="nav-auth-minimal">
            @auth
                <a href="{{ route('profile.index') }}" class="btn-sign-in">Profile</a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline m-0">
                    @csrf
                    <button type="submit" class="btn-get-started">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-sign-in">Sign in</a>
                <a href="{{ route('register') }}" class="btn-get-started">Get Started</a>
            @endauth
        </div>
    </nav>

    <main class="page-transition">
        @if(session('success'))
            <div class="auth-alert auth-alert-info auto-hide-alert d-flex justify-content-between align-items-center">
                <span><i class="fas fa-check-circle me-2"></i> {{ session('success') }}</span>
                <button type="button" class="btn-close btn-close-white btn-close-xs"
                    onclick="this.parentElement.remove()"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="auth-alert auth-alert-danger auto-hide-alert d-flex justify-content-between align-items-center">
                <span><i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}</span>
                <button type="button" class="btn-close btn-close-white btn-close-xs"
                    onclick="this.parentElement.remove()"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <footer>
        <div class="footer-grid">
            <div>
                <div class="logo footer-logo-sm">BSMF GARAGE</div>
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
    @stack('scripts')
</body>

</html>