<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'IMS')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/ims.css') }}">
</head>
<body class="{{ auth()->check() ? 'auth-shell' : 'guest-shell' }}">
    @auth
        <div id="sidebarOverlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>
        @include('partials.sidebar')
    @endauth

    <div class="app-frame">
        @auth
            @include('partials.navbar')
        @endauth

        <main class="app-content">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            @yield('content')
        </main>

        @auth
            <footer class="app-footer">
                <span>IMS Laravel 12</span>
                <span data-current-year></span>
            </footer>
        @endauth
    </div>

    <script src="{{ asset('assets/js/ims.js') }}"></script>
    <style>
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        @media (max-width: 992px) {
            .mobile-toggle {
                display: block !important;
            }
            .sidebar {
                transition: transform 0.3s ease;
            }
            .sidebar.mobile-open {
                display: flex !important;
                position: fixed;
                top: 0; left: 0; height: 100vh;
                z-index: 1000;
                width: 260px;
                box-shadow: 0 0.25rem 1rem rgba(47, 43, 61, 0.4);
            }
            .sidebar-overlay.mobile-open {
                display: block;
            }
        }
    </style>
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('mobile-open');
            document.getElementById('sidebarOverlay').classList.toggle('mobile-open');
        }
    </script>
</body>
</html>
