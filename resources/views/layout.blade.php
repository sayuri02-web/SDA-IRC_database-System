<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet"
      href="https://unpkg.com/aos@2.3.4/dist/aos.css">

    @livewireStyles

    @vite(['resources/css/app.css', 'resources/scss/app.scss', 'resources/js/app.js'])
    @stack('styles')

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Material Design Icons (CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">

    <title>SDA-IRC System</title>


</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">

        <!-- LOGO -->
        <div class="logo">
            <div class="icon">
                <img src="{{ asset('assets/images/logo.jpg') }}" alt="Church Logo">
            </div>
            <h4>SDA-IRC system</h4>
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

            <li>
                <form method="POST" action="{{ url('/logout') }}" id="logout-form" style="display:none;">
                    @csrf
                </form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="mdi mdi-logout"></i>
                    Logout
                </a>
            </li>

        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">

        <!-- TOPBAR -->
        <div class="topbar">
            <div class="topbar-left">
                <h4 class="topbar-title">Church Record Management System</h4>
            </div>
            <div class="topbar-right">
                <div class="topbar-date">
                    <i class="mdi mdi-calendar-outline"></i>
                    <span>{{ date('l, M d, Y') }}</span>
                </div>
                <div class="topbar-user">
                    <div class="topbar-avatar">
                        <i class="mdi mdi-account"></i>
                    </div>
                    <span class="topbar-username">Admin</span>
                </div>
            </div>
        </div>

        <div class="content-wrapper">
            @yield('content')
        </div>

    </div>

    {{-- Global Delete Confirmation Modal --}}
    @include('components.delete-confirm-modal')

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