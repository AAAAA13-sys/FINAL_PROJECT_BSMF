<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-Commerce-BFMSL - Premium Die-Cast')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Extra polish for Laravel transitions */
        .page-transition {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
</head>
<body>
    <nav class="fade-in">
        <a href="{{ route('home') }}" class="logo">BSMF <span>GARAGE</span></a>
        
        <button class="mobile-toggle" style="display: none; background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer;">
            <i class="fas fa-bars"></i>
        </button>

        <ul class="nav-links">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn">Collections <i class="fas fa-chevron-down" style="font-size: 0.7rem;"></i></a>
                <div class="dropdown-content">
                    <a href="{{ route('products.index') }}">All Models</a>
                    @foreach($globalCategories as $gc)
                        <a href="{{ route('products.index', ['category' => $gc->id]) }}">{{ $gc->name }}</a>
                    @endforeach
                </div>
            </li>
            @auth
                <li><a href="{{ route('profile.index') }}">Profile</a></li>
            @endauth
            <li><a href="{{ route('products.index') }}">Shop</a></li>
            @auth
                <li><a href="{{ route('wishlist.index') }}">Garage</a></li>
                <li><a href="{{ route('cart.index') }}">Cart</a></li>
                <li><a href="{{ route('orders.index') }}">Orders</a></li>
                @if(Auth::user()->role === 'admin')
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                @endif
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: white; font-weight: 800; cursor: pointer; text-transform: uppercase; font-size: 0.9rem;">Logout</button>
                    </form>
                </li>
            @else
                <li><a href="{{ route('login') }}">Login</a></li>
                <li><a href="{{ route('register') }}" class="btn btn-primary" style="padding: 0.5rem 1.5rem;">Join</a></li>
            @endauth
        </ul>
    </nav>

    <style>
        @media (max-width: 768px) {
            .mobile-toggle { display: block !important; }
            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: var(--bg-darker);
                flex-direction: column;
                padding: 2rem;
                gap: 1.5rem;
                border-bottom: 3px solid var(--secondary);
            }
            .nav-links.active { display: flex; }
        }
    </style>

    <script>
        document.querySelector('.mobile-toggle').addEventListener('click', function() {
            document.querySelector('.nav-links').classList.toggle('active');
        });
    </script>

    <main class="page-transition">
        @if(session('success'))
            <div class="auth-alert auth-alert-info" style="max-width: 1200px; margin: 2rem auto;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="auth-alert auth-alert-danger" style="max-width: 1200px; margin: 2rem auto;">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer>
        <div class="footer-grid">
            <div>
                <div class="logo" style="font-size: 1.5rem;">BSMF <span>GARAGE</span></div>
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
            &copy; {{ date('Y') }} E-Commerce-BFMSL. All rights reserved.
        </div>
    @auth
        <!-- Support Widget -->
        <div id="support-widget" style="position: fixed; bottom: 2rem; right: 2rem; z-index: 5000;">
            <!-- FAB -->
            <button id="support-fab" style="width: 60px; height: 60px; border-radius: 50%; background: var(--primary); border: none; color: white; font-size: 1.5rem; cursor: pointer; box-shadow: 0 4px 20px rgba(225, 29, 72, 0.4); display: flex; align-items: center; justify-content: center; transition: 0.3s;">
                <i class="fas fa-headset"></i>
            </button>

            <!-- Chatbox -->
            <div id="support-chatbox" class="glass" style="display: none; position: absolute; bottom: 80px; right: 0; width: 350px; border-radius: 20px; overflow: hidden; border: 2px solid var(--secondary); flex-direction: column;">
                <div style="background: var(--secondary); color: var(--bg-darker); padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="font-size: 0.9rem; font-weight: 900; text-transform: uppercase; margin: 0;"><i class="fas fa-shield-alt"></i> DISPUTE CENTER</h3>
                    <button id="close-chat" style="background: none; border: none; color: var(--bg-darker); cursor: pointer;"><i class="fas fa-times"></i></button>
                </div>
                
                <div style="height: 450px; overflow-y: auto; padding: 1.5rem; display: flex; flex-direction: column;" id="chat-content">
                    @if($userDisputes->count() > 0)
                        <div id="dispute-list">
                            <p style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; margin-bottom: 1rem;">Active Disputes</p>
                            @foreach($userDisputes as $dispute)
                                <div onclick="showConversation({{ $dispute->id }})" style="background: rgba(255,255,255,0.05); padding: 1rem; border-radius: 12px; margin-bottom: 1rem; border-left: 3px solid {{ $dispute->status === 'pending' ? 'var(--primary)' : 'var(--secondary)' }}; cursor: pointer; transition: 0.2s;">
                                    <div style="display: flex; justify-content: space-between; font-size: 0.75rem; margin-bottom: 0.3rem;">
                                        <span style="font-weight: 800; color: white;">Order #{{ $dispute->order_id }}</span>
                                        <span style="text-transform: uppercase; font-weight: 900; color: {{ $dispute->status === 'pending' ? 'var(--primary)' : 'var(--secondary)' }};">{{ $dispute->status }}</span>
                                    </div>
                                    <p style="font-size: 0.7rem; color: var(--text-muted); margin: 0;">{{ ucfirst($dispute->type) }}</p>
                                </div>

                                <!-- Hidden Conversation View -->
                                <div id="conv-{{ $dispute->id }}" class="conversation-view" style="display: none; flex-direction: column; height: 100%;">
                                    <button onclick="hideConversation({{ $dispute->id }})" style="background: none; border: none; color: var(--secondary); cursor: pointer; font-size: 0.7rem; margin-bottom: 1rem; text-align: left; padding: 0;"><i class="fas fa-arrow-left"></i> BACK TO LIST</button>
                                    
                                    <div class="message-thread" style="flex-grow: 1; overflow-y: auto; margin-bottom: 1rem; padding-right: 5px;">
                                        <!-- Initial Issue -->
                                        <div style="margin-bottom: 1.5rem;">
                                            <span style="font-size: 0.6rem; color: var(--text-muted); text-transform: uppercase;">Your Report</span>
                                            <div style="background: var(--primary)22; padding: 0.8rem; border-radius: 12px; border: 1px solid var(--primary)44; margin-top: 0.3rem;">
                                                <p style="font-size: 0.8rem; color: white; margin: 0;">{{ $dispute->description }}</p>
                                            </div>
                                        </div>

                                        @foreach($dispute->messages as $msg)
                                            <div style="margin-bottom: 1.5rem; text-align: {{ $msg->user_id == Auth::id() ? 'right' : 'left' }};">
                                                <span style="font-size: 0.6rem; color: var(--text-muted); text-transform: uppercase;">{{ $msg->user->role == 'admin' ? 'Support Agent' : 'You' }}</span>
                                                <div style="background: {{ $msg->user->role == 'admin' ? 'var(--secondary)22' : 'rgba(255,255,255,0.05)' }}; padding: 0.8rem; border-radius: 12px; border: 1px solid {{ $msg->user->role == 'admin' ? 'var(--secondary)44' : 'var(--glass-border)' }}; margin-top: 0.3rem; display: inline-block; max-width: 90%;">
                                                    <p style="font-size: 0.8rem; color: white; margin: 0; text-align: left;">{{ $msg->message }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <form action="{{ route('support.message.store', $dispute->id) }}" method="POST" style="margin-top: auto;">
                                        @csrf
                                        <div style="display: flex; gap: 0.5rem;">
                                            <input type="text" name="message" class="form-control" placeholder="Type a reply..." style="padding: 0.5rem; font-size: 0.8rem; margin-bottom: 0;" required>
                                            <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem;"><i class="fas fa-paper-plane"></i></button>
                                        </div>
                                    </form>
                                </div>
                            @endforeach
                            <hr style="border: none; border-top: 1px solid var(--glass-border); margin: 1.5rem 0;">
                        </div>
                    @endif

                    <div id="new-dispute-form">
                        <h4 style="font-size: 0.8rem; margin-bottom: 1rem; text-transform: uppercase; font-style: italic;">Open New <span>Ticket</span></h4>
                        <form action="{{ route('support.store') }}" method="POST">
                            @csrf
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <select name="order_id" class="form-control" style="padding: 0.6rem; font-size: 0.8rem;" required>
                                    <option value="">Select Order</option>
                                    @foreach ($userOrders as $order)
                                        <option value="{{ $order->id }}">Order #{{ $order->id }} ({{ $order->created_at->format('M d') }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <select name="type" class="form-control" style="padding: 0.6rem; font-size: 0.8rem;" required>
                                    <option value="wrong item">Wrong item</option>
                                    <option value="never received">Never received</option>
                                    <option value="damaged product">Damaged</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <textarea name="description" class="form-control" rows="3" style="padding: 0.6rem; font-size: 0.8rem;" placeholder="Describe your issue..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.8rem; font-size: 0.8rem;">START CONVERSATION</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Support Widget Logic
            const fab = document.getElementById('support-fab');
            const chatbox = document.getElementById('support-chatbox');
            const closeChat = document.getElementById('close-chat');
            const disputeList = document.getElementById('dispute-list');
            const newDisputeForm = document.getElementById('new-dispute-form');

            if (fab) {
                fab.addEventListener('click', () => {
                    const isOpen = chatbox.style.display === 'flex';
                    chatbox.style.display = isOpen ? 'none' : 'flex';
                    fab.innerHTML = isOpen ? '<i class="fas fa-headset"></i>' : '<i class="fas fa-chevron-down"></i>';
                });
            }

            if (closeChat) {
                closeChat.addEventListener('click', () => {
                    chatbox.style.display = 'none';
                    fab.innerHTML = '<i class="fas fa-headset"></i>';
                });
            }

            function showConversation(id) {
                if (disputeList) disputeList.style.display = 'none';
                newDisputeForm.style.display = 'none';
                document.getElementById('conv-' + id).style.display = 'flex';
            }

            function hideConversation(id) {
                document.getElementById('conv-' + id).style.display = 'none';
                if (disputeList) disputeList.style.display = 'block';
                newDisputeForm.style.display = 'block';
            }

            // Scroll Reveal Animation
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

            // Navigation Scroll Effect
            window.addEventListener('scroll', () => {
                const nav = document.querySelector('nav');
                if (window.scrollY > 50) {
                    nav.classList.add('scrolled');
                } else {
                    nav.classList.remove('scrolled');
                }
            });
            // FAQ Accordion
            document.querySelectorAll('.faq-question').forEach(q => {
                q.addEventListener('click', () => {
                    const item = q.parentElement;
                    item.classList.toggle('active');
                });
            });

            // Testimonial Slider
            function moveSlider(index) {
                const track = document.querySelector('.testimonial-track');
                const dots = document.querySelectorAll('.dot');
                if (track) {
                    track.style.transform = `translateX(-${index * 100}%)`;
                    dots.forEach((dot, i) => {
                        dot.classList.toggle('active', i === index);
                    });
                }
            }
            window.moveSlider = moveSlider;
        </script>
    @endauth
</body>
</html>
