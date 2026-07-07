<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Preconnect for Inter font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet"
      href="https://unpkg.com/aos@2.3.4/dist/aos.css">

    @livewireStyles

    @vite(['resources/css/app.css', 'resources/scss/app.scss', 'resources/js/app.js'])
    @stack('styles')

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Material Design Icons (CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">

    <title>SDA-IRCPI Management System</title>


</head>

<body>

    {{-- Global User Permissions --}}
    <script>
        window.userPermissions = {
            role: '{{ auth()->user()?->role?->value ?? "guest" }}',
            canManage: function(module) {
                if (this.role === 'admin') return true;
                if (this.role === 'certificate_manager' && module === 'certificates') return true;
                if (this.role === 'website_manager' && module === 'website-management') return true;
                return false;
            }
        };
    </script>

    <!-- SIDEBAR -->
    <div class="sidebar">

        <!-- LOGO -->
        <div class="logo">
            <div class="icon">
                <img src="{{ asset('assets/images/logo.jpg') }}" alt="Church Logo">
            </div>
            <h4>{{ auth()->user()?->role?->label() ?? 'Admin' }}</h4>
        </div>

        <!-- MENU -->
        <ul>

            <li>
                <a class="{{ request()->is('dashboard') ? 'active' : '' }}"
                   href="{{ url('/dashboard') }}">
                    <i class="mdi mdi-view-dashboard"></i>
                    Dashboard
                </a>
            </li>

            <li>
                <a class="{{ request()->is('members') ? 'active' : '' }}"
                   href="{{ url('/members') }}">
                    <i class="mdi mdi-account-group"></i>
                    Members
                </a>
            </li>

            <li>
                <a class="{{ request()->is('church') ? 'active' : '' }}"
                   href="{{ url('/church') }}">
                    <i class="mdi mdi-file-document"></i>
                    Church registration
                </a>
            </li>

            <li>
                <a class="{{ request()->is('certificates') ? 'active' : '' }}"
                   href="{{ url('/certificates') }}">
                    <i class="mdi mdi-chart-bar"></i>
                    Certifications
                </a>
            </li>

            <li>
                <a class="{{ request()->is('website-management') ? 'active' : '' }}"
                   href="{{ url('/website-management') }}">
                    <i class="mdi mdi-web"></i>
                    Website Management
                </a>
            </li>

            <li>
                <a class="{{ request()->is('settings') ? 'active' : '' }}"
                   href="{{ url('/settings') }}">
                    <i class="mdi mdi-cog"></i>
                    Settings
                </a>
            </li>

        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">

        <!-- TOPBAR -->
        <div class="topbar">
            <div class="topbar-left">
                <h4 class="topbar-title">Seventh-day Adventist Church Management System</h4>
            </div>
            <div class="topbar-right">
                <div class="topbar-date">
                    <i class="mdi mdi-calendar-outline"></i>
                    <span>{{ date('l, M d, Y') }}</span>
                </div>

                <div class="topbar-notification dropdown">
                    <button
                        class="btn position-relative"
                        data-bs-toggle="dropdown">

                        <i class="mdi mdi-bell-outline fs-4"></i>

                        @if($unreadNotifications > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $unreadNotifications }}
                            </span>
                        @endif

                    </button>

                    <div class="dropdown-menu dropdown-menu-end shadow notification-dropdown">
                        <div class="notification-header">
                            Notifications
                        </div>

                        @forelse($notifications as $notification)
                            <a href="#"
                            class="dropdown-item notification-item">

                                <div class="notification-title">
                                    {{ $notification->title }}
                                </div>

                                <small class="text-muted">
                                    {{ $notification->created_at->diffForHumans() }}
                                </small>
                            </a>
                        @empty
                            <div class="p-3 text-center text-muted">
                                No notifications
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="topbar-user dropdown">
                    <button class="topbar-user-btn" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="topbar-avatar">
                            <i class="mdi mdi-account"></i>
                        </div>
                        <span class="topbar-username">{{ auth()->user()->name ?? 'Admin' }}</span>
                        <i class="mdi mdi-chevron-down topbar-chevron"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-end shadow topbar-user-dropdown">
                        <div class="topbar-user-dropdown-header">
                            <strong>{{ auth()->user()->name ?? 'Admin' }}</strong>
                            <small>{{ auth()->user()->role?->label() ?? 'Admin' }}</small>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item topbar-dropdown-item">
                            <i class="mdi mdi-account-outline"></i> My Profile
                        </a>
                        <a href="#" class="dropdown-item topbar-dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="mdi mdi-swap-horizontal"></i> Switch Account
                        </a>
                        @if(auth()->user()?->isAdmin())
                        <div class="dropdown-divider"></div>
                        <a href="{{ url('/users') }}" class="dropdown-item topbar-dropdown-item">
                            <i class="mdi mdi-account-cog-outline"></i> User Management
                        </a>
                        @endif
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item topbar-dropdown-item topbar-dropdown-logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="mdi mdi-logout"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-wrapper">
            @yield('content')
        </div>

    </div>

    {{-- Global Delete Confirmation Modal --}}
    @include('components.delete-confirm-modal')

    {{-- Hidden Logout Form --}}
    <form method="POST" action="{{ url('/logout') }}" id="logout-form" style="display:none;">
        @csrf
    </form>

    {{-- Global Toast Notification Container --}}
    <div id="toast-app"></div>

    {{-- Global Access Required Modal --}}
    <div class="modal fade" id="accessRequiredModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content leaders-modal" style="border-radius:18px;">
                <div class="leaders-modal-bar" style="background: linear-gradient(to right, #e53935, #ff6b6b);"></div>
                <div class="modal-body p-4 text-center">
                    <i class="mdi mdi-shield-lock-outline" style="font-size:48px; color:#e53935;"></i>
                    <h5 class="fw-bold mt-3 mb-2">Access Restricted</h5>
                    <p class="text-muted mb-2" style="font-size:13px;">
                        You are currently signed in as <strong id="accessCurrentRole"></strong>.
                    </p>
                    <p class="text-muted mb-4" style="font-size:13px;">
                        This action requires an <strong id="accessRequiredRole"></strong> account.<br>
                        Please switch to an account with the required permissions.
                    </p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button class="btn btn-outline-secondary btn-sm px-4" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-success btn-sm px-4" onclick="document.getElementById('logout-form').submit();">
                            <i class="mdi mdi-account-switch-outline me-1"></i> Switch Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Laravel Flash Data Bridge --}}
    <div id="laravel-flash-data" class="d-none"
        data-success="{{ session('success') }}"
        data-error="{{ session('error') }}"
        data-warning="{{ session('warning') }}"
        data-info="{{ session('info') }}"
        data-flash-message="{{ session('flash_message') }}"
    ></div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

<script>
AOS.init({
    duration: 750,
    easing: 'cubic-bezier(0.4, 0, 0.2, 1)',
    once: true,
    offset: 60
});

// Global Access Control Interceptor
(function() {
    var roleLabels = {
        'admin': 'Administrator',
        'certificates': 'Certificate Manager or Administrator',
        'website-management': 'Website Manager or Administrator'
    };

    var currentRoleLabel = '{{ auth()->user()?->role?->label() ?? "Guest" }}';

    function showAccessModal(requiredModule) {
        var modal = document.getElementById('accessRequiredModal');
        if (!modal) return;
        document.getElementById('accessCurrentRole').textContent = currentRoleLabel;
        document.getElementById('accessRequiredRole').textContent = roleLabels[requiredModule] || 'Administrator';
        new bootstrap.Modal(modal).show();
    }

    // Intercept clicks on elements with data-requires attribute
    document.addEventListener('click', function(e) {
        var el = e.target.closest('[data-requires]');
        if (!el) return;

        var requiredModule = el.getAttribute('data-requires');
        if (window.userPermissions && window.userPermissions.canManage(requiredModule)) return; // allowed

        // Block the action
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        showAccessModal(requiredModule);
    }, true); // Use capture phase to intercept before other handlers

    // Listen for Vue-triggered access denied events
    window.addEventListener('show-access-denied', function(e) {
        var module = (e.detail && e.detail.module) || 'admin';
        showAccessModal(module);
    });
})();

// Global delete confirmation handler
(function() {
    let deleteForm = null;
    let deleteCallback = null;

    document.addEventListener('click', function(e) {
        const btn = e.target.closest('[data-delete-confirm]');
        if (!btn) return;
        e.preventDefault();

        const title = btn.dataset.deleteTitle || 'Delete Record';
        const msg = btn.dataset.deleteMsg || 'Are you sure? This action cannot be undone.';

        document.getElementById('globalDeleteTitle').textContent = title;
        document.getElementById('globalDeleteMsg').textContent = msg;

        // If button is inside a form, store the form for submission
        const form = btn.closest('form');
        if (form) {
            deleteForm = form;
            deleteCallback = null;
        } else {
            deleteForm = null;
            deleteCallback = btn.dataset.deleteCallback || null;
        }

        new bootstrap.Modal(document.getElementById('globalDeleteModal')).show();
    });

    document.getElementById('globalDeleteConfirmBtn').addEventListener('click', function() {
        if (deleteForm) {
            deleteForm.submit();
        } else if (deleteCallback && window[deleteCallback]) {
            window[deleteCallback]();
        }
        bootstrap.Modal.getInstance(document.getElementById('globalDeleteModal')).hide();
    });
})();
</script>

    @stack('scripts')
    @livewireScripts

</body>
</html>