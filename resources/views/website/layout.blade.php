<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SDA Church')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- MDI Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
    <!-- AOS -->
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
    <!-- SCSS + Vue -->
    @vite(['resources/scss/website/app.scss', 'resources/js/website.js'])
</head>
<body>

    {{-- NAVBAR --}}
    <nav class="ws-navbar">
        <div class="container">
            <div class="ws-navbar-inner">
                <a href="{{ route('website.home') }}" class="ws-brand">
                    <img src="{{ asset('assets/images/logo.jpg') }}" alt="Logo" class="ws-brand-logo">
                    <span>Seventh-day Adventist churh-IRCP.INC</span>
                </a>
                <button class="ws-nav-toggle" id="navToggle" aria-label="Toggle menu">
                    <span class="ws-toggle-bar"></span>
                    <span class="ws-toggle-bar"></span>
                    <span class="ws-toggle-bar"></span>
                </button>
                <ul class="ws-nav-links" id="navLinks">
                    <li><a href="{{ route('website.home') }}" class="{{ request()->routeIs('website.home') ? 'active' : '' }}">Home</a></li>
                    <li><a href="{{ route('website.about') }}" class="{{ request()->routeIs('website.about') ? 'active' : '' }}">About Us</a></li>
                    <li><a href="{{ route('website.pastors-message') }}" class="{{ request()->routeIs('website.pastors-message') ? 'active' : '' }}">Pastor's Message</a></li>
                    <li><a href="{{ route('website.ministries') }}" class="{{ request()->routeIs('website.ministries') ? 'active' : '' }}">Ministries</a></li>
                    <li><a href="{{ route('website.events') }}" class="{{ request()->routeIs('website.events') ? 'active' : '' }}">Events</a></li>
                    <li><a href="{{ route('website.announcements') }}" class="{{ request()->routeIs('website.announcements') ? 'active' : '' }}">Announcements</a></li>
                    <li><a href="{{ route('website.gallery') }}" class="{{ request()->routeIs('website.gallery') ? 'active' : '' }}">Gallery</a></li>
                    <li><a href="{{ route('website.contact') }}" class="{{ request()->routeIs('website.contact') ? 'active' : '' }}">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="ws-nav-backdrop" id="navBackdrop"></div>

    {{-- CONTENT --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="ws-footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5 class="ws-footer-title">SDA-IRCPI Church</h5>
                    <p class="ws-footer-text">Growing in Faith. Serving the Community. Sharing God's Love.</p>
                </div>
                <div class="col-lg-4">
                    <h5 class="ws-footer-title">Service Schedule</h5>
                    <p class="ws-footer-text">
                        <i class="mdi mdi-clock-outline me-1"></i>Sabbath School: 8:00 AM<br>
                        <i class="mdi mdi-clock-outline me-1"></i>Divine Service: 10:00 AM<br>
                        <i class="mdi mdi-clock-outline me-1"></i>AY Program: 2:00 PM
                    </p>
                </div>
                <div class="col-lg-4">
                    <h5 class="ws-footer-title">Connect</h5>
                    <p class="ws-footer-text">
                        <i class="mdi mdi-map-marker me-1"></i>Santol St., Dumanlas Rd., Buhangin, Davao City, Davao del Sur, Mindanao, Philippines, 8000<br>
                        <i class="mdi mdi-phone me-1"></i>09657561311<br>
                        <i class="mdi mdi-email me-1"></i>info@sdaircpi.org<br>
                        <i class="mdi mdi-laptop me-1"></i>Website created by Francis Lloyd Andrew T. Cantones
                    </p>
                </div>
            </div>
            <div class="ws-footer-bottom">
                <p>&copy; {{ date('Y') }} SDA Inter-Regional Conference. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 700, easing: 'ease-out-cubic', once: true, offset: 50 });

        // Navbar variant — auto-switch between transparent and light
        (function() {
            const navbar = document.querySelector('.ws-navbar');
            const hero = document.querySelector('.ws-hero, .ws-page-hero');

            function updateNavbar() {
                if (!hero) {
                    navbar.classList.add('ws-navbar-light');
                    return;
                }
                const heroBottom = hero.getBoundingClientRect().bottom;
                if (heroBottom <= 60) {
                    navbar.classList.add('ws-navbar-light');
                } else {
                    navbar.classList.remove('ws-navbar-light');
                }
            }

            updateNavbar();
            window.addEventListener('scroll', updateNavbar, { passive: true });
            window.addEventListener('resize', updateNavbar, { passive: true });
        })();

        // Mobile navigation
        (function() {
            const toggle = document.getElementById('navToggle');
            const links = document.getElementById('navLinks');
            const backdrop = document.getElementById('navBackdrop');
            var isOpen = false;

            function openNav() {
                isOpen = true;
                links.classList.add('show');
                backdrop.classList.add('show');
                toggle.classList.add('active');
            }

            function closeNav() {
                if (!isOpen) return;
                isOpen = false;
                links.classList.remove('show');
                backdrop.classList.remove('show');
                toggle.classList.remove('active');
            }

            // Toggle button
            toggle.addEventListener('click', function(e) {
                e.stopPropagation();
                if (isOpen) { closeNav(); } else { openNav(); }
            });

            // Backdrop click — close menu
            backdrop.addEventListener('click', function(e) {
                e.stopPropagation();
                closeNav();
            });

            // Prevent clicks inside the nav drawer from propagating to backdrop
            links.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            // Close on link click (navigate)
            links.querySelectorAll('a').forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 992) {
                        closeNav();
                    }
                });
            });

            // Close on resize to desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth > 992 && isOpen) {
                    closeNav();
                }
            });
        })();
    </script>
</body>
</html>
