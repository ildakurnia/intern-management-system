<header class="topbar">
    <div class="topbar-heading" style="display: flex; align-items: center;">
        <button class="mobile-toggle" onclick="toggleSidebar()" style="display: none; background: none; border: none; font-size: 1.5rem; cursor: pointer; margin-right: 1rem;">☰</button>
        <div>
            <p class="eyebrow">Intern Management System</p>
            <h1 class="topbar-title">@yield('page_heading', 'Dashboard')</h1>
        </div>
    </div>

    <div class="topbar-actions">
        <div class="topbar-search">
            <span>Ready for next module rollout</span>
        </div>

        <div class="user-chip">
            <img src="{{ asset('assets/img/avatars/1.png') }}" alt="User Avatar">
            <div>
                <span>{{ auth()->user()->name }}</span>
                <small>{{ ucfirst(auth()->user()->getRoleNames()->first() ?? 'User') }}</small>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="button button-muted">Logout</button>
        </form>
    </div>
</header>
