 <header>
    <div class="navbar">
        <div class="header-container">
            <a href="{{route('front.home')}}" class="logo">
                <img src="{{ URL::asset('/assets/frontend/assets/logo.svg')}}" alt="Sakura Motors" />
            </a>
            <div class="nav-main">
                <div class="desktop-contact">
                    <a href="https://wa.me/81298683668" target="_blank">
                        <i class="fa-regular c-t-a fa-phone"></i>
                        <span>+81-29-868-3668</span>
                    </a>
                </div>
                <nav class="nav-links">
                    <ul>
                        <li><a href="{{route('front.home')}}" class="{{  request()->routeIs('front.home') ? 'active' : '' }}">Home</a></li>
                        <li><a href="{{route('front.stock')}}" class="{{  request()->routeIs('front.stock') ? 'active' : '' }}">Stock</a></li>
                        <li class="dropdown">
                            <a href="#">About Us <i class="fas fa-chevron-down"></i></a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('front.company') }}">About Company</a></li>
                                <li><a href="{{ route('front.gallery') }}">Gallery</a></li>
                                <li><a href="{{ route('front.payment') }}">Payment</a></li>
                                <li><a href="{{ route('front.testimonials') }}">Testimonials</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ route('front.news') }}" class="{{  request()->routeIs('front.news*') ? 'active' : '' }}">News</a></li>
                        <li><a href="{{ route('front.contact') }}" class="{{  request()->routeIs('front.contact') ? 'active' : '' }}">Contact Us</a></li>
                        <li class="dropdown">
                            <a href="#">Whatsapp <i class="fas fa-chevron-down"></i></a>
                            <ul class="dropdown-menu">
                                <li><a href="https://wa.me/818068823553" target="_blank">Japan: +81-80-6882-3553</a></li>
                                <li><a href="https://wa.me/255716787502" target="_blank">Tanzania: +255-716-787-502</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>

            <div class="auth-buttons auth-buttons-desk">
                @auth('customer')
                    <div class="customer-dropdown" style="position: relative;">
                        <button class="customer-dropdown-btn" type="button" onclick="toggleCustomerDropdown()"
                                style="background: none; border: 1px solid var(--primary-color); color: var(--primary-color); padding: 10px 16px; border-radius: var(--border-radius); cursor: pointer; display: flex; align-items: center; font-size: 16px; font-weight: 600; height: 44px; min-width: 120px; justify-content: center; gap: 8px;">
                            <i class="fa-regular fa-user"></i>{{ Auth::guard('customer')->user()->name }}
                            <i class="fas fa-chevron-down" style="font-size: 12px;"></i>
                        </button>
                        <ul class="customer-dropdown-menu" id="customerDropdownMenu"
                            style="position: absolute; top: 100%; right: 0; background: white; border: 1px solid #e5e2e2; border-radius: var(--border-radius); box-shadow: 0px 4px 8px 0px #a5a5a529; min-width: 200px; z-index: 1000; display: none; list-style: none; padding: 6px 0; margin: 0;">
                            <li><a href="{{ route('front.customer.dashboard') }}"
                                   style="display: flex; align-items: center; padding: 8px 12px; color: var(--text-color); text-decoration: none; font-family: var(--font-heading); font-weight: 400; font-size: 14px; line-height: 20px;">
                                <i class="fas fa-tachometer-alt" style="margin-right: 8px; width: 16px;"></i>Dashboard</a></li>
                            <li><a href="{{ route('front.customer.profile') }}"
                                   style="display: flex; align-items: center; padding: 8px 12px; color: var(--text-color); text-decoration: none; font-family: var(--font-heading); font-weight: 400; font-size: 14px; line-height: 20px;">
                                <i class="fas fa-user" style="margin-right: 8px; width: 16px;"></i>My Profile</a></li>
                            <li>
                                <form method="POST" action="{{ route('front.customer.logout') }}" style="margin: 0;">
                                    @csrf
                                    <button type="submit"
                                            style="display: flex; align-items: center; padding: 8px 12px; color: #dc3545; background: none; border: none; width: 100%; text-align: left; cursor: pointer; font-family: var(--font-heading); font-weight: 400; font-size: 14px; line-height: 20px;">
                                        <i class="fas fa-sign-out-alt" style="margin-right: 8px; width: 16px;"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('front.customer.login') }}" class="login">Log in</a>
                    <a href="{{ route('front.customer.signup') }}" class="signup">Sign up</a>
                @endauth
            </div>
            <div class="support-main">
                <div class="support-btn">
                    <i class="fa-regular fa-phone"></i>
                    <i class="fa-brands fa-whatsapp"></i>
                    <i class="fa-regular fa-user"></i>
                </div>
                <div class="hamburger" onclick="toggleMenu()">
                    <i class="fa-regular fa-bars"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="mobile-menu" id="mobileMenu">
        <div class="top-row">
            <div class="logo">
                <img src="{{ URL::asset('/assets/frontend/assets/logo.svg') }}" alt="Sakura Motors" />
            </div>
            <div class="support-main">
                <div class="support-btn">
                    <i class="fa-regular fa-phone"></i>
                    <i class="fa-brands fa-whatsapp"></i>
                    <i class="fa-regular fa-user"></i>
                </div>
                <div class="icons">
                    <i class="fa-regular fa-xmark-large" onclick="toggleMenu()"></i>
                </div>
            </div>
        </div>
        <div class="mbl-bottom">
            <ul>
                <li><a href="{{ route('front.home') }}" class="{{ request()->routeIs('front.home') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ route('front.stock') }}" class="{{ request()->routeIs('front.stock') ? 'active' : '' }}">Stock</a></li>
                <li><a href="{{ route('front.company') }}" class="{{ request()->routeIs('front.company') ? 'active' : '' }}">Company Profile</a></li>
                <li><a href="{{ route('front.gallery') }}" class="{{ request()->routeIs('front.gallery') ? 'active' : '' }}">Gallery</a></li>
                <li><a href="{{ route('front.payment') }}" class="{{ request()->routeIs('front.payment') ? 'active' : '' }}">Payment</a></li>
                <li><a href="{{ route('front.testimonials') }}" class="{{ request()->routeIs('front.testimonials') ? 'active' : '' }}">Testimonials</a></li>
                <li><a href="{{ route('front.news') }}" class="{{ request()->routeIs('front.news*') ? 'active' : '' }}">News</a></li>
                <li><a href="{{ route('front.contact') }}" class="{{ request()->routeIs('front.contact') ? 'active' : '' }}">Contact Us</a></li>
            </ul>
            <div class="cont-info">
                <h1><i class="fa-brands fa-whatsapp"></i> Whatsapp:</h1>
                <p>
                    Japan: <a href="https://wa.me/818068823553" target="_blank"><strong>+81-80-6882-3553</strong></a>
                </p>
                <p>
                    Tanzania: <a href="https://wa.me/255716787502" target="_blank"><strong>+255-716-787-502</strong></a>
                </p>
            </div>

            <div class="auth-buttons">
                @auth('customer')
                    <div class="customer-info mb-3">
                        <p class="mb-2"><strong>{{ Auth::guard('customer')->user()->name }}</strong></p>
                        <a href="{{ route('front.customer.dashboard') }}" class="btn btn-primary btn-sm me-2">Dashboard</a>
                        <form method="POST" action="{{ route('front.customer.logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary btn-sm">Logout</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('front.customer.login') }}" class="login">Log in</a>
                    <a href="{{ route('front.customer.signup') }}" class="signup">Sign up</a>
                @endauth
            </div>
        </div>
    </div>
</header>

<script>
function toggleCustomerDropdown() {
    const menu = document.getElementById('customerDropdownMenu');
    if (menu) {
        menu.style.display = menu.style.display === 'none' || menu.style.display === '' ? 'block' : 'none';
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.querySelector('.customer-dropdown');
    const menu = document.getElementById('customerDropdownMenu');

    if (dropdown && menu && !dropdown.contains(event.target)) {
        menu.style.display = 'none';
    }
});

// Add hover effects to dropdown items
document.addEventListener('DOMContentLoaded', function() {
    const dropdownItems = document.querySelectorAll('.customer-dropdown-menu li');

    dropdownItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f5f4f4';
        });

        item.addEventListener('mouseleave', function() {
            this.style.backgroundColor = 'transparent';
        });
    });

    // Add hover effect to the dropdown button
    const dropdownBtn = document.querySelector('.customer-dropdown-btn');
    if (dropdownBtn) {
        dropdownBtn.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#d420281f';
            this.style.transition = 'background-color 0.3s ease, color 0.3s ease';
        });

        dropdownBtn.addEventListener('mouseleave', function() {
            this.style.backgroundColor = 'transparent';
        });
    }
});
</script>