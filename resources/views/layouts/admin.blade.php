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
    <style>
        :root {
            --sidebar-width: 240px;
        }
        body { 
            background: #000814; 
            color: white;
            font-family: 'Outfit', sans-serif;
        }
        .admin-container { display: flex; min-height: 100vh; }
        
        .admin-sidebar { 
            width: var(--sidebar-width); 
            height: 100vh;
            background: #00050a; 
            border-right: 1px solid var(--glass-border); 
            padding: 1.5rem 1rem;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }

        .admin-nav {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            flex-grow: 1;
        }

        .admin-nav-link { 
            text-decoration: none; 
            display: flex; 
            align-items: center; 
            padding: 0.6rem 1rem;
            color: rgba(255, 255, 255, 0.5);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.5px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 8px;
        }

        .admin-nav-link i { width: 24px; font-size: 1rem; margin-right: 12px; opacity: 0.7; }

        .admin-nav-link:hover {
            color: var(--secondary);
            background: rgba(255, 255, 255, 0.05);
            transform: translateX(5px);
        }

        .admin-nav-link.active { 
            background: rgba(251, 191, 36, 0.1); 
            color: var(--secondary) !important; 
            border: 1px solid rgba(251, 191, 36, 0.2);
        }
        .admin-nav-link.active i { opacity: 1; }

        .admin-main { 
            margin-left: var(--sidebar-width);
            padding: 2rem; 
            width: calc(100% - var(--sidebar-width));
            flex-grow: 1;
            background: radial-gradient(circle at top right, #00122e 0%, #000814 100%); 
        }

        /* Admin Table Styles */
        .admin-table-container {
            background: rgba(0, 8, 20, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            color: white;
        }

        .admin-table th {
            text-align: left;
            padding: 0.75rem 1rem;
            background: rgba(255, 255, 255, 0.03);
            color: var(--secondary);
            text-transform: uppercase;
            font-size: 0.65rem;
            font-weight: 900;
            letter-spacing: 1.5px;
            border-bottom: 2px solid var(--glass-border);
        }

        .admin-table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--glass-border);
            vertical-align: middle;
            font-size: 0.85rem;
        }

        .admin-table tr:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        /* UI Elements */
        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
        }

        .card-title-premium {
            font-style: italic;
            text-transform: uppercase;
            font-weight: 900;
            letter-spacing: -1px;
            color: white;
            font-family: 'Outfit';
        }

        .card-title-premium span {
            color: var(--secondary);
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <aside class="admin-sidebar">
            <a href="{{ route('admin.dashboard') }}" class="logo d-block mb-3 text-decoration-none" style="font-size: 1.4rem;">BSMF ADMIN</a>
            <div style="margin-top: -1.2rem; margin-bottom: 2rem;">
                <span style="color: var(--secondary); font-weight: 900; letter-spacing: 2px; font-size: 0.6rem; text-transform: uppercase; font-family: 'Outfit';">Control Panel</span>
            </div>
            
            <nav class="admin-nav">
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
                
                <div style="margin-top: auto; padding-top: 2rem;">
                    <hr style="border: none; border-top: 1px solid var(--glass-border); margin-bottom: 2rem;">
                    <a href="{{ route('home') }}" class="admin-nav-link" style="opacity: 0.6; font-size: 0.75rem;">
                        <i class="fas fa-external-link-alt"></i> Live Garage
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" class="btn" style="background: rgba(239, 68, 68, 0.1); color: var(--danger); border: 1px solid rgba(239, 68, 68, 0.3); width: 100%; padding: 0.8rem; font-weight: 800; text-transform: uppercase; border-radius: 12px; font-size: 0.75rem;">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <main class="admin-main fade-in">
            @if(session('success'))
                <div class="alert glass border-secondary mb-5 p-4 d-flex align-items-center" style="color: var(--secondary); font-weight: 800;">
                    <i class="fas fa-check-circle me-3 fs-4"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert glass border-danger mb-5 p-4 d-flex align-items-center" style="color: var(--danger); font-weight: 800;">
                    <i class="fas fa-exclamation-triangle me-3 fs-4"></i> {{ session('error') }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>
