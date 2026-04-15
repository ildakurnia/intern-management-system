<header class="topbar">
    <div class="topbar-heading">
        <p class="eyebrow">Intern Management System</p>
        <h1 class="topbar-title">@yield('page_heading', 'Dashboard')</h1>
    </div>

    <div class="topbar-actions">
        <div class="topbar-search">
            <span>Ready for next module rollout</span>
        </div>

        <div class="user-chip">
            <img src="{{ asset('assets/img/avatars/1.png') }}" alt="User Avatar">
            <div>
                <span>{{ auth()->user()->name }}</span>
                <small>{{ auth()->user()->role?->label() }}</small>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="button button-muted">Logout</button>
        </form>
    </div>
</header>
