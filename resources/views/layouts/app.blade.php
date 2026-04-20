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
        <div id="sidebarOverlay" class="sidebar-overlay" data-sidebar-close></div>
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
</body>
</html>
