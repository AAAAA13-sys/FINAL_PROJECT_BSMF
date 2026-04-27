/**
 * BSMF Garage - Main Application Scripts
 */

document.addEventListener('DOMContentLoaded', () => {
    // Mobile Menu Toggle
    const mobileToggle = document.querySelector('.mobile-toggle');
    const navLinks = document.querySelector('.nav-links');
    if (mobileToggle && navLinks) {
        mobileToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
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
        if (nav) {
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        }
    });

    // FAQ Accordion
    document.querySelectorAll('.faq-question').forEach(q => {
        q.addEventListener('click', () => {
            const item = q.parentElement;
            item.classList.toggle('active');
        });
    });

    // Global handler to block 'e', 'E', '+', '-' in number inputs
    document.addEventListener('keydown', (e) => {
        if (e.target.type === 'number') {
            if (['e', 'E', '+', '-'].includes(e.key)) {
                e.preventDefault();
            }
        }
    });


    // Block scroll stacking on refresh
    if ('scrollRestoration' in history) {
        history.scrollRestoration = 'manual';
    }

    // Auto-hide Alerts
    const alerts = document.querySelectorAll('.auto-hide-alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(() => alert.remove(), 500);
        }, 3000);
    });
});

/**
 * Global Helper Functions
 */

// Testimonial Slider
window.moveSlider = function(index) {
    const track = document.querySelector('.testimonial-track');
    const dots = document.querySelectorAll('.dot');
    if (track) {
        track.style.transform = `translateX(-${index * 100}%)`;
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });
    }
};

// Password Visibility Toggle
window.togglePassword = function(btn) {
    const input = btn.parentElement.querySelector('input');
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
};

/**
 * Product Detail Scripts
 */
document.addEventListener('DOMContentLoaded', () => {
    // Thumbnail Switcher
    const mainImage = document.getElementById('mainProductImage');
    document.querySelectorAll('.thumbnail-switch').forEach(thumb => {
        thumb.addEventListener('click', function() {
            if (mainImage) mainImage.src = this.src;
        });
    });
});

/**
 * Product Listing Scripts
 */
document.addEventListener('DOMContentLoaded', () => {
    // Sort Dropdown Handler
    const headerSort = document.getElementById('headerSort');
    if (headerSort) {
        headerSort.addEventListener('change', function() {
            const hiddenSort = document.getElementById('hiddenSort');
            const filterForm = hiddenSort ? hiddenSort.closest('form') : null;
            if (hiddenSort && filterForm) {
                hiddenSort.value = this.value;
                filterForm.submit();
            }
        });
    }
});

/**
 * Search Suggestion System
 */
async function handleSearchInput(inputId, suggestionsId) {
    const input = document.getElementById(inputId);
    const suggestionsBox = document.getElementById(suggestionsId);

    if (!input || !suggestionsBox) return;

    input.addEventListener('input', async (e) => {
        const query = e.target.value;
        if (query.length < 2) {
            suggestionsBox.style.display = 'none';
            return;
        }

        try {
            const response = await fetch(`/api/search-suggestions?query=${encodeURIComponent(query)}`);
            const products = await response.json();

            if (products.length > 0) {
                suggestionsBox.innerHTML = products.map(p => `
                    <a href="/products/${p.id}" class="suggestion-item">
                        <img src="${p.main_image || '/images/placeholder-car.webp'}" alt="${p.name}">
                        <div>
                            <div class="suggestion-name">${p.name}</div>
                            <div class="suggestion-price">$${parseFloat(p.price).toFixed(2)}</div>
                        </div>
                    </a>
                `).join('');
                suggestionsBox.style.display = 'block';
            } else {
                suggestionsBox.style.display = 'none';
            }
        } catch (err) {
            console.error('Search error:', err);
        }
    });

    document.addEventListener('click', (e) => {
        if (!input.contains(e.target) && !suggestionsBox.contains(e.target)) {
            suggestionsBox.style.display = 'none';
        }
    });
}

// Initialize Search on different pages
document.addEventListener('DOMContentLoaded', () => {
    handleSearchInput('searchInput', 'searchSuggestions'); // Hero search
    handleSearchInput('sidebarSearchInput', 'sidebarSearchSuggestions'); // Sidebar search
});

/**
 * Support Widget Logic
 */
document.addEventListener('DOMContentLoaded', () => {
    const fab = document.getElementById('support-fab');
    const chatbox = document.getElementById('support-chatbox');
    const closeChat = document.getElementById('close-chat');

    if (fab && chatbox) {
        fab.addEventListener('click', () => {
            const isOpen = chatbox.style.display === 'flex';
            chatbox.style.display = isOpen ? 'none' : 'flex';
            fab.innerHTML = isOpen ? '<i class="fas fa-headset"></i>' : '<i class="fas fa-chevron-down"></i>';
        });
    }

    if (closeChat && chatbox && fab) {
        closeChat.addEventListener('click', () => {
            chatbox.style.display = 'none';
            fab.innerHTML = '<i class="fas fa-headset"></i>';
        });
    }
});

window.showConversation = function(id) {
    const disputeList = document.getElementById('dispute-list');
    const newDisputeForm = document.getElementById('new-dispute-form');
    if (disputeList) disputeList.style.display = 'none';
    if (newDisputeForm) newDisputeForm.style.display = 'none';
    const conv = document.getElementById('conv-' + id);
    if (conv) conv.style.display = 'flex';
};

window.hideConversation = function(id) {
    const conv = document.getElementById('conv-' + id);
    if (conv) conv.style.display = 'none';
    const disputeList = document.getElementById('dispute-list');
    const newDisputeForm = document.getElementById('new-dispute-form');
    if (disputeList) disputeList.style.display = 'block';
    if (newDisputeForm) newDisputeForm.style.display = 'block';
};
